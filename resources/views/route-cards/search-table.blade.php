@php $i=0; @endphp
@foreach ($data as $product)
    @php $i++; @endphp
    <tr> 
        <th scope="row" class="p-3">
            <div class="d-flex align-items-center">
                <span class="">
                    <a class="text-0D161A fw-semibold mb-0 text-decoration-none">{{ $product->product->product_modified_name }}<br />
                        <span class="text-445B64"
                            style="font-size: 12px">#{{ $product->product->erp_nomenclature }}</span>
                    </a>
                </span>
            </div>
        </th>

        <td class="p-3"> {{ $product->sub_product_name }} </td>
        <td class="p-3"> {{ $product->product->unit }} </td>

        @php
            $so_group = $product->product->group;

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
            <span class="rounded-pill bg-{{ $addClass }} px-2 py-1 fw-bolder">{{ $product->product->group }}</span>
        </td>

        <td class="p-3">
            <a href="{{ route('admin.process', $product->id) }}"><i class="fa-solid fa-eye text-445B64"></i></a>
        </td>
    </tr>
@endforeach
