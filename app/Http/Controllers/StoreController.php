<?php
namespace App\Http\Controllers;

use App\Models\Store;
use App\Models\User;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Border;


class StoreController extends Controller
{
    public function index(Request $request)
    {
        $sortField = $request->input('sort_field', 'id');
        $sortDirection = $request->input('sort_direction', 'asc');

        if (Auth::user()->isAdmin()) {
            $stores = Store::orderBy($sortField, $sortDirection)->get();
        } else {
            $stores = Store::where('status', 1)->orderBy($sortField, $sortDirection)->get();
        }

        // Obtener los nombres de los usuarios
        foreach ($stores as $store) {
            $store->created_by = User::find($store->userid)->name ?? 'N/A';
        }

        return view('livewire.store.index', compact('stores', 'sortField', 'sortDirection'));
    }

    public function create()
    {
        return view('livewire.store.create');
    }

    public function edit(Store $store)
    {
        return view('livewire.store.edit', compact('store'));
    }

    public function update(Request $request, Store $store)
    {
        $request->validate([
            'name' => [
                'required',
                'string',
                'max:50',
                'regex:/^[a-zA-Z0-9áéíóúÁÉÍÓÚñÑ\s]+$/'
            ],
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
        ], [
            
            'name.regex' => 'El nombre no puede tener caracteres',
            
        ]);

        $store->update([
            'name' => $request->name,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
        ]);

        return redirect()->route('store.index')->with('success', 'Tienda actualizada correctamente.');
    }


    public function show(Request $request)
{
    // Cargar todas las tiendas con sus productos y categorías
    $stores = Store::with(['products.category'])->get();

    // Filtro por nombre de producto
    if ($request->filled('search')) {
        $stores = $stores->filter(function ($store) use ($request) {
            return $store->products->contains(function ($product) use ($request) {
                return stripos($product->nombre, $request->search) !== false;
            });
        });
    }

    $categories = Category::all(); // Obtener todas las categorías

    return view('livewire.store.show', compact('stores', 'categories'));
}






    public function destroy(Store $store)
    {
        // Agregar lógica para eliminar la tienda
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
                'regex:/^[a-zA-Z0-9áéíóúÁÉÍÓÚñÑ\s]+$/'
            ],
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
        ], [
            'name.required' => 'El nombre de la tienda es obligatorio.',
            'name.string' => 'El nombre de la tienda debe ser una cadena de texto.',
            'name.max' => 'El nombre de la tienda no puede tener más de 255 caracteres.',
            'name.regex' => 'El nombre de la tienda solo puede contener letras y espacios.',
            'latitude.required' => 'La latitud es obligatoria.',
            'latitude.numeric' => 'La latitud debe ser un número.',
            'longitude.required' => 'La longitud es obligatoria.',
            'longitude.numeric' => 'La longitud debe ser un número.',
        ]);

        Store::create([
            'name' => $request->name,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
        ]);

        return redirect()->route('store.index')->with('success', 'Tienda creada exitosamente.');
    }

    public function toggleStatus(Store $store)
    {
        $store->status = !$store->status;
        $store->save();

        return redirect()->route('store.index')->with('success', 'Estado de la tienda actualizado correctamente.');
    }

   

public function exportToExcel()
{
    // Obtener todas las tiendas
    $stores = Store::all();

    // Crear una nueva hoja de cálculo
    $spreadsheet = new Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();

    // Configurar el título principal
    $sheet->setCellValue('A1', 'ElectroSearch');
    $sheet->mergeCells('A1:E1');
    $sheet->getStyle('A1')->applyFromArray([
        'font' => [
            'size' => 22,
            'bold' => true,
            'color' => ['argb' => 'FFFFFF']
        ],
        'alignment' => [
            'horizontal' => Alignment::HORIZONTAL_CENTER,
            'vertical' => Alignment::VERTICAL_CENTER
        ],
        'fill' => [
            'fillType' => Fill::FILL_SOLID,
            'startColor' => ['argb' => '007BFF']
        ]
    ]);

    // Configurar el subtítulo
    $sheet->setCellValue('A2', 'Lista de Tiendas');
    $sheet->mergeCells('A2:E2');
    $sheet->getStyle('A2')->applyFromArray([
        'font' => [
            'size' => 16,
            'bold' => true,
            'color' => ['argb' => 'FFFFFF']
        ],
        'alignment' => [
            'horizontal' => Alignment::HORIZONTAL_CENTER,
            'vertical' => Alignment::VERTICAL_CENTER
        ],
        'fill' => [
            'fillType' => Fill::FILL_SOLID,
            'startColor' => ['argb' => '0056A0']
        ]
    ]);

    // Configurar los encabezados de columnas
    $headers = ['ID', 'Nombre', 'Latitud', 'Longitud', 'Creado por'];
    $column = 'A';
    foreach ($headers as $header) {
        $sheet->setCellValue($column . '3', $header);
        $sheet->getStyle($column . '3')->applyFromArray([
            'font' => [
                'bold' => true,
                'color' => ['argb' => 'FFFFFF']
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['argb' => '4BACC6']
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['argb' => '000000']
                ]
            ]
        ]);
        $column++;
    }

    // Agregar datos de las tiendas
    $row = 4;
    foreach ($stores as $store) {
        $createdBy = User::find($store->userid)->name ?? 'N/A';
        $sheet->setCellValue('A' . $row, $store->id);
        $sheet->setCellValue('B' . $row, $store->name);
        $sheet->setCellValue('C' . $row, $store->latitude);
        $sheet->setCellValue('D' . $row, $store->longitude);
        $sheet->setCellValue('E' . $row, $createdBy);

        // Estilo de las filas de datos
        $sheet->getStyle('A' . $row . ':E' . $row)->applyFromArray([
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_LEFT,
                'vertical' => Alignment::VERTICAL_CENTER
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['argb' => 'CCCCCC']
                ]
            ]
        ]);
        $row++;
    }

    // Ajustar ancho de columnas automáticamente
    foreach (range('A', 'E') as $columnID) {
        $sheet->getColumnDimension($columnID)->setAutoSize(true);
    }

    // Crear el archivo Excel y descargarlo
    $writer = new Xlsx($spreadsheet);
    $fileName = 'tiendas_' . now()->format('Ymd_His') . '.xlsx';
    $temp_file = tempnam(sys_get_temp_dir(), $fileName);
    $writer->save($temp_file);

    return response()->download($temp_file, $fileName)->deleteFileAfterSend(true);
}

}