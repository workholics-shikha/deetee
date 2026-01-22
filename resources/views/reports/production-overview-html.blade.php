@php $unit = request('unit'); @endphp
@if (sizeof($productionOverview) > 0)
@foreach ($productionOverview as $poverview)
<tr>
    <td class="text-445B64 p-3"> {{ $poverview->so_date }} </td>
    <td class="text-445B64 p-3 text-left">
        {{ $poverview->total_so }} </td>
    <td class="text-445B64 p-3 text-left">
        {{ getCompletedSOCount($poverview->so_date, $unit) }}
    </td>
    <td class="text-445B64 p-3 text-left">
        {{ $poverview->total_quantity }} </td>
    <td class="text-445B64 p-3 text-left">
        {{ getCompletedQtyOverAll($poverview->so_date, $unit) }}
    </td>
</tr>
@endforeach
@else
<tr>
    <td colspan="7">
        <center> No data available </center>
    </td>
</tr>
@endif