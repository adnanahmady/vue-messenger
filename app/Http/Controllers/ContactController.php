<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * This controller handles chat users operation.
 *
 * @package App\Http\Controllers
 */
class ContactController extends Controller
{
    /**
     * Returns all contacts except authenticated
     * user.
     *
     * @param Request $request Request.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        $contacts = User::withUnreadCount($user = $request->user())->whereIdNot($user->id)->get();

        return response()->json($contacts, 200);
    }
}
