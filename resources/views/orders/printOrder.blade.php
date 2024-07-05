<!DOCTYPE html>
<html>
<head>
    <title>Order #{{ $order->id }}</title>
    <style>
        body {
            font-family: 'Courier New', Courier, monospace;
            font-size: 12px;
            width: 80mm;
        }
        .header, .footer {
            text-align: center;
        }
        .order-details {
            width: 100%;
            border-top: 1px solid #000;
            border-bottom: 1px solid #000;
            margin-bottom: 10px;
        }
        .order-details th, .order-details td {
            text-align: left;
            padding: 5px;
        }
        .order-details th {
            border-bottom: 1px solid #000;
        }
        .total {
            border-top: 1px solid #000;
            text-align: right;
            padding-top: 5px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Grocery Garden</h1>
        <p>Tel: 0346-0323336</p>
        <p>Order Number: {{ $order->id }}</p>
    </div>
    <table class="order-details">
        <thead>
            <tr>
                <th>Product</th>
                <th>Qty</th>
                <th>Price</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($order->items as $item)
                <tr>
                    <td>{{ $item->product->name }}</td>
                    <td>{{ $item->quantity }}</td>
                    <td>{{ number_format($item->price, 2) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
    <div class="total">
        <p>Total: Rs. {{ number_format($order->total, 2) }}</p>
    </div>
    <div class="footer">
        <p>Thank you for shopping with us</p>
    </div>
</body>
</html>
