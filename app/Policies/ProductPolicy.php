<?php

namespace App\Policies;

use App\Models\User;

class ProductPolicy
{
    /**
     * Determine whether the user can view the model.
     */
    public function view(): bool
    {
        $user= auth()->user();
        if(!$user)return false;
        return $user->hasPermissions("product"  , "view");
    }

    public function detail(): bool
    {
        $user= auth()->user();
        if(!$user)return false;
        return $user->hasPermissions("product"  , "detail");
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(): bool
    {
        $user= auth()->user();
        if(!$user)return false;
        return $user->hasPermissions("product"  , "create");
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(): bool
    {
        $user= auth()->user();
        if(!$user)return false;
        return $user->hasPermissions("product"  , "update");
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(): bool
    {
        $user= auth()->user();
        if(!$user)return false;
        return $user->hasPermissions("product"  , "delete");
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(): bool
    {
        $user= auth()->user();
        if(!$user)return false;
        return $user->hasPermissions("product"  , "restore");
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(): bool
    {
        $user= auth()->user();
        if(!$user)return false;
        return $user->hasPermissions("product"  , "forceDelete");
    }
}
