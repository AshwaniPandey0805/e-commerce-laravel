<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Confirmation</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            margin: 20px;
            padding: 0;
        }
        h1 {
            color: #007BFF;
        }
        .container {
            width: 100%;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 5px;
            background-color: #f9f9f9;
        }
        .order-summary table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }
        .order-summary th, .order-summary td {
            padding: 10px;
            border: 1px solid #ddd;
            text-align: left;
        }
        .order-summary th {
            background-color: #f1f1f1;
        }
        .total {
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="container">

        @if ($mailData['type'] == 'customer')
            <h1>Order Confirmation - #{{ $mailData['order']->id }}</h1>
            <p>Dear {{ $mailData['order']->first_name }} {{ $mailData['order']->last_name }},</p>
            <p>Thank you for your order! Here are the details of your purchase:</p>    
        @endif
        @if ($mailData['type'] == 'admin')
            {{-- <h1>Order Confirmation - #{{ $mailData['order']->id }}</h1>
            <p>Dear {{ $mailData['order']->first_name }} {{ $mailData['order']->last_name }},</p> --}}
            <h1>You've received order #{{ $mailData['order']->id }}</h1>    
        @endif
        
        
        <hr>
        
        <h3>Order Details</h3>
        <p><strong>Order Number:</strong> #{{ $mailData['order']->id }}</p>
        <p><strong>Order Date:</strong> {{ \Carbon\Carbon::parse($mailData['order']->created_at)->format("d M, Y H:i:s") }}</p>
        
        <h3>Billing Information</h3>
        <p><strong>Name:</strong> {{ $mailData['order']->first_name }} {{ $mailData['order']->last_name }}</p>
        <p><strong>Address:</strong> {{ $mailData['order']->apartement }}, {{ $mailData['order']->address  }}, {{ $mailData['order']->city }}, {{ $mailData['order']->state }} </p>
        <p><strong>Email:</strong> {{ $mailData['order']->email }}</p>
        <p><strong>Phone Number:</strong> {{ $mailData['order']->mobile }}</p>
        
        {{-- <h3>Shipping Information</h3>
        <p><strong>Name:</strong> {{ $maillData['order']->first_name }} {{ $maillData['order']->last_name }} or Recipient's Name]</p>
        <p><strong>Address:</strong> [Shipping Address]</p>
        <p><strong>Expected Delivery Date:</strong> [Delivery Date]</p> --}}
        
        <hr>
        
        <h3>Order Summary</h3>
        <div class="order-summary">
            <table>
                <thead>
                    <tr>
                        <th>Item</th>
                        <th>Quantity</th>
                        <th>Unit Price</th>
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody>
                    @if (isset($mailData['order']->getOrderItems))
                        @foreach ($mailData['order']->getOrderItems as $item)
                            <tr>
                                <td>{{ $item->name }}</td>
                                <td>{{ $item->qty }}</td>
                                <td>${{ number_format($item->price, 2) }}</td>
                                <td>${{ number_format(($item->price * $item->qty), 2)  }}</td>
                            </tr>        
                        @endforeach
                    @endif
                    <tr>
                        <td colspan="3" class="total">Subtotal</td>
                        <td class="total">${{ number_format($mailData['order']->subtotal , 2) }}</td>
                    </tr>
                    <tr>
                        <td colspan="3" class="total">Shipping</td>
                        <td class="total">${{ number_format($mailData['order']->shipping , 2) }}</td>
                    </tr>
                    @if (isset($mailData['order']->coupon_code))
                        <tr>
                            <td colspan="3" class="total">Discount Amount - {{$mailData['order']->coupon_code}}</td>
                            <td class="total">${{ number_format($mailData['order']->discount , 2) }}</td>
                        </tr>    
                    @endif
                    {{-- <tr>
                        <td colspan="3" class="total">Tax</td>
                        <td class="total">[Tax Amount]</td>
                    </tr> --}}
                    <tr>
                        <td colspan="3" class="total">Total</td>
                        <td class="total">${{ number_format($mailData['order']->grand_total , 2) }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
        
        <hr>
        
        <h3>Payment Method</h3>
        {{-- <p>[Payment Method Details]</p> --}}
        <p>[Cash On Delivery]</p>
        
        {{-- <h3>Notes</h3>
        <p>[Any additional notes or instructions]</p> --}}
        
        <hr>
        
        <p>Thank you for shopping with us! If you have any questions or need further assistance, feel free to reply to this email.</p>
        
        
    </div>
</body>
</html>
