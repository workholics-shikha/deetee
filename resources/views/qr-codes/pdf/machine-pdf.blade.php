<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title> Machine QR Codes </title>
    <style>
        .pdf-container {

            /* width: 100%; */
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
        <table width="100%" cellspacing="0" cellpadding="5" style="border-collapse: collapse;">
            <tbody id="myTableBody">

                <!-- body -->
                @php
                $operations = $data->operations ?? collect();
                $chunks = $operations->chunk(4);
                $rowCount = 0;
                @endphp

                @foreach ($chunks as $chunk)
                @if ($rowCount % 3 == 0)
                @if ($rowCount > 0)
            </tbody>
        </table>
        <div style="page-break-after: always;"></div>
        <table width="100%" cellspacing="0" cellpadding="5" style="border-collapse: collapse;">
            <tbody id="myTableBody">
                @endif

                <tr>
                    <td colspan="4">
                        <div class="pdf-header p-3">
                            <div class="logo">
                                <img src="{{ asset('assets/images/pdfLogo.png') }}" alt="Logo" class="me-2">
                                DeeTee Industries Pvt. Ltd.
                            </div>

                        </div>
                    </td>
                </tr>

                <tr>
                    <td style="border-right: none; vertical-align: top;">
                        <p style="margin-bottom: 0;">Machine Name:</p>
                        <h6 style="font-weight: 600;">{{ $data->machine ?? '-' }}</h6>
                    </td>
                    <td style="border-left: none; text-align: right;">
                        <img src="{{ $data->machine_qr_code ?? asset(DEFAULT_QR) }}" alt="QR Code"
                            style="width: 80px; height: 80px;">
                    </td>
                    <td style="border-right: none; vertical-align: top;">
                        <p style="margin-bottom: 0;">Unit name:</p>
                        <h6 style="font-weight: 600;">{{ $data->unit_name ?? '-' }}</h6>
                    </td>
                    <td style="border-left: none; text-align: right;">
                        {{-- <img src="{{ $data->so_product_qr_code ?? asset(DEFAULT_QR) }}" alt="QR Code"
                            style="width: 80px; height: 80px;"> --}}
                    </td>
                </tr>

                <tr>
                    <th colspan="4">Operations</th>
                </tr>
                @endif

                <tr>
                    @foreach ($chunk as $operation)
                    <td style="border: 1px solid #000; text-align: center;">
                        <img width="130" height="130" src="{{ $operation->operation_qr_code }}" alt="QR">
                        <p class="m-2">{{ $operation->operation_name }}</p>
                    </td>
                    @endforeach

                    @for ($i = $chunk->count(); $i < 4; $i++) <td style="border: 1px solid #000;">
                        </td>
                        @endfor
                </tr>

                @php $rowCount++; @endphp
                @endforeach

                <!-- body -->

            </tbody>
        </table>

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