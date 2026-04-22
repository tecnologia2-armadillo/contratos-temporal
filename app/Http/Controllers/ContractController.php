<?php

namespace App\Http\Controllers;

use App\Models\Personal;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class ContractController extends Controller
{
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
            'signature' => 'required', // This will be the base64 string
        ]);

        $signatureData = $request->input('signature');

        // Generate the PDF from a blade view
        $pdf = Pdf::loadView('contract.pdf', [
            'person' => $person,
            'signature' => $signatureData,
            'date' => now()->format('d/m/Y H:i')
        ]);

        // For now, we allow downloading it immediately as requested.
        return $pdf->download("Contrato_{$person->per_num_doc}.pdf");
    }
}
