<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class EntrepriseController extends Controller
{
    //
public function createEmployee(Request $request)
{
    $entreprise = $request->user();

    if ($entreprise->role !== 'entreprise') {
        return response()->json(['message' => 'Forbidden'], 403);
    }

    $data = $request->validate([
        'name' => 'required|string',
        'email' => 'required|email|unique:users,email',
    ]);

    $employee = User::create([
        'name' => $data['name'],
        'email' => $data['email'],
        'password' => bcrypt('password'),
        'role' => 'employee',
        'entreprise_id' => $entreprise->id
    ]);

    return response()->json($employee, 201);
}

public function purchaseSeats(Request $request, Training $training)
{
    $entreprise = $request->user();

    if ($entreprise->role !== 'entreprise') {
        return response()->json(['message' => 'Forbidden'], 403);
    }

    $data = $request->validate([
        'seats' => 'required|integer|min:1'
    ]);

    $record = EntrepriseTrainingSeat::firstOrCreate(
        [
            'entreprise_id' => $entreprise->id,
            'training_id' => $training->id
        ]
    );

    $record->increment('seats_purchased', $data['seats']);

    return response()->json([
        'message' => 'Seats purchased successfully',
        'data' => $record
    ]);
}

public function enrollEmployee(Request $request, Training $training)
{
    $entreprise = $request->user();

    if ($entreprise->role !== 'entreprise') {
        return response()->json(['message' => 'Forbidden'], 403);
    }

    $data = $request->validate([
        'employee_id' => 'required|exists:users,id'
    ]);

    $employee = User::find($data['employee_id']);

    if ($employee->entreprise_id !== $entreprise->id) {
        return response()->json(['message' => 'Employee not part of this entreprise'], 403);
    }

    $seats = EntrepriseTrainingSeat::where('entreprise_id', $entreprise->id)
        ->where('training_id', $training->id)
        ->first();

    if (!$seats) {
        return response()->json(['message' => 'No seats purchased'], 400);
    }

    if ($seats->seats_used >= $seats->seats_purchased) {
        return response()->json(['message' => 'No available seats'], 400);
    }

    Enrollment::create([
        'training_id' => $training->id,
        'user_id' => $employee->id,
        'statut' => 'acceptee'
    ]);

    $seats->increment('seats_used');

    return response()->json(['message' => 'Employee enrolled']);
}

public function employeesProgress(Request $request)
{
    $entreprise = $request->user();

    if ($entreprise->role !== 'entreprise') {
        return response()->json(['message' => 'Forbidden'], 403);
    }

    $employees = User::where('entreprise_id', $entreprise->id)
        ->with(['enrollments.training'])
        ->get();

    return response()->json($employees);
}
}
