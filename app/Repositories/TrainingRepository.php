<?php
namespace App\Repositories;

use App\DTOs\CreateTrainingDTO;
use App\DTOs\UpdateTrainingDTO;
use App\Models\Training;

class TrainingRepository implements TrainingRepositoryInterface {

    public function all(array $filters = []) {
        $query = Training::query();
        if(isset($filters['category_id'])) {
            $query->where('category_id', $filters['category_id']);
        }
        if(isset($filters['level'])) {
            $query->where('level', $filters['level']);
        }
        return $query->paginate(15);
    }

    public function find(int $id): ?Training {
        return Training::find($id);
    }

    public function create(CreateTrainingDTO $dto): Training {
        return Training::create([
            'title' => $dto->title,
            'description' => $dto->description,
            'level' => $dto->level,
            'category_id' => $dto->category_id,
            'price' => $dto->price,
            'start_date' => $dto->start_date,
            'max_participants' => $dto->max_participants,
            'trainer_id' => $dto->trainer_id
        ]);
    }

    public function update(Training $training, UpdateTrainingDTO $dto): Training {
        $training->update(array_filter([
            'title' => $dto->title,
            'description' => $dto->description,
            'level' => $dto->level,
            'category_id' => $dto->category_id,
            'price' => $dto->price,
            'start_date' => $dto->start_date,
            'max_participants' => $dto->max_participants
        ]));
        return $training;
    }

    public function delete(Training $training): void {
        $training->delete();
    }

    public function getByTrainer(int $trainerId) {
        return Training::where('trainer_id', $trainerId)->paginate(15);
    }

    public function statistics() {
        return Training::withCount('enrollments')
            ->get()
            ->map(function($training) {
                $completed = $training->enrollments()->where('status','terminee')->count();
                $total = $training->enrollments_count;
                return [
                    'training_id' => $training->id,
                    'title' => $training->title,
                    'completion_rate' => $total ? round($completed/$total*100,2) : 0
                ];
            });
    }
}
