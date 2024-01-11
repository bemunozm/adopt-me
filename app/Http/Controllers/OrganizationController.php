<?php

namespace App\Http\Controllers;

use App\Models\Organization;
use App\Models\User;
use Illuminate\Http\Request;

class OrganizationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = auth()->id();
        $user = User::find($user);
        return view('pages.dashboard', compact('user'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('auth.organization-register');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        Organization::create($request->all());

        // Realizar acciones adicionales como enviar un correo electrónico de confirmación, etc.

        // Redireccionar a una página específica después de la creación
        return redirect()->intended(session('url.intended') ?? route('organization.index'))->with('success', 'Organización creada con éxito.');
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
}
