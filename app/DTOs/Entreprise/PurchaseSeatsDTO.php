<?php
namespace App\DTOs;

class PurchaseSeatsDTO {
    public int $entreprise_id;
    public int $training_id;
    public int $seats;

    public function __construct(array $data)
    {
        $this->entreprise_id = $data['entreprise_id'];
        $this->training_id = $data['training_id'];
        $this->seats = $data['seats'];
    }
}
