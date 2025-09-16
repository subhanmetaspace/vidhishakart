<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Confirmation</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f6f6f6;
            margin: 0;
            padding: 0;
        }
        .email-container {
            max-width: 600px;
            margin: 30px auto;
            background-color: #ffffff;
            padding: 20px;
            border-radius: 6px;
        }
        h2 {
            color: #333333;
        }
        .order-details {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        .order-details th,
        .order-details td {
            border: 1px solid #dddddd;
            padding: 12px;
            text-align: left;
        }
        .order-details th {
            background-color: #f4f4f4;
        }
        .total {
            font-weight: bold;
        }
        .btn {
            display: inline-block;
            background-color: #5c6ac4;
            color: #ffffff;
            padding: 10px 20px;
            margin-top: 20px;
            text-decoration: none;
            border-radius: 4px;
        }
        .footer {
            text-align: center;
            font-size: 12px;
            color: #777777;
            margin-top: 30px;
        }
    </style>
</head>
<body>
    <div class="email-container">
        <h2>Thank you for your order, {{ "User" }}!</h2>
        <p>We're happy to let you know that we've received your order <strong>#{{ "2134" }}</strong>.</p>
        @php // $data = (session('order') !== null)? session('order') : []; @endphp
        <table class="order-details">
            <thead>
                <tr>
                    <th>Product</th>
                    <th>Quantity</th>
                    <th>Price</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
               {{-- @foreach($order->items as $item)
                <tr>
                    <td>{{ $item->name }}</td>
                    <td>{{ $item->quantity }}</td>
                    <td>₹{{ number_format($item->price, 2) }}</td>
                    <td>₹{{ number_format($item->price * $item->quantity, 2) }}</td>
                </tr>
                @endforeach --}}
             @php //print_r($data) @endphp
            </tbody>
        </table>

        {{--<p class="total">Subtotal: ₹{{ number_format($order->subtotal, 2) }}</p>
        @if($order->shipping_cost)
        <p class="total">Shipping: ₹{{ number_format($order->shipping_cost, 2) }}</p>
        @endif
        <p class="total">Total: ₹{{ number_format($order->total, 2) }}</p>--}}

        <a href="{{ route('home') }}" class="btn">Continue Shopping</a>

        <div class="footer">
            <p>If you have any questions, please contact our support.</p>
            <p>&copy; {{ date('Y') }} Vidhisha Kart. All rights reserved.</p>
        </div>
    </div>
</body>
</html>
