<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Repositories\Eloquent\ORM\Interfaces\UserRepositoryInterface;
use Illuminate\Console\Command;

final class CreateAdminUser extends Command
{
    /**
     * Create a new console command instance.
     */
    public function __construct()
    {
        $this->signature = 'create-admin-user';
        $this->description = 'Create admin user for the application';

        parent::__construct();
    }

    /**
     * Handle the command.
     *
     * @param \App\Repositories\Eloquent\ORM\Interfaces\UserRepositoryInterface $userRepository
     *
     * @return bool
     *
     * @throws \Exception
     */
    public function handle(UserRepositoryInterface $userRepository): bool
    {
        /** null|\App\Models\User $admin */
        $admin = $userRepository->find(1);
        $headers = ['id', 'name', 'email', 'password'];
        $password = 'password';

        if ($admin instanceof User) {
            $this->output->success('Admin User created.');
            $this->output->text('---');
            $this->output->table($headers, [[$admin->id, $admin->name, $admin->email, $password]]);

            return 1;
        }

        $admin = $userRepository->create([
            'name' => 'Administrator',
            'email' => 'admin@lucky.draw',
            'is_admin' => true,
            'password' => \bcrypt('password')
        ]);

        $this->output->success('Admin User created.');
        $this->output->text('---');
        $this->output->table($headers, [[$admin->id, $admin->name, $admin->email, $password]]);

        return 1;
    }    
}
