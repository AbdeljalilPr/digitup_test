<?php
namespace App\DTOs;

class CreateEmployeeDTO {
    public string $name;
    public string $email;
    public int $entreprise_id;

    public function __construct(array $data)
    {
        $this->name = $data['name'];
        $this->email = $data['email'];
        $this->entreprise_id = $data['entreprise_id'];
    }
}
