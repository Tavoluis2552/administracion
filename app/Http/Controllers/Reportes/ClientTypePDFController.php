<?php

namespace App\Http\Controllers\Reportes;

use App\Http\Controllers\Controller;
use App\Models\ClientType;
use Illuminate\Http\Request;
use TCPDF;

class ClientTypePDFController extends Controller
{
    public function exportPdf()
    {
        $clientTypes = ClientType::all();

        $clientTypesArray = $clientTypes->map(function ($clientType) {
            return [
                'id' => $clientType->id,
                'name' => $clientType->name,
                'state' => $clientType->state == 1 ? 'Activo' : 'Inactivo'
            ];
        })->toArray();

        $pdf = new TCPDF();
        $pdf->SetCreator('Laravel TCPDF');
        $pdf->SetAuthor('Laravel');
        $pdf->SetTitle('Lista de Tipos de Cliente');
        $pdf->SetSubject('Reporte de Tipos de Cliente');
        
        // Configuración de márgenes
        $pdf->SetMargins(10, 10, 10);
        $pdf->SetAutoPageBreak(true, 10);
        
        // Eliminar la línea de encabezado (borde superior)
        $pdf->SetHeaderData('', 0, '', '', [0, 0, 0], [255, 255, 255]);

        $pdf->AddPage();

        // Encabezado del PDF
        $pdf->SetFont('helvetica', 'B', 18);
        $pdf->Cell(0, 20, 'Lista de Tipos de Cliente', 0, 1, 'C');

        //$pdf->SetCellPadding(4);
 
        // Encabezados de la tabla
        $pdf->SetFont('helvetica', 'B', 10);
        $pdf->SetFillColor(242, 242, 242); 

        $header = ['ID', 'Tipo de cliente', 'Estado'];
        $widths = [30, 120, 40];

        foreach ($header as $i => $col) {
            $pdf->MultiCell($widths[$i], 10, $col, 1, 'C', 1, 0);
        }
        $pdf->Ln();

        $pdf->SetFont('helvetica', '', 10);

        // Imprimir los datos de cada tipo de cliente
        foreach ($clientTypesArray as $clientType) {
        if ($pdf->GetY() > 260) { // Si la posición Y está cerca del final de la página
            $pdf->AddPage(); // Añadir una nueva página
            // Imprimir los encabezados nuevamente en la nueva página
            $pdf->SetFont('helvetica', 'B', 10);
            $pdf->SetFillColor(242, 242, 242); 
            foreach ($header as $i => $col) {
                $pdf->MultiCell($widths[$i], 10, $col, 1, 'C', 1, 0);
            }
            $pdf->Ln();
        }
        $pdf->SetFont('helvetica', '', 10);

        $pdf->MultiCell($widths[0], 10, ' ' . $clientType['id'] . ' ', 1, 'C', 0, 0);
        $pdf->MultiCell($widths[1], 10, $clientType['name'], 1, 'C', 0, 0);
        $pdf->MultiCell($widths[2], 10, $clientType['state'], 1, 'C', 0, 0);
        $pdf->Ln();
        }

        return response($pdf->Output('tipos_cliente.pdf', 'D'))->header('Content-Type', 'application/pdf');
    }
}
