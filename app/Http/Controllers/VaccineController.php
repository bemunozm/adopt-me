<?php

namespace App\Http\Controllers;

use App\Models\Pet;
use App\Models\User;
use App\Models\Vaccine;
use Faker\Provider\ar_EG\Person;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class VaccineController extends Controller
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
   
        return view('pages.vaccine-create', compact('pet', 'user'));
}

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validación de los datos
        $validatedData = $request->validate([
            'title' => 'required|max:255',
            'description' => 'required',
            'date' => 'required|date',
            'vet' => 'required|max:255',
            'image' => 'nullable|image',
            'pet_id' => 'required|exists:pets,id' // Asegúrate de que el pet_id exista en la tabla pets
        ]);

        // Manejar la subida de la imagen
        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('vaccine_certificates', 'public');
        }

        // Crear el registro de la vacuna
        Vaccine::create([
            'name' => $validatedData['title'],
            'description' => $validatedData['description'],
            'date' => $validatedData['date'],
            'vet' => $validatedData['vet'],
            'image' => $imagePath,
            'pet_id' => $validatedData['pet_id']
        ]);

        // Redirigir a donde sea necesario después de guardar la vacuna
        return redirect()->route('vaccine.show', $request->pet_id)->with('success', 'Vacuna agregada correctamente.');
    }
    /**
     * Display the specified resource.
     */
    public function show( $id)
    {
        $user = Auth::user(); 
        $pet = Pet::find($id);
        $adopter = auth()->user()->adopter;
        $vaccines = Vaccine::where('pet_id', $id)->get();
        return view('pages.vaccine-show', compact('vaccines', 'user', 'pet'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $user = auth()->id();
        $user = User::find($user);
        $vaccine = Vaccine::findOrFail($id);
        return view('pages.vaccine-edit', compact('vaccine', 'user'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        // Validación de los datos
        $validatedData = $request->validate([
            'title' => 'required|max:255',
            'description' => 'required',
            'date' => 'required|date',
            'vet' => 'required|max:255',
            'image' => 'nullable|image',
            'pet_id' => 'required|exists:pets,id' // Asegúrate de que el pet_id exista en la tabla pets
        ]);

        // Encuentra la vacuna por ID
        $vaccine = Vaccine::findOrFail($id);

        // Manejar la subida de la nueva imagen y eliminar la antigua si es necesario
        if ($request->hasFile('image')) {
            // Eliminar la imagen antigua si existe
            if ($vaccine->image) {
                Storage::disk('public')->delete('vaccine_certificates/' . $vaccine->image);
            }

            // Guardar la nueva imagen y obtener la ruta
            $imagePath = $request->file('image')->store('vaccine_certificates', 'public');
            $vaccine->image = $imagePath;
        }

        // Actualizar los otros datos de la vacuna
        $vaccine->name = $validatedData['title'];
        $vaccine->description = $validatedData['description'];
        $vaccine->date = $validatedData['date'];
        $vaccine->vet = $validatedData['vet'];
        $vaccine->pet_id = $validatedData['pet_id'];

        $vaccine->save();

        // Redirigir a donde sea necesario después de actualizar la vacuna
        return redirect()->route('vaccine.show', $request->pet_id)->with('success', 'Vacuna actualizada correctamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        // Encuentra la vacuna por ID
        $vaccine = Vaccine::findOrFail($id);

        // Si la vacuna tiene una imagen asociada, elimínala del sistema de archivos
        if ($vaccine->image) {
            Storage::delete('public/' . $vaccine->image);
        }

        // Elimina la vacuna de la base de datos
        $vaccine->delete();

        // Redirige a una ruta con un mensaje de éxito
        return redirect()->route('vaccine.show', $vaccine->pet->id)->with('success', 'Vacuna eliminada correctamente.');
    }
}
