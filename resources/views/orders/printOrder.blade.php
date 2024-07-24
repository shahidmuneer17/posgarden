<!DOCTYPE html>
<html>
<head>
    <title>Order #{{ $order->id }}</title>
    <style>
        body {
            font-family: Verdana, Geneva, sans-serif;
            font-size: 12px; /* Adjust font size for readability */
            margin: 0;
            font-weight: 600;
            padding: 0;
            max-width: 270px; /* Set the width of the document */
        }
        .header, .footer {
            text-align: center;
            margin-bottom: 5px;
        }
        .order-details {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 5px;
        }
        .order-details th, .order-details td {
            border: 0px solid #000;
            padding: 2px;
        }
        .order-details th {
            text-align: left;
            background-color: #f2f2f2;
        }
        .total {
            text-align: right;
            margin-top: 5px;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="header">
        <h2>Grocery Garden</h2>
        <p>Tel: 0346-0323336</p>
        <p>Order Number: {{ $order->id }}</p>
    </div>
    <table class="order-details">
        <thead>
            <tr>
                <th style="width: 60%;">Product</th> <!-- Adjust width for first column -->
                <th style="width: 20%;">Qty</th>     <!-- Adjust width for second column -->
                <th style="width: 20%;">Price</th>   <!-- Adjust width for third column -->
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
        <p>Total: {{ number_format($order->total, 2) }}</p>
    </div>
    @if($order->discount > 0)
    <div class="total">
        <p>Discount: {{ number_format($order->discount, 2) }}</p>
    </div>
    @php
    $net_total = $order->total - $order->discount;
    @endphp
    <div class="total">
        <p>Net Total: {{ number_format($net_total, 2) }}</p>
    </div>
    @endif
    <div class="total">
        <p>Payment: {{ number_format($orderPayment->amount, 2) }}</p>
    </div>
    @php
    $net1_total = $order->total - $order->discount;
    $balance = $net1_total - $orderPayment->amount;
    @endphp
    @if($balance > 0)
    <div class="total">
        <p>Balance: {{ number_format($net1_total - $orderPayment->amount, 2) }}</p>
    </div>
    @endif

    <div class="footer">
        <p>Thank you for shopping with us</p>
    </div>
</body>
</html>
