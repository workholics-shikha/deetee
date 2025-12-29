@section('title', 'Operator Details')
@extends('layouts.app')
@section('content')

    <div class="main-content-area">
        <div class="">
            <h5 class="text-0077B3 fw-semibold mt-5 ms-3"> <i class="fa-solid fa-long-arrow-left me-1 text-637381"></i>
                Operators /
                <a class="text-637381" style="text-decoration:none;" href="{{ route('admin.operators') }}">
                    {{ $user_data->name }} # {{ $user_data->id }}</a>
            </h5>
        </div>

        <div class="main-content operatorDetail-page">
            <div class="row">
                <div class="col-12 d-block d-xl-flex mb-4">

                    <div class="card qr-card border-0 rounded-3 mb-1 me-1">
                        <div class="card-body">
                            <div class="">
                                <div class="position-relative p-3 mx-auto" style="width: fit-content">
                                    <img src="{{ $user_data->profile_image }}"
                                        style="width: 113px; height: 113px; border-radius: 80px;">
                                    <span class="bg-white position-absolute p-1 rounded-2"
                                        style="left: 49px; bottom: -4px;">
                                        <img src="{{ $user_data->user_qr_code }}" style="width: 40px; height:'40px;">
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card border-0 rounded-3 mb-1 w-100">
                        <div class="card-body p-4 pb-0">
                            <div class="d-flex align-items-start justify-content-between border-bottom mb-3">
                                <div class="">
                                    <h5 class="text-0D161A fw-semibold mb-0">
                                        {{ $user_data->name }}
                                    </h5>
                                    <p class="text-445B64"># {{ $user_data->id }}</p>
                                </div>
                                <div class="d-flex align-items-center">
                                    <h6 class="text-00B200 mb-0 me-2">Present </h6>
                                    <img src="{{ asset('assets/images/completeDot.png') }}"
                                        style="width: 20px; height: 20px;">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12 col-md-4 col-xl-3 mb-3">
                                    <h6 class="text-445B64 mb-1">Department</h6>
                                    <h6 class="text-0D161A fw-medium">{{ $user_data->department }}</h6>
                                </div>
                                <div class="col-12 col-md-4 col-xl-3 mb-3">
                                    <h6 class="text-445B64 mb-1">Designation</h6>
                                    <h6 class="text-0D161A fw-medium">{{ $user_data->designation }}</h6>
                                </div>
                                <div class="col-12 col-md-4 col-xl-3 mb-3">
                                    <h6 class="text-445B64 mb-1">Unit</h6>
                                    <h6 class="text-0D161A fw-medium"> {{ $user_data->unit_name }}</h6>
                                </div>
                                {{-- <div class="col-12 col-md-4 col-xl-3 mb-3">
                                    <h6 class="text-445B64 mb-2">Group</h6>
                                    <h6 class="text-0D161A fw-medium">
                                        <span class="rounded-pill bg-D5FFCC text-success fw-normal px-2 py-1 ms-2"> B </span>
                                    </h6>
                                </div> --}}
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- ========== --}}

            <div class="col-12 col-xl-12">
                <div class="row">
                    <div class="col-12 col-lg-8 mb-4 h-100">
                        <div class="card bg-white rounded-4 border-0">
                            <div class="card-body">
                                <h6 class="text-445B64">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="16"
                                        viewBox="0 0 12 14" fill="none" class="me-1">
                                        <path
                                            d="M2 13.6668C1.44444 13.6668 0.972222 13.4724 0.583333 13.0835C0.194444 12.6946 0 12.2224 0 11.6668V9.66683H2V0.333496H12V11.6668C12 12.2224 11.8056 12.6946 11.4167 13.0835C11.0278 13.4724 10.5556 13.6668 10 13.6668H2ZM10 12.3335C10.1889 12.3335 10.3472 12.2696 10.475 12.1418C10.6028 12.0141 10.6667 11.8557 10.6667 11.6668V1.66683H3.33333V9.66683H9.33333V11.6668C9.33333 11.8557 9.39722 12.0141 9.525 12.1418C9.65278 12.2696 9.81111 12.3335 10 12.3335ZM4 5.00016V3.66683H10V5.00016H4ZM4 7.00016V5.66683H10V7.00016H4Z"
                                            fill="#445B64" />
                                    </svg> Production <i class="fa-solid fa-angle-right ms-2"></i>
                                </h6>
                                <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>

                                <div class="">
                                    <div id="chart1" class="my-0"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-lg-4 mb-4 h-100">
                        <div class="card border-0 rounded-4">
                            <div class="card-body">
                                <form method="GET" action="{{ url()->current() }}">
                                    <div class="row">
                                        <div class="col-12 mb-2">
                                            <div class="pe-0 pe-lg-4 ">
                                                <h6 class="text-445B64 fs-14">Filter: From Date - To Date</h6>
                                                <div class="row">
                                                    <div class="col-12 col-lg-6 d-flex align-items-center mb-lg-0 mb-3">
                                                        <input type="date" name="from_date" class="form-control" required
                                                            value="{{ request('from_date') }}">
                                                    </div>
                                                    <div class="col-12 col-lg-6 d-flex align-items-center mb-lg-0 mb-3">
                                                        <input type="date" name="to_date" class="form-control" required
                                                            value="{{ request('to_date') }}">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-12 d-flex align-items-center mb-3">
                                            <button type="submit"
                                                class="btn btn-skyBlue btn-sm rounded-2 me-2">Submit</button>
                                            <a href="{{ url()->current() }}" class="btn btn-skyBlue btn-sm rounded-2">
                                                <i class="fa-solid fa-arrow-rotate-right"></i>
                                            </a>
                                        </div>
                                        <hr>
                                        <div class="col-12 mb-2">
                                            @php
                                                $ttlOptTime = $operationalTime = $operatorEfficiency = 0;
                                                $dayCount =
                                                    isset($dayCount) && is_numeric($dayCount) && $dayCount > 0
                                                        ? $dayCount
                                                        : 1;

                                                if (!empty($soHistory) && count($soHistory) > 0) {
                                                    foreach ($soHistory as $history) {
                                                        $ttlOptTime += is_numeric($history->time_taken_minutes)
                                                            ? $history->time_taken_minutes
                                                            : 0;
                                                    }

                                                    $operationalTime = $ttlOptTime > 0 ? $ttlOptTime / 60 : 0;

                                                    $operatorEfficiency =
                                                        $dayCount > 0 && $operationalTime > 0
                                                            ? $operationalTime / (450 * $dayCount)
                                                            : 0;
                                                }
                                            @endphp

                                            <h6 class="text-445B64 fs-14">Total Operational Time (in minutes)</h6>
                                            <h4 class="text-0D161A mb-0 fw-bolder"> {{ round($operationalTime, 2) }}</h4>
                                        </div>

                                        <hr class="col-3 horizontal-hr">

                                        <div class="col-12">
                                            <h6 class="text-445B64 fs-14">Average Operator Efficiency (%)</h6>
                                            <h4 class="text-0D161A mb-0 fw-bolder mb-1">
                                                {{ round($operatorEfficiency, 2) }}%</h4>
                                        </div>

                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- SO HISTORY --}}
            <div class="col-12 col-xl-12">
                <div class="row">
                    <div class="col-12">
                        <div class="card border-0 rounded-4 mb-4 overflow-hidden">
                            <div class="card-header p-3 bg-white border-bottom">
                                <div class="row align-items-center">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div class="">
                                            <h6 class="text-445B64 fw-semibold mb-0">SO History</h6>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!------------------------------------------------->
                            <div class="card-body p-0">
                                <div class="row">
                                    <div class="col-12">
                                        <div class="table-responsive">
                                            <table class="table rounded-3 mb-0">
                                                <thead>
                                                    <tr>
                                                        <th scope="col" class="text-6C7D83 py-2 px-3 fw-medium"> Date
                                                        </th>
                                                        <th scope="col" class="text-6C7D83 py-2 px-3 fw-medium">
                                                            Machine </th>
                                                        <th scope="col" class="text-6C7D83 py-2 px-3 fw-medium"> SO No.
                                                        </th>
                                                        <th scope="col" class="text-6C7D83 py-2 px-3 fw-medium">
                                                            Product </th>
                                                        <th scope="col" class="text-6C7D83 py-2 px-3 fw-medium">
                                                            Sub-Product </th>
                                                        <th scope="col" class="text-6C7D83 py-2 px-3 fw-medium">
                                                            Pass Item </th>
                                                        <th scope="col" class="text-6C7D83 py-2 px-3 fw-medium">
                                                            Process </th>
                                                        <th scope="col" class="text-6C7D83 py-2 px-3 fw-medium">
                                                            Duration </th>
                                                        <th scope="col" class="text-6C7D83 py-2 px-3 fw-medium">
                                                            Quantity </th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @if (sizeof($soHistory) > 0)
                                                        @foreach ($soHistory as $history)
                                                            <tr>
                                                                <td scope="row" class="p-3">
                                                                    <div class="">
                                                                        <h6 class="text-445B64 mb-0 fw-medium">
                                                                            {{ \Carbon\Carbon::parse($history->end_date)->format('Y-m-d') }}
                                                                        </h6>
                                                                    </div>
                                                                </td>
                                                                <td class="text-445B64 p-3">
                                                                    {{ $history->machine->machine }} </td>

                                                                <td class="text-445B64 p-3">
                                                                    <div class="d-flex align-items-center">
                                                                        <h6 class="text-445B64 mb-0"> {{ $history->soProduct->so_no }} </h6>
                                                                    </div>
                                                                </td>
                                                                <td class="text-445B64 p-3">
                                                                    {{ $history->product->product_modified_name }} </td>
                                                                <td class="text-445B64 p-3">
                                                                    {{ $history->subProduct->sub_product_name }} </td>
                                                                <td class="text-445B64 p-3">
                                                                    {{ optional($history->pass)->pass_no ?? '-' }} </td>
                                                                <td class="text-445B64 p-3">
                                                                    {{ $history->operation->operation_name }}</td>
                                                                <td class="text-445B64 p-3">
                                                                    {{ round($history->time_taken_minutes / 60, 2) }} Mins
                                                                </td>
                                                                <td class="text-445B64 p-3">
                                                                    {{ $history->total_quantity_processed }} </td>
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
                    </div>
                </div>
            </div>
        </div>

    </div>

    <script>
        // Generate last 7 days dynamically (matches your series length)
        var days = @json($labels); // dynamic labels from PHP 
        var options = {
            chart: {
                type: 'bar',
                height: 235
            },
            series: [{
                name: 'Total production',
                data: @json($count7Days) // Inject Laravel data directly
            }],
            xaxis: {
                categories: days
            }
        }
        var chart = new ApexCharts(document.querySelector("#chart1"), options);
        chart.render();
    </script>

@stop
