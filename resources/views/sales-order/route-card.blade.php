<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title> Route Card </title>
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
                                <h6 class="" style="font-weight: 600;"> {{ $saleOrder->so_no }} </h6>
                            </td>
                            <td class="" style="border: none; text-align: right;">
                                <img src="{{$saleOrder->so_qr_code}}" alt="QR Code" style="width: 80px; height: 80px;">
                            </td>
                        </tr>
                    </table>
                </td>
                <td class="">
                    <table>
                        <tr>
                            <td class="" style="border: none; vertical-align: top;">
                                <p class="" style="margin-bottom: 0;">Product</p>
                                <h6 class="" style="font-weight: 600;"> {{ $data->item_name }} </h6><br>
                                <span class="text-445B64" style="font-size: 12px"> @if(!is_null($data->sub_product_id))
                                    {{ getSubProductname($data->sub_product_id) }} @endif</span>
                            </td>
                            <td class="" style="border: none; text-align: right;">
                                <img src="{{ $data->so_product_qr_code}}" alt="QR Code"
                                    style="width: 80px; height: 80px;">
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
            @php
            $parts = explode('-', $saleOrder->so_no);
            $group = implode('-', array_slice($parts, 4));
            $sizeVals = getSizeValue($group);
            @endphp

            <tr class="">
                <td class="">
                    <table>
                        <tr>
                            <td class="" style="border: none; vertical-align: top;">
                                <p class="" style="margin-bottom: 0;"> {{ $sizeVals[0] }}:</p>
                                <h6 class="" style="font-weight: 600;"> {{ !empty($data->size1) ? $data->size1 : '-' }}
                                </h6>
                            </td>
                            <td class="" style="border: none; text-align: center;">
                                <p class="" style="margin-bottom: 0;">{{ $sizeVals[1] }}:</p>
                                <h6 class="" style="font-weight: 600;"> {{ !empty($data->size2) ? $data->size2 : '-' }}
                                </h6>
                            </td>
                            <td class="" style="border: none; text-align: right;">
                                <p class="" style="margin-bottom: 0;">{{ $sizeVals[2] }}:</p>
                                <h6 class="" style="font-weight: 600;"> {{ !empty($data->size3) ? $data->size3 : '-' }}
                                </h6>
                            </td>
                        </tr>
                    </table>
                </td>
                <td class="">
                    <table>
                        <tr>
                            <td class="" style="border: none; vertical-align: top;">
                                <p class="" style="margin-bottom: 0;">Quantity:</p>
                                <h6 class="" style="font-weight: 600;"> {{ $data->soquantity }} </h6>
                            </td>
                            <td class="" style="border: none; text-align: center;">
                                <p class="" style="margin-bottom: 0;">Hardness:</p>
                                <h6 class="" style="font-weight: 600;"> {{ $data->hardness }} </h6>
                            </td>
                            <td class="" style="border: none; text-align: right;">
                                <p class="" style="margin-bottom: 0;">Material:</p>
                                <h6 class="" style="font-weight: 600;"> {{ $data->material }} </h6>
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
                    <th colspan="4">Operations</th>
                </tr>
            </thead>
            <input type="hidden" id="searchInput" data-id="{{ $data->id }}"
                search-url="http://localhost/deetee/admin/route-card-preview" value="">
            <tbody id="myTableBody">
                <input type="hidden" id="searchInput" data-id="{{ $data->id }}"
                    search-url="http://localhost/deetee/admin/route-card-preview" value="">
                @php $i = 0; @endphp
                @foreach($getOperationList as $operation)
                @php $qr = $operation->operationData?->operation_qr_code; @endphp
                @if($i % 4 == 0)
                <tr>
                    @endif
                    <td style="text-align:center;">

                        @if($qr)
                        <img width="130" height="130" src="{{ asset($qr) }}" alt="QR Code">
                        @else
                        <div
                            style="height:130px;border:1px dashed #999;display:flex;align-items:center;justify-content:center;font-size:12px;">
                            QR not found
                        </div>
                        @endif
                        <p class="m-2">{{ $operation->operation_name }}</p>
                    </td>
                    @php $i++; @endphp
                    @if($i % 4 == 0)
                </tr>
                @endif
                @if($i % 12 == 0)
                <div style="page-break-after: always;" style="height:100px"></div>
                @endif
                @endforeach
                @if($i % 4 != 0)
                </tr>
                @endif
            </tbody>
        </table>

        <!-- Footer -->
        <div class="footer d-flex flex-column align-items-center flex-wrap align-content-stretch">
            Generated Document
        </div>
    </div>
</body>

</html>