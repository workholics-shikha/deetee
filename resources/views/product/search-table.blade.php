@php $i=0; @endphp
@foreach ($data as $product)
    @php $i++; @endphp
    <tr>

        <th scope="row">
            <div class="d-flex align-items-center">
                <span class="table-square-icon rounded-3 img-zoom">
                    <img width="40" height="40" viewBox="0 0 12 12" fill="none"
                        src="{{ $product->product_qr_code ?? '#' }}" alt="QR Code">
                </span>
                <span class="ps-2"> <a
                        class="text-0D161A fw-semibold mb-0 text-decoration-none">{{ $product->product_modified_name ?? '' }}<br />
                        <span class="text-445B64"
                            style="font-size: 12px">#{{ $product->erp_nomenclature ?? '' }}</span></a>
                </span>
            </div>
        </th>
        <td class="p-3"> {{ $product->erp_product }} </td>
        <td class="p-3">
            @if ($product->subProducts->isNotEmpty())
                @foreach ($product->subProducts as $sub)
                    {{ $sub->sub_product_name }}  ({{ $sub->id }})<br>
                @endforeach
            @else
                @if ($product->product_flow == 'Available')
                    (All Sub-Products of {{ $product->group }} Group)
                @else
                    NA
                @endif
            @endif
        </td>

        <td class="p-3">{{ $product->unit ?? '' }}</td> 

        @php
            $so_group = $product->group ?? '';
            $addClass = '';

            if ($so_group == 'A' || $so_group == 'F') {
                $addClass = 'D5FFCC text-116600';
            }
            if ($so_group == 'B') {
                $addClass = 'FFCCF7 text-B20095';
            }
            if ($so_group == 'C-SB' || $so_group == 'C-TCOK') {
                $addClass = 'CCCCFF text-0000FF';
            }
            if ($so_group == 'D' || $so_group == 'E' || $so_group == 'L') {
                $addClass = 'FF9898 text-B20095';
            }
            if ($so_group == 'G') {
                $addClass = 'CCFFCC text-00B200';
            }
            if ($so_group == 'H' || $so_group == 'I' || $so_group == 'J') {
                $addClass = 'CCFFCC text-CC2200';
            }
            if ($so_group == 'K') {
                $addClass = 'CCFFCC text-006699';
            }
            if ($so_group == 'M') {
                $addClass = 'CCEEFF text-006699';
            }

        @endphp

        <td class="p-3">
            <span class="rounded-pill bg-{{ $addClass }} px-2 py-1 fw-bolder">
                {{ $product->group ?? '' }} </span>
        </td>

        @php
            $color = $color1 = '#00b200';
            $img = $img1 = 'completeDot.png';
            if ($product->product_flow == 'Available') {
                $color = '#00b200';
                $img = 'completeDot.png';
            } else {
                $color = 'red';
                $img = 'notCompleteDot.png';
            }

            if ($product->cycle_flow == 'Available') {
                $color1 = '#00b200';
                $img1 = 'completeDot.png';
            } else {
                $color1 = 'red';
                $img1 = 'notCompleteDot.png';
            }

        @endphp
        <td class="p-3" style="color:{{ $color }}">
            {{ $product->product_flow ?? 'NA' }}
            <img src="{{ asset('assets/images/') . '/' . $img }}" style="width: 20px; height: 20px" />
        </td>
        <td class="p-3" style="color:{{ $color1 }}">
            {{ $product->cycle_flow ?? 'NA' }}
            <img src="{{ asset('assets/images/') . '/' . $img1 }}" style="width: 20px; height: 20px" />
        </td>
    </tr>
@endforeach
