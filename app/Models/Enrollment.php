<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Enrollment extends Model
{
    //each enrolment is linked only with one user
    public function user() {
    return $this->belongsTo(User::class);
}
   //Each registration is linked to only one training.
public function training() {
    return $this->belongsTo(Training::class);
}
}
