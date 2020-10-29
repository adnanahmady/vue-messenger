<?php

namespace Tests\Feature;

use App\Http\Requests\CreateConversationRequest;
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
        $send = factory(Message::class, $amount = 5)->create([Message::FROM => auth()->id()]);
        $receive = factory(Message::class, $amount)->create([Message::TO => auth()->id()]);

        $response = $this->get(route('conversations', [
            CreateConversationRequest::CONTACT => auth()->user()
        ]))->assertStatus(200);

        $this->assertIsArray($response->json());
    }

    /** @test */
    public function guests_can_not_get_conversations()
    {
        $this->withoutExceptionHandling();
        $this->expectException(AuthenticationException::class);
        $contact = factory(User::class)->create();

        $this->get(route('conversations', [
            CreateConversationRequest::CONTACT => $contact
        ]))->assertStatus(200);
    }

    /** @test */
    public function stored_conversation_returns_created_object()
    {
        $this->withoutExceptionHandling();
        $this->be(factory(User::class)->create());
        $contact = factory(User::class)->create()->{User::ID};
        $text = factory(Message::class)->make()->{Message::TEXT};

        $response = $this->post(route('conversations.store'), compact(
            CreateConversationRequest::CONTACT,
            CreateConversationRequest::TEXT
        ))->assertStatus(201);

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
