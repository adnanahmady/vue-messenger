<?php

namespace App\Contracts;

use App\Message;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Http\Request;

interface MessageInterface
{
    /**
     * Marks message as read.
     *
     * @param bool $markAsRead Flag for define update read column.
     *
     * @return MessageInterface
     */
    public function markAsRead(bool $markAsRead = true): MessageInterface;

    /**
     * Sets query to receive conversations between
     * $userId and $contactId.
     *
     * @param Builder $builder Builder object.
     * @param int $userId User id.
     * @param int $contactId Contact id.
     *
     * @return Builder
     */
    public function scopeGetConversation(
        Builder $builder,
        int $userId,
        int $contactId
    ): Builder;

    /**
     * Creates a new instance.
     *
     * @param array $data Data.
     *
     * @return Message
     */
    public static function newMessage(array $data): MessageInterface;

    /**
     * returns the user who receive's message
     *
     * @return HasOne
     */
    public function toContact(): HasOne;

    /**
     * returns the user who send's message
     *
     * @return HasOne
     */
    public function fromContact(): HasOne;
}
