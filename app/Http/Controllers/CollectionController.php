<?php

namespace App\Http\Controllers;

use App\Models\Collection;
use App\Models\Organization;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;

class CollectionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        /// Obtener el usuario autenticado
        $user = Auth::user();

        // Obtener todas las colecciones relacionadas con la organización del usuario
        
        
        // Retornar una vista que muestre la lista de colecciones
        if($user->type == 'Adoptante'){
            $collections = Collection::all();
            return view('pages.collection', compact('collections', 'user'));
        }
        else{
            $collections = Collection::where('organization_id', $user->organization->id)->get();
            return view('pages.collection', compact('collections', 'user'));
        }
    }
    

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $user = Auth::user();
        return view('pages.collection-create', compact('user'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
{

    // Inicializar el arreglo de datos para la creación
    $collectionData = $request->all();

    // Verificar si se subió una imagen y procesarla
    if ($request->hasFile('image')) {
        $image = $request->file('image');
        $path = $image->store('collections', 'public');
        
        // Añadir la ruta de la imagen al arreglo de datos
        $collectionData['image'] = $path;
    }

    // Crear la colección con los datos del formulario
    Collection::create($collectionData);

    // Redirigir al usuario con un mensaje de éxito
    return redirect()->route('collection.index')->with('success', 'Colecta creada con éxito.');
}

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $user = auth()->id();
        $user = User::find($user);
        $collection = Collection::find($id);
            
        
            
            $collection = Collection::with('approvedDonations')->findOrFail($id);
            $amountCollected = $collection->approvedDonations->sum('pivot.amount');
            $sponsorCount = $collection->approvedDonations->unique('pivot.user_id')->count();
            $endDate = Carbon::parse($collection->finish_date);
            $now = Carbon::now();
            $daysLeft = $endDate->diffInDays($now, true); 
            return view('pages.collection-show', compact('collection', 'user', 'amountCollected', 'sponsorCount', 'daysLeft'));
    }

    public function approve($id, Request $request) {
        $user = auth()->user();
        $collection = Collection::findOrFail($id);
        $statusFilter = $request->input('status', 'Pendiente'); // El filtro predeterminado puede ser 'Pendiente'
    
        // Inicializa la consulta con una relación
        $approvalsQuery = $collection->users();
    
        // Aplica el filtro de estado si se ha proporcionado uno
        if (!is_null($statusFilter)) {
            $approvalsQuery->wherePivot('status', $statusFilter);
        }
    
        // Obtiene los registros filtrados o todos si no se ha aplicado ningún filtro
        $pendingApprovals = $approvalsQuery->get();
        
        // Si no hay registros y se ha aplicado un filtro, $approvals será una colección vacía
        return view('pages.collection-approve', compact('collection', 'user', 'pendingApprovals', 'statusFilter'));
    }
    

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $user = auth()->id();
        $user = User::find($user);
        $collection = Collection::findOrFail($id);
        return view('pages.collection-edit', compact('collection', 'user'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request,$id)
    {
        $collection = Collection::findOrFail($id);

        if ($request->hasFile('image')) {
            // Eliminar la imagen antigua si existe
            if ($collection->image && Storage::disk('public')->exists($collection->image)) {
                Storage::disk('public')->delete($collection->image);
            }
        }
        // Actualizar los atributos de la colecta
        $collectionData = $request->except(['image']);
    
        if ($request->hasFile('image')) {
            // Guardar la imagen en el disco público y obtener la ruta
            $path = $request->file('image')->store('collections', 'public');
            
            // Añadir la ruta de la imagen al arreglo de datos
            $collectionData['image'] = $path;
        }
        
        
        // Actualizar la colecta con los datos del formulario
        $collection->update($collectionData);
    
        return redirect()->route('collection.index')->with('success', 'Colecta actualizada con éxito.');

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $collection = Collection::findOrFail($id);

        // Eliminar la imagen del disco si existe
        if ($collection->image && Storage::disk('public')->exists($collection->image)) {
            Storage::disk('public')->delete($collection->image);
        }

        // Eliminar el registro de la colecta de la base de datos
        $collection->delete();

        return redirect()->route('collection.index')->with('success', 'Colecta eliminada con éxito.');
    }

    public function donate(Request $request, $collection_id)
{
    if ($request->has('user_id') && is_numeric($request->input('user_id'))) {
        
        $user = User::find($request->input('user_id'));
        if ($user) {
            Auth::login($user);
        } else {
            // Manejar el caso en que el user_id no corresponda a un usuario válido
            return redirect()->back()->withErrors(['user_id' => 'Usuario no válido']);
        }
    } else {
        // Si no hay un user_id en el request, verificar si hay un usuario actualmente autenticado
        $user = Auth::user();
        if (!$user) {
            // Manejar el caso en que no hay un usuario invitado ni autenticado
            // Podrías redirigir al login o manejar como una donación de invitado
            return redirect()->route('login')->with('error', 'Debe iniciar sesión para donar');
        }
    }

    $donationAmount = $request->input('amount'); // Obtener la cantidad donada del formulario
    $donationFile = $request->file('file'); // Obtener el archivo adjunto del formulario, si existe

    // Procesar y almacenar el archivo, si se proporcionó
    $filePath = null;
    if ($donationFile) {
        $filePath = $donationFile->store('donations', 'public');
    }

    // Añadir un nuevo registro a la relación con la cantidad donada y los detalles adicionales
    $user->collections()->attach($collection_id, [
        'amount' => $donationAmount,
        'status' => 'Pendiente', // o cualquier lógica para establecer el estado inicial
        'file' => $filePath // Almacena la ruta del archivo, si se proporcionó
    ]);
    if($user->id == 11){
        Auth::logout();
        return redirect()->route('collections')->with('success', '¡Donación realizada con éxito!');
    }
    return redirect()->route('collection.show', $collection_id)->with('success', '¡Donación realizada con éxito!');
}

public function changeStatus(Request $request)
{
    $relations = $request->input('relations', []);
    
    foreach ($relations as $relation) {
        $pivotId = $relation['pivotId'];
        $newStatus = $relation['status'];

        DB::table('collection_user')
            ->where('id', $pivotId)
            ->update(['status' => $newStatus]);
    }

    return redirect()->route('collection.index')->with('success', 'Estados actualizados correctamente.');
}

public function guestIndex()
{
    $collections = Collection::with(['organization'])
               ->where('finish_date', '>', Carbon::now())
               ->get();

    return view('collections', compact('collections'));
}

public function guestShow(string $id)
    {
        
        $user = User::find(11);
        $collection = Collection::find($id);

            $collection = Collection::with('approvedDonations')->findOrFail($id);
            $amountCollected = $collection->approvedDonations->sum('pivot.amount');
            $sponsorCount = $collection->approvedDonations->unique('pivot.user_id')->count();
            $endDate = Carbon::parse($collection->finish_date);
            $now = Carbon::now();
            $daysLeft = $endDate->diffInDays($now, true); 
            return view('guest-show', compact('collection', 'user', 'amountCollected', 'sponsorCount', 'daysLeft'));
    }

}
