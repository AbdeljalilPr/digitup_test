<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class EntrepriseTrainingSeat extends Model
{
    protected $fillable = [
        'entreprise_id',
        'training_id',
        'seats_purchased',
        'seats_used'
    ];

    public function entreprise()
    {
        return $this->belongsTo(Entreprise::class);
    }

    public function training()
    {
        return $this->belongsTo(Training::class);
    }
}
