<?php

namespace Tests\Unit;

use App\Events\NewMessage;
use App\Message;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BroadcastTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function new_message_event_should_be_broad_casted()
    {
        $message = factory(Message::class)->create([
            Message::FROM => factory(User::class)->create()->{User::ID},
            Message::TO => factory(User::class)->create()->{User::ID},
        ]);
        broadcast(new NewMessage($message));

        $this->assertEventIsBroadcasting(
            NewMessage::class,
            'private-contacts.' . $message->to
        );
    }
}
