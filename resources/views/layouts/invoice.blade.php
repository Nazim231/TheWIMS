<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Invoice</title>
    <style>
        * {
            margin: 0;
            padding: 0;
        }

        body {
            padding: 32px;
        }
    </style>
</head>

<body>
    <header style="position: relative;">
        <div>
            <h1 style="color: #6b45f7; text-transform: uppercase;">Invoice</h1>
            <p style="color: #9e9e9e;">Invoice ID: <span
                    style="font-weight: 700; color: black;">{{ '#' . $invoice['id'] }}</span></p>
        </div>
        <div style="position: fixed; right: 32px; top: 32px; text-align: right;">
            <p style="color: #9e9e9e;">Billing Date:
                <span style="font-weight: 700; color: black;">
                    {{ date('d M, Y', strtotime($invoice['created_at'])) }}
                </span>
            </p>
            <p style="color: #9e9e9e;">Printed On:
                <span style="font-weight: 700; color: black;">{{ $invoice['current_date'] }}</span>
            </p>
        </div>
    </header>
    <table style="width: 100%; margin-top: 16px; margin-bottom: 16px;">
        <tr>
            <td style="border-right: 1px solid #b4b4b4; width: 50% !important; padding: 16px 0;">
                <p style="font-weight: 700; margin-bottom: 8px;">FROM:</p>
                <p style="color: #3d3d3d; margin-bottom: 4px;">{{ $invoice['shop_name'] }}</p>
                <p style="color: #3d3d3d;">{{ $invoice['shop_address'] }}</p>
            </td>
            <td style="padding-left: 32px; padding-top: 16px; padding-bottom: 16px;">
                <p style="font-weight: 700; margin-bottom: 8px;">TO:</p>
                <p style="color: #3d3d3d; margin-bottom: 4px;">{{ $invoice['customer_name'] }}</p>
                <p style="color: #3d3d3d;">{{ $invoice['customer_mobile'] }}</p>
            </td>
        </tr>
    </table>
    <table style="width: 100%; margin-top: 32px;">
        <thead style="background: #c0c0c0;">
            <th style="padding: 0.5rem;">#</th>
            <th style="width: 50% !important; padding: 0.35rem;">Item</th>
            <th style="padding: 0.35rem;">Qty.</th>
            <th style="padding: 0.35rem;">Price</th>
            <th style="padding: 0.35rem; width: 15% !important;">Total Price</th>
        </thead>
        <tbody style="text-align: center; color: rgb(100, 100, 100);">
            @foreach ($invoice['products'] as $product)
                <tr>
                    <td style="padding: 0.35rem;">{{ $loop->iteration }}</td>
                    <td style="padding: 0.35rem;">{{ $product['product_name'] }}</td>
                    <td style="padding: 0.35rem;">{{ $product['quantity'] }}</td>
                    <td style="padding: 0.35rem;">{{ 'Rs. ' . $product['price'] }}</td>
                    <td style="padding: 0.35rem; text-align: right;">{{ 'Rs. ' . $product['total_price'] }}</td>
                </tr>
            @endforeach
            <tr>
                <td colspan="5" style="text-align: right; padding-top: 0.5rem;">Total Amount: <span style="font-weight: 700;">{{ $invoice['total_amount'] }}</span></td>
            </tr>
        </tbody>
    </table>
</body>

</html>
