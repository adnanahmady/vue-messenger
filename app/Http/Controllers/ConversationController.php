<?php

namespace App\Http\Controllers;

use App\Events\NewMessage;
use App\Http\Requests\CreateConversationRequest;
use App\Message;
use App\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * This controller handles messages operations.
 *
 * @package App\Http\Controllers
 */
class ConversationController extends Controller
{
    /**
     * Marks unread messages as read and
     * returns conversations between authenticated
     * user and given contact.
     *
     * @param Request $request Request.
     *
     * @param int $contactId Contact id.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request, $contactId): JsonResponse
    {
        Message::whereFrom($contactId)
            ->whereTo($request->user()->{User::ID})
            ->get()->each->markAsRead(true);
        $conversations = Message::GetConversation(
            $request->user()->{User::ID},
            $contactId
        )->get();

        return response()->json($conversations, 200);
    }

    /**
     * Stores new message.
     *
     * @param CreateConversationRequest $request Request.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(CreateConversationRequest $request): JsonResponse
    {
        $message = Message::newMessage([
            Message::FROM => $request->user()->{User::ID},
            Message::TO => $request->{CreateConversationRequest::CONTACT},
            Message::TEXT => $request->{CreateConversationRequest::TEXT},
        ]);

        broadcast(new NewMessage($message));

        return response()->json($message, 201);
    }
}
