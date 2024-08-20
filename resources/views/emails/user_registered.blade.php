<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro Exitoso - Eco Ruta</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #e9f5e1;
            color: #333;
            margin: 0;
            padding: 0;
        }
        .container {
            width: 90%;
            max-width: 600px;
            margin: auto;
            padding: 20px;
            background-color: #ffffff;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        h1 {
            color: #2c6f41;
            text-align: center;
        }
        p {
            font-size: 16px;
            line-height: 1.5;
            margin-bottom: 10px;
        }
        .footer {
            margin-top: 20px;
            font-size: 14px;
            color: #7f8c8d;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>¡Bienvenido a Eco Ruta!</h1>
        <p>Hola {{ $user->name }},</p>
        <p>Tu registro en Eco Ruta se ha completado con éxito.</p>
        
        <p><strong>Detalles de tu cuenta:</strong></p>
        <p><strong>Correo electrónico:</strong> {{ $user->email }}</p>

        <p>Por favor, utiliza estos datos para iniciar sesión en nuestra aplicación.</p>
        <p><strong>Nota:</strong> Te recomendamos que cambies tu contraseña después de tu primer inicio de sesión por razones de seguridad.</p>
        
        <div class="footer">
            <p>Si tienes alguna pregunta, no dudes en contactarnos.</p>
            <p>¡Gracias por ser parte de nuestra comunidad Eco Ruta!</p>
        </div>
    </div>
</body>
</html>