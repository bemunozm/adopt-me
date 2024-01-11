<?php

namespace App\Http\Controllers;

use App\Models\Site;
use App\Models\User;
use Illuminate\Http\Request;

class SiteController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = auth()->id();
        $user = User::find($user);
        return view('pages.sites', compact('user'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $user = auth()->id();
        $user = User::find($user);
        return view('pages.sites-create', compact('user'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        Site::create($request->all());
        return redirect()->route('sites.index');
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
    public function edit($id)
    {
        $user = auth()->id();
        $user = User::find($user);
        $site = Site::findOrFail($id);
        return view('pages.sites-edit', compact('site', 'user'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $site = Site::findOrFail($id);

        // Actualiza los atributos de la mascota
        $site->update($request->all());

        return redirect()->route('sites.index')->with('success', 'Sede actualizada con éxito.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
{
    $site = Site::findOrFail($id);

    // Obtén todos los pets asociados a la sede
    $pets = $site->pets;

    // Elimina los pets asociados
    foreach ($pets as $pet) {
        $pet->delete();
    }

    // Luego elimina la sede
    $site->delete();

    return redirect()->route('sites.index')->with('success', 'Sede borrada con éxito junto con sus mascotas.');
}

}
