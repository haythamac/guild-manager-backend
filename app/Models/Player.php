<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

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
        'class',
        'member_access_code',
        'discord'
    ];

    protected $casts = [
        'power_is_processed' => 'boolean',
    ];

    /**
     * Generate a unique member access code
     *
     * @return string
     */
    public static function generateMemberAccessCode()
    {
        $code = strtoupper(Str::random(16));
        
        // Ensure the code is unique
        while (self::where('member_access_code', $code)->exists()) {
            $code = strtoupper(Str::random(16));
        }
        
        return $code;
    }
}
