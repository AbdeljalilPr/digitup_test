<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\TrainingService;
use App\DTOs\CreateTrainingDTO;
use App\DTOs\UpdateTrainingDTO;
use App\Models\Training;

class TrainingController extends Controller
{
    protected TrainingService $trainingService;

    public function __construct(TrainingService $trainingService)
    {
        $this->trainingService = $trainingService;
    }

    public function index(Request $request)
    {
        $filters = $request->only(['category_id','level']);
        return response()->json($this->trainingService->getAll($filters));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title'=>'required|string',
            'description'=>'nullable|string',
            'level'=>'required|in:beginner,intermediate,expert',
            'category_id'=>'required|exists:categories,id',
            'price'=>'required|numeric',
            'start_date'=>'required|date',
            'max_participants'=>'required|integer'
        ]);

        $dto = new CreateTrainingDTO(array_merge($data,['trainer_id'=>auth()->id()]));
        $training = $this->trainingService->createTraining($dto);

        return response()->json($training, 201);
    }

    public function show(Training $training)
    {
        return response()->json($training);
    }

    public function update(Request $request, Training $training)
    {
        $data = $request->validate([
            'title'=>'sometimes|string',
            'description'=>'sometimes|string',
            'level'=>'sometimes|in:beginner,intermediate,expert',
            'category_id'=>'sometimes|exists:categories,id',
            'price'=>'sometimes|numeric',
            'start_date'=>'sometimes|date',
            'max_participants'=>'sometimes|integer'
        ]);

        $dto = new UpdateTrainingDTO($data);
        $training = $this->trainingService->updateTraining($training, $dto);

        return response()->json($training);
    }

    public function destroy(Training $training)
    {
        $this->trainingService->deleteTraining($training);
        return response()->json(['message'=>'Training deleted successfully']);
    }

    public function myTrainings()
    {
        return response()->json($this->trainingService->getByTrainer(auth()->id()));
    }

    public function trainingStatistics()
    {
        return response()->json($this->trainingService->getStatistics());
    }
}
