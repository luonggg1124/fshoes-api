<?php

namespace App\Policies;

class GroupPolicy
{
    /**
     * Determine whether the user can view the model.
     */
    public function view(): bool
    {
        $user= auth()->user();
        if(!$user)return false;
        return $user->hasPermissions("group"  , "view");
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(): bool
    {
        $user= auth()->user();
        if(!$user)return false;
        return $user->hasPermissions("group"  , "create");
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(): bool
    {
        $user= auth()->user();
        if(!$user)return false;
        return $user->hasPermissions("group"  , "update");
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(): bool
    {
        $user= auth()->user();
        if(!$user)return false;
        return $user->hasPermissions("group"  , "delete");
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(): bool
    {
        $user= auth()->user();
        if(!$user)return false;
        return $user->hasPermissions("group"  , "restore");
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(): bool
    {
        $user= auth()->user();
        if(!$user)return false;
        return $user->hasPermissions("group"  , "forceDelete");
    }
}
