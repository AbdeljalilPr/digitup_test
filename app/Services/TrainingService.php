<?php
namespace App\Services;

use App\DTOs\CreateTrainingDTO;
use App\DTOs\UpdateTrainingDTO;
use App\Repositories\TrainingRepositoryInterface;
use App\Models\Training;

class TrainingService {
    protected TrainingRepositoryInterface $trainingRepo;

    public function __construct(TrainingRepositoryInterface $trainingRepo) {
        $this->trainingRepo = $trainingRepo;
    }

    public function getAll(array $filters = []) {
        return $this->trainingRepo->all($filters);
    }

    public function getById(int $id): ?Training {
        return $this->trainingRepo->find($id);
    }

    public function createTraining(CreateTrainingDTO $dto): Training {
        return $this->trainingRepo->create($dto);
    }

    public function updateTraining(Training $training, UpdateTrainingDTO $dto): Training {
        return $this->trainingRepo->update($training, $dto);
    }

    public function deleteTraining(Training $training): void {
        $this->trainingRepo->delete($training);
    }

    public function getByTrainer(int $trainerId) {
        return $this->trainingRepo->getByTrainer($trainerId);
    }

    public function getStatistics() {
        return $this->trainingRepo->statistics();
    }
}
