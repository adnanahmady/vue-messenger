<?php

namespace App;

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
class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * number of Messages that are send to user
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function receives(): HasMany
    {
        return $this->hasMany(Message::class, Message::TO);
    }

    /**
     * number of Messages that are user did send to other contacts
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
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
     * @return void
     */
    public function scopeWhereIdNot($builder, int $id)
    {
        $builder->where('id', '!=', $id);
    }

    /**
     * Add's two alias for counting unread messages.
     *
     * @param Builder $builder Builder object.
     * @param User $user User object.
     *
     * @return void
     */
    public function scopeWithUnreadCount($builder, User $user): Builder
    {
        $builder->with(['sends' => function ($query) use ($user) {
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
