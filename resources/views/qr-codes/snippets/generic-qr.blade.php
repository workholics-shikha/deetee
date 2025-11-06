@php $rowCount = 0; @endphp 

@foreach($data->chunk(4) as $key => $chunk)

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
                    </div>
                </td>
            </tr>
            <tr>
                <th colspan="4">Generic Codes</th>
            </tr>
    @endif

    <tr>
        @foreach($chunk as $operation)
            <td style="border: 1px solid #000; text-align: center;">
                <img width="130" height="130" src="{{ asset('storage/generic-qrcodes') . '/' . $operation->qr_code }}" alt="QR">
                <p class="m-2">{{ $operation->code }}</p>
                <p class="m-2">{{ $operation->qr_use_for }}</p>
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



