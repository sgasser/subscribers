<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Subscriber extends Model
{
    public function fields()
    {
        return $this->belongsToMany(Field::class)->using(FieldSubscriber::class)->withTimestamps();
    }
}
