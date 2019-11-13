<?php

namespace BlackIT\FCMAble\Models;

use Illuminate\Database\Eloquent\Model;

class FCMToken extends Model
{
    protected $table='fcm_tokens';
    protected $fillable = [
        'token',
        'agent',
        'application'
    ];

    public function fcmable()
    {
        return $this->morphTo();
    }
}
