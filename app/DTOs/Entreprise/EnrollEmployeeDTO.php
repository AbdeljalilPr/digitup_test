<?php
namespace App\DTOs;

class EnrollEmployeeDTO {
    public int $employee_id;
    public int $training_id;
    public int $entreprise_id;

    public function __construct(array $data)
    {
        $this->employee_id = $data['employee_id'];
        $this->training_id = $data['training_id'];
        $this->entreprise_id = $data['entreprise_id'];
    }
}
