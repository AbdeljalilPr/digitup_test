<?php
namespace App\DTOs;

class UpdateTrainingDTO {
    public ?string $title;
    public ?string $description;
    public ?string $level;
    public ?int $category_id;
    public ?float $price;
    public ?string $start_date;
    public ?int $max_participants;

    public function __construct(array $data)
    {
        $this->title = $data['title'] ?? null;
        $this->description = $data['description'] ?? null;
        $this->level = $data['level'] ?? null;
        $this->category_id = $data['category_id'] ?? null;
        $this->price = $data['price'] ?? null;
        $this->start_date = $data['start_date'] ?? null;
        $this->max_participants = $data['max_participants'] ?? null;
    }
}
