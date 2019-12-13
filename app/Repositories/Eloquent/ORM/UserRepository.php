<?php

namespace App\Repositories\Eloquent\ORM;

use App\Models\User;
use App\Repositories\Eloquent\ORM\Interfaces\UserRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

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

    /**
     * Get all non admin users.
     * 
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getAllNonAdminUsers(): Collection
    {
        /** @var \Illuminate\Database\Eloquent\Collection $users */
        $users = $this->findBy(['is_admin' => '0']);

        return $users;
    }    
}
