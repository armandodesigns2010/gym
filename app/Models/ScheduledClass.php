<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ScheduledClass extends Model
{
    public function instructor()
    {
        return $this->belongsTo(User::class, 'instructor_id');
    }

    public function classType()
    {
        return $this->belongsTo(ClassType::class);
    }
}
