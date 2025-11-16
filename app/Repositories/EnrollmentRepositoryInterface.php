<?php
namespace App\Repositories;

use App\DTOs\EnrollToTrainingDTO;
use App\DTOs\GradeEnrollmentDTO;
use App\Models\Enrollment;

interface EnrollmentRepositoryInterface {
    public function enroll(EnrollToTrainingDTO $dto): Enrollment;
    public function getByUser(int $userId);
    public function grade(GradeEnrollmentDTO $dto): ?Enrollment;
}
