<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\EnrollmentService;
use App\DTOs\EnrollToTrainingDTO;
use App\DTOs\GradeEnrollmentDTO;
use App\Models\Training;

class EnrollmentController extends Controller
{
    protected EnrollmentService $enrollmentService;

    public function __construct(EnrollmentService $enrollmentService)
    {
        $this->enrollmentService = $enrollmentService;
    }

    public function enroll(Request $request, Training $training)
    {
        $dto = new EnrollToTrainingDTO($request->user()->id, $training->id);

        try {
            $enrollment = $this->enrollmentService->enrollUser($dto, $training);
            return response()->json($enrollment, 201);
        } catch (\Exception $e) {
            return response()->json(['message'=>$e->getMessage()], 400);
        }
    }

    public function myEnrollments(Request $request)
    {
        return response()->json($this->enrollmentService->getUserEnrollments($request->user()->id));
    }

    public function grade(Request $request, Training $training)
    {
        $data = $request->validate([
            'user_id' => 'required|exists:users,id',
            'note_finale' => 'required|numeric|min:0|max:100',
            'statut' => 'nullable|in:terminee,acceptee,en_attente'
        ]);

        $dto = new GradeEnrollmentDTO(
            $data['user_id'],
            $training->id,
            $data['note_finale'],
            $data['statut'] ?? null
        );

        try {
            $enrollment = $this->enrollmentService->gradeEnrollment(
                $dto,
                $training,
                $request->user()->role,
                $request->user()->id
            );

            if(!$enrollment) {
                return response()->json(['message'=>'User not enrolled'], 404);
            }

            return response()->json($enrollment);
        } catch (\Exception $e) {
            return response()->json(['message'=>$e->getMessage()], $e->getCode() ?: 400);
        }
    }
}
