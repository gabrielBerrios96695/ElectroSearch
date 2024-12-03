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
    
        // Filtrar por rol 1 (Administrador) o 2 (Vendedor)
        $query->whereIn('role', [1, 2]);
    
        $users = $query->orderBy($sortField, $sortDirection)->get();
        return view('livewire.users.index', compact('users', 'sortField', 'sortDirection', 'search'));
    }
    public function indexClient(Request $request)
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
    
        // Filtrar por rol 1 (Administrador) o 2 (Vendedor)
        $query->whereIn('role', [0, 3]);
    
        $users = $query->orderBy($sortField, $sortDirection)->get();
        return view('livewire.clients.index', compact('users', 'sortField', 'sortDirection', 'search'));
    }

    // Método para mostrar el formulario de registro de un nuevo cliente
    public function createClient()
    {
        return view('livewire.clients.create');
    }

    // Método para almacenar un nuevo cliente en la base de datos
    public function storeClient(Request $request)
{
    $request->validate([
        'name' => [
            'required',
            'string',
            'max:255',
            'regex:/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/',
            'regex:/^[^\s]+(\s[^\s]+)*$/', // Solo un espacio entre nombres
        ],
        'last_name' => [
            'required',
            'string',
            'max:255',
            'regex:/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/', // Evita caracteres especiales y números
        ],
        'second_last_name' => [
            'nullable',
            'string',
            'max:255',
            'regex:/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/', // Evita caracteres especiales y números
        ],
        'email' => 'required|string|email|max:255|unique:users',
        'phone' => 'nullable|regex:/^[0-9]{1,10}$/', // Permitimos hasta 10 dígitos, solo números
        'password' => 'required|string|min:8|confirmed',

    ], [
        'name.required' => 'El nombre es obligatorio.',
        'name.string' => 'El nombre debe ser una cadena de texto.',
        'name.max' => 'El nombre no puede tener más de 255 caracteres.',
        'name.regex' => 'El nombre solo puede contener letras y espacios.',
        'name.regex.regex' => 'El nombre solo puede contener un único espacio entre nombres.',
        'last_name.required' => 'El apellido es obligatorio.',
        'last_name.string' => 'El apellido debe ser una cadena de texto.',
        'last_name.max' => 'El apellido no puede tener más de 255 caracteres.',
        'last_name.regex' => 'El apellido no puede contener caracteres especiales ni números.',
        'second_last_name.string' => 'El segundo apellido debe ser una cadena de texto.',
        'second_last_name.max' => 'El segundo apellido no puede tener más de 255 caracteres.',
        'second_last_name.regex' => 'El segundo apellido no puede contener caracteres especiales ni números.',
        'email.required' => 'El correo electrónico es obligatorio.',
        'email.string' => 'El correo electrónico debe ser una cadena de texto.',
        'email.email' => 'El correo electrónico debe ser una dirección de correo válida.',
        'email.max' => 'El correo electrónico no puede tener más de 255 caracteres.',
        'email.unique' => 'El correo electrónico ya está en uso.',
        'phone.regex' => 'El teléfono debe contener solo números y puede tener hasta 10 dígitos.',
        'password.required' => 'La contraseña es obligatoria.',
        'password.string' => 'La contraseña debe ser una cadena de texto.',
        'password.min' => 'La contraseña debe tener al menos 8 caracteres.',
        'password.confirmed' => 'La confirmación de la contraseña no coincide.',

    ]);

    // Definir la contraseña antes de crear el usuario
    $password = $request->password;
    $phone = $request->phone;
    // Crear el usuario
    $user = User::create([
        'name' => $request->name,
        'last_name' => $request->last_name,
        'second_last_name' => $request->second_last_name,
        'email' => $request->email,
'phone' => $request->phone, // Guardar el teléfono
        'password' => Hash::make($password),
        'role' => 3
    ]);

    // Enviar el correo electrónico al usuario con la contraseña generada
    \Illuminate\Support\Facades\Mail::to($user->email)->send(new \App\Mail\UserRegistered($user, $password));

    return redirect()->route('clients.index')->with('success', 'Usuario creado exitosamente.');
    }

    // Método para cambiar el estado de un cliente (habilitar/deshabilitar)
    public function toggleStatusClient(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $user->status = !$user->status; // Cambia entre 1 y 0
        $user->save();

        return redirect()->route('clients.index')->with('success', 'Estado del cliente actualizado.');
    }

    // Método para exportar los datos de los Usuarios
    public function exportClients()
    {
        // Lógica para exportar a Excel, CSV, etc.
        // Aquí puedes usar paquetes como Maatwebsite Excel para hacer la exportación.
        return response()->download(storage_path('clients_export.xlsx'));
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
    // dd($request->all()); // Descomenta esto si necesitas depurar los datos

    // Validación de los datos recibidos
    $request->validate([
        'name' => [
            'required',
            'string',
            'max:255',
            'regex:/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/'
        ],
        'last_name' => [
            'required',
            'string',
            'max:255',
            'regex:/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/'
        ],
        'second_last_name' => [
            'nullable', // El segundo apellido es opcional
            'string',
            'max:255',
            'regex:/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]*$/', // Permitimos que esté vacío o tenga letras y espacios
        ],
        'email' => 'required|email|max:255|unique:users,email,' . $user->id,
        'role' => 'nullable|string|in:1,2,3', // Aseguramos que el rol esté dentro de los valores válidos
        'phone' => [
            'nullable', // Hacemos que el teléfono sea opcional
            'regex:/^[0-9]+$/', // Solo números
            'min:1', // Mínimo 1 dígito
            'max:14', // Máximo 14 dígitos
        ],
    ], [
        'name.required' => 'El nombre es obligatorio.',
        'name.string' => 'El nombre debe ser una cadena de texto.',
        'name.max' => 'El nombre no puede tener más de 255 caracteres.',
        'name.regex' => 'El nombre solo puede contener letras y espacios.',
        
        'last_name.required' => 'El apellido es obligatorio.',
        'last_name.string' => 'El apellido debe ser una cadena de texto.',
        'last_name.max' => 'El apellido no puede tener más de 255 caracteres.',
        'last_name.regex' => 'El apellido solo puede contener letras y espacios.',
        
        'second_last_name.string' => 'El segundo apellido debe ser una cadena de texto.',
        'second_last_name.max' => 'El segundo apellido no puede tener más de 255 caracteres.',
        'second_last_name.regex' => 'El segundo apellido solo puede contener letras y espacios.',
        
        'email.required' => 'El correo electrónico es obligatorio.',
        'email.email' => 'El correo electrónico debe ser una dirección de correo válida.',
        'email.max' => 'El correo electrónico no puede tener más de 255 caracteres.',
        'email.unique' => 'El correo electrónico ya está en uso.',
        
        'phone.regex' => 'El teléfono debe contener solo números.',
        'phone.min' => 'El teléfono debe tener al menos 1 dígito.',
        'phone.max' => 'El teléfono no puede tener más de 14 dígitos.',
    ]);

    // Si el teléfono está vacío, lo dejamos como NULL
    $phone = $request->phone ?: null;

    // Actualización de los datos del usuario
    $user->update([
        'name' => $request->name,
        'last_name' => $request->last_name,
        'second_last_name' => $request->second_last_name,
        'email' => $request->email,
        'role' => $request->role ?? 3, // Asignamos el rol 3 (Cliente) por defecto si no se proporciona
        'phone' => $phone, // Si el teléfono es vacío, se asigna null
    ]);

    // Redirigir a la lista de usuarios con un mensaje de éxito
    return back()->with('success', 'Usuario actualizado correctamente.');
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
            'regex:/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/',
            'regex:/^[^\s]+(\s[^\s]+)*$/', // Solo un espacio entre nombres
        ],
        'last_name' => [
            'required',
            'string',
            'max:255',
            'regex:/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/', // Evita caracteres especiales y números
        ],
        'second_last_name' => [
            'nullable',
            'string',
            'max:255',
            'regex:/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/', // Evita caracteres especiales y números
        ],
        'email' => 'required|string|email|max:255|unique:users',
        'phone' => 'nullable|regex:/^[0-9]{1,10}$/', // Permitimos hasta 10 dígitos, solo números
        'password' => 'required|string|min:8|confirmed',
        'role' => 'nullable|string',
    ], [
        'name.required' => 'El nombre es obligatorio.',
        'name.string' => 'El nombre debe ser una cadena de texto.',
        'name.max' => 'El nombre no puede tener más de 255 caracteres.',
        'name.regex' => 'El nombre solo puede contener letras y espacios.',
        'name.regex.regex' => 'El nombre solo puede contener un único espacio entre nombres.',
        'last_name.required' => 'El apellido es obligatorio.',
        'last_name.string' => 'El apellido debe ser una cadena de texto.',
        'last_name.max' => 'El apellido no puede tener más de 255 caracteres.',
        'last_name.regex' => 'El apellido no puede contener caracteres especiales ni números.',
        'second_last_name.string' => 'El segundo apellido debe ser una cadena de texto.',
        'second_last_name.max' => 'El segundo apellido no puede tener más de 255 caracteres.',
        'second_last_name.regex' => 'El segundo apellido no puede contener caracteres especiales ni números.',
        'email.required' => 'El correo electrónico es obligatorio.',
        'email.string' => 'El correo electrónico debe ser una cadena de texto.',
        'email.email' => 'El correo electrónico debe ser una dirección de correo válida.',
        'email.max' => 'El correo electrónico no puede tener más de 255 caracteres.',
        'email.unique' => 'El correo electrónico ya está en uso.',
        'phone.regex' => 'El teléfono debe contener solo números y puede tener hasta 10 dígitos.',
        'password.required' => 'La contraseña es obligatoria.',
        'password.string' => 'La contraseña debe ser una cadena de texto.',
        'password.min' => 'La contraseña debe tener al menos 8 caracteres.',
        'password.confirmed' => 'La confirmación de la contraseña no coincide.',
        'role.required' => 'El rol es obligatorio.',
        'role.string' => 'El rol debe ser una cadena de texto.',
    ]);

    // Definir la contraseña antes de crear el usuario
    $password = $request->password;
    $phone = $request->phone;
    // Crear el usuario
    $user = User::create([
        'name' => $request->name,
        'last_name' => $request->last_name,
        'second_last_name' => $request->second_last_name,
        'email' => $request->email,
        'phone' => (string)$request->phone, 

        'password' => Hash::make($password),
        'role' => $request->role,
    ]);

    // Enviar el correo electrónico al usuario con la contraseña generada
    \Illuminate\Support\Facades\Mail::to($user->email)->send(new \App\Mail\UserRegistered($user, $password));

    return redirect()->route('users.index')->with('success', 'Usuario creado exitosamente.');
}



public function toggleStatus(Request $request, User $user)
{
    // Validar la solicitud
    $validated = $request->validate([
        'status' => 'required|boolean',
    ]);

    // Cambiar el estado del usuario
    $user->status = $validated['status'];
    $user->save();

    // Redirigir a la página anterior con un mensaje de éxito
    return redirect()->back()->with('success', 'Estado del usuario actualizado exitosamente.');
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

