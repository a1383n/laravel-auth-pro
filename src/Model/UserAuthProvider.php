<?php

namespace LaravelAuthPro\Model;

use Illuminate\Database\Eloquent\Model;

class UserAuthProvider extends Model
{
    protected $casts = [
        'payload' => 'json',
    ];
}
