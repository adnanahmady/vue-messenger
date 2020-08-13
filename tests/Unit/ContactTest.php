<?php

namespace Tests\Unit;

use App\Message;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ContactTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function users_receives_must_be_returned_with_user()
    {
        $this->withoutExceptionHandling();
        $user = factory(User::class)->create();
        $users = factory(User::class, 4)->create();
        $users->map(function ($u) use ($user) {
            factory(Message::class)->create([
                'from' => $u->id,
                'to' => $user->id,
            ]);
        });

        $this->assertCount(4, User::with('receives')->first()->receives);
    }
}
