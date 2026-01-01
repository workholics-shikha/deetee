<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title> Product list-Route Card </title>
    <style>
        .pdf-container {
            max-width: 800px;
            margin: auto;
            padding: 20px;
            border: 1px solid #ccc;
            background-color: #fff;
        }

        .pdf-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 2px solid #000;
            border: 1px solid black;
            padding: 15px;
        }

        .pdf-header .logo {
            font-size: 24px;
            font-weight: bold;
            display: flex;
            align-items: center;
        }

        .pdf-header .unit {
            font-size: 16px;
            text-align: right;
        }

        .details {
            display: flex;
            justify-content: space-between;
            margin-bottom: 20px;
        }

        .details .info {
            flex: 1;
        }

        .details .qr {
            flex: 1;
            text-align: right;
        }

        .qr img {
            height: 80px;
            width: 80px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        table th,
        table td {
            border: 1px solid #000;
            text-align: left;
            padding: 8px;
        }

        table th {
            background-color: #f5f5f5;
        }

        .footer {
            text-align: center;
            margin-top: 20px;
            font-size: 12px;
            color: #555;
        }
    </style>
</head>

<body>
    <div class="pdf-container page">

        @foreach($data->chunk(3) as $chunk)
        @php $rowCount = 0; @endphp

        <table width="100%" cellspacing="0" cellpadding="8" style="border-collapse: collapse;">
            <tbody>

                {{-- HEADER --}}
                <tr>
                    <td colspan="6" style="border:1px solid #000;">
                        <div style="display:flex; align-items:center;">
                            <img src="{{ asset('assets/images/pdfLogo.png') }}" height="30">
                            <strong style="margin-left:10px;">DeeTee Industries Pvt. Ltd.</strong>
                        </div>

                    </td>
                </tr>

                <tr>
                    <td colspan="2" style="border:1px solid #000; border-right:none; vertical-align:top;">
                        <p>SO No: <strong>{{ $saleOrder->so_no }}</strong></p>
                    </td>

                    <td style="border:none; border-right:none; vertical-align:top;"></td>
                    <td style="border:none; border-right:none; vertical-align:top;"></td>
                    <td style="border:none; border-right:none; vertical-align:top;">
                        <strong> SO QR:</strong>
                    </td>
                    <td style="border:1px solid #000; border-left:none; text-align:center;">
                        <img src="{{ $saleOrder->so_qr_code }}" width="80">
                    </td>

                </tr>

                @php
                $parts = explode('-', $saleOrder->so_no);
                $group = implode('-', array_slice($parts, 4));
                $sizeVals = getSizeValue($group);
                @endphp

                <tr>
                    <th colspan="6" style="border:1px solid #000; text-align:left;">
                        Product details
                    </th>
                </tr>
                @foreach ($chunk as $details)

                <tr>
                    <td colspan="2" style="border:1px solid #000; border-right:none; vertical-align:top;">
                        <p>Product Name:</p>
                    </td>

                    <td style="border:1px solid #000; border-left:none; text-align:center;">
                        <strong> {{ $details->item_name }} </strong>
                    </td>

                    <td colspan="2" style="border:1px solid #000; border-right:none; vertical-align:top;">
                        <strong> Product QR:</strong>
                    </td>

                    <td style="border:1px solid #000; border-left:none; text-align:center;">
                        <img src="{{$details->so_product_qr_code}}" width="80">
                    </td>
                </tr>

                <tr class="">
                    <td class="" style="border:1px solid #000; border-right:none; vertical-align:top;">
                        <p class="" style="margin-bottom: 0;">{{ $sizeVals[0] }} </p>
                        <h6 class="" style="font-weight: 600;"> {{ !empty($details->size1) ?
                            $details->size1 : '-' }} </h6>
                    </td>
                    <td class="" style="border: none; text-align: center;">
                        <p class="" style="margin-bottom: 0;">{{ $sizeVals[1] }} </p>
                        <h6 class="" style="font-weight: 600;"> {{ !empty($details->size1) ?
                            $details->size1 : '-' }} </h6>
                    </td>
                    <td class="" style="border: none; text-align: right;">
                        <p class="" style="margin-bottom: 0;">{{ $sizeVals[2] }} </p>
                        <h6 class="" style="font-weight: 600;"> {{ !empty($details->size1) ?
                            $details->size1 : '-' }} </h6>
                    </td>

                    <td class="" style="border: none; vertical-align: top;">
                        <p class="" style="margin-bottom: 0;">Quantity </p>
                        <h6 class="" style="font-weight: 600;"> {{ !empty($details->soquantity) ?
                            $details->soquantity : '-' }} </h6>
                    </td>
                    <td class="" style="border: none; text-align: center;">
                        <p class="" style="margin-bottom: 0;">Hardness </p>
                        <h6 class="" style="font-weight: 600;"> {{ !empty($details->hardness) ?
                            $details->hardness : '-' }} </h6>
                    </td>
                    <td class="" style="border:1px solid #000; border-left:none; text-align:center;">
                        <p class="" style="margin-bottom: 0;">Material </p>
                        <h6 class="" style="font-weight: 600;"> {{ !empty($details->material) ?
                            $details->material : '-' }} </h6>
                    </td>

                </tr>
                @endforeach

            </tbody>
        </table>

        @if(!$loop->last)
        
        <div style="page-break-after: always;"></div>
        @endif

        @endforeach

        <!-- Footer -->
        <div class="footer d-flex flex-column align-items-center flex-wrap align-content-stretch">
            Generated Document
        </div>

    </div>
    <script type="text/php">
        if (isset($pdf)) {
        $pdf->page_script('
            $font = $fontMetrics->get_font("Helvetica", "normal");
            $pdf->text(270, 820, "Page " . $PAGE_NUM . " of " . $PAGE_COUNT, $font, 10);
        ');
    }
</script>
</body>

</html>