@section('title', 'Report-Maintenance Overview')
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
                            <div class="col-12 col-lg-6 border-end">
                                <div class="d-flex justify-content-between mb-3 mb-lg-0">
                                    <div class="d-flex align-items-center">
                                        <div class="table-circular-icon bg-F0F5F6 me-3" style="cursor: pointer">
                                            <i class="fa-solid fa-arrows-rotate"></i>
                                        </div>
                                        <span class="text-0D161A fw-semibold me-1">{{$maintenanceOverview->total()
                                            }}</span>
                                        <span class="text-445B64 fw-medium">Items</span>
                                    </div>
                                </div>
                            </div>

                            <div class="col-12 col-lg-6 d-flex align-items-center">
                                <!-- {{-- filter --}} -->
                                @php $unit = request('unit'); @endphp
                                <form method="GET" action="{{ url()->current() }}">
                                    <div class="row">
                                        <div class="col-12 col-xl-10">
                                            <div class="pe-0 pe-lg-4">
                                                <h6 class="text-445B64 fs-14"> Filter: From Date - To Date & Unit </h6>
                                                <div class="row">
                                                    <input type="hidden" id="page" value="{{ request('page', 1) }}">

                                                    <input type="hidden" id="searchInput"
                                                        search-url="{{ route('admin.maintenance-history-page') }}">
                                                    <div class="col-12 col-lg-4 d-flex align-items-center mb-lg-0 mb-3">
                                                        <input type="date" name="from_date" class="form-control"
                                                            required id="from_date"
                                                            value="{{ request('from_date') ?? \Carbon\Carbon::parse($fromDate)->format('Y-m-d') }}">
                                                    </div>
                                                    <div class="col-12 col-lg-4 d-flex align-items-center mb-lg-0 mb-3">
                                                        <input type="date" name="to_date" class="form-control" required
                                                            id="to_date"
                                                            value="{{request('to_date') ?? \Carbon\Carbon::parse($toDate)->format('Y-m-d') }}">
                                                    </div>
                                                    <div class="col-12 col-lg-4 d-flex align-items-center mb-lg-0 mb-3">
                                                        <select class="form-control @error('unit') is-invalid @enderror"
                                                            id="unit" name="unit">
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
                                    <h6 class="text-445B64 fw-semibold mb-0"> Maintenance Overview </h6>
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
                                                <th scope="col" class="text-6C7D83 py-2 px-3"> Date </th>
                                                <th scope="col" class="text-6C7D83 py-2 px-3"> Unit </th>
                                                <th scope="col" class="text-6C7D83 py-2 px-3"> Maintenance Type
                                                </th>
                                                <th scope="col" class="text-6C7D83 py-2 px-3"> Total Downtime
                                                    Duration</th>
                                            </tr>
                                        </thead>
                                        <tbody id="myTableBody">
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
                                    <br>
                                    <!-- Pagination Section -->
                                    <div class="paginationSO d-flex justify-content-center" id="paginationSOLinks">
                                        {{ $maintenanceOverview->links() }}
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