<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reporte de Ventas</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }

        h1, h2 {
            text-align: center;
            color: #333;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        table, th, td {
            border: 1px solid #ddd;
            padding: 8px;
        }

        th {
            background-color: #f2f2f2;
            color: black;
        }

        td {
            text-align: center;
        }

        .footer {
            margin-top: 20px;
            text-align: right;
            font-size: 12px;
            color: #777;
        }

        #chart-container {
            max-width: 400px;
            margin: 20px auto;
        }
    </style>
</head>
<body>
    <h1>Reporte de Productos Más Vendidos</h1>

    <h5>Fecha de Generación: {{ \Carbon\Carbon::now()->format('d-m-Y H:i:s') }}</h5>

    @if(isset($startDate) && isset($endDate))
        <h5>Rango de Fechas: {{ \Carbon\Carbon::parse($startDate)->format('d-m-Y') }} - {{ \Carbon\Carbon::parse($endDate)->format('d-m-Y') }}</h5>
    @endif

    <h2>Resultados del Reporte</h2>

    <table>
        <thead>
            <tr>
                <th>Producto</th>
                <th>Cantidad Vendida</th>
                <th>Precio Unitario (Bs)</th>  <!-- Columna para Precio Unitario -->
                <th>Total Recaudado (Bs)</th>
            </tr>
        </thead>
        <tbody>
            @foreach($salesData as $product)
                <tr>
                    <td>{{ $product->product->name }}</td>
                    <td>{{ $product->quantity }}</td>
                    <td>{{ number_format($product->unit_price, 2) }} Bs</td>  <!-- Mostrar Precio Unitario -->
                    <td>{{ number_format($product->total, 2) }} Bs</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        <p>Total Acumulado: {{ number_format($salesData->sum('total'), 2) }} Bs</p>
    </div>

</body>
</html>
