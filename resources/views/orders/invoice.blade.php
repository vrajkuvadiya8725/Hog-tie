<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Invoice #{{ $order->id }}</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 24px; }
        .row { display: flex; justify-content: space-between; margin-bottom: 10px; }
        table { width: 100%; border-collapse: collapse; margin-top: 16px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
    </style>
</head>
<body>
    <h1>Hog Tie Invoice</h1>
    <div class="row"><span>Order ID</span><strong>#{{ $order->id }}</strong></div>
    <div class="row"><span>Customer</span><strong>{{ $order->user->name }}</strong></div>
    <div class="row"><span>Payment Method</span><strong>{{ strtoupper($order->payment_method) }}</strong></div>
    <div class="row"><span>Payment Status</span><strong>{{ ucfirst($order->payment_status) }}</strong></div>
    <div class="row"><span>Address</span><strong>{{ $order->recipient_name }}, {{ $order->address_line }}, {{ $order->city }}, {{ $order->state }} - {{ $order->postal_code }}</strong></div>

    <table>
        <thead>
            <tr>
                <th>Product</th>
                <th>Qty</th>
                <th>Unit Price</th>
                <th>Line Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach($order->items as $item)
                <tr>
                    <td>{{ $item->product_name }}</td>
                    <td>{{ $item->quantity }}</td>
                    <td>Rs {{ number_format((float) $item->unit_price, 2) }}</td>
                    <td>Rs {{ number_format((float) $item->line_total, 2) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <h3 style="text-align: right; margin-top: 16px;">Grand Total: Rs {{ number_format((float) $order->total_amount, 2) }}</h3>
</body>
</html>
