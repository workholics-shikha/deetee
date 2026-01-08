 
@foreach ($records as $record)
 
<tr>
    <th scope="row" class="p-3">
        <div class="d-flex align-items-center">
            <span class="table-square-icon rounded-3 img-zoom">
                <img width="40" height="40" viewBox="0 0 12 12" fill="none" src="{{ $record->operation_qr_code }}" alt="Machine">
            </span>
            <span class="ps-2"> <a class="text-0D161A fw-semibold mb-0 text-decoration-none"> <strong>({{$record->id}}) {{ $record->operation_name }}</strong> </a>
            </span>
        </div>
    </th>
    <td class="p-3">{{ $record->unit }}</td>
    <td class="p-3">{{ $record->parameter_input }}</td>
    <td class="p-3">{{ $record->matrix }}</td>
    <td>
        @foreach ($record->machines as $machine)
           <small> {{$machine->id.'-'. $machine->machine}} </small>,<br> @if (!$loop->last) @endif
        @endforeach
    </td>
</tr>

@endforeach