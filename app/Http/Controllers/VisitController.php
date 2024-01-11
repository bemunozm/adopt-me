<?php

namespace App\Http\Controllers;

use App\Models\Pet;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class VisitController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
{
        $pet = $request->input('pet_id'); 
        $pet = Pet::find($pet);
        $user = Auth::user(); 
   
        return view('pages.visit-create', compact('pet', 'user'));
}

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'meeting_type' => 'required|string|max:255',
            'meeting_date' => 'required|date',
            'meeting_time' => 'required|date_format:H:i',
            'notes' => 'nullable|string',
            'status' => 'required|string|in:Pendiente,Confirmada,Rechazada', // Asegúrate de que estos valores estén permitidos
            'pet_id' => 'required|integer|exists:pets,id', // Asegúrate de que el pet_id recibido sea válido
            'organization_id' => 'required|integer|exists:organizations,id', // Asegúrate de que el organization_id recibido sea válido
        ]);

        $pet = Pet::findorFail($request->pet_id);

        $meetingDateTime = Carbon::createFromFormat(
            'Y-m-d H:i',
            $validatedData['meeting_date'] . ' ' . $validatedData['meeting_time']
        );

        $visitId = DB::table('organization_pet')->insertGetId([
            'title' => $request->title,
            'meeting_type' => $request->meeting_type,
            'meeting_date' => $meetingDateTime,
            'notes' => $request->notes,
            'status' => $request->status,
            'pet_id' => $request->pet_id,
            'organization_id' => $request->organization_id,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        return redirect()->route('visit.show', $pet->id)->with('success', 'Visita creada con éxito.');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $user = auth()->user(); // Puedes usar auth()->user() directamente
        $pet = Pet::findOrFail($id);

        // Asegúrate de que la relación 'organizations' en el modelo Pet cargue los datos adicionales
        $meetings = $pet->organizations()->withPivot('id', 'title','meeting_date', 'status', 'meeting_type', 'notes')->get();
    
        return view('pages.visit-show', compact('pet', 'meetings', 'user'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
{
    $user = Auth::user(); 

    $visit = DB::table('organization_pet')->where('id', $id)->first();
    if (!$visit) {
        return redirect()->back()->with('error', 'La reunión no existe.');
    }

    // Pasar la reunión a la vista
    return view('pages.visit-edit', compact('visit', 'user'));
}


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($meetingId)
{
    $meeting = DB::table('organization_pet')->where('id', $meetingId)->first();
    $pet = Pet::find($meeting->pet_id);
    $deleted = DB::table('organization_pet')->where('id', $meetingId)->delete();

    if ($deleted) {
        return redirect()->route('visit.show', $pet->id)->with('success', 'La reunión ha sido eliminada.');
    } else {
        return redirect()->route('visit.show', $pet->id)->with('error', 'La reunión no pudo ser eliminada.');
    }
}

    public function changeStatus(Request $request, $visitId)
    {
        $pet = Pet::find($request->pet_id);
        // Asegúrate de validar el estado que estás recibiendo
        $request->validate(['status' => 'required|in:Confirmada,Cancelada']);

        // Actualiza directamente la tabla pivot
        DB::table('organization_pet')->where('id', $visitId)->update([
            'status' => $request->status,
        ]);

        return redirect()->route('visit.show', $pet->id)->with('success', 'El estado de la visita ha sido actualizado.');
    }
}
