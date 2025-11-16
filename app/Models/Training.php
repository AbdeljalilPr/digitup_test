<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Training extends Model
{
    protected $fillable = ['title', 'description', 'duration', 'niveau', 'categorie_id', 'price', 'start_date', 'max_participants', 'status', 'formateur_id'];
    //each traiging refer to one category
    public function categorie() {
    return $this->belongsTo(Category::class, 'categorie_id');
}
    //each traiging gas only one trainer
 public function formateur() {
        return $this->belongsTo(User::class, 'formateur_id');
    }

    //all regestartion of this training
    public function inscriptions() {
        return $this->hasMany(Enrollment::class);
    }
//all person registred in this training
public function apprenants() {
    return $this->belongsToMany(User::class, 'enrollments', 'training_id', 'user_id')
                ->withPivot('statut', 'note_finale')
                ->withTimestamps();
}
}
