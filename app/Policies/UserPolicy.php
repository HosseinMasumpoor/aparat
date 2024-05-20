<?php

namespace App\Policies;

use App\Models\User;

class UserPolicy
{
    /**
     * Create a new policy instance.
     */
    public function __construct()
    {
        //
    }

    public function follow(User $user, User $following)
    {
        if ($user->id == $following->id || $user->followings()->find($following->id))
            return false;
        return true;
    }

    public function unfollow(User $user, User $following)
    {
        if ($user->id == $following->id || !$user->followings()->find($following->id))
            return false;
        return true;
    }
}
