<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Invoice</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f7f7f7;
            margin: 0;
            padding: 0;
        }
        .container {
            width: 80%;
            margin: 0 auto;
            background-color: #ffffff;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .header {
            text-align: center;
            border-bottom: 1px solid #eaeaea;
            padding-bottom: 20px;
            margin-bottom: 20px;
        }
        .header h1 {
            margin: 0;
        }
        .order-details {
            margin-bottom: 20px;
        }
        .order-details h2 {
            margin: 0 0 10px 0;
        }
        .order-details p {
            margin: 5px 0;
        }
        .footer {
            text-align: center;
            border-top: 1px solid #eaeaea;
            padding-top: 20px;
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Thank You for Your Order!</h1>
        </div>
        <div class="order-details">
            <h2>Order #{{ $mailData['order']->id }}</h2>
            <p><strong>Customer Name:</strong> {{ $mailData['order']->first_name }} {{ $mailData['order']->last_name }}</p>
            <p><strong>Email:</strong> {{ $mailData['order']->email }}</p>
            <p><strong>Phone:</strong> {{ $mailData['order']->mobile }}</p>
            <p><strong>Shipping Address:</strong> {{ $mailData['order']->address }}, {{ $mailData['order']->city }}, {{ $mailData['order']->state }} {{ $mailData['order']->zip }}, {{ $mailData['order']->country->name ?? '' }}</p>
        </div>
        <div class="order-items">
            <h2>Order Items</h2>
            <table width="100%" cellpadding="10" cellspacing="0" border="1" style="border-collapse: collapse;">
                <thead>
                    <tr>
                        <th>Product</th>
                        <th>Price</th>
                        <th>Qty</th>
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($mailData['order']->orderItems as $item)
                        <tr>
                            <td>{{ $item->name }}</td>
                            <td>${{ number_format($item->price, 2) }}</td>
                            <td>{{ $item->qty }}</td>
                            <td>${{ number_format($item->price * $item->qty, 2) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="order-summary">
            <h2>Order Summary</h2>
            <p><strong>Subtotal:</strong> ${{ number_format($mailData['order']->subtotal, 2) }}</p>
            <p><strong>Discount:</strong> ${{ number_format($mailData['order']->discount, 2) }}</p>
            <p><strong>Shipping:</strong> ${{ number_format($mailData['order']->shipping, 2) }}</p>
            <p><strong>Grand Total:</strong> ${{ number_format($mailData['order']->grand_total, 2) }}</p>
        </div>
        <div class="footer">
            <p>If you have any questions about your order, feel free to contact us at {{ config('app.contact_email') }}.</p>
            <p>Thank you for shopping with us!</p>
        </div>
    </div>
</body>
</html>
