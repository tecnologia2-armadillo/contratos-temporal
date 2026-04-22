<?php

namespace App\Http\Controllers;

use App\Models\Personal;
use Illuminate\Http\Request;

class PersonalController extends Controller
{
    /**
     * Display a listing of personal on the dashboard.
     */
    public function index()
    {
        $personal = Personal::with(['genero', 'ciudad', 'status', 'perfiles', 'datoBancario.banco'])->get();

        return view('dashboard', compact('personal'));
    }
}
