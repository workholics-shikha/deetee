{{-- OPERATOR --}}
@if ($activeTab == 'operator')
@php $i=0; @endphp
@foreach ($data as $operator)
@php $i++; @endphp
<tr>
    <td class="p-3">
        <div class="d-flex align-items-center">
            <span class="table-square-icon bg-F0F5F6 rounded-3 showModal" data-bs-toggle="modal"
                data-id="{{ $operator->id }}" data-bs-target="#exampleModal" data-type="Operator"
                style="cursor: pointer">
                <img width="30" height="30" viewBox="0 0 12 12" fill="none" src="{{ $operator->user_qr_code }}" alt=""
                    class="me-2" />
            </span>
            <span class="ps-2">
                <a class="text-0D161A fw-semibold mb-0 text-decoration-none">{{ $operator->name }}<br />
                    <span class="text-445B64" style="font-size: 12px">#{{ $operator->username }}
                    </span>
                </a>
            </span>
        </div>
    </td>
    <td class="p-3">{{ $operator->designation }} </td>
    <td class="p-3">{{ $operator->department }} </td>
    <td class="p-3">{{ $operator->unit }} </td>
    <td class="p-3">{{ $operator->unit_name }} </td>
    <td class="p-3">{{ $operator->employee_group }} </td>
    <td class="p-3" style="color: #00b200">
        Present
        <img src="{{ asset('assets/images/completeDot.png') }}" alt="" class="" style="width: 20px; height: 20px" />
    </td>
</tr>
@endforeach

{{-- operations --}}
@elseif($activeTab == 'operations')
@php $i=0; @endphp
@if (!empty($data))
@foreach ($data as $record)
@php $i++; @endphp

<tr>
    <th scope="row" class="p-3">
        <div class="d-flex align-items-center">
            <span class="table-square-icon bg-F0F5F6 rounded-3 showModal" data-bs-toggle="modal"
                data-id="{{ $record->id }}" data-bs-target="#exampleModal" data-type="Operations"
                style="cursor: pointer">
                <img width="30" height="30" viewBox="0 0 12 12" fill="none" src="{{ $record->operation_qr_code }}"
                    alt="" class="me-2" />
            </span>

            <span class="ps-2"> <a class="text-0D161A fw-semibold mb-0 text-decoration-none">
                    <strong> {{ $record->operation_name }}</strong>
                </a>
            </span>
        </div>
    </th>
    <td class="p-3">{{ $record->unit }}</td>
    <td class="p-3">{{ $record->parameter_input }}</td>
    <td class="p-3">{{ $record->matrix }}</td>
    <td>
        @foreach ($record->machines as $machine)
        <small> {{ $machine->id . '-' . $machine->machine }}
        </small>,<br>
        @if (!$loop->last)
        @endif
        @endforeach
    </td>
</tr>
@endforeach
@endif
@elseif($activeTab == 'machines')
@php $i=0; @endphp
@foreach ($data as $machine)
@php $i++; @endphp
<tr>
    <th scope="row" class="p-3">
        <div class="d-flex align-items-center">

            <span class="table-square-icon bg-F0F5F6 rounded-3 showModal" data-bs-toggle="modal"
                data-id="{{ $machine->id }}" data-bs-target="#exampleModal" data-type="Machine" style="cursor: pointer">
                <img width="30" height="30" viewBox="0 0 12 12" fill="none" src="{{ $machine->machine_qr_code }}"
                    alt="Machine">
            </span>

            <span class="ps-2"> <a class="text-0D161A fw-semibold mb-0 text-decoration-none">
                    <strong>{{ $machine->machine }}</strong> <br />
                    <span class="text-445B64" style="font-size: 12px">#{{ $machine->machine_type }}</span></a>
            </span>
        </div>
    </th>
    <td class="p-3">{{ $machine->machine }} </td>
    <td class="p-3">{{ $machine->unit_name }}</td>
    <td class="p-3">{{ $machine->sub_section }}</td>
    <td class="p-3">
        <span class="rounded-pill bg-D5FFCC px-2 py-1 text-success fw-bolder">{{ $machine->section }}</span>
    </td>
    <td class="p-3" style="color: #00b200">
        {{ $machine->machine_status }}
        <img src="{{ asset('assets/images/completeDot.png') }}" alt="" class="" style="width: 20px; height: 20px" />
    </td>
</tr>
@endforeach

@elseif($activeTab == 'products')
@php $i=0; @endphp
@foreach ($data as $product)
@php $i++; @endphp
<tr class="p-3">
    <th scope="row">
        <div class="d-flex align-items-center">
            <span class="table-square-icon bg-F0F5F6 rounded-3 showModal" data-bs-toggle="modal"
                data-id="{{ $product->product->id }}" data-bs-target="#exampleModal" data-type="Product"
                style="cursor: pointer">
                <img width="30" height="30" viewBox="0 0 12 12" fill="none"
                    src="{{ $product->product->product_qr_code }}" alt="QR Code">
            </span>
            <span class="ps-2"> <a class="text-0D161A fw-semibold mb-0 text-decoration-none">{{
                    $product->product->erp_product }}<br />
                    <span class="text-445B64" style="font-size: 12px">#{{ $product->product->erp_nomenclature
                        }}</span></a>
            </span>
        </div>
    </th>
    <td class="p-3">{{ $product->sub_product_name }} </td>
    <td class="p-3">{{ $product->product->unit }}</td>
    <td class="p-3">{{ $product->product->unit_number }}</td>

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
        <span class="rounded-pill bg-{{ $addClass }} px-2 py-1 fw-bolder">
            {{ $product->product->group }} </span>
    </td>
    <td class="p-3" style="color: #00b200">
        {{ $product->product->status }}
        <img src="{{ asset('assets/images/completeDot.png') }}" alt="" class="" style="width: 20px; height: 20px" />
    </td>
</tr>
@endforeach

@endif