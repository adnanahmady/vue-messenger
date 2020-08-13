<?php

namespace Tests\Unit;

use App\Events\NewMessage;
use App\Message;
use App\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BroadcastTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function new_message_event_should_be_broad_casted()
    {
        $message = factory(Message::class)->create([
            'from' => factory(User::class)->create()->id,
            'to' => factory(User::class)->create()->id,
        ]);
        broadcast(new NewMessage($message));

        $this->assertEventIsBroadcasting(
            NewMessage::class,
            'private-contacts.' . $message->to
        );
    }

    /**
     * Checks if event did broadcast.
     *
     * @param string $eventClassName Event name.
     * @param string $channelName Channel name.
     * @param int $limit Log filter limit.
     *
     * @return void
     */
    public function assertEventIsBroadcasting(string $eventClassName, string $channelName = '', int $limit = 30)
    {
        $split = explode("\n", file_get_contents(storage_path('logs/laravel.log')));
        $logfile = implode("\n", array_splice($split, $limit * -1));

        $broadcast = "[" . Carbon::now() . "] testing.INFO: Broadcasting [" . $eventClassName;
        $channel = '] on channels [' . $channelName . ']';
        $this->assertStringContainsString($broadcast . ($channelName ? $channel : ''), $logfile);
    }
}
