<?php
namespace App\DTOs;

class CreateTrainingDTO {
    public string $title;
    public ?string $description;
    public string $level;
    public int $category_id;
    public float $price;
    public string $start_date;
    public int $max_participants;
    public int $trainer_id;

    public function __construct(array $data)
    {
        $this->title = $data['title'];
        $this->description = $data['description'] ?? null;
        $this->level = $data['level'];
        $this->category_id = $data['category_id'];
        $this->price = $data['price'];
        $this->start_date = $data['start_date'];
        $this->max_participants = $data['max_participants'];
        $this->trainer_id = $data['trainer_id'];
    }
}
