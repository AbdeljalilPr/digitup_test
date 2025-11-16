<?php
namespace App\DTOs;

class EnrollToTrainingDTO {
    public int $user_id;
    public int $training_id;
    public string $status;

    public function __construct(int $user_id, int $training_id, string $status = 'en_attente')
    {
        $this->user_id = $user_id;
        $this->training_id = $training_id;
        $this->status = $status;
    }
}
