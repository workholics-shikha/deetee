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
                <img src="{{asset('assets/images/pdfLogo.png')}}" alt="" class="me-2">
                DeeTee Industries Pvt. Ltd.
            </div>
        </div>


        <!-- Table Section -->
        <table>
            <thead>
                <tr>
                    <th colspan="4">Generic Code</th>
                </tr>
            </thead>
            <tbody id="myTableBody">
                @php $i = 0; @endphp
                @foreach($data as $operation)
                @if($i % 4 == 0)
                <tr>
                    @endif
                    <td style="text-align:center;">
                        <img width="130" height="130"
                            src="{{ asset('storage/generic-qrcodes') . '/' . $operation->qr_code }}">

                        <p class="m-2">{{ $operation->qr_use_for }}</p>
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