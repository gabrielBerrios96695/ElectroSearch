<!DOCTYPE html>
<html>
<head>
    <title>Nota de Venta</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }
        .container {
            width: 80%;
            margin: auto;
        }
        .header, .footer {
            text-align: center;
        }
        .footer {
            margin-top: 50px;
            font-size: 12px;
        }
        .details {
            margin-top: 20px;
        }
        .details table {
            width: 100%;
            border-collapse: collapse;
        }
        .details table, .details th, .details td {
            border: 1px solid black;
        }
        .details th, .details td {
            padding: 10px;
            text-align: left;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Nota de Venta</h1>
            <p>Fecha: {{ \Carbon\Carbon::now()->format('d/m/Y') }}</p>
        </div>

        <div class="details">
            <h3>Detalles de la Venta</h3>
            <p><strong>Cliente:</strong> {{ $sale->customer->name }}</p>
            <p><strong>Vendedor:</strong> {{ $sale->user->name }}</p>

            <table>
                <thead>
                    <tr>
                        <th>Producto</th>
                        <th>Cantidad</th>
                        <th>Precio</th>
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($sale->details as $detail)
                        <tr>
                            <td>{{ $detail->product->name }}</td>
                            <td>{{ $detail->quantity }}</td>
                            <td>{{ $detail->price }} Bs</td>
                            <td>{{ $detail->total }} Bs</td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="3" style="text-align: right;"><strong>Total:</strong></td>
                        <td>{{ $sale->total_amount }} Bs</td>
                    </tr>
                </tfoot>
            </table>
        </div>

        <div class="footer">
            <p>Gracias por su compra.</p>
        </div>
    </div>
</body>
</html>
