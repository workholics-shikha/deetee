@forelse ($machines as $machine)
    <tr> 
        <th scope="row" class="p-3">
            <div class="d-flex align-items-center">
                <span class="table-square-icon rounded-3 img-zoom">
                    <img width="40" height="40" viewBox="0 0 12 12" fill="none"
                        src="{{ $machine->machine_qr_code }}" alt="Machine">
                </span>
                <span class="ps-2"> <a class="text-0D161A fw-semibold mb-0 text-decoration-none"> <strong>
                            {{ $machine->machine }}</strong> <br /> <span class="text-445B64" style="font-size: 12px">
                            {{ $machine->section }}</span></a>
                </span>
            </div>
        </th>

        <td class="p-3">{{ $machine->machine_type }} </td>
        <td class="p-3">{{ $machine->unit_name }}</td>
        <td class="p-3">{{ $machine->sub_section }}</td>

        @php
            $statusColors = [
                'breakdown'   => '#FF0000',
                'maintenance' => '#FF8C00',
                'in-working'  => '#007BFF', 
                'active'      => '#00B200', // green
            ];

            $textColor = $statusColors[$machine->machine_status] ?? '#000000'; // fallback to black
        @endphp

        <td class="p-3" style="color: {{ $textColor }}">
            {{ ucfirst($machine->machine_status) }}
            <img src="{{ asset('assets/images/completeDot.png') }}" alt="" class=""
                style="width: 20px; height: 20px" />
        </td>
        <td> <a class="rounded-pill" type="submit"
                href="{{ route('admin.machine-details', ['id' => $machine->id]) }}">View
                Details</a> <button type="button" class="btn generateQR" data-bs-toggle="modal" data-bs-target="#QRgenerateModal" data-type="Operator" data-id="{{ $machine->id }}"> View PDF </button> </td>
    </tr>
@empty
    <tr>
        <td colspan="8" class="text-center">No results found.</td>
    </tr>
@endforelse
