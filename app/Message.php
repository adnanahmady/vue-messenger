<?php

namespace App;

use App\Contracts\MessageInterface;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Http\Request;

/**
 * Model Message
 * @package App
 */
class Message extends Model implements MessageInterface
{
    /**
     * @var "const" table name
     */
    public const TABLE = 'messages';

    /**
     * @var "const" table primary key
     */
    public const ID = 'id';

    /**
     * @var "const" table column
     */
    public const FROM = 'from';

    /**
     * @var "const" table column
     */
    public const TO = 'to';

    /**
     * @var "const" table column
     */
    public const TEXT = 'text';

    /**
     * @var "const" table column
     */
    public const READ = 'read';

    /**
     * @var "const" query alias
     */
    public const UNREAD_MESSAGES_COUNT = 'unread_messages_count';

    /**
     * @var "const" query alias
     */
    public const SENDER_ID = 'sender_id';

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
     * @param array $data Data.
     *
     * @return MessageInterface
     */
    public static function newMessage(array $data): MessageInterface
    {
        $message = new static;

        foreach ($data as $key => $value) {
            $message->{$key} = $value;
        }
        $message->save();

        return $message;
    }

    /**
     * returns the user who send's message
     *
     * @return HasOne
     */
    public function fromContact(): HasOne
    {
        return $this->hasOne(User::class, User::ID, self::FROM);
    }

    /**
     * returns the user who receive's message
     *
     * @return HasOne
     */
    public function toContact(): HasOne
    {
        return $this->hasOne(User::class, User::ID, self::TO);
    }

    /**
     * Marks message as read.
     *
     * @param bool $markAsRead Flag for define update read column.
     *
     * @return MessageInterface
     */
    public function markAsRead(bool $markAsRead = true): MessageInterface
    {
        $action = $markAsRead ? 'whereNull' : 'whereNotNull';

        $this->$action(self::READ)->update([
            self::READ => $markAsRead ? Carbon::now() : null
        ]);

        return $this;
    }

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
    ): Builder {
        return $builder->where(function ($query) use ($userId, $contactId) {
            $query->where(Message::FROM, $userId);
            $query->where(Message::TO, $contactId);
        })->orWhere(function ($query) use ($userId, $contactId) {
            $query->where(Message::FROM, $contactId);
            $query->where(Message::TO, $userId);
        });
    }
}
