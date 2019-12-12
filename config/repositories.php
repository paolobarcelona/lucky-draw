<?php
use App\Repositories\Eloquent\ORM\Interfaces\UserRepositoryInterface;
use App\Repositories\Eloquent\ORM\UserRepository;

return [
    UserRepositoryInterface::class => UserRepository::class
];