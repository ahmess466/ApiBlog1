<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    protected $fillable = [
        'post_id',
        'comment',
        'user_id'
    ];

    protected $casts = [
        'post_id' => 'integer',  // Ensures post_id is treated as an integer
        'user_id' => 'integer',  // Ensures user_id is treated as an integer
    ];
}
