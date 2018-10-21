<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    protected $guarded = [];

    public function votes()
    {
        return $this->morphMany('App\Models\Vote', 'votable');
    }
}
