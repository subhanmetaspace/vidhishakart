<!DOCTYPE html>
<html>
<head>
    <title>New Order Notification</title>
</head>
<body>
    <h2>New Order Received</h2>
    
    <p><strong>Order Number:</strong> {{ $order->order_number }}</p>
    <p><strong>Customer Name:</strong> {{ $order->first_name }}</p>
    <p><strong>Phone:</strong> {{ $order->phone }}</p>
    <p><strong>Email:</strong> {{ $order->email ?? 'N/A' }}</p>
    <p><strong>Address:</strong> {{ $order->address1 }}</p>
    
    <h3>Product Details</h3>
    <p><strong>Product Name:</strong> {{ $product->title }}</p>
    <p><strong>Quantity:</strong> {{ $thankData['quantity'] }}</p>
    <p><strong>Subtotal:</strong> {{ number_format($thankData['total_price'], 2) }}</p>
    <p><strong>Shipping Cost:</strong> {{ number_format($thankData['shipping_cost'], 2) }}</p>
    <p><strong>VAT:</strong> {{ $thankData['vat'] == 'yes' ? '5%' : 'No' }}</p>
    <p><strong>Total Amount:</strong> {{ number_format($order->total_amount, 2) }}</p>
    
    <p><strong>Payment Method:</strong> {{ ucfirst($order->payment_method) }}</p>
    <p><strong>Order Date:</strong> {{ $order->created_at->format('d M Y H:i') }}</p>
    
    <p>Please process this order as soon as possible.</p>
</body>
</html>