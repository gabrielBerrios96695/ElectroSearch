<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Recibo de Venta</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            color: #333;
            margin: 0;
            padding: 0;
            background-color: #f4f7fa;
        }

        .container {
            width: 80%;
            margin: 30px auto;
            padding: 30px;
            border: 1px solid #ddd;
            border-radius: 10px;
            background-color: white;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            font-size: 14px;
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 2px solid #ddd;
        }

        .header div {
            text-align: right;
        }

        .header h1 {
            font-size: 22px;
            margin: 0;
            color: #0073e6;
        }

        .header p {
            font-size: 14px;
            color: #666;
            margin: 0;
        }

        .details {
            margin: 20px 0;
            font-size: 14px;
            line-height: 1.6;
        }

        .details strong {
            color: #333;
        }

        .table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            font-size: 14px;
        }

        .table thead th {
            background-color: #0073e6;
            color: #fff;
            padding: 12px 15px;
            text-align: left;
            border: 1px solid #ddd;
        }

        .table tbody td {
            padding: 12px 15px;
            border: 1px solid #ddd;
            text-align: left;
        }

        .table tfoot td {
            font-weight: bold;
            text-align: right;
            padding: 12px 15px;
            border-top: 2px solid #0073e6;
        }

        .total {
            text-align: right;
            font-size: 16px;
            font-weight: bold;
            color: #0073e6;
            margin-top: 20px;
        }

        .footer {
            text-align: center;
            font-size: 12px;
            color: #666;
            margin-top: 30px;
            border-top: 1px solid #ddd;
            padding-top: 10px;
        }

        .note {
            font-size: 12px;
            color: #999;
            margin-top: 15px;
        }

        /* Sombra suave para las tablas */
        .table td, .table th {
            border-radius: 5px;
        }

        /* Fondo de la página */
        .container {
            background-color: #ffffff;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.05);
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Encabezado -->
        <div class="header">
            <div>
                <h1>Recibo de Venta #{{ $sale->id }}</h1>
                <p>Fecha: {{ $sale->created_at->format('d/m/Y H:i') }}</p>
            </div>
        </div>

        <!-- Información de Vendedor y Cliente -->
        <div class="details">
            <p><strong>Vendedor:</strong> 
                {{ $sale->user ? $sale->user->name . ' ' . $sale->user->last_name . ' ' . ($sale->user->second_last_name ?? '') : 'Desconocido' }}
            </p>
            <p><strong>Cliente:</strong> 
                {{ $sale->customer ? $sale->customer->name . ' ' . $sale->customer->last_name . ' ' . ($sale->customer->second_last_name ?? '') : 'Desconocido' }}
            </p>
        </div>

        <!-- Detalles de Productos -->
        <h3>Detalles de Productos</h3>
        <table class="table">
            <thead>
                <tr>
                    <th>Producto</th>
                    <th>Cantidad</th>
                    <th>Precio Unitario</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach($sale->details as $detail)
                    <tr>
                        <td>{{ $detail->product->name }}</td>
                        <td>{{ $detail->quantity }}</td>
                        <td>{{ number_format($detail->price, 2) }} Bs</td>
                        <td>{{ number_format($detail->total, 2) }} Bs</td>
                    </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="3">Total de la Venta:</td>
                    <td>{{ number_format($sale->total_amount, 2) }} Bs</td>
                </tr>
            </tfoot>
        </table>

        <!-- Mensaje Final -->
        <div class="footer">
            <p>Gracias por tu Pedido. ¡Te esperamos nuevamente!</p>
        </div>

        <div class="note">
            <p>Este es un recibo digital, por favor guárdelo para futuras referencias.</p>
        </div>
    </div>
</body>
</html>
