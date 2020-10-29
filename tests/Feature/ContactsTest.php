<?php

namespace Tests\Feature;

use App\Message;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\User;

class ContactsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function contacts_must_return_array()
    {
        $this->withoutExceptionHandling();
        $this->be(factory(User::class)->create());
        factory(User::class, 4)->create();

        $response = $this->get(route('contacts'))->assertStatus(200);

        $this->assertIsArray($response->json());
        $this->assertCount(4, $response->json());
    }

    /** @test */
    public function contacts_must_have_sends_relation_containing_unread_messages_count()
    {
        $usersCount = 4;
        $unreadCount = 2;
        $this->withoutExceptionHandling();
        $this->be($from = factory(User::class)->create());
        $users = factory(User::class, $usersCount)->create();
        $users->map(function ($user) use ($from, $unreadCount) {
            factory(Message::class, $unreadCount)->create([
                Message::FROM => $user->{User::ID},
                Message::TO => $from->{User::ID},
            ]);

            factory(Message::class, 1)->state('read')->create([
                Message::FROM => $user->{User::ID},
                Message::TO => $from->{User::ID},
            ]);
        });
        $response = $this->get(route('contacts'))->assertStatus(200);

        array_map(function ($user) use ($unreadCount) {
            $this->assertEquals($unreadCount, current($user['sends'])[Message::UNREAD_MESSAGES_COUNT]);
        }, $response->json());

        $this->assertIsArray($response->json());
        $this->assertCount($usersCount, $response->json());
    }

    /** @test */
    public function contacts_must_not_be_able_for_guests()
    {
        $this->expectException(AuthenticationException::class);
        $this->withoutExceptionHandling();

        $this->get(route('contacts'));
    }
}
