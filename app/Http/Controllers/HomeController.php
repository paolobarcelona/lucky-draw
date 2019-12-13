<?php

namespace App\Http\Controllers;

use App\Repositories\Eloquent\ORM\Interfaces\UserRepositoryInterface;
use App\Repositories\Eloquent\ORM\Interfaces\WinnerRepositoryInterface;
use Illuminate\Contracts\Support\Renderable;

final class HomeController extends Controller
{
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
     * @param \App\Repositories\Eloquent\ORM\Interfaces\UserRepositoryInterface $userRepository
     * @param \App\Repositories\Eloquent\ORM\Interfaces\WinnerRepositoryInterface $winnerRepository
     */
    public function __construct(
        UserRepositoryInterface $userRepository,
        WinnerRepositoryInterface $winnerRepository
    ) {
        $this->middleware('auth');
        
        $this->userRepository = $userRepository;
        $this->winnerRepository = $winnerRepository;
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(): Renderable
    {
        $users = $this->userRepository->getAllNonAdminUsers();
        $winners = $this->winnerRepository->all();

        return view('home', \compact('users', 'winners'));
    }
}
