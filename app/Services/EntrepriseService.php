<?php
namespace App\Services;

use App\Repositories\EntrepriseRepositoryInterface;
use App\DTOs\CreateEmployeeDTO;
use App\DTOs\PurchaseSeatsDTO;
use App\DTOs\EnrollEmployeeDTO;

class EntrepriseService {

    protected EntrepriseRepositoryInterface $repo;

    public function __construct(EntrepriseRepositoryInterface $repo)
    {
        $this->repo = $repo;
    }

    public function createEmployee(CreateEmployeeDTO $dto) {
        return $this->repo->createEmployee($dto);
    }

    public function purchaseSeats(PurchaseSeatsDTO $dto) {
        return $this->repo->purchaseSeats($dto);
    }

    public function enrollEmployee(EnrollEmployeeDTO $dto) {
        return $this->repo->enrollEmployee($dto);
    }

    public function getEmployeesProgress(int $entreprise_id) {
        return $this->repo->getEmployeesProgress($entreprise_id);
    }
}
