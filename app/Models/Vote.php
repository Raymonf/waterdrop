<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Vote extends Model
{
    public const VOTABLES = [
        'report' => Report::class,
    ];

    protected $guarded = [];
}
