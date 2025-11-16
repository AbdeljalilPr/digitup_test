<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Entreprise extends Model
{
    protected $fillable = ['name'];

    public function employees()
    {
        return $this->hasMany(User::class);
    }

    public function seats()
    {
        return $this->hasMany(EntrepriseTrainingSeat::class);
    }
}
