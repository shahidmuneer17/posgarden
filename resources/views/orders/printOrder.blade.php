<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Receipt</title>
    <style>
        body {
            font-family: 'Monospace', sans-serif;
            max-width: 300px; /* Adjusted for thermal printer width */
            margin: 0;
            font-size: 12px; /* Smaller font size for thermal printer */
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        table, th, td {
            border: none; /* Removed borders for simplicity */
        }
        th, td {
            padding: 2px; /* Reduced padding */
            text-align: left;
        }
        .total-row > td {
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div>
        <h3>Grocery Garden</h3> <!-- Smaller heading for space efficiency -->
        <p><strong>Tel: 0346-0323336</strong></p>
        <p>--------------------</p> <!-- Shorter line for narrow paper -->
        <table>
            <tr>
                <td>Product</td>
                <td>Qty</td>
                <td>Price</td>
            </tr>
            @foreach($order->items as $item)
                <tr>
                    <td>{{ $item->product->name }}</td>
                    <td>{{ $item->quantity }}</td>
                    <td>Rs. {{ $item->price }}</td>
                </tr>
            @endforeach
            <tr class="total-row">
                <td colspan="2">Total</td>
                @php
                $total = 0;
                foreach($order->items as $item) {
                    $total += $item->price * $item->quantity; // Fixed calculation to multiply by quantity
                }
                @endphp
                <td>Rs. {{ $total }}</td>
            </tr>
        </table>
        <p>--------------------</p> <!-- Shorter line for narrow paper -->
        <p>Thank you for shopping with us</p>
    </div>
</body>
</html>