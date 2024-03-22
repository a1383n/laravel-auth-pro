<?php

namespace LaravelAuthPro\Model;

use Illuminate\Database\Eloquent\Model;

class AuthenticatableProviders extends Model
{
    protected $fillable = [
        'provider_type',
        'provider_id',
        'payload'
    ];

    protected $casts = [
        'payload' => 'json',
    ];
}
