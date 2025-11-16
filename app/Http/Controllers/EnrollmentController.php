<?php

namespace App\Http\Controllers;

use App\Models\Enrollment;
use App\Models\Training;
use Illuminate\Http\Request;

class EnrollmentController extends Controller
{
   public function enroll(Request $request, Training $training)
{
    $user = $request->user();

    // Already enrolled?
    if($training->enrollments()->where('user_id',$user->id)->exists()){
        return response()->json(['message'=>'Already enrolled'], 400);
    }

    // Check max participants
    if($training->enrollments()->count() >= $training->max_participants){
        return response()->json(['message'=>'Training full'], 400);
    }

    $enrollment = $training->enrollments()->create([
        'user_id' => $user->id,
        'status' => 'en_attente'
    ]);

    return response()->json($enrollment, 201);
}
    //return all enrollments of the apprenant ho logged in
    public function myEnrollments(Request $request) {
        $user = $request->user();
        return response()->json(Enrollment::with('training')->where('user_id',$user->id)->paginate(15));
    }

    //the formateur can grade the apprenants in his trainings
    public function grade(Request $request, Training $training)
{
    $user = $request->user();

    if ($user->role === 'admin') {
        // allowed
    }
    elseif ($user->role === 'formateur') {
        if ($training->formateur_id !== $user->id) {
            return response()->json(['message' => 'Forbidden'], 403);
        }
    }
    else {
        return response()->json(['message' => 'Forbidden'], 403);
    }

    $data = $request->validate([
        'user_id' => 'required|exists:users,id',
        'note_finale' => 'required|numeric|min:0|max:100',
        'statut' => 'nullable|in:terminee,acceptee,en_attente'
    ]);

    $enrollment = Enrollment::where('training_id', $training->id)
        ->where('user_id', $data['user_id'])
        ->first();

    if (!$enrollment) {
        return response()->json(['message' => 'User not enrolled'], 404);
    }

    $enrollment->update([
        'note_finale' => $data['note_finale'],
        'statut' => $data['statut'] ?? $enrollment->statut
    ]);

    return response()->json($enrollment);
}
}
