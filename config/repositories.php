<?php
use App\Repositories\Eloquent\ORM\Interfaces\UserRepositoryInterface;
use App\Repositories\Eloquent\ORM\Interfaces\WinningNumberRepositoryInterface;
use App\Repositories\Eloquent\ORM\UserRepository;
use App\Repositories\Eloquent\ORM\WinningNumberRepository;

return [
    UserRepositoryInterface::class => UserRepository::class,
    WinningNumberRepositoryInterface::class => WinningNumberRepository::class
];