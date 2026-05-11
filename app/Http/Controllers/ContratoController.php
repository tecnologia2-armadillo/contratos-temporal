<?php

namespace App\Http\Controllers;

use App\Models\Contrato;
use App\Models\Personal;
use App\Models\PersonalNoVinculado;
use App\Services\GoogleDriveService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class ContratoController extends Controller
{
    protected $driveService;

    public function __construct(GoogleDriveService $driveService)
    {
        $this->driveService = $driveService;
    }

    /**
     * Index — lista de contratos (DataTables server-side) o vista.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $query = Contrato::query();

            // Filtrar inactivos por defecto
            if ($request->input('show_inactive') !== 'true') {
                $query->where('activo', true);
            }

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

        $driveFolderId = null;
        $drivePersonalId = null;
        $driveNVId = null;

        try {
            // 1. Carpeta Principal del Contrato
            $folderName = "{$validated['nombre']} ({$validated['fecha_inicio']} - {$validated['fecha_fin']})";
            $driveFolderId = $this->driveService->createFolder($folderName);

            if ($driveFolderId) {
                // 2. Subcarpeta Personal
                $drivePersonalId = $this->driveService->createFolder("Personal", $driveFolderId);
                // 3. Subcarpeta Personal No Vinculado
                $driveNVId = $this->driveService->createFolder("Personal No Vinculado", $driveFolderId);
            }
        } catch (\Exception $e) {
            Log::error("Error creating Drive folder structure for contract: " . $e->getMessage());
        }

        $contrato = Contrato::create(array_merge($validated, [
            'activo' => true,
            'drive_folder_id' => $driveFolderId,
            'drive_personal_folder_id' => $drivePersonalId,
            'drive_nv_folder_id' => $driveNVId,
        ]));

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
     * Alternar estado activo/inactivo.
     */
    public function toggleActivo($id)
    {
        $contrato = Contrato::findOrFail($id);
        $contrato->activo = !$contrato->activo;
        $contrato->save();

        return response()->json([
            'success' => true,
            'message' => $contrato->activo ? 'Contrato habilitado.' : 'Contrato inhabilitado.',
            'activo'  => $contrato->activo,
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

        // Filtros específicos
        if ($request->filled('nombre')) {
            $nombre = $request->nombre;
            $query->where(DB::raw("CONCAT(per_primer_nombre, ' ', per_segundo_nombre, ' ', per_primer_apellido, ' ', per_segundo_apellido)"), 'ilike', "%{$nombre}%");
        }

        if ($request->filled('identificacion')) {
            $query->where('per_num_doc', 'ilike', "%{$request->identificacion}%");
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

        // Filtros específicos
        if ($request->filled('nombre')) {
            $nombre = $request->nombre;
            $query->where(DB::raw("CONCAT(nombre, ' ', apellido)"), 'ilike', "%{$nombre}%");
        }

        if ($request->filled('identificacion')) {
            $query->where('identificacion', 'ilike', "%{$request->identificacion}%");
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

    /**
     * Exportar reporte de firmas a Excel (CSV).
     */
    public function exportExcel($id)
    {
        $contrato = Contrato::findOrFail($id);
        
        // 1. Personal Vinculado
        $vinculados = Personal::with(['datoBancario.banco'])
            ->leftJoin('contrato_personal as cp', function ($join) use ($id) {
                $join->on('cp.personal_id', '=', 'personal.per_id')
                     ->where('cp.contrato_id', '=', $id);
            })
            ->select('personal.*', 'cp.ip_firma', 'cp.created_at as fecha_firma_pivot')
            ->get();

        // 2. Personal No Vinculado
        $noVinculados = PersonalNoVinculado::leftJoin('contrato_personal_no_vinculado as cpnv', function ($join) use ($id) {
                $join->on('cpnv.personal_no_vinculado_id', '=', 'personal_no_vinculado.id')
                     ->where('cpnv.contrato_id', '=', $id);
            })
            ->select('personal_no_vinculado.*', 'cpnv.ip_firma', 'cpnv.created_at as fecha_firma_pivot')
            ->get();

        $filename = "Reporte_Firmas_" . Str::slug($contrato->nombre) . "_" . now()->format('Ymd_His') . ".csv";
        
        $headers = [
            "Content-type"        => "text/csv; charset=UTF-8",
            "Content-Disposition" => "attachment; filename=$filename",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ];

        $columns = ['Tipo Personal', 'Nombre Completo', 'Tipo Doc', 'Identificacion', 'Telefono', 'Correo', 'Banco', 'Cuenta', 'Firmado', 'Fecha Firma', 'IP Firma'];

        $callback = function() use($vinculados, $noVinculados, $columns) {
            $file = fopen('php://output', 'w');
            fputs($file, "\xEF\xBB\xBF"); // BOM para compatibilidad con Excel UTF-8
            fputcsv($file, $columns, ";");

            foreach ($vinculados as $p) {
                fputcsv($file, [
                    'Vinculado',
                    $p->nombre_completo,
                    $p->per_tipo_doc,
                    $p->per_num_doc,
                    $p->per_telefono_whatsapp,
                    $p->per_correo,
                    $p->datoBancario?->banco?->ban_banco_nombre ?? 'N/A',
                    $p->datoBancario?->dba_num_cuenta ?? 'N/A',
                    $p->ip_firma ? 'SI' : 'NO',
                    $p->fecha_firma_pivot ? \Carbon\Carbon::parse($p->fecha_firma_pivot)->format('d/m/Y H:i') : '',
                    $p->ip_firma ?? ''
                ], ";");
            }

            foreach ($noVinculados as $p) {
                fputcsv($file, [
                    'No Vinculado',
                    $p->nombre . ' ' . $p->apellido,
                    $p->tipo_identificacion,
                    $p->identificacion,
                    $p->telefono,
                    $p->correo,
                    $p->banco,
                    $p->numero_cuenta,
                    $p->ip_firma ? 'SI' : 'NO',
                    $p->fecha_firma_pivot ? \Carbon\Carbon::parse($p->fecha_firma_pivot)->format('d/m/Y H:i') : '',
                    $p->ip_firma ?? ''
                ], ";");
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
