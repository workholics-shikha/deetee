<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title> Product list-Route Card </title>
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
                <img src="{{asset('assets/images/pdfLogo.png')}}" alt="" class="me-2"> DeeTee Industries Pvt. Ltd.
            </div>
            @if(isset($data->so_unitname) && $data->so_unitname != '')
            <div class="unit">
                <h6 class=""> {{ $data->so_unitname }} </h6>
            </div>
            @endif
        </div>

        <!-- SO No. and Product Table -->
        <table class="so-details">
            <tr class="">
                <td class="">
                    <table>
                        <tr>
                            <td class="" style="border: none; vertical-align: top;">
                                <p class="" style="margin-bottom: 0;">SO No:</p>
                            </td>
                            <td class="" style="border: none; text-align: right;">
                                <h6 class="" style="font-weight: 600;"> {{ $saleOrder->so_no }} </h6>
                            </td>
                        </tr>
                    </table>
                </td>
                <td class="">
                    <table>
                        <tr>
                            <td class="" style="border: none; vertical-align: top;">
                                <p class="mt-4" style="margin-bottom: 0;">SO No:</p>
                            </td>
                            <td class="" style="border: none; text-align: right;">
                                <img src="{{$saleOrder->so_qr_code}}" alt="QR Code" style="width: 80px; height: 80px;">
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>

        </table>

        <!-- Table Section -->
        <table>
            <thead>
                <tr>
                    <th colspan="4">Product Details</th>
                </tr>
            </thead>

            <tbody id="myTableBody">

                <table class="so-details">

                    @php
                        $parts = explode('-', $saleOrder->so_no);
                        $group = implode('-', array_slice($parts, 4));
                        $sizeVals = getSizeValue($group);
                    @endphp

                    @if(!empty($data))
                    @foreach ($data as $details)

                    <tr class="">
                        <td class="">
                            <table>
                                <tr>
                                    <td class="" style="border: none; vertical-align: top;">
                                        <p class="" style="margin-bottom: 0;">Product Name:</p>
                                    </td>
                                    <td class="" style="border: none; text-align: right;">
                                        <h6 class="" style="font-weight: 600;"> {{ $details->item_name }}  </h6>
                                    </td>
                                </tr>
                            </table>
                        </td>
                        <td class="">
                            <table>
                                <tr>
                                    <td class="" style="border: none; vertical-align: top;">
                                        <p class="mt-4" style="margin-bottom: 0;">Product QR:</p>
                                    </td>
                                    <td class="" style="border: none; text-align: right;">
                                        <img src="{{$details->so_product_qr_code}}" alt="QR Code" style="width: 80px; height: 80px;">
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                     
                    <tr class="">
                        <td class="">
                            <table>
                                <tr>
                                    <td class="" style="border: none; vertical-align: top;">
                                        <p class="" style="margin-bottom: 0;">{{ $sizeVals[0] }}:</p>
                                        <h6 class="" style="font-weight: 600;"> {{ !empty($details->size1) ?
                                            $details->size1 : '-' }} </h6>
                                    </td>
                                    <td class="" style="border: none; text-align: center;">
                                        <p class="" style="margin-bottom: 0;">{{ $sizeVals[1] }}:</p>
                                        <h6 class="" style="font-weight: 600;"> {{ !empty($details->size1) ?
                                            $details->size1 : '-' }} </h6>
                                    </td>
                                    <td class="" style="border: none; text-align: right;">
                                        <p class="" style="margin-bottom: 0;">{{ $sizeVals[2] }}:</p>
                                        <h6 class="" style="font-weight: 600;"> {{ !empty($details->size1) ?
                                            $details->size1 : '-' }} </h6>
                                    </td>
                                </tr>
                            </table>
                        </td>
                        <td class="">
                            <table>
                                <tr>
                                    <td class="" style="border: none; vertical-align: top;">
                                        <p class="" style="margin-bottom: 0;">Quantity:</p>
                                        <h6 class="" style="font-weight: 600;"> {{ !empty($details->soquantity) ?
                                            $details->soquantity : '-' }} </h6>
                                    </td>
                                    <td class="" style="border: none; text-align: center;">
                                        <p class="" style="margin-bottom: 0;">Hardness:</p>
                                        <h6 class="" style="font-weight: 600;"> {{ !empty($details->hardness) ?
                                            $details->hardness : '-' }} </h6>
                                    </td>
                                    <td class="" style="border: none; text-align: right;">
                                        <p class="" style="margin-bottom: 0;">Material:</p>
                                        <h6 class="" style="font-weight: 600;"> {{ !empty($details->material) ?
                                            $details->material : '-' }} </h6>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                    @endforeach
                    @endif

                </table>

                <div style="page-break-after: always;" style="height:100px"></div>

            </tbody>
        </table>

        <!-- Footer -->
        <div class="footer d-flex flex-column align-items-center flex-wrap align-content-stretch">
            Generated Document
        </div>
    </div>
</body>

</html>