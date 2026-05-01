@php $unit = request('unit'); @endphp
@if (sizeof($soCompletionTracking) > 0)
@foreach ($soCompletionTracking as $completionTracking)
@php
if (
$completionTracking->soProduct &&
isset($completionTracking->soProduct->so_group)
) {
$sizeVals = getSizeValue(
$completionTracking->soProduct->so_group,
);
} else {
$sizeVals = []; // or some default value
}
@endphp
<tr>
    <th scope="row" class="p-3">
        {{
        \Carbon\Carbon::parse($completionTracking->end_date)->format('Y-m-d')
        }}
    </th>
    <td class="text-445B64 p-3">
        {{ $completionTracking->soProduct->so_date }}</td>
    <td class="text-445B64 p-3">
        {{ $completionTracking->soProduct->so_no }}</td>
    <td class="text-445B64 p-3">
        {{ $completionTracking->soProduct->so_group }}</td>
    <td class="text-445B64 p-3">
        {{ $completionTracking->product_name }} </td>
    <td class="text-445B64 p-3">
        {{ $completionTracking->sub_product_name }} </td>
    <td> {{ optional($completionTracking->pass)->pass_no ?? '-' }} </td>
    <td class="text-445B64 p-3"> {{ $sizeVals[0] ?? '' }}:
        {{ !empty($completionTracking->salesorderProducts->size1) ?
        $completionTracking->salesorderProducts->size1 : '-' }}
    </td>
    <td class="text-445B64 p-3"> {{ $sizeVals[1] ?? '' }}:
        {{ !empty($completionTracking->salesorderProducts->size2) ?
        $completionTracking->salesorderProducts->size2 : '-' }}
    </td>
    <td class="text-445B64 p-3"> {{ $sizeVals[2] ?? '' }}:
        {{ !empty($completionTracking->salesorderProducts->size3) ?
        $completionTracking->salesorderProducts->size3 : '-' }}
    </td>

    <td class="text-445B64 p-3">
        {{ $completionTracking->salesorderProducts->hardness }}
    </td>
    <td class="text-445B64 p-3">
        {{ $completionTracking->salesorderProducts->material }}
    </td>

    <td class="text-445B64 p-3">
        {{ $completionTracking->salesorderProducts->soquantity }}
    </td>
    <td class="text-445B64 p-3">
        {{ \Carbon\Carbon::parse($completionTracking->start_date)->format('Y-m-d') }}
    </td>
    <td class="text-445B64 p-3 text-left">
        {{ getCompletedQty($completionTracking->end_date, $completionTracking->so_product_id, $completionTracking->sub_product_id, $unit) }}
    </td>
</tr>
@endforeach
@else
    <tr>
        <td colspan="15" > <center> No data available </center> </td>
    </tr>
@endif