<?php

namespace App\Contracts;

use App\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\HasMany;

interface UserInterface
{
    /**
     * Add's two alias for counting unread messages.
     *
     * @param Builder $builder Builder object.
     * @param User $user User object.
     *
     * @return Builder
     */
    public function scopeWithUnreadCount(Builder $builder, User $user): Builder;

    /**
     * Checks query not contain specified id.
     *
     * @param Builder $builder Builder object.
     * @param int $id Specified id.
     *
     * @return Builder
     */
    public function scopeWhereIdNot(Builder $builder, int $id): Builder;

    /**
     * number of Messages that are user did send to other contacts
     *
     * @return HasMany
     */
    public function sends(): HasMany;

    /**
     * number of Messages that are send to user
     *
     * @return HasMany
     */
    public function receives(): HasMany;
}
