<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Training;
class TrainingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    //show all trainings
   public function index(Request $request)
{
    $q = Training::with('category','formateur')
        ->withCount('enrollments');

    // keyword search
    if ($request->filled('q')) {
        $q->where('titre','like','%'.$request->q.'%');
    }

    // filter by category
    if ($request->filled('category_id')) {
        $q->where('category_id', $request->category_id);
    }

    // filter by level
    if ($request->filled('level')) {
        $q->where('niveau', $request->level);
    }

    // order by popularity
    if ($request->order_by_popularity) {
        $q->orderBy('enrollments_count', 'desc');
    }

    // default latest
    else {
        $q->orderBy('id','desc');
    }

    return response()->json($q->paginate(15));
}
    /**
     * Store a newly created resource in storage.
     */
    //create new training
    public function store(Request $request)
    {
        //
        $data = $request->validate([
            'titre'=>'required|string',
            'description'=>'nullable|string',
            'duree'=>'required|integer',
            'niveau'=>'required|in:debutant,intermediaire,expert',
            'categorie_id'=>'required|exists:categories,id',
            'prix'=>'required|numeric',
            'date_debut'=>'required|date',
            'nombre_max_participants'=>'nullable|integer',
            'statut'=>'nullable|in:en_cours,terminee,annulee',
        ]);

        $data['formateur_id'] = $request->user()->id;
        $training = Training::create($data);
        return response()->json($training,201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Training $training)
    {
        //
        return response()->json($training->load('category','formateur'));
    }

    /**
     * Update the specified resource in storage.
     */

    //the formateur can update only his training
    public function update(Request $request, Training $training)
{
    // Admin can edit everything
    if ($request->user()->role === 'admin') {
        // OK
    }
    // Formateur can edit ONLY his trainings
    elseif ($request->user()->role === 'formateur') {
        if ($training->formateur_id !== $request->user()->id) {
            return response()->json(['message' => 'Forbidden'], 403);
        }
    }
    // Others (apprenant...) are forbidden
    else {
        return response()->json(['message' => 'Forbidden'], 403);
    }

    // Validation
    $data = $request->validate([
        'titre' => 'sometimes|string',
        'description' => 'nullable|string',
        'duree' => 'sometimes|integer',
        'niveau' => 'sometimes|in:debutant,intermediaire,expert',
        'categorie_id' => 'sometimes|exists:categories,id',
        'prix' => 'sometimes|numeric',
        'date_debut' => 'sometimes|date',
        'nombre_max_participants' => 'nullable|integer',
        'statut' => 'nullable|in:en_cours,terminee,annulee',
    ]);

    $training->update($data);

    return response()->json($training);
}
    /**
     * Remove the specified resource from storage.
     */
    //delete training
    public function destroy(Request $request,Training $training)
    {
        //
        //  $this->authorize('delete', $training);
            if ($request->user()->role !== 'formateur' || $training->formateur_id !== $request->user()->id) {
        return response()->json(['message' => 'Forbidden'], 403);
    }
        $training->delete();
        return response()->json(['message'=>'deleted']);
    }
     //showing the trainings of the formatuer
      public function myTrainings(Request $request) {
        $user = $request->user();
        return response()->json(Training::where('formateur_id',$user->id)->paginate(15));


    }
public function trainingStatistics()
{
    $trainings = Training::withCount('enrollments')->get();

    $data = $trainings->map(function($training) {
        $completed = $training->enrollments()->where('status','terminee')->count();
        $total = $training->enrollments_count;
        $completionRate = $total ? ($completed / $total) * 100 : 0;

        return [
            'training_id' => $training->id,
            'title' => $training->title,
            'total_enrollments' => $total,
            'completion_rate' => round($completionRate, 2),
        ];
    });

    $mostPopular = $trainings->sortByDesc('enrollments_count')->take(5);

    return response()->json([
        'all_trainings' => $data,
        'most_popular' => $mostPopular
    ]);
}}



