<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reporte de Ventas</title>
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
            text-align: center;
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 2px solid #ddd;
        }

        .header h1 {
            font-size: 24px;
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

        .table th, .table td {
            padding: 12px 15px;
            border: 1px solid #ddd;
            text-align: left;
        }

        .table th {
            background-color: #0073e6;
            color: #fff;
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

        .table td, .table th {
            border-radius: 5px;
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Encabezado -->
        <div class="header">
            <h1>Reporte de Productos Más Vendidos</h1>
            <p>Desde: {{ $startDate->format('d/m/Y') }} Hasta: {{ $endDate ? $endDate->format('d/m/Y') : 'Hoy' }}</p>
        </div>

        <!-- Detalles de Productos -->
        <h3>Detalles de Productos</h3>
        <table class="table">
            <thead>
                <tr>
                    <th>Producto</th>
                    <th>Cantidad Vendida</th>
                    <th>Total Recaudado</th>
                </tr>
            </thead>
            <tbody>
                @foreach($salesData as $data)
                    <tr>
                        <td>{{ $data->name }}</td>
                        <td>{{ $data->quantity }}</td>
                        <td>{{ number_format($data->total, 2) }} Bs</td>
                    </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="2">Total Acumulado:</td>
                    <td>{{ number_format($salesData->sum('total'), 2) }} Bs</td>
                </tr>
            </tfoot>
        </table>


        <div class="note">
            <p>Este es un reporte digital, por favor guárdalo para futuras referencias.</p>
        </div>
    </div>
</body>
</html>
