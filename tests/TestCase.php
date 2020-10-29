<?php

namespace Tests;

use Carbon\Carbon;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    /**
     * Checks if event did broadcast.
     *
     * @param string $eventClassName Event name.
     * @param string $channelName Channel name.
     * @param int $limit Log filter limit.
     *
     * @return void
     */
    public function assertEventIsBroadcasting(
        string $eventClassName,
        string $channelName = '',
        int $limit = 30
    ) {
        $split = explode("\n", file_get_contents(storage_path('logs/laravel.log')));
        $logfile = implode("\n", array_splice($split, $limit * -1));

        $broadcast = "[" . Carbon::now() . "] testing.INFO: Broadcasting [" . $eventClassName;
        $channel = '] on channels [' . $channelName . ']';
        $this->assertStringContainsString($broadcast . ($channelName ? $channel : ''), $logfile);
    }
}
