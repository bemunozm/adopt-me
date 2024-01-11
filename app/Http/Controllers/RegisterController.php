<?php

namespace App\Http\Controllers;

// use App\Http\Requests\RegisterRequest;

use App\Models\Adopter;
use App\Models\User;

class RegisterController extends Controller
{
    public function create()
    {
        return view('auth.register');
    }

    public function store()
    {
        $attributes = request()->validate([
            'rut' => 'required|numeric',
            'cv' => 'required|string|max:1',
            'name' => 'nullable|string|max:255',
            'last_name' => 'nullable|string|max:255',
            'phone' => 'nullable|numeric',
            'email' => 'required|string|email|max:255|unique:users',
            'birthdate' => 'nullable|date',
            'address' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:255',
            'state' => 'nullable|string|max:255',
            'gender' => 'nullable|string|in:Masculino,Femenino,Otro',
            'marital_status' => 'nullable|string|max:255',
            'type' => 'required|string|in:Organizacion,Adoptante', // Asegurarse de que el tipo sea uno de los valores permitidos
            'password' => 'required|string|min:8',
        ]);
        $user = User::create($attributes);
        auth()->login($user);

        if ($user->type === 'Organizacion') {
            return redirect()->route('organization.create');
        } else if ($user->type === 'Adoptante') {
            return redirect()->route('adopter.create');
        }

    }
}
