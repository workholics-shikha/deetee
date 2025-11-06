@php $i=0; @endphp
@foreach ($records as $record)
@php $i++; @endphp
<tr>
    <td class="p-3">{{ $i }}</td>
    <td class="p-3">
            {{ $record->operation_name }}
    </td>
    <td class="p-3">{{ $record->created_at }}</td>
</tr>
@endforeach