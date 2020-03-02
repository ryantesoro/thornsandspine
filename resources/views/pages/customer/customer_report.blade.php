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
            <h1 class="text-center pt-4">Customer Report</h1>
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
        </div>
        <br>
        <br>
        <table class="table table-bordered table-sm">
            <thead>
                <tr>
                    <th scope="col">Last Name</th>
                    <th scope="col">First Name</th>
                    <th scope="col">Email Address</th>
                    <th scope="col">Address</th>
                    <th scope="col">City</th>
                    <th scope="col">Province</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($data['customers'] as $customer)
                <tr>
                    <td>{{ucwords($customer->last_name)}}</td>
                    <td>{{ucwords($customer->first_name)}}</td>
                    <td>{{$customer->email}}</td>
                    <td>{{ucwords($customer->address)}}</td>
                    <td>{{ucwords($customer->city)}}</td>
                    <td>{{ucwords($customer->province)}}</td>
                </tr>
                @endforeach
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