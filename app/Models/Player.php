<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Player extends Model
{
    protected $fillable = [
        'ign',
        'power',
        'power_screenshot_path',
        'power_is_processed',
        'level',
        'status',
        'guild',
        'class'
    ];

    protected $casts = [
        'power_is_processed' => 'boolean',
    ];
}
