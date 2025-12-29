<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title> Pass Sheet </title>
    <style>
        .pdf-container {
            width: 100%;
            max-width: 800px;
            margin: auto;
            padding: 20px;
            border: 1px solid #ccc;
            background-color: #fff;

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

            .page {
                width: 210mm;
                height: 297mm;
                padding: 20mm;
                margin: 0 auto;
                background-color: #fff;
                box-sizing: border-box;
                page-break-after: always;
            }
        }
    </style>
</head>

<body>
    <div class="pdf-container page">
        <!-- Header Section -->
        <div class="pdf-header p-3">
            <div class="logo">
                <img src="{{ asset('assets/images/pdfLogo.png') }}" alt="" class="me-2"> DeeTee Industries Pvt. Ltd.
            </div>
            <div class="unit"> <h6 class=""> {{ $data->so_unitname }} </h6> </div>
        </div>
          <input type="hidden" id="searchInput" data-id="{{ $data->id }}" search-url="http://localhost/deetee/admin/route-card-preview" value="">
          
        <!-- SO No. and Product Table -->
        <table class="so-details">
            <tr class="">
                <td class="">
                    <table>
                        <tr>
                            <td class="" style="border: none; vertical-align: top;">
                                <p class="" style="margin-bottom: 0;">SO No:</p>
                                <h6 class="" style="font-weight: 600;"> {{ $saleOrder->so_no }} </h6>
                            </td>
                            <td class="" style="border: none; text-align: right;">
                                <img src="{{ $saleOrder->so_qr_code }}" alt="QR Code" style="width: 80px; height: 80px;">
                            </td>
                        </tr>
                    </table>
                </td>
                <td class="">
                    <table>
                        <tr>
                            <td class="" style="border: none; vertical-align: top;">
                                <p class="" style="margin-bottom: 0;">Product</p>
                                <h6 class="" style="font-weight: 600;"> {{ $data->item_name }} </h6>
                            </td>
                            <td class="" style="border: none; text-align: right;">
                                <img src="{{ $data->so_product_qr_code }}" alt="QR Code"
                                    style="width: 80px; height: 80px;">
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
        
        @php
            $parts = explode('-', $saleOrder->so_no);
            $group = implode('-', array_slice($parts, 4));
            $sizeVals = getSizeValue($group); 
        @endphp
 
        <table width="100%" cellspacing="0" cellpadding="6" style="border-collapse:collapse; table-layout:fixed;">
            <tr>
                    <td style="border:1px solid #000; width:16.66%; text-align:center;">
                        <p style="margin:0;">{{ $sizeVals[0] }} </p>
                        <strong style="font-size:11px;"> {{ !empty($data->size1) ? $data->size1 : '-' }} </strong>
                    </td>

                    <td style="border:1px solid #000; width:16.66%; text-align:center;">
                        <p style="margin:0;">{{ $sizeVals[1] }} </p>
                        <strong style="font-size:11px;"> {{ !empty($data->size2) ? $data->size2 : '-' }} </strong>
                    </td>

                    <td style="border:1px solid #000; width:16.99%; text-align:center;">
                        <p style="margin:0;">{{ $sizeVals[2] }} </p>
                        <strong style="font-size:11px;"> {{ !empty($data->size3) ? $data->size3 : '-' }} </strong>
                    </td>

                    <td style="border:1px solid #000; width:16.31%; text-align:center;">
                        <p style="margin:0;">Quantity </p>
                        <strong style="font-size:11px;"> {{ $data->soquantity }} </strong>
                    </td>

                    <td style="border:1px solid #000; width:16.66%; text-align:center;">
                        <p style="margin:0;">Hardness </p>
                        <strong style="font-size:11px;"> {{ $data->hardness }} </strong>
                    </td>

                    <td style="border:1px solid #000; width:16.66%; text-align:center;">
                        <p style="margin:0;">Material </p>
                        <strong style="font-size:11px;"> {{ $data->material }} </strong>
                    </td>
            </tr>
        </table>

        <!-- Table Section -->
        <table>
            <thead>
                <tr>
                    <th colspan="4">Pass Sheet </th>
                </tr>
            </thead>
            <tbody>
                <tr>

                    @php $i = 0; @endphp
                    @foreach ($pass_sheet as $datum)
                        @if ($i % 4 == 0)
                <tr>
                    @endif
                    <td style="text-align:center;">
                        <img width="130" height="130" src="{{ $datum->pass_sheet_qr_code }}">
                        <p class="m-2">{{ $datum->pass_no }}</p>
                    </td>
                    @php $i++; @endphp
                    @if ($i % 4 == 0)
                </tr>
                @endif
                @endforeach
                @if ($i % 4 != 0)
                    </tr>
                @endif

                </tr>
            </tbody>
        </table>

        <!-- Footer -->
        <div class="footer">
            Generated Document
        </div>
    </div>
</body>

</html>
