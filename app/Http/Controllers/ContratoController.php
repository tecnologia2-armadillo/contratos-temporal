<?php

namespace App\Http\Controllers;

use App\Models\Contrato;
use App\Models\Personal;
use App\Models\PersonalNoVinculado;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ContratoController extends Controller
{
    /**
     * Index — lista de contratos (DataTables server-side) o vista.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $query = Contrato::query();

            if ($request->has('search') && $request->search['value']) {
                $search = $request->search['value'];
                $query->where('nombre', 'ilike', "%{$search}%");
            }

            $totalRecords    = Contrato::count();
            $filteredRecords = $query->count();

            $contratos = $query
                ->orderBy('created_at', 'desc')
                ->offset($request->start)
                ->limit($request->length)
                ->get();

            return response()->json([
                'draw'            => intval($request->draw),
                'recordsTotal'    => $totalRecords,
                'recordsFiltered' => $filteredRecords,
                'data'            => $contratos,
            ]);
        }

        return view('contratos.index');
    }

    /**
     * Crear nuevo contrato.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nombre'      => 'required|string|max:255',
            'terminos'    => 'required|string',
            'fecha_inicio' => 'required|date',
            'fecha_fin'   => 'required|date|after_or_equal:fecha_inicio',
        ]);

        $contrato = Contrato::create($validated);

        return response()->json([
            'success'  => true,
            'message'  => 'Contrato creado correctamente.',
            'contrato' => $contrato,
        ]);
    }

    /**
     * Actualizar contrato existente.
     */
    public function update(Request $request, $id)
    {
        $contrato = Contrato::findOrFail($id);

        $validated = $request->validate([
            'nombre'      => 'required|string|max:255',
            'terminos'    => 'required|string',
            'fecha_inicio' => 'required|date',
            'fecha_fin'   => 'required|date|after_or_equal:fecha_inicio',
        ]);

        $contrato->update($validated);

        return response()->json([
            'success'  => true,
            'message'  => 'Contrato actualizado correctamente.',
            'contrato' => $contrato,
        ]);
    }

    /**
     * Vista de detalle de un contrato.
     */
    public function detalle($id)
    {
        $contrato = Contrato::findOrFail($id);
        return view('contratos.detalle', compact('contrato'));
    }

    /**
     * DataTables AJAX — Personal vinculado con estado de firma para este contrato.
     */
    public function personalData(Request $request, $id)
    {
        $query = Personal::with(['genero', 'ciudad', 'status', 'perfiles', 'datoBancario.banco'])
            ->leftJoin('contrato_personal as cp', function ($join) use ($id) {
                $join->on('cp.personal_id', '=', 'personal.per_id')
                     ->where('cp.contrato_id', '=', $id);
            })
            ->select(
                'personal.*',
                'cp.ip_firma',
                'cp.contrato_src as pivot_contrato_src'
            );

        if ($request->has('search') && $request->search['value']) {
            $search = $request->search['value'];
            $query->where(function ($q) use ($search) {
                $q->where('per_primer_nombre', 'ilike', "%{$search}%")
                  ->orWhere('per_primer_apellido', 'ilike', "%{$search}%")
                  ->orWhere('per_num_doc', 'ilike', "%{$search}%")
                  ->orWhere('per_telefono_whatsapp', 'ilike', "%{$search}%")
                  ->orWhere('per_correo', 'ilike', "%{$search}%");
            });
        }

        $totalRecords    = Personal::count();
        $filteredRecords = $query->count();

        $personal = $query
            ->offset($request->start)
            ->limit($request->length)
            ->get()
            ->map(function ($p) {
                $p->firmado_contrato    = !is_null($p->ip_firma);
                $p->contrato_src_pivot  = $p->pivot_contrato_src;
                return $p;
            });

        return response()->json([
            'draw'            => intval($request->draw),
            'recordsTotal'    => $totalRecords,
            'recordsFiltered' => $filteredRecords,
            'data'            => $personal,
        ]);
    }

    /**
     * DataTables AJAX — Personal no vinculado con estado de firma para este contrato.
     */
    public function personalNVData(Request $request, $id)
    {
        $query = PersonalNoVinculado::query()
            ->leftJoin('contrato_personal_no_vinculado as cpnv', function ($join) use ($id) {
                $join->on('cpnv.personal_no_vinculado_id', '=', 'personal_no_vinculado.id')
                     ->where('cpnv.contrato_id', '=', $id);
            })
            ->select(
                'personal_no_vinculado.*',
                'cpnv.ip_firma',
                'cpnv.contrato_src as pivot_contrato_src'
            );

        if ($request->has('search') && $request->search['value']) {
            $search = $request->search['value'];
            $query->where(function ($q) use ($search) {
                $q->where('nombre', 'ilike', "%{$search}%")
                  ->orWhere('apellido', 'ilike', "%{$search}%")
                  ->orWhere('identificacion', 'ilike', "%{$search}%")
                  ->orWhere('telefono', 'ilike', "%{$search}%")
                  ->orWhere('correo', 'ilike', "%{$search}%");
            });
        }

        $totalRecords    = PersonalNoVinculado::count();
        $filteredRecords = $query->count();

        $personal = $query
            ->offset($request->start)
            ->limit($request->length)
            ->orderBy('personal_no_vinculado.id', 'desc')
            ->get()
            ->map(function ($p) {
                $p->firmado_contrato   = !is_null($p->ip_firma);
                $p->contrato_src_pivot = $p->pivot_contrato_src;
                return $p;
            });

        return response()->json([
            'draw'            => intval($request->draw),
            'recordsTotal'    => $totalRecords,
            'recordsFiltered' => $filteredRecords,
            'data'            => $personal,
        ]);
    }
}
