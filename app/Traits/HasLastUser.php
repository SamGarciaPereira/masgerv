<?php

namespace App\Traits;

use App\Models\User;
use Illuminate\Support\Facades\Auth;

trait HasLastUser
{
    public static function bootHasLastUser()
    {
        static::saving(function ($model) {
            if (Auth::check()) {
                $model->last_user_id = Auth::id();
            }
        });
    }

    public function editor()
    {
        return $this->belongsTo(User::class, 'last_user_id');
    }
}