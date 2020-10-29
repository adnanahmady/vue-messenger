<?php

namespace App;

use App\Contracts\UserInterface;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\DB;

/**
 * Class User
 * @package App
 */
class User extends Authenticatable implements UserInterface
{
    use Notifiable;

    /**
     * @var "const" table name
     */
    public const TABLE = 'users';

    /**
     * @var "const" table primary key
     */
    public const ID = 'id';

    /**
     * @var "const" table column
     */
    public const NAME = 'name';

    /**
     * @var "const" table column
     */
    public const EMAIL = 'email';

    /**
     * @var "const" table column
     */
    public const PASSWORD = 'password';

    /**
     * @var "const" table column
     */
    public const REMEMBER_TOKEN = 'remember_token';

    /**
     * @var "const" table column
     */
    public const PHONE = 'phone';

    /**
     * @var "const" table column
     */
    public const PROFILE_IMAGE = 'profile_image';

    /**
     * @var "const" table column
     */
    public const EMAIL_VERIFIED_AT = 'email_verified_at';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        self::NAME,
        self::EMAIL,
        self::PASSWORD,
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        self::PASSWORD,
        self::REMEMBER_TOKEN,
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        self::EMAIL_VERIFIED_AT => 'datetime',
    ];

    /**
     * number of Messages that are send to user
     *
     * @return HasMany
     */
    public function receives(): HasMany
    {
        return $this->hasMany(Message::class, Message::TO);
    }

    /**
     * number of Messages that are user did send to other contacts
     *
     * @return HasMany
     */
    public function sends(): HasMany
    {
        return $this->hasMany(Message::class, Message::FROM);
    }

    /**
     * Checks query not contain specified id.
     *
     * @param Builder $builder Builder object.
     * @param int $id Specified id.
     *
     * @return Builder
     */
    public function scopeWhereIdNot(Builder $builder, int $id): Builder
    {
        return $builder->where(self::ID, '!=', $id);
    }

    /**
     * Add's two alias for counting unread messages.
     *
     * @param Builder $builder Builder object.
     * @param User $user User object.
     *
     * @return Builder
     */
    public function scopeWithUnreadCount(Builder $builder, User $user): Builder
    {
        return $builder->with(['sends' => function ($query) use ($user) {
            $raw = sprintf(
                '"%1$s", "%1$s" AS %2$s, count("%1$s") AS %3$s',
                Message::FROM,
                Message::SENDER_ID,
                Message::UNREAD_MESSAGES_COUNT
            );

            $query
                ->select(DB::raw($raw))
                ->whereTo($user->id)
                ->whereNull(Message::READ)
                ->groupBy(Message::SENDER_ID);
        }]);
    }
}
