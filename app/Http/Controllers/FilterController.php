<?php

namespace App\Http\Controllers;

use App\Models\Pet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FilterController extends Controller
{
    public function petFilter(Request $request)
    {
        $user = Auth::user();
        $statusFilter = $request->input('status', 'Sin Adoptar');

        if ($statusFilter == 'Adoptado') {
            $pets = Pet::where('status', 'Adoptado')->get();
        } elseif ($statusFilter == 'Sin Adoptar') {
            $pets = Pet::where('status', 'Sin Adoptar')->get();
        } else {
            $pets = Pet::all();
        }

        return view('pages.pet', compact('pets', 'user', 'statusFilter'));
    }

}
