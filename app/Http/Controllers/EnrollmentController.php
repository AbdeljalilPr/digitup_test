<?php

namespace App\Http\Controllers;

use App\Models\Enrollment;
use App\Models\Training;
use Illuminate\Http\Request;

class EnrollmentController extends Controller
{
    public function enroll(Request $request, Training $training) {
        $user = $request->user();

        //check if training is full
        $count = Enrollment::where('training_id', $training->id)->where('statut','acceptee')->count();
        if ($training->nombre_max_participants && $count >= $training->nombre_max_participants) {
            return response()->json(['message'=>'Training is full'], 422);
        }

        $en = Enrollment::updateOrCreate(
            ['user_id'=>$user->id, 'training_id'=>$training->id],
            ['statut'=>'en_attente']
        );

        return response()->json($en,201);
    }
    //return all enrollments of the apprenant ho logged in
    public function myEnrollments(Request $request) {
        $user = $request->user();
        return response()->json(Enrollment::with('training')->where('user_id',$user->id)->paginate(15));
    }

    //the formateur can grade the apprenants in his trainings
    public function grade(Request $request, Training $training) {
        // $this->authorize('grade', $training);
        if ($request->user()->role !== 'formateur' || $training->formateur_id !== $request->user()->id) {
            return response()->json(['message' => 'Forbidden'], 403);
        }
        $payload = $request->validate([
            'user_id'=>'required|exists:users,id',
            'note_finale'=>'required|numeric|min:0|max:100',
            'statut'=>'nullable|in:terminee,acceptee,en_attente'
        ]);

        $en = Enrollment::where('training_id',$training->id)->where('user_id',$payload['user_id'])->firstOrFail();
        $en->update([
            'note_finale'=>$payload['note_finale'],
            'statut'=>$payload['statut'] ?? $en->statut
        ]);
        return response()->json($en);
    }
}
