<?php
namespace App\Repositories;

use App\DTOs\CreateEmployeeDTO;
use App\DTOs\PurchaseSeatsDTO;
use App\DTOs\EnrollEmployeeDTO;
use App\Models\User;
use App\Models\Training;
use App\Models\Enrollment;
use App\Models\EntrepriseTrainingSeat;

class EntrepriseRepository implements EntrepriseRepositoryInterface {

    public function createEmployee(CreateEmployeeDTO $dto) {
        return User::create([
            'name' => $dto->name,
            'email' => $dto->email,
            'password' => bcrypt('password'),
            'role' => 'employee',
            'entreprise_id' => $dto->entreprise_id
        ]);
    }

    public function purchaseSeats(PurchaseSeatsDTO $dto) {
        $record = EntrepriseTrainingSeat::firstOrCreate([
            'entreprise_id' => $dto->entreprise_id,
            'training_id' => $dto->training_id
        ]);
        $record->increment('seats_purchased', $dto->seats);
        return $record;
    }

    public function enrollEmployee(EnrollEmployeeDTO $dto) {
        Enrollment::create([
            'training_id' => $dto->training_id,
            'user_id' => $dto->employee_id,
            'statut' => 'acceptee'
        ]);

        $seats = EntrepriseTrainingSeat::where('entreprise_id', $dto->entreprise_id)
            ->where('training_id', $dto->training_id)
            ->first();

        if($seats) {
            $seats->increment('seats_used');
        }

        return true;
    }

    public function getEmployeesProgress(int $entreprise_id) {
        return User::where('entreprise_id', $entreprise_id)
            ->with(['enrollments.training'])
            ->get();
    }
}
