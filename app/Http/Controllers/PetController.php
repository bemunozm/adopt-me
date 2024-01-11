<?php

namespace App\Http\Controllers;

use App\Models\Adopter;
use App\Models\Collection;
use App\Models\Image;
use App\Models\Pet;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class PetController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = Auth::user();

        if($user->type == 'Adoptante'){
            
            $pets = Pet::where('status', 'Sin Adoptar')->get();

            return view('pages.pet', compact('user', 'pets'));
        }
        else{
            $user = User::with('organization.sites.pets')->find($user->id);
        
        // Inicializa $pets como una colección vacía por defecto
            $pets = collect();

            if ($user && $user->organization) {
            $pets = $user->organization->sites->flatMap(function ($site) {
                return $site->pets;
            });
            }
            return view('pages.pet', compact('user', 'pets'));
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {   
        $user = auth()->id();
        $user = User::find($user);
        $sites = $user->organization->sites;
        return view('pages.pet-create', compact('sites', 'user'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $pet = Pet::create([
            'name' => $request->name,
            'species' => $request->species,
            'race' => $request->race,
            'size' => $request->size,
            'age' => $request->age,
            'sex' => $request->sex,
            'energy' => $request->energy,
            'social_children' => $request->social_children,
            'social_dog' => $request->social_dog,
            'social_cat' => $request->social_cat,
            'description' => $request->description,
            'status' => $request->status,
            'site_id' => $request->site_id,
        ]);

        if ($request->hasFile('images')) {
            $images = $request->file('images');
    
            foreach ($images as $image) {
                // Guarda cada imagen en el disco y crea un registro en la base de datos
                $path = $image->store('pet_images', 'public'); // Guarda las imágenes en storage/app/public/pet_images
    
                // Asegúrate de que el modelo Image tenga los campos fillable configurados correctamente
                Image::create([
                    'pet_id' => $pet->id,
                    'image' => $path // Guarda la ruta de la imagen
                ]);
            }
        }

        return redirect()->route('pet.index');
    }

    public function guestDonate($id){
        $user = User::find(11);
        $collection = Collection::find($id);
        return view('guest-donate', compact('collection', 'user'));
    }
    public function show($id)
    {
        
        $user = Auth::user();
        $currentRouteName = Route::currentRouteName();

    if ($currentRouteName == 'donate.show') {
        $collection = Collection::find($id);
        return view('pages.donate-create', compact('collection', 'user'));
    } else {
        $adopted = false;

        if ($user->type == 'Adoptante' && $user->adopter) {
            $adopted = $user->adopter->pets()->where('pet_id', $id)->exists();
        }
        $pet = Pet::with(['images', 'vaccines', 'site.organization'])->findOrFail($id);
        return view('pages.pet-show', compact('pet', 'user', 'adopted'));
    }
            
        
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $user = auth()->id();
        $user = User::find($user);
        $sites = $user->organization->sites;
        $pet = Pet::findOrFail($id);
        return view('pages.pet-edit', compact('pet', 'sites', 'user'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $pet = Pet::findOrFail($id);

        // Actualiza los atributos de la mascota
        $pet->update($request->except('images')); // Excluye las imágenes del request

        // Maneja la actualización de las imágenes si se han enviado nuevas
        if ($request->has('images')) {
            $imagesData = $request->file('images'); // Datos de las imágenes desde el formulario

            // Crea y guarda las nuevas imágenes asociadas a la mascota
            foreach ($imagesData as $imageData) {
                $path = $imageData->store('pet_images', 'public'); // Guarda las imágenes en storage/app/public/pet_images
    
                // Asegúrate de que el modelo Image tenga los campos fillable configurados correctamente
                Image::create([
                    'pet_id' => $pet->id,
                    'image' => $path // Guarda la ruta de la imagen
                ]);
            }
        }

        return redirect()->route('pet.index')->with('success', 'Mascota actualizada con éxito.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $pet = Pet::findOrFail($id);

        foreach ($pet->images as $image) {
            Storage::disk('public')->delete($image->image);
            $image->delete();
        }

        $pet->delete();

        return redirect()->route('pet.index')->with('success', 'Mascota borrada con exito');
    }

    public function adopt($pet_id)
    {
        $user = Auth::user();
    
        $adopter = $user->adopter;
    
        $adopter->pets()->attach($pet_id);
    
        // Asegúrate de pasar el pet_id correctamente a la ruta
        return redirect()->route('pet.show', ['pet' => $pet_id])->with('success', '¡Solicitud realizada con éxito!');
    }

    public function mypet()
    {
        
    $user = Auth::user();     
    $adopter = auth()->user()->adopter;
   
    if (!$adopter) {
        // Manejar el caso en que el usuario no tenga un adopter asociado
        return redirect()->back()->with('error', 'Adopter no encontrado.');
    }

    // Obtener los pets que tienen una relación 'Aceptada' con el adopter
    $pets = $adopter->pets()->wherePivot('status', 'Aceptado')->get();
    
    // Enviar los pets a la vista
    return view('pages.mypet', compact('pets', 'user'));

    }


    public function guestIndex()
{
    $pets = Pet::with(['site.organization'])
               ->where('status', 'Sin Adoptar')
               ->get();

    return view('pets', compact('pets'));
}

}