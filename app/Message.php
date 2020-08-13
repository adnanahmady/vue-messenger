<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Http\Request;

/**
 * Model Message
 * @package App
 */
class Message extends Model
{
    /**
     * @var "const" table column
     */
    const FROM = 'from';

    /**
     * @var "const" table column
     */
    const TO = 'to';

    /**
     * @var "const" table column
     */
    const TEXT = 'text';

    /**
     * @var "const" table column
     */
    const READ = 'read';

    /**
     * @var "const" query alias
     */
    const UNREAD_MESSAGES_COUNT = 'unread_messages_count';

    /**
     * @var "const" query alias
     */
    const SENDER_ID = 'sender_id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        self::FROM,
        self::TO,
        self::TEXT,
        self::READ,
    ];

    /**
     * Creates a new instance.
     *
     * @param Request $request Request object.
     *
     * @return Message
     */
    public static function newMessage(Request $request): Message
    {
        $message = new static;
        $message->{Message::FROM} = $request->user()->id;
        $message->{Message::TO} = $request->contact;
        $message->{Message::TEXT} = $request->text;
        $message->save();

        return $message;
    }

    /**
     * returns the user who send's message
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function fromContact(): HasOne
    {
        return $this->hasOne(User::class, 'id', 'from');
    }

    /**
     * returns the user who receive's message
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function toContact(): HasOne
    {
        return $this->hasOne(User::class, 'id', 'to');
    }

    /**
     * Marks message as read.
     *
     * @param Builder $builder Builder.
     * @param bool $markAsRead Flag for define update read column.
     *
     * @return void
     */
    public function scopeMarkAsRead($builder, bool $markAsRead = true)
    {
        $action = $markAsRead ? 'whereNull' : 'whereNotNull';

        $builder->$action(self::READ)->update([self::READ => $markAsRead ? Carbon::now() : null]);
    }

    /**
     * Sets query to receive conversations between
     * $userId and $contactId.
     *
     * @param Builder $builder Builder object.
     * @param int $userId User id.
     * @param int $contactId Contact id.
     *
     * @return void
     */
    public function scopeGetConversation(Builder $builder, int $userId, int $contactId)
    {
        $builder->where(function ($query) use ($userId, $contactId) {
            $query->where(Message::FROM, $userId);
            $query->where(Message::TO, $contactId);
        })->orWhere(function ($query) use ($userId, $contactId) {
            $query->where(Message::FROM, $contactId);
            $query->where(Message::TO, $userId);
        });
    }
}
