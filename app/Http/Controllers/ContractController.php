<?php

namespace App\Http\Controllers;

use App\Models\Personal;
use App\Services\GoogleDriveService;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class ContractController extends Controller
{
    protected $driveService;

    public function __construct(GoogleDriveService $driveService)
    {
        $this->driveService = $driveService;
    }

    /**
     * Show the public signing form.
     */
    public function show($token)
    {
        $person = Personal::where('signature_token', $token)->firstOrFail();
        return view('contract.sign', compact('person'));
    }

    /**
     * Store the signature and generate the PDF.
     */
    public function sign(Request $request, $token)
    {
        $person = Personal::where('signature_token', $token)->firstOrFail();

        $request->validate([
            'signature' => 'required',
        ]);

        $signatureData = $request->input('signature');

        // Generate the PDF
        $pdf = Pdf::loadView('contract.pdf', [
            'person' => $person,
            'signature' => $signatureData,
            'date' => now()->format('d/m/Y H:i'),
            'ip'   => $request->ip()
        ]);

        // Upload to Google Drive
        try {
            $fileName = "Contrato_{$person->per_num_doc}_{$person->per_primer_apellido}.pdf";
            $driveResult = $this->driveService->uploadFile($pdf->output(), $fileName);

            if ($driveResult && isset($driveResult['link'])) {
                return view('contract.success', [
                    'person'   => $person,
                    'driveLink' => $driveResult['link'],
                    'driveId'  => $driveResult['id']
                ]);
            }
        } catch (\Exception $e) {
            // Log full error for internal review
            \Log::error("Manual Sign Error: " . $e->getMessage());

            // Try to extract a clean message if it's a JSON from Google
            $errorMessage = $e->getMessage();
            $decoded = json_decode($e->getMessage(), true);
            if ($decoded && isset($decoded['error']['message'])) {
                $errorMessage = $decoded['error']['message'];
            }

            return back()->with('error', 'Error de Google Drive: ' . $errorMessage);
        }

        return back()->with('error', 'No se pudo obtener el enlace de Google Drive.');
    }
}

