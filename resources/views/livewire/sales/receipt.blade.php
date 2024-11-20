<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Recibo de Venta</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            color: #333;
            margin: 20px;
            position: relative;
            background: url('/storage/images/fondoRecibo.jpg') no-repeat center center fixed;
            background-size: cover;
        }

        .watermark {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: url('/storage/images/fondoReporte.png') no-repeat center;
            background-size: contain;
        
            z-index: -1;
        }

        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #ddd;
            padding-bottom: 10px;
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
            margin-top: 20px;
            font-size: 14px;
            line-height: 1.5;
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
            padding: 10px;
            text-align: left;
            border: 1px solid #ddd;
        }

        .table tbody td {
            padding: 10px;
            border: 1px solid #ddd;
            text-align: left;
        }

        .total {
            text-align: right;
            margin-top: 20px;
            font-size: 16px;
            font-weight: bold;
            color: #0073e6;
        }
    </style>
</head>
<body>
    <!-- Marca de agua -->
    <div class="watermark"></div>

    <div class="header">
        <h1>Recibo de Venta #{{ $sale->id }}</h1>
        <p>Fecha: {{ $sale->created_at->format('d/m/Y H:i') }}</p>
    </div>

    <div class="details">
        <p><strong>Vendedor:</strong> 
            {{ $sale->user ? $sale->user->name . ' ' . $sale->user->last_name . ' ' . ($sale->user->second_last_name ?? '') : 'Desconocido' }}
        </p>
        <p><strong>Cliente:</strong> 
            {{ $sale->customer ? $sale->customer->name . ' ' . $sale->customer->last_name . ' ' . ($sale->customer->second_last_name ?? '') : 'Desconocido' }}
        </p>
    </div>

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
    </table>

    <div class="total">
        <p>Total de la Venta: {{ number_format($sale->total_amount, 2) }} Bs</p>
    </div>
</body>
</html>
