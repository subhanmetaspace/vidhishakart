<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>New Order Notification</title>
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
        .pending-payment {
            background-color: #fff3cd;
            border: 1px solid #ffeeba;
            padding: 15px;
            border-radius: 4px;
            margin-top: 20px;
            color: #856404;
        }
        .pending-payment h3 {
            margin-top: 0;
        }
        .pending-payment ol {
            margin: 0;
            padding-left: 20px;
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
        <h2>New Order Received</h2>

        <p><strong>Order Number:</strong> {{ $order->order_number }}</p>
        <p><strong>Customer Name:</strong> {{ $order->first_name }}</p>
        <p><strong>Phone:</strong> {{ $order->phone }}</p>
        <p><strong>Email:</strong> {{ $order->email ?? 'N/A' }}</p>
        <p><strong>Address:</strong> {{ $order->address1 }}</p>

        <h3>Product Details</h3>
        <table class="order-details">
            <thead>
                <tr>
                    <th>Product</th>
                    <th>Quantity</th>
                    <th>Subtotal</th>
                    <th>Shipping</th>
                    <th>VAT</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>{{ $product->title }}</td>
                    <td>{{ $thankData['quantity'] }}</td>
                    <td>₹{{ number_format($thankData['total_price'], 2) }}</td>
                    <td>₹{{ number_format($thankData['shipping_cost'], 2) }}</td>
                    <td>{{ $thankData['vat'] == 'yes' ? '5%' : 'No' }}</td>
                    <td>₹{{ number_format($order->total_amount, 2) }}</td>
                </tr>
            </tbody>
        </table>

        <p><strong>Payment Method:</strong> {{ ucfirst($order->payment_method) }}</p>
        <p><strong>Order Date:</strong> {{ $order->created_at->format('d M Y H:i') }}</p>
        <a href="{{ url('/order/view/'.$order->order_number) }}" 
           style="display: inline-block; background-color: #5c6ac4; color: #ffffff; padding: 10px 20px; text-decoration: none; border-radius: 4px; font-weight: bold; margin: 15px 0;">
           View Order
        </a>

        <div class="pending-payment">
            <h3>Pending Payment Instructions</h3>
            <p>This order has a pending payment. The balance will be updated when payment is received.</p>
            <ol>
                <li><strong>Deposit the Amount:</strong> Please deposit the final visible amount into the above bank account.</li>
                <li><strong>Share Payment Proof:</strong> After making the deposit, send a screenshot or scanned copy of the payment slip to <a href="mailto:info@vidhishakart.com">info@vidhishakart.com</a> for verification.</li>
                <li><strong>Order Processing:</strong> Upon confirmation of the deposit, your order will be processed accordingly.</li>
            </ol>
            <p>If you have any questions or require further assistance, feel free to contact us via email at <a href="mailto:info@vidhishakart.com">info@vidhishakart.com</a>.</p>
        </div>

        <div class="footer">
            <p>&copy; {{ date('Y') }} Vidhisha Kart. All rights reserved.</p>
        </div>
    </div>
</body>
</html>
