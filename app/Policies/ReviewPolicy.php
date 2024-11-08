<?php

namespace App\Policies;

use App\Models\User;

class ReviewPolicy
{
    /**
     * Determine whether the user can view the model.
     */
    public function view(): bool
    {
        $user= auth()->user();
        if(!$user)return false;
        return $user->hasPermissions("review"  , "view");
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(): bool
    {
        $user= auth()->user();
        if(!$user)return false;
        return $user->hasPermissions("review"  , "create");
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(): bool
    {
        $user= auth()->user();
        if(!$user)return false;
        return $user->hasPermissions("review"  , "update");
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(): bool
    {
        $user= auth()->user();
        if(!$user)return false;
        return $user->hasPermissions("review"  , "delete");
    }
}
