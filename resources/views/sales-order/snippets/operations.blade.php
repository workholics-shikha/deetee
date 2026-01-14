 
@php $rowCount = 0; @endphp 

@foreach($getOperationList->chunk(4) as $key => $chunk)

    @if($rowCount % 3 == 0)

        @if($rowCount > 0)
            </table>
            <div style="page-break-after: always;"></div>
        @endif
 
        <table width="100%" cellspacing="0" cellpadding="5" style="border-collapse: collapse;">
         <tbody id="myTableBody">
            <tr>
                <td colspan="4">
                    <div class="pdf-header p-3">
                        <div class="logo">
                            <img src="{{ asset('assets/images/pdfLogo.png') }}" alt="Logo" class="me-2">
                            DeeTee Industries Pvt. Ltd.
                        </div>
                        @if(!empty($data->so_unitname))
                            <div class="unit">
                                <h6>{{ $data->so_unitname }}</h6>
                            </div>
                        @endif
                    </div>
                </td>
            </tr>

          <tr>
            <!-- SO No -->
            <td style="border:1px solid #000; border-right:none; vertical-align:top;">
                <p>SO No:</p>
                <strong>{{ $saleOrder->so_no }}</strong>
            </td>

            <!-- SO QR -->
            <td style="border:1px solid #000; border-left:none; text-align:center;">
                <img src="{{ $saleOrder->so_qr_code }}" width="80">
            </td>

            <!-- Product -->
            <td style="border:1px solid #000; border-right:none; vertical-align:top;">
                <p>Product</p>
                <strong>{{ $data->item_name }}</strong><br>
                <span style="font-size:11px;">
                    @if(!is_null($data->sub_product_id))
                        {{ getSubProductname($data->sub_product_id) }}
                    @endif
                </span>
            </td>

            <!-- Product QR -->
            <td style="border:1px solid #000; border-left:none; text-align:center;">
                <img src="{{ $data->so_product_qr_code }}" width="80">
            </td>
        </tr>

         </tbody>  
        </table>

        @php
            $parts = explode('-', $saleOrder->so_no);
            $group = implode('-', array_slice($parts, 4));
            $sizeVals = getSizeValue($group); 
        @endphp
 
        <table width="100%" cellspacing="0" cellpadding="6" style="border-collapse:collapse; table-layout:fixed; margin-top:5px;">
            <tr>
                    <td style="border:1px solid #000; width:16.66%; text-align:center;">
                        <p style="margin:0;">{{ $sizeVals[0] }} </p>
                        <strong style="font-size:11px;"> {{ !empty($data->size1) ? $data->size1 : '-' }} </strong>
                    </td>

                    <td style="border:1px solid #000; width:16.66%; text-align:center;">
                        <p style="margin:0;">{{ $sizeVals[1] }} </p>
                        <strong style="font-size:11px;"> {{ !empty($data->size2) ? $data->size2 : '-' }} </strong>
                    </td>

                    <td style="border:1px solid #000; width:16.66%; text-align:center;">
                        <p style="margin:0;">{{ $sizeVals[2] }} </p>
                        <strong style="font-size:11px;"> {{ !empty($data->size3) ? $data->size3 : '-' }} </strong>
                    </td>

                    <td style="border:1px solid #000; width:16.66%; text-align:center;">
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
 
        <table width="100%" cellspacing="0" cellpadding="6"
               style="border-collapse:collapse; table-layout:fixed; margin-top:5px;">
            <tr>
                <th colspan="4" style="border:1px solid #000; text-align:left;">
                    Operations
                </th>
            </tr>
    @endif
 
    <tr>
        @foreach($chunk as $operation)
            <td style="border:1px solid #000; text-align:center;">
                <img src="{{ optional($operation->operationData)->operation_qr_code }}" width="110" height="110"><br>
                <span style="font-size:11px;">{{ $operation->operation_name }}</span>
            </td>
        @endforeach

        @for($i = $chunk->count(); $i < 4; $i++)
            <td style="border:1px solid #000;"></td>
        @endfor
    </tr>

    @php $rowCount++; @endphp

@endforeach

</table>