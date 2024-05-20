<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Video;
use App\Models\VideoRepublish;
use Illuminate\Auth\Access\Response;

class VideoPolicy
{
    /**
     * Determine whether the user can change video status
     */
    public function changeState(User $user, Video $video = null)
    {
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can republish video
     */
    public function republish(User $user, Video $video = null)
    {
        return (
            $user->id != $video->user_id &&
            VideoRepublish::where([
                'user_id' => $user->id,
                'video_id' => $video->id
            ])->count() == 0
        );
    }

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user)
    {
        //
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Video $video)
    {
        //
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user)
    {
        //
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Video $video)
    {
        //
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Video $video)
    {
        //
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Video $video)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Video $video)
    {
        //
    }
}
