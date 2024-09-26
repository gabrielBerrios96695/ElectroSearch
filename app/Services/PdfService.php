<?php

namespace App\Services;

use Mpdf\Mpdf;

class PdfService
{
    public function generatePdf($view, $data = [], $filename = null)
    {
        $mpdf = new Mpdf();
        $pdfContent = view($view, $data)->render();
        $mpdf->WriteHTML($pdfContent);

        // En lugar de guardar el archivo, devolver el contenido del PDF
        return $mpdf->Output('', 'S');
    }
}

