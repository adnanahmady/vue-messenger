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
    public function users_received_messages_must_be_returned_with_user()
    {
        $this->withoutExceptionHandling();
        $to = factory(User::class)->create();
        $users = factory(User::class, 4)->create();
        $users->map(function ($user) use ($to) {
            factory(Message::class)->create([
                Message::FROM => $user->{User::ID},
                Message::TO => $to->{User::ID},
            ]);
        });

        $this->assertCount(4, User::with('receives')->first()->receives);
    }
}
