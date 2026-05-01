@php $unit = request('unit'); @endphp
@if (sizeof($soRollTracking) > 0)
@foreach ($soRollTracking as $rollTracking)
@if ($rollTracking->total_quantity_processed > 0)
@php
if (
$rollTracking->soProduct &&
isset($rollTracking->soProduct->so_group)
) {
$sizeVals = getSizeValue(
$rollTracking->soProduct->so_group,
);
} else {
$sizeVals = []; // or some default value
}
@endphp
<tr>
    <th scope="row" class="p-3">
        {{
        \Carbon\Carbon::parse($rollTracking->end_date)->format('Y-m-d')
        }}
    </th>
    <td class="text-445B64 p-3">
        {{ $rollTracking->machine->machine }}</td>
    <td class="text-445B64 p-3">
        {{ $rollTracking->soProduct->so_no }}</td>
    <td class="text-445B64 p-3">
        {{ $rollTracking->product->product_modified_name }}
    </td>
    <td class="text-445B64 p-3 text-center">
        {{ $rollTracking->subProduct->sub_product_name }}
    </td>
    <td class="text-445B64 p-3 text-center">
        {{ optional($rollTracking->pass)->pass_no ?? '-' }}
    </td>
    <td class="text-445B64 p-3"> {{ $sizeVals[0] ?? '' }}:
        {{ !empty($rollTracking->salesorderProducts->size1) ?
        $rollTracking->salesorderProducts->size1 : '-' }} </td>

    <td class="text-445B64 p-3"> {{ $sizeVals[1] ?? '' }}:
        {{ !empty($rollTracking->salesorderProducts->size2) ?
        $rollTracking->salesorderProducts->size2 : '-' }} </td>

    <td class="text-445B64 p-3"> {{ $sizeVals[2] ?? '' }}:
        {{ !empty($rollTracking->salesorderProducts->size3) ?
        $rollTracking->salesorderProducts->size3 : '-' }} </td>

    <td class="text-445B64 p-3 text-center">
        {{ $rollTracking->salesorderProducts->soquantity }} </td>

    <td class="text-445B64 p-3 text-center">
        {{ $rollTracking->salesorderProducts->hardness }} </td>

    <td class="text-445B64 p-3 text-center">
        {{ $rollTracking->salesorderProducts->material }} </td>

    <td class="text-445B64 p-3 text-center">
        {{ $rollTracking->operation->operation_name }} </td>

    <td class="text-445B64 p-3 text-center">
        {{ $rollTracking->total_quantity_processed }} </td>

    @php
    $ideal = $rollTracking->ideal_cycle_time;
    $qty = $rollTracking->total_quantity_processed ?? 0;

    // Only multiply when ideal_cycle_time is NOT "NA" and NOT null
    $idealTotal = $ideal !== 'NA' && !is_null($ideal)
    ? $ideal * $qty
    : 'NA';
    @endphp

    <td>{{ $idealTotal }}</td>

    <td class="text-445B64 p-3 text-center">
        {{ round($rollTracking->time_taken_minutes / 60, 2) }}
        Mins</td>
</tr>
@endif
@endforeach
@else
<tr>
    <td colspan="15">
        <center> No data available </center>
    </td>
</tr>
@endif