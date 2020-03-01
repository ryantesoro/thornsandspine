<!DOCTYPE html>
<html>

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <link rel="stylesheet" href="{{ URL::asset('vendor/bootstrap/css/bootstrap.min.css') }}">
</head>

<body>
    <div class="container-fluid">
        <h1>Thorns and Spines</h1>
        <h2>Product Sales Report</h2>
        <br>
        <h4>{!! $data['date_range'] !!}<h4>
        <br>
        <table class="table table-bordered pt-4">
            <thead>
                <tr>
                    <th scope="col">Name</th>
                    <th scope="col">Total Orders</th>
                    <th scope="col">Total Sales</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($data['sales'] as $row)
                <tr>
                    <th scope="row">{{ $row['name'] }}</th>
                    <td>{{ $row['total_orders'] }}</td>
                    <td>{{ number_format($row['total_sales'], 2, '.', ',') }} PHP</td>
                </tr>
                @endforeach
                <tr>
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