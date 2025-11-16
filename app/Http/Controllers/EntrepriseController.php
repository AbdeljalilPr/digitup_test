<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\EntrepriseService;
use App\DTOs\CreateEmployeeDTO;
use App\DTOs\PurchaseSeatsDTO;
use App\DTOs\EnrollEmployeeDTO;
use App\Models\Training;

class EntrepriseController extends Controller
{
    protected EntrepriseService $service;

    public function __construct(EntrepriseService $service)
    {
        $this->service = $service;
    }

    public function createEmployee(Request $request)
    {
        $entreprise = $request->user();
        if ($entreprise->role !== 'entreprise') {
            return response()->json(['message'=>'Forbidden'], 403);
        }

        $data = $request->validate([
            'name'=>'required|string',
            'email'=>'required|email|unique:users,email'
        ]);

        $dto = new CreateEmployeeDTO(array_merge($data,['entreprise_id'=>$entreprise->id]));
        $employee = $this->service->createEmployee($dto);

        return response()->json($employee, 201);
    }

    public function purchaseSeats(Request $request, Training $training)
    {
        $entreprise = $request->user();
        if ($entreprise->role !== 'entreprise') {
            return response()->json(['message'=>'Forbidden'], 403);
        }

        $data = $request->validate([
            'seats'=>'required|integer|min:1'
        ]);

        $dto = new PurchaseSeatsDTO([
            'entreprise_id'=>$entreprise->id,
            'training_id'=>$training->id,
            'seats'=>$data['seats']
        ]);

        $record = $this->service->purchaseSeats($dto);

        return response()->json([
            'message'=>'Seats purchased successfully',
            'data'=>$record
        ]);
    }

    public function enrollEmployee(Request $request, Training $training)
    {
        $entreprise = $request->user();
        if ($entreprise->role !== 'entreprise') {
            return response()->json(['message'=>'Forbidden'], 403);
        }

        $data = $request->validate([
            'employee_id'=>'required|exists:users,id'
        ]);

        $dto = new EnrollEmployeeDTO([
            'employee_id'=>$data['employee_id'],
            'training_id'=>$training->id,
            'entreprise_id'=>$entreprise->id
        ]);

        $this->service->enrollEmployee($dto);

        return response()->json(['message'=>'Employee enrolled']);
    }

    public function employeesProgress(Request $request)
    {
        $entreprise = $request->user();
        if ($entreprise->role !== 'entreprise') {
            return response()->json(['message'=>'Forbidden'], 403);
        }

        $progress = $this->service->getEmployeesProgress($entreprise->id);

        return response()->json($progress);
    }
}
