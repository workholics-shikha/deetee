@php $rowCount = 0; @endphp

@foreach($pass_sheet->chunk(4) as $chunk)

@if($rowCount % 3 == 0)

@if($rowCount > 0)
</table>
<div style="page-break-after: always;"></div>
@endif

<table width="100%" cellspacing="0" cellpadding="6" style="border-collapse:collapse; table-layout:fixed;">
    <tbody>

        {{-- HEADER --}}
        <tr>
            <td colspan="4" style="border:1px solid #000;">
                <div style="display:flex; align-items:center;">
                    <img src="{{ asset('assets/images/pdfLogo.png') }}" height="30">
                    <strong style="margin-left:10px;">DeeTee Industries Pvt. Ltd.</strong>
                </div>

                @if(!empty($data->so_unitname))
                  <div style="margin-top:4px;">
                    {{ $data->so_unitname }}
                  </div>
                @endif
            </td>
        </tr>

        {{-- SO / PRODUCT ROW --}}
        <tr>
            <td style="border:1px solid #000; border-right:none; vertical-align:top;">
                <p>SO No:</p>
                <strong>{{ $saleOrder->so_no }}</strong>
            </td>

            <td style="border:1px solid #000; border-left:none; text-align:center;">
                <img src="{{ $saleOrder->so_qr_code }}" width="80">
            </td>

            <td style="border:1px solid #000; border-right:none; vertical-align:top;">
                <p>Product</p>
                <strong>{{ $data->item_name }}</strong>
            </td>

            <td style="border:1px solid #000; border-left:none; text-align:center;">
                <img src="{{ $data->so_product_qr_code }}" width="80">
            </td>
        </tr>

        {{-- SIZE / QTY BLOCK --}}
        @php
        $parts = explode('-', $saleOrder->so_no);
        $group = implode('-', array_slice($parts, 4));
        $sizeVals = getSizeValue($group);
        @endphp

        <tr>
            <td colspan="4" style="border:1px solid #000; padding:0;">
                <table width="100%" cellspacing="0" cellpadding="6"
                    style="border-collapse:collapse; table-layout:fixed;">
                    <tr>
                        <td style="border:1px solid #000; text-align:center; width:16.66%;">
                            <p style="margin:0;">{{ $sizeVals[0] }}</p>
                            <strong style="font-size:11px;">{{ $data->size1 ?? '-' }}</strong>
                        </td>

                        <td style="border:1px solid #000; text-align:center; width:16.66%;">
                            <p style="margin:0;">{{ $sizeVals[1] }}</p>
                            <strong style="font-size:11px;">{{ $data->size2 ?? '-' }}</strong>
                        </td>

                        <td style="border:1px solid #000; text-align:center; width:16.66%;">
                            <p style="margin:0;">{{ $sizeVals[2] }}</p>
                            <strong style="font-size:11px;">{{ $data->size3 ?? '-' }}</strong>
                        </td>

                        <td style="border:1px solid #000; text-align:center; width:16.66%;">
                            <p style="margin:0;">Quantity</p>
                            <strong style="font-size:11px;">{{ $data->soquantity }}</strong>
                        </td>

                        <td style="border:1px solid #000; text-align:center; width:16.66%;">
                            <p style="margin:0;">Hardness</p>
                            <strong style="font-size:11px;">{{ $data->hardness }}</strong>
                        </td>

                        <td style="border:1px solid #000; text-align:center; width:16.66%;">
                            <p style="margin:0;">Material</p>
                            <strong style="font-size:11px;">{{ $data->material }}</strong>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>

        {{-- PASS SHEET HEADER --}}
        <tr>
            <th colspan="4" style="border:1px solid #000; text-align:left;">
                Pass Sheet
            </th>
        </tr>
        @endif

        {{-- PASS SHEET QR GRID --}}
        <tr>
            @foreach($chunk as $datum)
            <td style="border:1px solid #000; text-align:center;">
                <img src="{{ $datum->pass_sheet_qr_code }}" width="120" height="120"><br>
                <strong>{{ $datum->pass_no }}</strong>
            </td>
            @endforeach

            @for($i = $chunk->count(); $i < 4; $i++) <td style="border:1px solid #000;">
                </td>
                @endfor
        </tr>

        @php $rowCount++; @endphp

        @endforeach

    </tbody>
</table>