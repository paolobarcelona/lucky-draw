<?php

use App\Repositories\Eloquent\ORM\Interfaces\UserRepositoryInterface;
use App\Repositories\Eloquent\ORM\Interfaces\WinningNumberRepositoryInterface;
use Illuminate\Database\Seeder;
use Faker\Factory as FakerFactory;

class UserWinningNumberTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(
        UserRepositoryInterface $userRepository,
        WinningNumberRepositoryInterface $winningNumberRepo
    ): void {
        /** @var \Faker\Generator $faker */
        $faker = FakerFactory::create();

        $users = [];

        for ($counter = 0; $counter < 9; $counter++) {
            $users[] = $userRepository->create([
                'name' => $faker->unique()->name,
                'email' => $faker->unique()->email,
                'password' => bcrypt('password'),
            ]);
        }

        foreach ($users as $user) {
            /** @var \App\Models\User $user*/
            $randomNumber = $faker->randomNumber(1);

            for ($counter = 0; $counter < $randomNumber; $counter++) {
                $winningNumberRepo->create([
                    'winning_number' => $faker->unique()->randomNumber(4, true),
                    'user_id' => $user->id
                ]);
            }
        }
    }
}
