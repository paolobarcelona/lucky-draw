<?php

namespace Tests\Unit\Repositories\Eloquent\ORM;

use App\Models\User;
use App\Repositories\Eloquent\ORM\Interfaces\UserRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Mockery\MockInterface;
use Tests\AbstractTestCase;

/**
 * @covers \App\Repositories\Eloquent\ORM\UserRepository
 */
final class UserRepositoryTest extends AbstractTestCase
{
    /**
     * Should get all non admin users.
     *
     * @return void
     */
    public function testGetAllNonAdminUsers(): void
    {
        /** @var \App\Repositories\Eloquent\ORM\Interfaces\UserRepositoryInterface $repository */
        $repository = $this->getRepository(UserRepositoryInterface::class);

        $users = $repository->getAllNonAdminUsers();

        foreach ($users as $user) {
            self::assertNotEquals(1, $user->is_admin);
        }
    }
}
