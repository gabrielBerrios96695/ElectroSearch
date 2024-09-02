<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\UserRegistered;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Font;
use PhpOffice\PhpSpreadsheet\Style\Border;


class UserController extends Controller
{
    public function index(Request $request)
{
    $sortField = $request->input('sort_field', 'id');
    $sortDirection = $request->input('sort_direction', 'asc');
    $search = $request->input('search'); // Obtener el término de búsqueda

    // Filtrar usuarios por término de búsqueda si existe
    $query = User::query();
    
    if ($search) {
        $query->where('name', 'like', "%{$search}%")
              ->orWhere('email', 'like', "%{$search}%");
    }

    $users = $query->orderBy($sortField, $sortDirection)->get();

    return view('livewire.users.index', compact('users', 'sortField', 'sortDirection', 'search'));
}


    public function create()
    {
        return view('livewire/users.create');
    }

    public function edit(User $user)
    {
        return view('livewire/users.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
                'regex:/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/'
            ],
            'email' => 'required|email|max:255|unique:users,email,' . $user->id,
            'role' => 'required|string',
        ], [
            'name.required' => 'El nombre es obligatorio.',
            'name.string' => 'El nombre debe ser una cadena de texto.',
            'name.max' => 'El nombre no puede tener más de 255 caracteres.',
            'name.regex' => 'El nombre solo puede contener letras y espacios.',
            'email.required' => 'El correo electrónico es obligatorio.',
            'email.email' => 'El correo electrónico debe ser una dirección de correo válida.',
            'email.max' => 'El correo electrónico no puede tener más de 255 caracteres.',
            'email.unique' => 'El correo electrónico ya está en uso.',
            'role.required' => 'El rol es obligatorio.',
            'role.string' => 'El rol debe ser una cadena de texto.',
        ]);

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'role' => $request->role,
        ]);

        return redirect()->route('users.index')->with('success', 'Usuario actualizado correctamente.');
    }

    public function destroy(User $user)
    {
        // Eliminar lógicamente el usuario
        $user->status = 0;
        $user->save();

        return redirect()->route('users.index')->with('success', 'Usuario deshabilitado correctamente.');
    }

    public function store(Request $request)
{
    $request->validate([
        'name' => [
            'required',
            'string',
            'max:255',
            'regex:/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/'
        ],
        'email' => 'required|string|email|max:255|unique:users',
        'password' => 'required|string|min:8|confirmed',
        'role' => 'required|string',
    ], [
        'name.required' => 'El nombre es obligatorio.',
        'name.string' => 'El nombre debe ser una cadena de texto.',
        'name.max' => 'El nombre no puede tener más de 255 caracteres.',
        'name.regex' => 'El nombre solo puede contener letras y espacios.',
        'email.required' => 'El correo electrónico es obligatorio.',
        'email.string' => 'El correo electrónico debe ser una cadena de texto.',
        'email.email' => 'El correo electrónico debe ser una dirección de correo válida.',
        'email.max' => 'El correo electrónico no puede tener más de 255 caracteres.',
        'email.unique' => 'El correo electrónico ya está en uso.',
        'password.required' => 'La contraseña es obligatoria.',
        'password.string' => 'La contraseña debe ser una cadena de texto.',
        'password.min' => 'La contraseña debe tener al menos 8 caracteres.',
        'password.confirmed' => 'La confirmación de la contraseña no coincide.',
        'role.required' => 'El rol es obligatorio.',
        'role.string' => 'El rol debe ser una cadena de texto.',
    ]);

    // Definir la contraseña antes de crear el usuario
    $password = $request->password;

    // Crear el usuario
    $user = User::create([
        'name' => $request->name,
        'email' => $request->email,
        'password' => Hash::make($password),
        'role' => $request->role,
    ]);

    // Enviar el correo electrónico al usuario con la contraseña generada
    \Illuminate\Support\Facades\Mail::to($user->email)->send(new \App\Mail\UserRegistered($user, $password));

    return redirect()->route('users.index')->with('success', 'Usuario creado exitosamente.');
}



    public function toggleStatus($id)
    {
        $user = User::findOrFail($id);
        $user->status = !$user->status; // Alternar el estado
        $user->save();

        return redirect()->route('users.index')->with('success', 'Estado del usuario actualizado.');
    }

    public function exportToExcel()
{
    $users = User::all(); // Obtiene todos los usuarios

    // Crear una nueva hoja de cálculo
    $spreadsheet = new Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();

    // Configurar el título en grande y centrarlo
    $sheet->setCellValue('A1', 'ElectroSearch');
    $sheet->mergeCells('A1:E1'); // Fusionar celdas A1 a E1
    $sheet->getStyle('A1')->getFont()->setSize(22)->setBold(true);
    $sheet->getStyle('A1')->getAlignment()
        ->setHorizontal(Alignment::HORIZONTAL_CENTER)
        ->setVertical(Alignment::VERTICAL_CENTER);
    $sheet->getStyle('A1')->getFill()
        ->setFillType(Fill::FILL_SOLID)
        ->getStartColor()->setARGB('007BFF');
    $sheet->getStyle('A1')->getFont()->getColor()->setARGB('FFFFFF'); // Color de texto blanco

    // Configurar el subtítulo
    $sheet->setCellValue('A2', 'Lista de Usuarios');
    $sheet->mergeCells('A2:E2'); // Fusionar celdas A2 a E2
    $sheet->getStyle('A2')->getFont()->setSize(16)->setBold(true);
    $sheet->getStyle('A2')->getAlignment()
        ->setHorizontal(Alignment::HORIZONTAL_CENTER)
        ->setVertical(Alignment::VERTICAL_CENTER);
    $sheet->getStyle('A2')->getFill()
        ->setFillType(Fill::FILL_SOLID)
        ->getStartColor()->setARGB('0056A0'); // Color de fondo para el subtítulo
    $sheet->getStyle('A2')->getFont()->getColor()->setARGB('FFFFFF'); // Color de texto blanco

    // Configurar encabezados de la hoja de cálculo
    $sheet->setCellValue('A3', 'ID');
    $sheet->setCellValue('B3', 'Nombre');
    $sheet->setCellValue('C3', 'Correo');
    $sheet->setCellValue('D3', 'Rol');
    $sheet->setCellValue('E3', 'Estado');

    // Configurar estilo para encabezados
    $sheet->getStyle('A3:E3')->getFont()->setBold(true);
    $sheet->getStyle('A3:E3')->getFill()
        ->setFillType(Fill::FILL_SOLID)
        ->getStartColor()->setARGB('CCCCCC');
    $sheet->getStyle('A3:E3')->getAlignment()
        ->setHorizontal(Alignment::HORIZONTAL_CENTER)
        ->setVertical(Alignment::VERTICAL_CENTER);
    $sheet->getStyle('A3:E3')->getBorders()
        ->getAllBorders()->setBorderStyle(Border::BORDER_THIN);

    // Añadir datos de los usuarios
    $row = 4; // Comenzar en la fila 4 después del subtítulo
    foreach ($users as $user) {
        $sheet->setCellValue('A' . $row, $user->id);
        $sheet->setCellValue('B' . $row, $user->name);
        $sheet->setCellValue('C' . $row, $user->email);
        $sheet->setCellValue('D' . $row, $user->role);
        $sheet->setCellValue('E' . $row, $user->status ? 'Activo' : 'Inactivo');
        $row++;
    }

    // Configurar estilo para celdas de datos
    $sheet->getStyle('A4:E' . ($row - 1))->getBorders()
        ->getAllBorders()->setBorderStyle(Border::BORDER_THIN);

    // Configurar ancho de columnas
    foreach (range('A', 'E') as $columnID) {
        $sheet->getColumnDimension($columnID)->setAutoSize(true);
    }

    // Crear el archivo Excel y descargarlo
    $writer = new Xlsx($spreadsheet);
    $fileName = 'usuarios_' . now()->format('Ymd_His') . '.xlsx';
    $temp_file = tempnam(sys_get_temp_dir(), $fileName);
    $writer->save($temp_file);

    return response()->download($temp_file, $fileName)->deleteFileAfterSend(true);
}


}

