<?php
namespace App\DTOs;

class GradeEnrollmentDTO {
    public int $user_id;
    public int $training_id;
    public float $note_finale;
    public ?string $statut;

    public function __construct(int $user_id, int $training_id, float $note_finale, ?string $statut = null)
    {
        $this->user_id = $user_id;
        $this->training_id = $training_id;
        $this->note_finale = $note_finale;
        $this->statut = $statut;
    }
}
