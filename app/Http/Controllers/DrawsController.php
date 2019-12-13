<?php

namespace App\Http\Controllers;

use App\Models\DrawAttempt;
use App\Repositories\Eloquent\ORM\Interfaces\UserRepositoryInterface;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;

final class DrawsController extends Controller
{
    /**
     * @var \App\Repositories\Eloquent\ORM\Interfaces\UserRepositoryInterface
     */
    private $userRepository;

    /**
     * Create a new controller instance.
     *
     * @param \App\Repositories\Eloquent\ORM\Interfaces\UserRepositoryInterface $userRepository
     */
    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->middleware('auth');

        $this->userRepository = $userRepository;
    }

    /**
     * Show the create draw form.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(): Renderable
    {
        return view('create_draw', ['prizes' => DrawAttempt::PRIZES_READABLE]);
    }

    /**
     * Show the application dashboard.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function store(Request $request): Renderable
    {
        $validatedData = $request->validate([
            'prize' => \sprintf('required|string|max:255|in:%s', \implode(',', DrawAttempt::PRIZES)),
            'winning_number' => 'required_unless:is_generated_randomly,true|numeric',
            'is_generated_randomly' => 'nullable|boolean'
        ]);

        dd($validatedData);

        return view('create_draw', ['prizes' => DrawAttempt::PRIZES_READABLE]);
    }
}
