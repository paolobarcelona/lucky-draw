<?php

namespace App\Http\Controllers;

use App\Repositories\Eloquent\ORM\Interfaces\UserRepositoryInterface;
use Illuminate\Contracts\Support\Renderable;

final class UsersController extends Controller
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
     * Show the winning number for the user id
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function showWinningNumbers(string $userId): Renderable
    {
        $user = $this->userRepository->findOrFail($userId);

        return view('winning_numbers', \compact('user'));
    }
}
