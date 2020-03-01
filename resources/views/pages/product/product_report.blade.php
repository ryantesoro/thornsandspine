<!DOCTYPE html>
<html>

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <link rel="stylesheet" href="{{ URL::asset('vendor/bootstrap/css/bootstrap.min.css') }}">
    <style>
        .text-left, .text-right {
            margin-bottom: 0;
        }

        p {
            font-size: 14px;
        }

        table > thead > tr > th {
            font-size: 13px;
        }

        table > tbody > tr > td,
        table > tbody > tr > th {
            font-size: 12px;
        }
    </style>
</head>

<body>
    <div class="container-fluid">
        <div class="d-flex flex-row">
            <h1 class="text-center pt-4">Product Sales Report</h1>
            <img style="float:right" src="{{ $data['logo_url'] }}">
        </div>
        <br><br>
        <h4 class="pb-3">Thorns and Spines</h4>
        <div class="row">
            <div class="col-md-6">
                <p class="text-left">
                    {{ $data['configurations']['address'] }}
                </p>
                <p class="text-left">
                    {{ $data['configurations']['contact_number'] }}
                </p>
                <p class="text-left">
                    {{ $data['configurations']['email'] }}
                </p>
            </div>
            <div class="col-md-6">
                <p style="margin-right: 120px;" class="text-right">
                    Date:
                </p>
                <p class="text-right">
                    From: {{ $data['from'] }}
                </p>
                <p class="text-right">
                    To: {{ $data['to'] }}
                </p>
            </div>
        </div>
        <table class="table table-bordered table-sm">
            <thead>
                <tr>
                    <th scope="col">Code</th>
                    <th scope="col">Name</th>
                    <th scope="col">Price</th>
                    <th scope="col">Sold</th>
                    <th scope="col">Total Sales</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($data['sales'] as $row)
                <tr>
                    <th scope="row">#{{ $row['code'] }}</th>
                    <td>{{ $row['name'] }}</td>
                    <td>{{ number_format($row['price'], 2, '.', ',') }} PHP</td>
                    <td>{{ $row['total_orders'] }}</td>
                    <td>{{ number_format($row['total_sales'], 2, '.', ',') }} PHP</td>
                </tr>
                @endforeach
                <tr>
                    <td colspan="2" class="border-md-0"></td>
                    <th scope="row" class="text-right">Total</th>
                    <td>{{ $data['total']['orders'] }}</td>
                    <td>{{ number_format($data['total']['sales'], 2, '.', ',') }} PHP</td>
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