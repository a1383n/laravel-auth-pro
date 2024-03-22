<?php

namespace LaravelAuthPro\Model;

use Illuminate\Database\Eloquent\Model;

class AuthenticatableProviders extends Model
{
    protected $casts = [
        'payload' => 'json',
    ];
}
