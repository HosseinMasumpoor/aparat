<?php

namespace App\Observers;

use App\Models\Channel;
use App\Models\User;
use Str;

class UserObserver
{
    /**
     * Handle the User "created" event.
     */
    public function created(User $user): void
    {
        $channelName = !empty($user->email) ? Str::before($user->email, '@') : Str::after($user->mobile, '+98');
        Channel::create([
            'name' => $channelName,
            'user_id' => $user->id
        ]);
    }

    /**
     * Handle the User "updated" event.
     */
    public function updated(User $user): void
    {
        //
    }

    /**
     * Handle the User "deleted" event.
     */
    public function deleted(User $user): void
    {
        //
    }

    /**
     * Handle the User "restored" event.
     */
    public function restored(User $user): void
    {
        //
    }

    /**
     * Handle the User "force deleted" event.
     */
    public function forceDeleted(User $user): void
    {
        //
    }
}
