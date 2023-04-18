<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Your order has been cancelled</title>
</head>
<body>
    <h1>Your order has been cancelled</h1>
    <p>Dear {{ $customer->name }},</p>
    <p>We regret to inform you that your order has been cancelled. The following items will no longer be shipped:</p>
    <ul>
        @foreach ($items as $item)
            <li>{{ $item['product']->name }} x {{ $item['quantity'] }}</li>
        @endforeach
    </ul>
    <p>We apologize for any inconvenience this may have caused. If you have any questions or concerns, please feel free to contact us at support@example.com.</p>
    <p>Thank you for your understanding.</p>
</body>
</html>
