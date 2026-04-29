<?php

namespace App\Http\Controllers;

use App\Models\Contrato;
use App\Models\Personal;
use App\Models\PersonalNoVinculado;
use App\Services\GoogleDriveService;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class FirmaContratoController extends Controller
{
    protected $driveService;

    public function __construct(GoogleDriveService $driveService)
    {
        $this->driveService = $driveService;
    }

    // ── Paso 1: Formulario de identificación ──────────────────────────────────

    public function show($contrato_id)
    {
        $contrato = Contrato::findOrFail($contrato_id);
        return view('contratos.firmar', compact('contrato'));
    }

    // ── Paso 2: Validar identificación y redirigir al formulario de firma ─────

    public function validar(Request $request, $contrato_id)
    {
        $contrato = Contrato::findOrFail($contrato_id);

        $request->validate([
            'tipo_identificacion'  => 'required|string',
            'numero_identificacion' => 'required|string',
        ], [
            'tipo_identificacion.required'   => 'Selecciona el tipo de identificación.',
            'numero_identificacion.required' => 'Ingresa tu número de identificación.',
        ]);

        $tipo   = $request->tipo_identificacion;
        $numero = trim($request->numero_identificacion);

        // Buscar en personal vinculado
        $personal = Personal::where('per_tipo_doc', $tipo)
                            ->where('per_num_doc', $numero)
                            ->first();

        if ($personal) {
            return redirect()->route('firmar.sign.show', [$contrato_id, $personal->signature_token]);
        }

        // Buscamos si ya existe en personal no vinculado para no duplicar
        $nv = PersonalNoVinculado::where('tipo_identificacion', $tipo)
                                  ->where('identificacion', $numero)
                                  ->first();

        if ($nv) {
            return redirect()->route('firmar.sign_nv.show', [$contrato_id, $nv->id]);
        }

        // Si no es personal vinculado ni no vinculado existente, lo llevamos a registrarse
        return redirect()->route('firmar.registro_nv.show', [
            'contrato_id' => $contrato_id,
            'tipo' => $tipo,
            'numero' => $numero
        ]);
    }

    // ── Paso 3: Registro y Firma para nuevos No Vinculados ──────────────────

    public function showRegistroNV(Request $request, $contrato_id)
    {
        $contrato = Contrato::findOrFail($contrato_id);
        $tipo = $request->query('tipo');
        $numero = $request->query('numero');

        return view('contratos.registro_firma_nv', compact('contrato', 'tipo', 'numero'));
    }

    public function processRegistroNV(Request $request, $contrato_id)
    {
        $contrato = Contrato::findOrFail($contrato_id);

        $request->validate([
            'nombre' => 'required|string',
            'apellido' => 'required|string',
            'telefono' => 'required|string',
            'correo' => 'required|email',
            'fecha_nacimiento' => 'required|date',
            'numero_cuenta' => 'required|string',
            'tipo_cuenta' => 'required|string',
            'banco' => 'required|string',
            'identificacion' => 'required|string',
            'tipo_identificacion' => 'required|string',
            'signature' => 'required',
        ]);

        // Verificación final de duplicado antes de crear
        $person = PersonalNoVinculado::where('tipo_identificacion', $request->tipo_identificacion)
                                      ->where('identificacion', $request->identificacion)
                                      ->first();

        if (!$person) {
            // 1. Crear el registro solo si no existe
            $person = PersonalNoVinculado::create($request->only([
                'nombre', 'apellido', 'telefono', 'correo', 'fecha_nacimiento',
                'numero_cuenta', 'tipo_cuenta', 'banco', 'identificacion', 'tipo_identificacion'
            ]));
        }

        // 2. Generar el PDF
        $pdf = Pdf::loadView('contratos.pdf_contrato', [
            'contrato'  => $contrato,
            'nombre'    => $person->nombre . ' ' . $person->apellido,
            'tipo_doc'  => $person->tipo_identificacion,
            'num_doc'   => $person->identificacion,
            'signature' => $request->input('signature'),
            'date'      => now()->format('d/m/Y H:i'),
        ]);

        // 3. Subir a Drive
        $driveLink = null;
        try {
            $fileName = "Contrato_{$contrato->id}_{$person->identificacion}_{$person->apellido}.pdf";
            $driveResult = $this->driveService->uploadFile($pdf->output(), $fileName, $contrato->drive_nv_folder_id);
            if ($driveResult && isset($driveResult['link'])) {
                $driveLink = $driveResult['link'];
            }
        } catch (\Exception $e) {
            \Log::error("Drive Error (Registro + Firma NV): " . $e->getMessage());
        }

        // 4. Guardar en tabla pivot
        $person->contratos()->attach($contrato_id, [
            'ip_firma'    => $request->ip(),
            'contrato_src' => $driveLink,
        ]);

        return view('contratos.firma_exitosa', [
            'contrato'  => $contrato,
            'nombre'    => $person->nombre . ' ' . $person->apellido,
            'driveLink' => $driveLink,
        ]);
    }

    // ── Firma Personal Vinculado ───────────────────────────────────────────────

    public function showSign($contrato_id, $token)
    {
        $contrato = Contrato::findOrFail($contrato_id);
        $person   = Personal::where('signature_token', $token)->firstOrFail();

        // Verificar si ya firmó este contrato específico
        $yaFirmo = $person->contratos()
                          ->wherePivot('contrato_id', $contrato_id)
                          ->wherePivotNotNull('ip_firma')
                          ->exists();

        if ($yaFirmo) {
            $pivot = $person->contratos()->find($contrato_id)?->pivot;
            return view('contratos.firma_exitosa', [
                'contrato'   => $contrato,
                'nombre'     => $person->nombre_completo,
                'driveLink'  => $pivot?->contrato_src,
            ]);
        }

        return view('contratos.sign', compact('contrato', 'person', 'token'));
    }

    public function processSign(Request $request, $contrato_id, $token)
    {
        $contrato = Contrato::findOrFail($contrato_id);
        $person   = Personal::where('signature_token', $token)->firstOrFail();

        $request->validate(['signature' => 'required']);

        $pdf = Pdf::loadView('contratos.pdf_contrato', [
            'contrato'  => $contrato,
            'nombre'    => $person->nombre_completo,
            'tipo_doc'  => $person->per_tipo_doc,
            'num_doc'   => $person->per_num_doc,
            'signature' => $request->input('signature'),
            'date'      => now()->format('d/m/Y H:i'),
        ]);

        $driveLink = null;
        try {
            $fileName    = "Contrato_{$contrato->id}_{$person->per_num_doc}_{$person->per_primer_apellido}.pdf";
            $driveResult = $this->driveService->uploadFile($pdf->output(), $fileName, $contrato->drive_personal_folder_id);
            if ($driveResult && isset($driveResult['link'])) {
                $driveLink = $driveResult['link'];
            }
        } catch (\Exception $e) {
            \Log::error("Drive Error (Firma Vinculado): " . $e->getMessage());
        }

        // Guardar / actualizar en tabla pivot
        $person->contratos()->syncWithoutDetaching([
            $contrato_id => [
                'ip_firma'    => $request->ip(),
                'contrato_src' => $driveLink,
            ]
        ]);

        return view('contratos.firma_exitosa', [
            'contrato'  => $contrato,
            'nombre'    => $person->nombre_completo,
            'driveLink' => $driveLink,
        ]);
    }

    // ── Firma Personal No Vinculado ───────────────────────────────────────────

    public function showSignNV($contrato_id, $nv_id)
    {
        $contrato = Contrato::findOrFail($contrato_id);
        $person   = PersonalNoVinculado::findOrFail($nv_id);

        // Verificar si ya firmó este contrato específico
        $yaFirmo = $person->contratos()
                          ->wherePivot('contrato_id', $contrato_id)
                          ->wherePivotNotNull('ip_firma')
                          ->exists();

        if ($yaFirmo) {
            $pivot = $person->contratos()->find($contrato_id)?->pivot;
            return view('contratos.firma_exitosa', [
                'contrato'  => $contrato,
                'nombre'    => $person->nombre . ' ' . $person->apellido,
                'driveLink' => $pivot?->contrato_src,
            ]);
        }

        return view('contratos.sign_nv', compact('contrato', 'person'));
    }

    public function processSignNV(Request $request, $contrato_id, $nv_id)
    {
        $contrato = Contrato::findOrFail($contrato_id);
        $person   = PersonalNoVinculado::findOrFail($nv_id);

        $request->validate(['signature' => 'required']);

        $pdf = Pdf::loadView('contratos.pdf_contrato', [
            'contrato'  => $contrato,
            'nombre'    => $person->nombre . ' ' . $person->apellido,
            'tipo_doc'  => $person->tipo_identificacion,
            'num_doc'   => $person->identificacion,
            'signature' => $request->input('signature'),
            'date'      => now()->format('d/m/Y H:i'),
        ]);

        $driveLink = null;
        try {
            $fileName    = "Contrato_{$contrato->id}_{$person->identificacion}_{$person->apellido}.pdf";
            $driveResult = $this->driveService->uploadFile($pdf->output(), $fileName, $contrato->drive_nv_folder_id);
            if ($driveResult && isset($driveResult['link'])) {
                $driveLink = $driveResult['link'];
            }
        } catch (\Exception $e) {
            \Log::error("Drive Error (Firma NV): " . $e->getMessage());
        }

        // Guardar / actualizar en tabla pivot
        $person->contratos()->syncWithoutDetaching([
            $contrato_id => [
                'ip_firma'    => $request->ip(),
                'contrato_src' => $driveLink,
            ]
        ]);

        return view('contratos.firma_exitosa', [
            'contrato'  => $contrato,
            'nombre'    => $person->nombre . ' ' . $person->apellido,
            'driveLink' => $driveLink,
        ]);
    }
}
