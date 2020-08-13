<?php

use App\User;
use App\Message;
use Illuminate\Database\Seeder;

class MessageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $usersCount = User::count();

        User::all()->map(function ($user) use ($usersCount) {
            foreach (range(1, $usersCount) as $key) {
                do {
                    $target = rand(1, $usersCount);
                } while ($target === $user->id);

                factory(Message::class)->create([
                    Message::TO => $user->id,
                    Message::FROM => $target,
                ]);
                factory(Message::class)->create([
                    Message::TO => $target,
                    Message::FROM => $user->id,
                ]);
            }
        });
    }
}
