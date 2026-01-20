@section('title', 'Reports')
@extends('layouts.app')
@section('content')

<!-- Main Content Area Start -->
<div class="main-content-area">
    <div class="main-content report-page">
        <div class="row">
            <div class="col-12">
                <div class="card border-0 rounded-3 mb-4">
                    <div class="card-body px-0">
                          
                        <div class="row px-3 pt-3">
                            <div class="col-12 col-lg-6 border-end"> </div>

                            <div class="col-12 col-lg-6 d-flex align-items-center">
                                {{-- filter --}}
                                @php $unit = request('unit'); @endphp
                                <form method="GET" action="{{ url()->current() }}">
                                    <input type="hidden" name="tab" value="{{ request('tab') }}" id="tabName">
                                    <div class="row">
                                        <div class="col-12 col-xl-10">
                                            <div class="pe-0 pe-lg-4">
                                                <h6 class="text-445B64 fs-14">Filter: From Date - To Date & Unit</h6>
                                                <div class="row">
                                                    <input type="hidden" id="searchInput" search-url="{{ route('admin.so-completion-tracking-page') }}" >
                                                    <div class="col-12 col-lg-4 d-flex align-items-center mb-lg-0 mb-3">
                                                        <input type="date" name="from_date" class="form-control"
                                                            required
                                                            value="{{ request('from_date') ?? \Carbon\Carbon::parse($fromDate)->format('Y-m-d') }}">
                                                    </div>
                                                    <div class="col-12 col-lg-4 d-flex align-items-center mb-lg-0 mb-3">
                                                        <input type="date" name="to_date" class="form-control" required
                                                            value="{{ request('to_date') ?? \Carbon\Carbon::parse($toDate)->format('Y-m-d') }}">
                                                    </div>
                                                    <div class="col-12 col-lg-4 d-flex align-items-center mb-lg-0 mb-3">
                                                        <select class="form-control @error('unit') is-invalid @enderror"
                                                            name="unit">
                                                            <option value=""> All Unit </option>
                                                            <option value="Tooling" @if ($unit=='Tooling' )
                                                                {{ 'selected' }} @endif> Tooling </option>
                                                            <option value="RMR" @if ($unit=='RMR' ) {{ 'selected' }}
                                                                @endif> RMR </option>
                                                            <option value="TMR" @if ($unit=='TMR' ) {{ 'selected' }}
                                                                @endif> TMR </option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-12 col-xl-2 d-flex align-items-center mt-4">
                                            <button type="submit" class="btn btn-skyBlue btn-sm rounded-2 me-2"> Submit </button>
                                            <a href="{{ url()->current() }}" class="btn btn-skyBlue btn-sm rounded-2"
                                                title="Reset">Reset</a>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
 
                    <!-- First card -->
                    <div class="card border-0 rounded-3 mb-4 overflow-hidden">
                        <div class="card-header bg-white border-bottom">
                            <div class="row align-items-center">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div class="m-2">
                                        <h6 class="text-445B64 fw-semibold mb-0"> Sale Order Completion Tracking </h6>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-body p-0">
                            <div class="row">
                                <div class="col-12">
                                    <div class="table-responsive">
                                        <table class="table rounded-3 mb-0">
                                            <thead>
                                                <tr>
                                                    <th scope="col" class="text-6C7D83 py-2 px-3">Date</th>
                                                    <th scope="col" class="text-6C7D83 py-2 px-3">SO <br> Date.
                                                    </th>
                                                    <th scope="col" class="text-6C7D83 py-2 px-3">SO No.</th>
                                                    <th scope="col" class="text-6C7D83 py-2 px-3">Group </th>
                                                    <th scope="col" class="text-6C7D83 py-2 px-3">Product </th>
                                                    <th scope="col" class="text-6C7D83 py-2 px-3">Sub-Product </th>
                                                    <th scope="col" class="text-6C7D83 py-2 px-3">Pass Item </th>
                                                    <th scope="col" class="text-6C7D83 py-2 px-3 text-center">
                                                        Size1</th>
                                                    <th scope="col" class="text-6C7D83 py-2 px-3 text-center">
                                                        Size2</th>
                                                    <th scope="col" class="text-6C7D83 py-2 px-3 text-center">
                                                        Size3</th>

                                                    <th scope="col" class="text-6C7D83 py-2 px-3 text-left">
                                                        Hardness</th>
                                                    <th scope="col" class="text-6C7D83 py-2 px-3 text-left">
                                                        Material</th>

                                                    <th scope="col" class="text-6C7D83 py-2 px-3"> SO <br> Quanity
                                                    </th>
                                                    <th scope="col" class="text-6C7D83 py-2 px-3"> SO <br> Start
                                                        <br> Date
                                                    </th>
                                                    <th scope="col" class="text-6C7D83 py-2 px-3">Quantity <br> Produced
                                                    </th>
                                                </tr>
                                            </thead>
                                            <tbody id="myTableBody" >
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
                                                        {{
                                                        \Carbon\Carbon::parse($completionTracking->start_date)->format('Y-m-d')
                                                        }}
                                                    </td>
                                                    <td class="text-445B64 p-3 text-left">
                                                        {{ getCompletedQty($completionTracking->end_date,
                                                        $completionTracking->so_product_id,
                                                        $completionTracking->sub_product_id, $unit) }}
                                                    </td>
                                                </tr>
                                                @endforeach
                                                @else
                                                <tr>
                                                    <td colspan="15">
                                                        <center> No data available </center>
                                                    </td>
                                                </tr>
                                                @endif
                                            </tbody>
                                        </table>
                                          <br>
                                        <!-- Pagination Section -->
                                        <div class="pagination d-flex justify-content-center" id="paginationLinks">
                                            {{ $soCompletionTracking->links() }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
 
            </div>
        </div>
    </div>
</div>
 
@stop