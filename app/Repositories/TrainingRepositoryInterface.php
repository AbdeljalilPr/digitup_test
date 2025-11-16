<?php
namespace App\Repositories;

use App\DTOs\CreateTrainingDTO;
use App\DTOs\UpdateTrainingDTO;
use App\Models\Training;

interface TrainingRepositoryInterface {
    public function all(array $filters = []);
    public function find(int $id): ?Training;
    public function create(CreateTrainingDTO $dto): Training;
    public function update(Training $training, UpdateTrainingDTO $dto): Training;
    public function delete(Training $training): void;
    public function getByTrainer(int $trainerId);
    public function statistics();
}
