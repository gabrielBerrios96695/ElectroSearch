<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Font;
use PhpOffice\PhpSpreadsheet\Style\Border;

class ProductController extends Controller
{
    public function index(Request $request)
{
    $sortField = $request->input('sort_field', 'id'); // Campo de ordenamiento, por defecto 'id'
    $sortDirection = $request->input('sort_direction', 'asc'); // Dirección de ordenamiento, por defecto 'asc'
    $search = $request->input('search'); // Obtener el término de búsqueda

    // Crear consulta base para productos
    $query = Product::query();

    // Filtrar productos por término de búsqueda si existe
    if ($search) {
        $query->where('name', 'like', "%{$search}%")
              ->orWhere('description', 'like', "%{$search}%")
              ->orWhereHas('category', function ($q) use ($search) {
                  $q->where('name', 'like', "%{$search}%");
              });
    }

    // Ordenar los productos y obtener los resultados
    $products = $query->orderBy($sortField, $sortDirection)->get();

    return view('livewire.products.index', compact('products', 'sortField', 'sortDirection', 'search'));
}



public function create()
{
    $categories = Category::all();
    return view('livewire.products.create', compact('categories'));
}

public function store(Request $request)
{
    $request->validate([
        'name' => [
            'required',
            'regex:/^[a-zA-Z0-9\s]+$/'
        ],
        'description' => [
            'required',
            'regex:/^[a-zA-Z0-9\s]+$/'
        ],
        'price' => 'required|numeric|min:0',
        'image' => 'required|image',
        'category_id' => 'required|exists:categories,id',
        'store_id' => 'required|exists:stores,id',
    ], [
        'name.required' => 'El nombre es obligatorio.',
        'name.regex' => 'El nombre solo puede contener letras, números y espacios.',
        'description.required' => 'La descripción es obligatoria.',
        'description.regex' => 'La descripción solo puede contener letras, números y espacios.',
        'price.required' => 'El precio es obligatorio.',
        'price.numeric' => 'El precio debe ser un número.',
        'price.min' => 'El precio debe ser mayor o igual a 0.',
        'image.required' => 'La imagen es obligatoria.',
        'image.image' => 'El archivo debe ser una imagen.',
        'category_id.required' => 'La categoría es obligatoria.',
        'category_id.exists' => 'La categoría debe existir en la base de datos.',
        'store_id.required' => 'El ID de la tienda es obligatorio.',
        'store_id.exists' => 'El ID de la tienda debe existir en la base de datos.',
    ]);

    $productCount = Product::where('store_id', $request->store_id)->count() + 1;

    if ($request->hasFile('image')) {
        $extension = $request->image->extension();
        $imageName = 't' . $request->store_id . '-p' . $productCount . '.' . $extension;
        $path = $request->image->storeAs('public/images', $imageName);
    }

    Product::create([
        'name' => $request->name,
        'description' => $request->description,
        'price' => $request->price,
        'image' => $imageName ?? null,
        'category_id' => $request->category_id,
        'store_id' => $request->store_id,
    ]);

    return redirect()->route('products.index')->with('success', 'Producto creado exitosamente.');
}


    public function edit(Product $product)
    {
        // Obtener todas las categorías
        $categories = Category::all();
    
        // Pasar el producto y las categorías a la vista
        return view('livewire.products.edit', compact('product', 'categories'));
    }
    

    public function update(Request $request, Product $product)
{
    $request->validate([
        'name' => [
            'required',
            'regex:/^[a-zA-Z0-9\s]+$/'
        ],
        'description' => [
            'required',
            'regex:/^[a-zA-Z0-9\s]+$/'
        ],
        'price' => 'required|numeric|min:0',
        'image' => 'nullable|image',
        'category_id' => 'required|exists:categories,id',
        'store_id' => 'required|exists:stores,id',
    ], [
        'name.required' => 'El nombre es obligatorio.',
        'name.regex' => 'El nombre solo puede contener letras, números y espacios.',
        'description.required' => 'La descripción es obligatoria.',
        'description.regex' => 'La descripción solo puede contener letras, números y espacios.',
        'price.required' => 'El precio es obligatorio.',
        'price.numeric' => 'El precio debe ser un número.',
        'price.min' => 'El precio debe ser mayor o igual a 0.',
        'image.nullable' => 'La imagen es opcional.',
        'image.image' => 'El archivo debe ser una imagen.',
        'category_id.required' => 'La categoría es obligatoria.',
        'category_id.exists' => 'La categoría debe existir en la base de datos.',
        'store_id.required' => 'El ID de la tienda es obligatorio.',
        'store_id.exists' => 'El ID de la tienda debe existir en la base de datos.',
    ]);

    // Manejo de la imagen
    if ($request->hasFile('image')) {
        // Eliminar la imagen anterior si existe
        if ($product->image) {
            Storage::delete('public/images/' . $product->image);
        }

        // Almacenar la nueva imagen
        $extension = $request->image->extension();
        $imageName = 't' . $request->store_id . '-p' . $product->id . '.' . $extension;
        $path = $request->image->storeAs('public/images', $imageName);
        $product->image = $imageName;
    }

    // Actualizar el producto con los nuevos datos
    $product->update([
        'name' => $request->name,
        'description' => $request->description,
        'price' => $request->price,
        'image' => $product->image,
        'category_id' => $request->category_id,
        'store_id' => $request->store_id,
    ]);

    return redirect()->route('products.index')->with('success', 'Producto actualizado correctamente.');
}


    public function destroy(Product $product)
    {
        
            try {
                if ($product->imagen) {
                    Storage::delete('public/images/' . $product->imagen);
                }
        
                $product->delete();
                return redirect()->route('products.index')->with('success', 'Producto eliminado correctamente.');
            } catch (\Exception $e) {
                return redirect()->route('products.index')->with('error', 'Ocurrió un error al intentar eliminar el producto.');
            }
        
    }

    public function exportToExcel()
{
    $products = Product::all(); // Obtener todos los productos

    // Crear una nueva hoja de cálculo
    $spreadsheet = new Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();

    // Configurar el título principal
    $sheet->setCellValue('A1', 'ElectroSearch');
    $sheet->mergeCells('A1:F1');
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
    $sheet->setCellValue('A2', 'Lista de Productos');
    $sheet->mergeCells('A2:F2');
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

    // Configurar los encabezados
    $headers = ['ID', 'Nombre', 'Descripción', 'Precio', 'Categoría', 'Tienda'];
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

    // Agregar datos de los productos
    $row = 4;
    foreach ($products as $product) {
        $sheet->setCellValue('A' . $row, $product->id);
        $sheet->setCellValue('B' . $row, $product->nombre);
        $sheet->setCellValue('C' . $row, $product->descripcion);
        $sheet->setCellValue('D' . $row, $product->precio);
        $sheet->setCellValue('E' . $row, $product->categoria);
        $sheet->setCellValue('F' . $row, $product->store->nombre ?? 'N/A'); // Asumiendo relación con 'store'

        // Estilo de filas de datos
        $sheet->getStyle('A' . $row . ':F' . $row)->applyFromArray([
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
    foreach (range('A', 'F') as $columnID) {
        $sheet->getColumnDimension($columnID)->setAutoSize(true);
    }

    // Crear el archivo Excel y descargarlo
    $writer = new Xlsx($spreadsheet);
    $fileName = 'productos_' . now()->format('Ymd_His') . '.xlsx';
    $temp_file = tempnam(sys_get_temp_dir(), $fileName);
    $writer->save($temp_file);

    return response()->download($temp_file, $fileName)->deleteFileAfterSend(true);
}

}