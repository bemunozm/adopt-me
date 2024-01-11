<?php

namespace App\Http\Controllers;

use App\Models\Adopter;
use App\Models\Pet;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdopterController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = auth()->id();
        $user = User::find($user);
        return view('pages.adopters.dashboard', compact('user'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('auth.adopter-register');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Inicializar el arreglo de datos para la creación
        $collectionData = $request->all();

        // Verificar si se subió una imagen y procesarla
        if ($request->hasFile('adopter_profile_image')) {
            $image = $request->file('adopter_profile_image');
            $path = $image->store('adopters', 'public');
            
            // Añadir la ruta de la imagen al arreglo de datos
            $collectionData['adopter_profile_image'] = $path;
        }

        if ($request->hasFile('adopter_cover_image')) {
            $image = $request->file('adopter_cover_image');
            $path = $image->store('adopters', 'public');
            
            // Añadir la ruta de la imagen al arreglo de datos
            $collectionData['adopter_cover_image'] = $path;
        }

        // Crear la colección con los datos del formulario
        Adopter::create($collectionData);

       return redirect()->intended(session('url.intended') ?? route('adopter.index'));
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
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
    public function destroy(string $id)
    {
        //
    }

    public function approve($id, Request $request) {
        $user = auth()->user();
        $pet = Pet::findOrFail($id);
        $statusFilter = $request->input('status', 'Pendiente'); // El filtro predeterminado puede ser 'Pendiente'
    
        // Inicializa la consulta con una relación
        $approvalsQuery = $pet->adopters();
    
        // Aplica el filtro de estado si se ha proporcionado uno
        if (!is_null($statusFilter)) {
            $approvalsQuery->wherePivot('status', $statusFilter);
        }
    
        // Obtiene los registros filtrados o todos si no se ha aplicado ningún filtro
        $pendingApprovals = $approvalsQuery->get();
        
        // Si no hay registros y se ha aplicado un filtro, $approvals será una colección vacía
        return view('pages.adopter-approve', compact('pet', 'user', 'pendingApprovals', 'statusFilter'));
    }

    public function changeStatus(Request $request)
{
    $relations = $request->input('relations', []);

    foreach ($relations as $relation) {
        $pivotId = $relation['pivotId'];
        $newStatus = $relation['status'];

        // Encuentra la relación específica
        $relation = DB::table('adopter_pet')->find($pivotId);
        $petId = $relation->pet_id;

        // Verifica si ya hay una adopción aceptada para este pet
        $existingAdoption = DB::table('adopter_pet')
            ->where('pet_id', $petId)
            ->where('status', 'Aceptado')
            ->first();

        if ($newStatus == 'Aceptado' && $existingAdoption) {
            session()->flash('error', "La adopción para el pet con ID {$petId} ya ha sido aceptada y no se puede cambiar a 'Aceptado'.");
            continue;   
        }

        // Actualiza el estado de la relación
        DB::table('adopter_pet')
            ->where('id', $pivotId)
            ->update(['status' => $newStatus]);

            $acceptedAdoptionExists = DB::table('adopter_pet')
            ->where('pet_id', $petId)
            ->where('status', 'Aceptado')
            ->exists();
            DB::table('pets')
            ->where('id', $petId)
            ->update(['status' => $acceptedAdoptionExists ? 'Adoptado' : 'Sin Adoptar']);
    
        // Si el nuevo estado es 'Aceptado', actualizar el estado del pet
        if ($newStatus == 'Aceptado') {
            DB::table('pets')
                ->where('id', $petId)
                ->update(['status' => 'Adoptado']);
        }
    }

    return redirect()->route('pet.index')->with('success', 'Estados actualizados correctamente.');
}
}
