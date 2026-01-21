@php $unit = request('unit'); @endphp
@if (sizeof($maintenanceOverview) > 0)
@foreach ($maintenanceOverview as $overview)
<tr>
    <th scope="row" class="p-3">
        {{ $overview->created_date }}</th>
    <th scope="row" class="p-3">
        {{ $overview->unit_name }}</th>
    <td class="text-445B64 p-3">
        {{ ucfirst($overview->monitor_for) }}</td>
    <td class="text-445B64 p-3">
        {{ round($overview->total_seconds / 60, 2) }} Mins</td>
</tr>
@endforeach
@else
<tr>
    <td colspan="7">
        <center> No data available </center>
    </td>
</tr>
@endif