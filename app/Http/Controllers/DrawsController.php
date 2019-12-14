<?php

namespace App\Http\Controllers;

use App\Models\DrawAttempt;
use App\Repositories\Eloquent\ORM\Interfaces\UserRepositoryInterface;
use App\Repositories\Eloquent\ORM\Interfaces\WinnerRepositoryInterface;
use App\Services\Draw\DrawServiceInterface;
use Exception;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

final class DrawsController extends Controller
{
    /**
     * @var \App\Services\Draw\DrawServiceInterface
     */
    private $drawService;

    /**
     * @var \App\Repositories\Eloquent\ORM\Interfaces\UserRepositoryInterface
     */
    private $userRepository;

    /**
     * @var \App\Repositories\Eloquent\ORM\Interfaces\WinnerRepositoryInterface
     */
    private $winnerRepository;

    /**
     * Create a new controller instance.
     *
     * @param \App\Services\Draw\DrawServiceInterface $drawService
     * @param \App\Repositories\Eloquent\ORM\Interfaces\UserRepositoryInterface $userRepository
     * @param \App\Repositories\Eloquent\ORM\Interfaces\WinnerRepositoryInterface $winnerRepository
     */
    public function __construct(
        DrawServiceInterface $drawService,
        UserRepositoryInterface $userRepository,
        WinnerRepositoryInterface $winnerRepository
    ) {
        $this->middleware('auth');

        $this->drawService = $drawService;
        $this->userRepository = $userRepository;
        $this->winnerRepository = $winnerRepository;
    }

    /**
     * Show the create draw form.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(): Renderable
    {
        $winners = $this->winnerRepository->all();

        $prizes = DrawAttempt::PRIZES_READABLE;

        foreach ($winners as $winner) {
            unset($prizes[$winner->drawAttempt()->first()->prize]);
        }

        return view('create_draw', ['prizes' => $prizes]);
    }

    /**
     * Show the application dashboard.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\RedirectResponse
     *
     * @throws \App\Exceptions\NoWinningNumbersFoundException
     * @throws \App\Exceptions\PrizeAlreadyExistsException
     */
    public function store(Request $request): RedirectResponse
    {
        $validatedData = $request->validate([
            'prize' => \sprintf('required|string|max:255|in:%s', \implode(',', DrawAttempt::PRIZES)),
            'is_generated_randomly' => 'nullable|boolean',
            'winning_number' => 'required_if:is_generated_randomly,false|numeric'
        ]);

        $draw = null;

        try {
            $drawResponse = $this->drawService->createDrawAttempt($validatedData);
            $drawAttempt = $drawResponse->getDrawAttempt();

            $message = \sprintf(
                \config('responses.new_draw_created_no_winner'),
                $drawAttempt->winning_number ?? null
            );

            // Is there a winner?
            if ($drawResponse->getWinner() !== null) {
                $message = \sprintf(
                    \config('responses.new_draw_created_with_winner'),
                    $drawAttempt->winning_number ?? null,
                    $drawResponse->getUser()->name ?? '',
                    DrawAttempt::PRIZES_READABLE[$drawAttempt->prize]
                );
            }

            $error = false;
        } catch (Exception $exception) {
            $message = $exception->getMessage();
            $error = true;
        }

        return redirect()
            ->route('draw-view')
            ->with('message', $message)
            ->with('error', $error);
    }
}
