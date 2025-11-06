@php $rowCount = 0; @endphp 

@foreach($pass_sheet->chunk(4) as $key => $chunk)

    @if($rowCount % 3 == 0)
        @if($rowCount > 0)
            </tbody>
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
                <td style="border-right: none; vertical-align: top;">
                    <p style="margin-bottom: 0;">SO No:</p>
                    <h6 style="font-weight: 600;">{{ $saleOrder->so_no }}</h6>
                </td>
                <td style="border-left: none; text-align: right;">
                    <img src="{{ $saleOrder->so_qr_code }}" alt="QR Code" style="width: 80px; height: 80px;">
                </td>
                <td style="border-right: none; vertical-align: top;">
                    <p style="margin-bottom: 0;">Product</p>
                    <h6 style="font-weight: 600;">{{ $data->item_name }}</h6>
                </td>
                <td style="border-left: none; text-align: right;">
                    <img src="{{ $data->so_product_qr_code }}" alt="QR Code" style="width: 80px; height: 80px;">
                </td>
            </tr>
            <tr>
                <th colspan="4">Pass Sheet</th>
            </tr>
    @endif

    <tr>
        @foreach($chunk as $datum)
            <td style="border: 1px solid #000; text-align: center;">
                    <img width="130" height="130" src="{{ $datum->pass_sheet_qr_code }}">
                    <p class="m-2">{{ $datum->pass_no }}</p>
            </td>
        @endforeach
        @for ($i = $chunk->count(); $i < 4; $i++)
            <td style="border: 1px solid #000;"></td>
        @endfor
    </tr>

    @php $rowCount++; @endphp

@endforeach

</tbody>
</table>



