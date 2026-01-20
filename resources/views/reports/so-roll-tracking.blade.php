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
                            {{-- <div class="col-12 col-lg-6 border-end"> </div>

                            <div class="col-12 col-lg-6 d-flex align-items-center"> --}}
                                {{-- filter --}}
                                @php $unit = request('unit'); @endphp
                                <form method="GET" action="{{ url()->current() }}">

                                    {{-- <div class="row">

                                        <div class="col-12 col-lg-8">
                                            <div class="d-flex justify-content-between mb-3 mb-lg-0">
                                                <div class="d-flex align-items-center">
                                                    <div class="table-circular-icon bg-F0F5F6 me-3"
                                                        style="cursor: pointer">
                                                        <i class="fa-solid fa-arrows-rotate"></i>
                                                    </div>
                                                    <span class="text-0D161A fw-semibold me-1">{{
                                                        $soRollTracking->total() }}</span>
                                                    <span class="text-445B64 fw-medium">Items</span>
                                                </div>

                                            </div>
                                        </div>

                                        <div class="col-12 col-xl-10">
                                            <div class="pe-0 pe-lg-4">
                                                <h6 class="text-445B64 fs-14">Filter: From Date - To Date & Unit</h6>
                                                <div class="row">
                                                    <input type="hidden" id="searchInput"
                                                        search-url="{{ route('admin.so-roll-tracking-page')}}">
                                                    <div class="col-12 col-lg-4 d-flex align-items-center mb-lg-0 mb-3">
                                                        <input type="date" name="from_date" class="form-control"
                                                            required
                                                            value="{{ request('from_date') ?? \Carbon\Carbon::parse($fromDate)->format('Y-m-d')}}">
                                                    </div>
                                                    <div class="col-12 col-lg-4 d-flex align-items-center mb-lg-0 mb-3">
                                                        <input type="date" name="to_date" class="form-control" required
                                                            value="{{ request('to_date') ?? \Carbon\Carbon::parse($toDate)->format('Y-m-d')}}">
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
                                            <button type="submit" class="btn btn-skyBlue btn-sm rounded-2 me-2"> Submit
                                            </button>
                                            <a href="{{ url()->current() }}" class="btn btn-skyBlue btn-sm rounded-2"
                                                title="Reset">Reset</a>
                                        </div>
                                    </div> --}}

                                    <div class="row">
                                        <div class="col-12 col-lg-4">
                                            <div class="d-flex justify-content-between mb-3 mb-lg-0">
                                                <div class="d-flex align-items-center">
                                                    <div class="table-circular-icon bg-F0F5F6 me-3"
                                                        style="cursor: pointer">
                                                        <i class="fa-solid fa-arrows-rotate"></i>
                                                    </div>
                                                    <span class="text-0D161A fw-semibold me-1">{{
                                                        $soRollTracking->total() }}</span>
                                                    <span class="text-445B64 fw-medium">Items</span>
                                                </div>

                                            </div>
                                        </div>
                                        <div class="col-12 col-lg-8 d-flex align-items-center">
                                            <div class="col-12 col-xl-10">
                                                <div class="pe-0 pe-lg-4">
                                                    <h6 class="text-445B64 fs-14"> Filter: From Date - To Date & Unit </h6>
                                                    <div class="row">
                                                        <input type="search" id="searchInput" search-url="{{ route('admin.so-roll-tracking-page')}}" style="display:none;">
                                                        <div
                                                            class="col-12 col-lg-4 d-flex align-items-center mb-lg-0 mb-3">
                                                            <input type="date" name="from_date" class="form-control" required value="{{ request('from_date') ?? \Carbon\Carbon::parse($fromDate)->format('Y-m-d')}}">
                                                        </div>
                                                        <div
                                                            class="col-12 col-lg-4 d-flex align-items-center mb-lg-0 mb-3">
                                                            <input type="date" name="to_date" class="form-control"
                                                                required value="{{ request('to_date') ?? \Carbon\Carbon::parse($toDate)->format('Y-m-d')}}">
                                                        </div>
                                                        <div
                                                            class="col-12 col-lg-4 d-flex align-items-center mb-lg-0 mb-3">
                                                            <select
                                                                class="form-control @error('unit') is-invalid @enderror"
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
                                                <button type="submit" class="btn btn-skyBlue btn-sm rounded-2 me-2">
                                                    Submit
                                                </button>
                                                <a href="{{ url()->current() }}"
                                                    class="btn btn-skyBlue btn-sm rounded-2" title="Reset">Reset</a>
                                            </div>
                                        </div>
                                    </div>

                                </form>
                                {{--
                            </div>
                        </div> --}}
                    </div>
                </div>

                <hr>

                <!-- First card -->
                <div class="card border-0 rounded-3 mb-4 overflow-hidden">
                    <div class="card-header bg-white border-bottom">
                        <div class="row align-items-center">
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="m-2">
                                    <h6 class="text-445B64 fw-semibold mb-0"> SO Roll Tracking </h6>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-body p-0">
                        <div class="row">
                            <div class="col-12">
                                <div class="table-responsive">
                                    <table class="table rounded-3 mb-0" id="myTable">
                                        <thead>
                                            <tr>
                                            <tr>
                                                <th scope="col" class="text-6C7D83 py-2 px-3">Date</th>
                                                <th scope="col" class="text-6C7D83 py-2 px-3">Machine</th>
                                                <th scope="col" class="text-6C7D83 py-2 px-3">SO No.</th>
                                                <th scope="col" class="text-6C7D83 py-2 px-3">Product</th>
                                                <th scope="col" class="text-6C7D83 py-2 px-3 text-center">Sub
                                                    Product</th>
                                                <th scope="col" class="text-6C7D83 py-2 px-3 text-center"> Pass Item
                                                </th>
                                                <th scope="col" class="text-6C7D83 py-2 px-3 text-left">
                                                    Size1</th>
                                                <th scope="col" class="text-6C7D83 py-2 px-3 text-left">
                                                    Size2</th>
                                                <th scope="col" class="text-6C7D83 py-2 px-3 text-left">
                                                    Size3</th>
                                                <th scope="col" class="text-6C7D83 py-2 px-3 text-left">
                                                    Quantity</th>
                                                <th scope="col" class="text-6C7D83 py-2 px-3 text-center">
                                                    Hardness</th>
                                                <th scope="col" class="text-6C7D83 py-2 px-3 text-center">
                                                    Material</th>
                                                <th scope="col" class="text-6C7D83 py-2 px-3 text-center">
                                                    Process</th>
                                                <th scope="col" class="text-6C7D83 py-2 px-3 text-center">
                                                    Quantity <br> Produced </th>
                                                <th scope="col" class="text-6C7D83 py-2 px-3 text-center">
                                                    ICT</th>
                                                <th scope="col" class="text-6C7D83 py-2 px-3 text-center">
                                                    Total <br> Duration</th>
                                            </tr>
                                        </thead>
                                        <tbody id="myTableBody">
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

                                                <td> {{ $idealTotal }} </td>

                                                <td class="text-445B64 p-3 text-center"> {{
                                                    round($rollTracking->time_taken_minutes / 60, 2) }} Mins</td>
                                            </tr>
                                            @endif
                                            @endforeach
                                            @else
                                            <tr>
                                                <td colspan="12">
                                                    <center> No data available </center>
                                                </td>
                                            </tr>
                                            @endif
                                        </tbody>
                                    </table>
                                    <br>
                                    <!-- Pagination Section -->
                                    <div class="pagination d-flex justify-content-center" id="paginationLinks">
                                        {{ $soRollTracking->links() }}
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