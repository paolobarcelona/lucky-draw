<?php

namespace App\Repositories\Eloquent\ORM;

use App\Models\User;
use App\Repositories\Eloquent\ORM\Interfaces\UserRepositoryInterface;

final class UserRepository extends AbstractRepository implements UserRepositoryInterface
{
    /**
     * UserRepository constructor.
     * 
     * @param \App\Models\User $user
     */
    public function __construct(User $user)
    {
        parent::__construct($user);   
    }
}
