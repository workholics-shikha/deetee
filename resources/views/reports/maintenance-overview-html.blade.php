@php $unit = request('unit'); @endphp
@if(!empty($maintenanceHistory))
@if (sizeof($maintenanceHistory) > 0)
@foreach ($maintenanceHistory as $history)
<tr>
    <th scope="row" class="p-3">
        {{ \Carbon\Carbon::parse($history->created_at)->format('M d Y')
        }}
    </th>
    <td class="text-445B64 p-3"> {{ $history->unit_name }}
    </td>
    <td class="text-445B64 p-3"> {{ $history->machine }}</td>
    <td class="text-445B64 p-3">
        {{ ucfirst($history->monitor_for) }}</td>
    <td class="text-445B64 p-3 text-center">
        {{ $history->start_date_time }}</td>
    <td class="text-445B64 p-3 text-center">
        {{ $history->end_date_time }}</td>
    <td class="text-445B64 p-3 text-center">
        {{ round($history->total_seconds / 60, 2) }}</td>
</tr>
@endforeach
@else
<tr>
    <td colspan="7">
        <center> No data available </center>
    </td>
</tr>
@endif
@endif