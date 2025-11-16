<?php
namespace App\Repositories;

use App\DTOs\EnrollToTrainingDTO;
use App\DTOs\GradeEnrollmentDTO;
use App\Models\Enrollment;
use App\Models\Training;

class EnrollmentRepository implements EnrollmentRepositoryInterface {

    public function enroll(EnrollToTrainingDTO $dto): Enrollment {
        return Enrollment::create([
            'user_id' => $dto->user_id,
            'training_id' => $dto->training_id,
            'status' => $dto->status
        ]);
    }

    public function getByUser(int $userId) {
        return Enrollment::with('training')->where('user_id', $userId)->paginate(15);
    }

    public function grade(GradeEnrollmentDTO $dto): ?Enrollment {
        $enrollment = Enrollment::where('user_id', $dto->user_id)
            ->where('training_id', $dto->training_id)
            ->first();

        if ($enrollment) {
            $enrollment->update([
                'note_finale' => $dto->note_finale,
                'statut' => $dto->statut ?? $enrollment->statut
            ]);
        }

        return $enrollment;
    }
}
