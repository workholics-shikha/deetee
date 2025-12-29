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
                            <div class="row">
                                <div class="col-12">
                                    <div class="tab-menu pb-0">
                                        <a href="#" class="tab mb-0 active" onclick="openTab(event, 'MIS')">
                                            <h6 class="text-445B64">MIS</h6>
                                        </a>
                                        <a href="#" class="tab mb-0" onclick="openTab(event, 'SaleOrders')">
                                            <h6 class="text-0D161A">Sale Orders</h6>
                                        </a>
                                        <a href="#" class="tab mb-0" onclick="openTab(event, 'Maintenance')">
                                            <h6 class="text-445B64">Maintenance</h6>
                                        </a>
                                    </div>
                                </div>
                            </div>

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
                                                        <div class="col-12 col-lg-4 d-flex align-items-center mb-lg-0 mb-3">
                                                            <input type="date" name="from_date" class="form-control"
                                                                required
                                                                value="{{ request('from_date') ?? \Carbon\Carbon::parse($fromDate)->format('Y-m-d') }}">
                                                        </div>
                                                        <div class="col-12 col-lg-4 d-flex align-items-center mb-lg-0 mb-3">
                                                            <input type="date" name="to_date" class="form-control"
                                                                required
                                                                value="{{ request('to_date') ?? \Carbon\Carbon::parse($toDate)->format('Y-m-d') }}">
                                                        </div>
                                                        <div class="col-12 col-lg-4 d-flex align-items-center mb-lg-0 mb-3">
                                                            <select class="form-control @error('unit') is-invalid @enderror"
                                                                name="unit">
                                                                <option value=""> All Unit </option>
                                                                <option value="Tooling"
                                                                    @if ($unit == 'Tooling') {{ 'selected' }} @endif>
                                                                    Tooling </option>
                                                                <option value="RMR"
                                                                    @if ($unit == 'RMR') {{ 'selected' }} @endif>
                                                                    RMR </option>
                                                                <option value="TMR"
                                                                    @if ($unit == 'TMR') {{ 'selected' }} @endif>
                                                                    TMR </option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-12 col-xl-2 d-flex align-items-center mt-4">
                                                <button type="submit"
                                                    class="btn btn-skyBlue btn-sm rounded-2 me-2">Submit</button>
                                                <a href="{{ url()->current() }}" class="btn btn-skyBlue btn-sm rounded-2"
                                                    title="Reset">Reset</a>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- tab 1 --}}
                    <div id="MIS" class="tabcontent">
                        <!-- First card -->
                        <div class="card border-0 rounded-3 mb-4 overflow-hidden">
                            <div class="card-header bg-white border-bottom">
                                <div class="row align-items-center">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div class="m-2">
                                            <h6 class="text-445B64 fw-semibold mb-0">Production Overview</h6>
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
                                                        <th scope="col" class="text-6C7D83 py-2 px-3">Date </th>
                                                        <th scope="col" class="text-6C7D83 py-2 px-3">Total SO Opened
                                                        </th>
                                                        <th scope="col" class="text-6C7D83 py-2 px-3">Total SO Completed
                                                        </th>
                                                        <th scope="col" class="text-6C7D83 py-2 px-3">Total SO Quantity
                                                        </th>
                                                        <th scope="col" class="text-6C7D83 py-2 px-3">Total Quantity
                                                            Produced</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
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
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Second card -->
                        <div class="card border-0 rounded-4 mb-4 overflow-hidden">
                            <div class="card-header bg-white border-bottom">
                                <div class="row align-items-center">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div class="m-2">
                                            <h6 class="text-445B64 fw-semibold mb-0">Maintenance Overview</h6>
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
                                                        <th scope="col" class="text-6C7D83 py-2 px-3">Unit</th>
                                                        <th scope="col" class="text-6C7D83 py-2 px-3">Maintenance Type
                                                        </th>
                                                        <th scope="col" class="text-6C7D83 py-2 px-3">Total Downtime
                                                            Duration</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
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
                                                </tbody>
                                            </table>
                                            {{-- <div class="d-flex justify-content-center mt-3">
                                                {{ $maintenanceOverview->withPath(request()->url())->appends(request()->query())->links() }}
                                            </div> --}}
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                    {{-- tab 1 end --}}

                    {{-- tab 2 --}}
                    <div id="SaleOrders" class="tabcontent" style="display: none;">
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
                                                        <th scope="col" class="text-6C7D83 py-2 px-3"> SO <br> Quanity
                                                        </th>
                                                        <th scope="col" class="text-6C7D83 py-2 px-3"> SO <br> Start
                                                            <br> Date
                                                        </th>
                                                        <th scope="col" class="text-6C7D83 py-2 px-3">Quantity <br>
                                                            Produced </th>
                                                    </tr>
                                                </thead>
                                                <tbody>
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
                                                                    {{ \Carbon\Carbon::parse($completionTracking->end_date)->format('Y-m-d') }}
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
                                                                    {{ !empty($completionTracking->salesorderProducts->size1) ? $completionTracking->salesorderProducts->size1 : '-' }}
                                                                </td>
                                                                <td class="text-445B64 p-3"> {{ $sizeVals[1] ?? '' }}:
                                                                    {{ !empty($completionTracking->salesorderProducts->size2) ? $completionTracking->salesorderProducts->size2 : '-' }}
                                                                </td>
                                                                <td class="text-445B64 p-3"> {{ $sizeVals[2] ?? '' }}:
                                                                    {{ !empty($completionTracking->salesorderProducts->size3) ? $completionTracking->salesorderProducts->size3 : '-' }}
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
                                                            <td colspan="12">
                                                                <center> No data available </center>
                                                            </td>
                                                        </tr>
                                                    @endif
                                                </tbody>
                                            </table>
                                            <!-- Pagination Section -->
                                            {{-- <div class="paginationQ d-flex justify-content-center" id="operator-paginate">
                                                {{ $soCompletionTracking->appends(request()->query())->links() }}
                                            </div> --}}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Second card -->
                        <div class="card border-0 rounded-4 mb-4 overflow-hidden">
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
                                            <table class="table rounded-3 mb-0">
                                                <thead>
                                                    <tr>
                                                    <tr>
                                                        <th scope="col" class="text-6C7D83 py-2 px-3">Date</th>
                                                        <th scope="col" class="text-6C7D83 py-2 px-3">Machine</th>
                                                        <th scope="col" class="text-6C7D83 py-2 px-3">SO No.</th>
                                                        <th scope="col" class="text-6C7D83 py-2 px-3">Product</th>
                                                        <th scope="col" class="text-6C7D83 py-2 px-3 text-center">Sub
                                                            Product</th>
                                                        <th scope="col" class="text-6C7D83 py-2 px-3 text-center"> Pass Item </th>    
                                                        <th scope="col" class="text-6C7D83 py-2 px-3 text-center">
                                                            Size1</th>
                                                        <th scope="col" class="text-6C7D83 py-2 px-3 text-center">
                                                            Size2</th>
                                                        <th scope="col" class="text-6C7D83 py-2 px-3 text-center">
                                                            Size3</th>
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
                                                <tbody>
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
                                                                        {{ \Carbon\Carbon::parse($rollTracking->end_date)->format('Y-m-d') }}
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
                                                                        {{ !empty($rollTracking->salesorderProducts->size1) ? $rollTracking->salesorderProducts->size1 : '-' }}
                                                                    </td>
                                                                    <td class="text-445B64 p-3"> {{ $sizeVals[1] ?? '' }}:
                                                                        {{ !empty($rollTracking->salesorderProducts->size2) ? $rollTracking->salesorderProducts->size2 : '-' }}
                                                                    </td>
                                                                    <td class="text-445B64 p-3"> {{ $sizeVals[2] ?? '' }}:
                                                                        {{ !empty($rollTracking->salesorderProducts->size3) ? $rollTracking->salesorderProducts->size3 : '-' }}
                                                                    </td>
                                                                    <td class="text-445B64 p-3 text-center">
                                                                        {{ $rollTracking->operation->operation_name }}</td>
                                                                    <td class="text-445B64 p-3 text-center">
                                                                        {{ $rollTracking->total_quantity_processed }}</td>

                                                                    @php
                                                                        $ideal = $rollTracking->ideal_cycle_time;
                                                                        $qty = $rollTracking->total_quantity_processed ?? 0;

                                                                        // Only multiply when ideal_cycle_time is NOT "NA" and NOT null
                                                                        $idealTotal =
                                                                            $ideal !== 'NA' && !is_null($ideal)
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
                                                            <td colspan="12">
                                                                <center> No data available </center>
                                                            </td>
                                                        </tr>
                                                    @endif
                                                </tbody>
                                            </table>
                                            {{-- <div class="d-flex justify-content-center mt-3">
                                                {{ $soRollTracking->appends(request()->query())->links() }}
                                            </div> --}}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    {{-- tab 2 end --}}
                      {{-- tab 3 --}}
                    <div id="Maintenance" class="tabcontent" style="display: none;">
                        <!-- First card -->
                        <div class="card border-0 rounded-3 mb-4 overflow-hidden">
                            <div class="card-header bg-white border-bottom">
                                <div class="row align-items-center">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div class="m-2">
                                            <h6 class="text-445B64 fw-semibold mb-0"> Maintenance History </h6>
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
                                                        <th scope="col" class="text-6C7D83 py-2 px-3">Unit</th>
                                                        <th scope="col" class="text-6C7D83 py-2 px-3">Machine</th>
                                                        <th scope="col" class="text-6C7D83 py-2 px-3">Type</th>
                                                        <th scope="col" class="text-6C7D83 py-2 px-3 text-center">Start
                                                            TIme</th>
                                                        <th scope="col" class="text-6C7D83 py-2 px-3 text-center">End
                                                            Time</th>
                                                        <th scope="col" class="text-6C7D83 py-2 px-3 text-center">
                                                            Duration(in min)</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @if (sizeof($maintenanceHistory) > 0)
                                                        @foreach ($maintenanceHistory as $history)
                                                            <tr>
                                                                <th scope="row" class="p-3">
                                                                    {{ \Carbon\Carbon::parse($history->created_at)->format('M d Y') }}
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
                                                </tbody>
                                            </table>
                                            <div class="d-flex justify-content-center mt-3">
                                                {{ $maintenanceHistory->appends(request()->query())->links() }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    {{-- tab 3 end --}}
                </div>
            </div>
        </div>
    </div>

    {{-- Modal end --}}
    <script>
       
        document.addEventListener("DOMContentLoaded", function() {
            const urlParams = new URLSearchParams(window.location.search);
            const activeTab = urlParams.get("tab") || "MIS";

            openTab({
                currentTarget: document.querySelector(`[onclick="openTab(event, '${activeTab}')"]`)
            }, activeTab);

        });

        function openTab(evt, cityName) {
            var i, tabcontent, tablinks;

            // Hide all tab content
            tabcontent = document.getElementsByClassName("tabcontent");
            for (i = 0; i < tabcontent.length; i++) {
                tabcontent[i].style.display = "none";
            }

            // Remove 'active' class from all tabs
            tablinks = document.getElementsByClassName("tab");
            for (i = 0; i < tablinks.length; i++) {
                tablinks[i].classList.remove("active");
            }

            // Display the selected tab's content and add 'active' class
            document.getElementById(cityName).style.display = "block";
            evt.currentTarget.classList.add("active");

            // Update the URL to reflect the active tab but KEEP existing parameters
            const urlParams = new URLSearchParams(window.location.search);
            urlParams.set("tab", cityName);
            // Don't delete page parameter - let it stay for pagination

            // Use history.replaceState to update the URL without reloading the page
            const newUrl = `${window.location.pathname}?${urlParams.toString()}`;
            window.history.replaceState({}, "", newUrl);
        }

        //===========
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('.pagination a').forEach(link => {
                console.log(link.href);
            });

            // Monitor clicks on pagination links
            document.addEventListener('click', function(e) {
                if (e.target.closest('.pagination a')) {
                    e.preventDefault();
                    const link = e.target.closest('.pagination a');
                    window.location.href = link.getAttribute('href');
                }
            });
        });
    </script>
@stop