<?php

namespace Tests\Feature;

use App\Message;
use App\User;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ConversationsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function with_sending_contacts_id_conversations_must_return()
    {
        $this->withoutExceptionHandling();
        $this->be(factory(User::class)->create());
        factory(User::class)->create();
        $send = factory(Message::class, 5)->create(['from' => auth()->id()]);
        $receive = factory(Message::class, 5)->create(['to' => auth()->id()]);

        $response = $this->get(route('conversations', ['contact' => auth()->user()]))
            ->assertStatus(200);

        $this->assertIsArray($response->json());
    }

    /** @test */
    public function guests_can_not_get_conversations()
    {
        $this->withoutExceptionHandling();
        $this->expectException(AuthenticationException::class);
        $contact = factory(User::class)->create();

        $this->get(route('conversations', ['contact' => $contact]))->assertStatus(200);
    }

    /** @test */
    public function stored_conversation_returns_created_object()
    {
        $this->withoutExceptionHandling();
        $this->be(factory(User::class)->create());
        $contact = factory(User::class)->create()->id;
        $text = factory(Message::class)->make()->text;

        $response = $this->post(route('conversations.store'), compact('contact', 'text'))
            ->assertStatus(201);

        $this->assertContains($text, $response->json());
    }

    /** @test */
    public function guests_can_not_store_conversations()
    {
        $this->withoutExceptionHandling();
        $this->expectException(AuthenticationException::class);

        $this->post(route('conversations.store'));
    }
}
