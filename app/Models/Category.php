<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
       protected $fillable = ['name', 'description', 'icon'];
    //echa category contains many trainings
    public function trainings() {
    return $this->hasMany(Training::class, 'categorie_id');
}
}
