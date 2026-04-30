<?php

namespace App\Http\Controllers;

use App\Models\PersonalNoVinculado;
use App\Services\GoogleDriveService;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class PersonalNoVinculadoController extends Controller
{
    protected $driveService;

    public function __construct(GoogleDriveService $driveService)
    {
        $this->driveService = $driveService;
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $query = PersonalNoVinculado::query();

            // Filter by Global Search
            if ($request->has('search') && $request->search['value']) {
                $search = $request->search['value'];
                $query->where(function($q) use ($search) {
                    $q->where('nombre', 'ilike', "%{$search}%")
                      ->orWhere('apellido', 'ilike', "%{$search}%")
                      ->orWhere('identificacion', 'ilike', "%{$search}%")
                      ->orWhere('telefono', 'ilike', "%{$search}%")
                      ->orWhere('correo', 'ilike', "%{$search}%");
                });
            }

            $totalRecords = PersonalNoVinculado::count();
            $filteredRecords = $query->count();

            $personal = $query->offset($request->start)
                              ->limit($request->length)
                              ->orderBy('id', 'desc')
                              ->get();

            return response()->json([
                "draw" => intval($request->draw),
                "recordsTotal" => $totalRecords,
                "recordsFiltered" => $filteredRecords,
                "data" => $personal
            ]);
        }
        
        return response()->json(['error' => 'Not found'], 404);
    }

    public function showSignForm()
    {
        return view('contract.sign_no_vinculado');
    }

    public function sign(Request $request)
    {
        $request->validate([
            'nombre' => 'required',
            'apellido' => 'required',
            'telefono' => 'required',
            'correo' => 'required|email',
            'fecha_nacimiento' => 'required|date',
            'numero_cuenta' => 'required',
            'tipo_cuenta' => 'required',
            'banco' => 'required',
            'identificacion' => 'required',
            'tipo_identificacion' => 'required',
            'signature' => 'required',
        ]);

        $signatureData = $request->input('signature');

        $person = [
            'nombre_completo' => $request->nombre . ' ' . $request->apellido,
            'identificacion' => $request->identificacion,
            'tipo_identificacion' => $request->tipo_identificacion,
        ];

        // Generate the PDF
        $pdf = Pdf::loadView('contract.pdf_no_vinculado', [
            'person' => $person,
            'signature' => $signatureData,
            'date' => now()->format('d/m/Y H:i'),
            'ip' => $request->ip()
        ]);

        // Upload to Google Drive using the environment variable
        $driveLink = null;
        try {
            $fileName = "Contrato_{$request->identificacion}_{$request->apellido}.pdf";
            $folderId = env('GOOGLE_DRIVE_FOLDER_PERSONAL_NO_VINCULADO_ID');
            $driveResult = $this->driveService->uploadFile($pdf->output(), $fileName, $folderId);

            if ($driveResult && isset($driveResult['link'])) {
                $driveLink = $driveResult['link'];
            }
        } catch (\Exception $e) {
            \Log::error("Drive Error for No Vinculado: " . $e->getMessage());
        }

        // Save row to database
        $model = PersonalNoVinculado::create([
            'nombre'              => $request->nombre,
            'apellido'            => $request->apellido,
            'telefono'            => $request->telefono,
            'correo'              => $request->correo,
            'fecha_nacimiento'    => $request->fecha_nacimiento,
            'numero_cuenta'       => $request->numero_cuenta,
            'tipo_cuenta'         => $request->tipo_cuenta,
            'banco'               => $request->banco,
            'identificacion'      => $request->identificacion,
            'tipo_identificacion' => $request->tipo_identificacion,
        ]);

        return view('contract.success_no_vinculado', compact('model'));
    }
}
