<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title> Route Card </title>
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
    
        <!-- Table Section -->
         <input type="hidden" id="searchInput" data-id="{{ $data->id }}" >
            
        <table width="100%" cellspacing="0" cellpadding="5" style="border-collapse: collapse;">
            <tbody id="myTableBody">
                   @include('sales-order.snippets.operations')
            </tbody>
        </table>
         
        <!-- Footer --> 
        <div class="footer d-flex flex-column align-items-center flex-wrap align-content-stretch" >
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