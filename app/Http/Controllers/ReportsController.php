<?php

namespace App\Http\Controllers;

use App\Models\SaleDetail;
use Illuminate\Http\Request;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Chart\Chart;
use PhpOffice\PhpSpreadsheet\Chart\Legend;
use PhpOffice\PhpSpreadsheet\Chart\PlotArea;
use PhpOffice\PhpSpreadsheet\Chart\Title;
use PhpOffice\PhpSpreadsheet\Chart\DataSeries;
use PhpOffice\PhpSpreadsheet\Chart\DataSeriesValues;
use PhpOffice\PhpSpreadsheet\Chart\Layout;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ReportsController extends Controller
{
    public function index(Request $request)
    {
        $startDate = $request->input('start_date') ? Carbon::parse($request->input('start_date')) : now()->startOfMonth();
        $endDate = $request->input('end_date') ? Carbon::parse($request->input('end_date'))->endOfDay() : null;
        $limit = $request->input('limit', 5);
    
        $query = DB::table('sale_details')
            ->join('products', 'sale_details.product_id', '=', 'products.id')
            ->join('sales', 'sale_details.sale_id', '=', 'sales.id')
            ->select('products.name', DB::raw('SUM(sale_details.quantity) as quantity'), DB::raw('SUM(sale_details.total) as total'))
            ->groupBy('products.name')
            ->orderBy('quantity', 'desc');
    
        if ($endDate) {
            $query->whereBetween('sales.created_at', [$startDate, $endDate]);
        } else {

            $query->whereDate('sales.created_at', '=', $startDate);
        }
    

        $salesData = $query->limit($limit)->get();
        $totalProducts = DB::table('products')->count();
    
        return view('livewire.reports.index', compact('salesData', 'startDate', 'endDate', 'totalProducts'));
    }
    

    public function exportExcel(Request $request)
    {
        // Obtener las fechas de inicio y fin
        $startDate = $request->input('start_date') ? Carbon::parse($request->input('start_date')) : now()->startOfMonth();
        $endDate = $request->input('end_date') ? Carbon::parse($request->input('end_date'))->endOfDay() : null;
        $limit = $request->input('limit');
    
        // Aplicar los filtros de fecha y límite
        $salesQuery = SaleDetail::select('product_id', \DB::raw('SUM(quantity) as quantity'), \DB::raw('SUM(total) as total'))
            ->with('product:name,id')
            ->groupBy('product_id')
            ->orderBy('total', 'desc');
    
        // Si se aplicaron fechas, filtrar por fecha
        if ($startDate) {
            $salesQuery->whereHas('sale', function ($query) use ($startDate, $endDate) {
                $query->where('created_at', '>=', $startDate);
                if ($endDate) {
                    $query->where('created_at', '<=', $endDate);
                }
            });
        }
    
        // Aplicar el límite de productos
        if ($limit) {
            $salesData = $salesQuery->take($limit)->get();
        } else {
            $salesData = $salesQuery->get();
        }
    
        // Asegúrate de asignar el nombre del producto a cada dato de ventas
        foreach ($salesData as $data) {
            $data->name = $data->product->name;
        }
    
        // Crear un nuevo objeto de hoja de cálculo
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
    
        // Construir el título del reporte basado en las fechas
        $reportTitle = "Reporte de los productos más vendidos";
        if ($endDate) {
            $reportTitle .= " del " . $startDate->format('d/m/Y') . " al " . $endDate->format('d/m/Y');
        } else {
            $reportTitle .= " del " . $startDate->format('d/m/Y');
        }
    
        // Colocar el título del reporte con color verde
        $sheet->setCellValue('A1', $reportTitle);
        $sheet->mergeCells('A1:C1');
        $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(14)->getColor()->setRGB('008000'); // Verde
        $sheet->getStyle('A1')->getAlignment()->setHorizontal('center');
    
        // Subtítulo de la fecha de creación
        $sheet->setCellValue('A2', 'Fecha de creación: ' . now()->format('d/m/Y'));
        $sheet->mergeCells('A2:C2');
        $sheet->getStyle('A2')->getFont()->setBold(true)->setSize(12)->getColor()->setRGB('008000'); // Verde
        $sheet->getStyle('A2')->getAlignment()->setHorizontal('center');
    
        // Colocar encabezados en la hoja de cálculo
        $sheet->setCellValue('A4', 'Producto');
        $sheet->setCellValue('B4', 'Cantidad Vendida');
        $sheet->setCellValue('C4', 'Total Vendido');
    
        // Estilo para los encabezados de la tabla
        $headerStyle = $sheet->getStyle('A4:C4');
        $headerStyle->getFont()->setBold(true);
        $headerStyle->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
    
        // Colocar los datos de ventas en la tabla
        $row = 5;
        foreach ($salesData as $data) {
            $sheet->setCellValue('A' . $row, $data->name); // Nombre del producto
            $sheet->setCellValue('B' . $row, $data->quantity); // Cantidad vendida
            $sheet->setCellValue('C' . $row, $data->total); // Total vendido
    
            // Estilo para los datos
            $dataStyle = $sheet->getStyle('A' . $row . ':C' . $row);
            $dataStyle->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN); // Borde
            $dataStyle->getFont()->getColor()->setRGB('333333'); // Color de texto oscuro
            $row++;
        }
    
        // Autoajustar el tamaño de las columnas
        foreach (range('A', 'C') as $column) {
            $sheet->getColumnDimension($column)->setAutoSize(true);
        }
    
        // Crear un gráfico (opcional)
        $dataSeriesLabels = [new DataSeriesValues('String', 'Worksheet!$C$4', null, 1)]; // Etiqueta
        $xAxisTickValues = [new DataSeriesValues('String', 'Worksheet!$A$5:$A$' . ($row - 1), null, $row - 5)]; // Productos
        $dataSeriesValues = [new DataSeriesValues('Number', 'Worksheet!$C$5:$C$' . ($row - 1), null, $row - 5)]; // Totales
    
        // Configurar el gráfico
        $series = new DataSeries(
            DataSeries::TYPE_PIECHART, // Gráfico de torta
            null,
            range(0, count($dataSeriesValues) - 1),
            $dataSeriesLabels,
            $xAxisTickValues,
            $dataSeriesValues
        );
    
        // Crear el área del gráfico
        $layout = new Layout();
        $plotArea = new PlotArea($layout, [$series]);
    
        // Crear el gráfico
        $chart = new Chart(
            'sales_chart',
            new Title('Productos más vendidos'),
            new Legend(Legend::POSITION_RIGHT, null, false),
            $plotArea
        );
    
        // Establecer la posición del gráfico en la hoja
        $chart->setTopLeftPosition('E4');
        $chart->setBottomRightPosition('K20');
    
        // Agregar el gráfico a la hoja
        $sheet->addChart($chart);
    
        // Guardar el archivo Excel con el gráfico
        $fileName = 'reporte_ventas_con_grafico.xlsx';
        $filePath = storage_path('app/public/' . $fileName);
    
        $writer = new Xlsx($spreadsheet);
        $writer->setIncludeCharts(true);
        $writer->save($filePath);
    
        // Descargar el archivo Excel generado
        return response()->download($filePath)->deleteFileAfterSend(true);
    }
    public function reportTopSellers(Request $request)
{
$startDate = $request->input('start_date') ? Carbon::parse($request->input('start_date')) : now()->startOfMonth();
$endDate = $request->input('end_date') ? Carbon::parse($request->input('end_date'))->endOfDay() : null;
$limit = $request->input('limit', 5); 


$query = DB::table('sales')
    ->join('users', 'sales.user_id', '=', 'users.id')
    ->select('users.name', DB::raw('SUM(sales.total_amount) as total_sales'))
    ->where('sales.status', 'completed') // Solo ventas con estado 'completed'
    ->whereIn('users.role', [1, 2]) // Filtrar usuarios con roles 1 o 2
    ->groupBy('users.name')
    ->orderBy('total_sales', 'desc');

// Si hay fecha de fin, aplicar whereBetween para el rango de fechas
if ($endDate) {
    $query->whereBetween('sales.created_at', [$startDate, $endDate]);
} else {
    // Si no hay fecha de fin, solo filtrar por la fecha exacta de inicio
    $query->whereDate('sales.created_at', '=', $startDate);
}

// Obtener los datos con el límite
$topSellers = $query->limit($limit)->get();

// Total de vendedores para ajustar el límite
$totalSellers = DB::table('users')->whereIn('role', [1, 2])->count();

return view('livewire.reports.top_sellers', compact('topSellers', 'startDate', 'endDate', 'totalSellers'));

}
public function exportExcelTopSellers(Request $request)
{
    // Obtener las fechas de inicio y fin
    $startDate = $request->input('start_date') ? Carbon::parse($request->input('start_date')) : now()->startOfMonth();
    $endDate = $request->input('end_date') ? Carbon::parse($request->input('end_date'))->endOfDay() : null;
    $limit = $request->input('limit', 5); // Límite de vendedores a mostrar
    
    // Aplicar los filtros de fecha y límite
    $salesQuery = DB::table('sales')
        ->join('users', 'sales.user_id', '=', 'users.id')
        ->select('users.name', DB::raw('SUM(sales.total_amount) as total_sales'))
        ->where('sales.status', 'completed') // Solo ventas con estado 'completed'
        ->whereIn('users.role', [1, 2]) // Filtrar usuarios con roles 1 o 2
        ->groupBy('users.name')
        ->orderBy('total_sales', 'desc');
    
    // Si se aplicaron fechas, filtrar por fecha
    if ($startDate && $endDate) {
        $salesQuery->whereBetween('sales.created_at', [$startDate, $endDate]);
    }

    // Aplicar el límite de vendedores
    $topSellers = $salesQuery->limit($limit)->get();

    // Crear un nuevo objeto de hoja de cálculo
    $spreadsheet = new Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();

    // Título del reporte basado en las fechas
    $reportTitle = "Reporte de los vendedores con más ventas acumuladas";
    if ($endDate) {
        $reportTitle .= " del " . $startDate->format('d/m/Y') . " al " . $endDate->format('d/m/Y');
    } else {
        $reportTitle .= " del " . $startDate->format('d/m/Y');
    }

    // Colocar el título del reporte
    $sheet->setCellValue('A1', $reportTitle);
    $sheet->mergeCells('A1:B1');
    $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(14);

    // Subtítulo de la fecha de creación
    $sheet->setCellValue('A2', 'Fecha de creación: ' . now()->format('d/m/Y'));
    $sheet->mergeCells('A2:B2');
    $sheet->getStyle('A2')->getFont()->setBold(true);

    // Encabezados de las columnas
    $sheet->setCellValue('A4', 'Vendedor');
    $sheet->setCellValue('B4', 'Total Ventas');
    
    // Agregar los datos de vendedores
    $row = 5;
    foreach ($topSellers as $seller) {
        $sheet->setCellValue('A' . $row, $seller->name);
        $sheet->setCellValue('B' . $row, $seller->total_sales);
        $row++;
    }

    // Autoajustar las columnas
    foreach (range('A', 'B') as $column) {
        $sheet->getColumnDimension($column)->setAutoSize(true);
    }

    // Crear el gráfico de torta
    $dataSeriesLabels = [new DataSeriesValues('String', 'Worksheet!$B$4', null, 1)];
    $xAxisTickValues = [new DataSeriesValues('String', 'Worksheet!$A$5:$A$' . ($row - 1), null, $row - 5)];
    $dataSeriesValues = [new DataSeriesValues('Number', 'Worksheet!$B$5:$B$' . ($row - 1), null, $row - 5)];

    $series = new DataSeries(
        DataSeries::TYPE_PIECHART, // Gráfico de torta
        null,
        range(0, count($dataSeriesValues) - 1),
        $dataSeriesLabels,
        $xAxisTickValues,
        $dataSeriesValues
    );

    // Configurar el área de gráfico y leyenda
    $plotArea = new PlotArea(null, [$series]);
    $chart = new Chart(
        'chart1', 
        new Title('Vendedores con más ventas'), 
        new Legend(Legend::POSITION_RIGHT, null, false), 
        $plotArera
    );

    // Posición del gráfico en la hoja
    $chart->setTopLeftPosition('D5');
    $chart->setBottomRightPosition('K20');
    
    // Agregar el gráfico a la hoja
    $sheet->addChart($chart);

    // Guardar el archivo Excel con el gráfico
    $fileName = 'reporte_top_vendedores.xlsx';
    $filePath = storage_path('app/public/' . $fileName);
    
    $writer = new Xlsx($spreadsheet);
    $writer->setIncludeCharts(true);
    $writer->save($filePath);
    
    // Retornar el archivo para descarga
    return response()->download($filePath)->deleteFileAfterSend(true);
}

}