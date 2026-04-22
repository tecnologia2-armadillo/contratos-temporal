<?php

namespace App\Http\Controllers;

use App\Models\Personal;
use Illuminate\Http\Request;

class PersonalController extends Controller
{
    /**
     * Display a listing of personal on the dashboard.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $query = Personal::with(['genero', 'ciudad', 'status', 'perfiles', 'datoBancario.banco']);

            // Filter by Global Search (Name, Doc, Phone, Email)
            if ($request->has('search') && $request->search['value']) {
                $search = $request->search['value'];
                $query->where(function($q) use ($search) {
                    $q->where('per_primer_nombre', 'ilike', "%$search%")
                      ->orWhere('per_primer_apellido', 'ilike', "%$search%")
                      ->orWhere('per_num_doc', 'ilike', "%$search%")
                      ->orWhere('per_telefono_whatsapp', 'ilike', "%$search%")
                      ->orWhere('per_correo', 'ilike', "%$search%");
                });
            }

            // Filter by Birth Date Range
            if ($request->has('birth_start') && $request->birth_start) {
                $query->where('per_fecha_nacimiento', '>=', $request->birth_start);
            }
            if ($request->has('birth_end') && $request->birth_end) {
                $query->where('per_fecha_nacimiento', '<=', $request->birth_end);
            }

            $totalRecords = Personal::count();
            $filteredRecords = $query->count();

            $personal = $query->offset($request->start)
                              ->limit($request->length)
                              ->get();

            return response()->json([
                "draw" => intval($request->draw),
                "recordsTotal" => $totalRecords,
                "recordsFiltered" => $filteredRecords,
                "data" => $personal
            ]);
        }

        return view('dashboard');
    }
}
