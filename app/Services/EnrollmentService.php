<?php
namespace App\Services;

use App\DTOs\EnrollToTrainingDTO;
use App\DTOs\GradeEnrollmentDTO;
use App\Repositories\EnrollmentRepositoryInterface;
use App\Models\Training;

class EnrollmentService {
    protected EnrollmentRepositoryInterface $enrollmentRepo;

    public function __construct(EnrollmentRepositoryInterface $enrollmentRepo)
    {
        $this->enrollmentRepo = $enrollmentRepo;
    }

    public function enrollUser(EnrollToTrainingDTO $dto, Training $training)
    {
        if($training->enrollments()->where('user_id', $dto->user_id)->exists()){
            throw new \Exception('Already enrolled');
        }

        if($training->enrollments()->count() >= $training->max_participants){
            throw new \Exception('Training full');
        }

        return $this->enrollmentRepo->enroll($dto);
    }

    public function getUserEnrollments(int $userId) {
        return $this->enrollmentRepo->getByUser($userId);
    }

    public function gradeEnrollment(GradeEnrollmentDTO $dto, Training $training, $userRole, $currentUserId) {
        // Authorization check
        if($userRole === 'formateur' && $training->trainer_id !== $currentUserId) {
            throw new \Exception('Forbidden', 403);
        }

        return $this->enrollmentRepo->grade($dto);
    }
}
