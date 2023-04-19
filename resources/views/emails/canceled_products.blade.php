<h1>Order Cancellation Confirmation</h1>
<p>Dear {{ $customer->name }},</p>
<p>Your order has been cancelled successfully. The following items will not be shipped:</p>
<table>
    <thead>
        <tr>
            <th>Product Name</th>
            <th>Quantity</th>
            <th>Unit Price</th>
            <th>Total Price</th>
        </tr>
    </thead>
    <tbody>
        @foreach($items as $item)
            <tr>
                <td>{{ $item->product->name }}</td>
                <td>{{ $item->quantity }}</td>
                <td>{{ $item->unit_price }}</td>
                <td>{{ $item->quantity * $item->unit_price }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
<p>Thank you for your understanding.</p>
