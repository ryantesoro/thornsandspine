<!DOCTYPE html>
<html>

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <link rel="stylesheet" href="{{ URL::asset('vendor/bootstrap/css/bootstrap.min.css') }}">
    <style>
        .text-left,
        .text-right {
            margin-bottom: 0;
        }

        p {
            font-size: 14px;
        }

        table>tbody>tr>td,
        table>tbody>tr>th {
            font-size: 14px;
            padding: 0;
            word-wrap: break-word;
        }

        table.table>thead>tr>th {
            font-size: 13px;
        }

        table.table>tbody>tr>td,
        table.table>tbody>tr>th {
            font-size: 12px;
        }

        div > p {
            margin: 0;
            word-wrap: break-word;
        }
    </style>
</head>

<body>
    <div class="container-fluid">
        <div class="d-flex flex-row">
            <h3>Thorns And Spines</h3>
            <img style="float:right" src="{{ $data['logo_url'] }}">
        </div>
        <br>
        <div class="row">
            <div class="col-md-6">
                <p class="text-left">
                    <b>Order Code:</b> #{{ $data['order']->code }}
                </p>
                <p class="text-left">
                    <b>Order Date: </b>{{ $data['order']->created_at->format('m-d-Y') }}
                </p>
                <p class="text-left">
                    <b>Shipping Agent: </b>{{ $data['shipping_agent']->name }}
                </p>
                @if ($data['order']->status == 2)
                <p class="text-left">
                    <b>Tracking Number: </b>{{ $data['order']->tracking_number }}
                </p>
                @endif
                <p class="text-left">
                    <b>Total Items: </b>{{ count($data['products']) }}
                </p>
                <p class="text-left">
                    <b>Order Remarks: </b>{{ $data['order']->remarks }}
                </p>
            </div>
        </div>
        <br><br>
        <div style="width: 15%; display:inline-block; vertical-align: top;">
            <p><b>Customer</b></p>
        </div>
        <div style="width: 25%; display:inline-block; vertical-align: top; margin-left: 5%;">
            <p>{{ ucwords($data['customer']['first_name']).' '.ucwords($data['customer']['last_name']) }}</p>
            <p>{{ ucwords($data['customer']['address']) }}</p>
            <p>{{ ucwords($data['customer']['city']).', '.ucwords($data['customer']['province']) }}</p>
            <p>{{ $data['customer']['contact_number'] }}</p>
        </div>
        <div style="width: 10%; display:inline-block;"></div>
        <div style="width: 15%; display:inline-block; vertical-align: top;">
            <p><b>Recipient</b></p>
        </div>
        <div style="width: 25%; display:inline-block; vertical-align: top; margin-left: 5%;">
            <p>{{ ucwords($data['recipient']['first_name']).' '.ucwords($data['recipient']['last_name']) }}</p>
            <p>{{ ucwords($data['recipient']['address']) }}</p>
            <p>{{ ucwords($data['city']).', '.ucwords($data['province']) }}</p>
            <p>{{ $data['recipient']['contact_number'] }}</p>
        </div>
        <table class="table table-bordered table-sm pt-5">
            <thead>
                <tr>
                    <th scope="col">Product Code</th>
                    <th scope="col">Product Name</th>
                    <th scope="col">Pot Type</th>
                    <th scope="col">Product Price</th>
                    <th scope="col">Quantity</th>
                    <th scope="col">Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($data['products'] as $product)
                <tr>
                    <th scope="row">#{{ $product['code'] }}</th>
                    <td>{{ ucwords($product['name']) }}</td>
                    <td>{{ ucwords($product['pot_type']) }}</td>
                    <td>{{ number_format($product['price'], 2, '.', ',') }} PHP</td>
                    <td>x{{ $product['quantity'] }}</td>
                    <td class="text-right">{{ number_format($product['sub_total'], 2, '.', ',') }} PHP</td>
                </tr>
                @endforeach
                <tr>
                    <td colspan="4"></td>
                    <th scope="row" class="text-right">Sub Total</th>
                    <td class="text-right">{{ number_format($data['total']['total_product_price'], 2, '.', ',') }} PHP</td>
                </tr>
                <tr>
                    <td colspan="4"></td>
                    <th scope="row" class="text-right">Shipping Fee</th>
                    <td class="text-right">{{ number_format($data['shipping_fee']['price'], 2, '.', ',') }} PHP</td>
                </tr>
                @if ($data['order']->loyalty_points != 0)
                <tr>
                    <td colspan="4"></td>
                    <th scope="row" class="text-right">Loyalty Points</th>
                    <td class="text-right">-{{ $data['order']->loyalty_points }}.00 PHP</td>
                </tr>
                @endif
                <tr>
                    <td colspan="4"></td>
                    <th scope="row" class="text-right">Grand Total</th>
                    <td class="text-right">{{ number_format($data['total']['grand_total'], 2, '.', ',') }} PHP</td>
                </tr>
            </tbody>
        </table>
    </div>
    <script type="text/php">
        if (isset($pdf)) {
            $text = "page {PAGE_NUM} / {PAGE_COUNT}";
            $size = 10;
            $font = $fontMetrics->getFont("Verdana");
            $width = $fontMetrics->get_text_width($text, $font, $size) / 2;
            $x = ($pdf->get_width() - $width) / 2;
            $y = $pdf->get_height() - 35;
            $pdf->page_text($x, $y, $text, $font, $size);
        }

        if (isset($pdf)) {
            $size = 10;
            $text = "Printed at: ".date('m/d/Y');
            $font = $fontMetrics->getFont("Verdana");
            $width = $fontMetrics->get_text_width($text, $font, $size) / 2;
            $x = $width-10;
            $y = $pdf->get_height() - 35;
            $pdf->page_text($x, $y, $text, $font, $size);
        }
    </script>
</body>

</html>