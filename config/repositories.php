<?php
use App\Repositories\Eloquent\ORM\Interfaces\UserRepositoryInterface;
use App\Repositories\Eloquent\ORM\Interfaces\WinnerRepositoryInterface;
use App\Repositories\Eloquent\ORM\Interfaces\WinningNumberRepositoryInterface;
use App\Repositories\Eloquent\ORM\UserRepository;
use App\Repositories\Eloquent\ORM\WinnerRepository;
use App\Repositories\Eloquent\ORM\WinningNumberRepository;

return [
    UserRepositoryInterface::class => UserRepository::class,
    WinnerRepositoryInterface::class => WinnerRepository::class,
    WinningNumberRepositoryInterface::class => WinningNumberRepository::class
];