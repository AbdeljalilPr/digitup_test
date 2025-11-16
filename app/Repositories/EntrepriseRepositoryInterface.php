<?php
namespace App\Repositories;

use App\DTOs\CreateEmployeeDTO;
use App\DTOs\PurchaseSeatsDTO;
use App\DTOs\EnrollEmployeeDTO;

interface EntrepriseRepositoryInterface {
    public function createEmployee(CreateEmployeeDTO $dto);
    public function purchaseSeats(PurchaseSeatsDTO $dto);
    public function enrollEmployee(EnrollEmployeeDTO $dto);
    public function getEmployeesProgress(int $entreprise_id);
}
