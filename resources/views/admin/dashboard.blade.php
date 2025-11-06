@section('title', 'Dashboard')
@extends('layouts.app')
@section('content')
    <style>
        #radialChart .apexcharts-radial-series path {
            stroke-linecap: round !important;
            /* Force rounded ends */
        }

        @import url(https://fonts.googleapis.com/css?family=Roboto);

        body {
            font-family: Roboto, sans-serif;
        }

        #chart {
            max-width: 100%;
            margin: 35px auto;
        }

        #lineChart {
            max-width: 100%;
            margin: 35px auto;
        }

        .vertcal-hr {
            border-left: 1px solid hsla(200, 10%, 50%, 100);
            height: 62px;
            width: 1px;
            margin: 0;
        }
    </style>
    <!-- Main Content Area Start -->
    <div class="main-content-area">
        <div class="main-content">

            <!-- Status Container Start -->
            <div class="">

                <div class="col-12">
                    <div class="row mb-3">
                        <div class="col-12 col-lg-7 mb-lg-0 mb-3">
                            <div class="card border-0 rounded-4">
                                <div class="card-body p-4">
                                    <div class="d-flex d-lg-block flex-wrap">
                                        <div class="d-flex gap-md-3 gap-lg-4 justify-content-around">
                                            <div class="">
                                                <div class="pe-4">
                                                    <h6 class="text-445B64 fs-14"> Total Sale Order </h6>
                                                    <h5 class="text-0D161A mb-0 fw-bolder"> {{ $sales_order }} </h5>
                                                    <h6 class="fs-14 text-445B64 mb-0"><span class="fw-semibold"> </span>
                                                    </h6>
                                                </div>
                                            </div>
                                            <hr class="vertcal-hr">
                                            <div class="">
                                                <div class="border-right-mobile-0 pe-4">
                                                    <h6 class="text-445B64 fs-14"> Sale Order Completed </h6>
                                                    <h5 class="text-0D161A mb-0 fw-bolder"> {{ $completedSO ?? 0 }} </h5>
                                                    <h6 class="fs-14 text-445B64 mb-0"><span class="fw-semibold"> </span>
                                                    </h6>
                                                </div>
                                            </div>
                                            <hr class="vertcal-hr">
                                            <div class="">
                                                <div class="pe-4">
                                                    <h6 class="text-445B64 fs-14"> Total Machine Downtime </h6>
                                                    <h5 class="text-0D161A mb-0 fw-bolder"> {{ $machineDowntime ?? 0 }}
                                                    </h5>
                                                    <h6 class="fs-14 text-445B64 mb-0"><span class="fw-semibold"> </span>
                                                    </h6>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-lg-5">
                            <div class="card border-0 rounded-4">
                                <div class="card-body p-4">
                                    <form method="GET" action="{{ url()->current() }}">
                                        <div class="row">
                                            <div class="col-12 col-xl-9">
                                                <div class="pe-0 pe-lg-4">
                                                    <h6 class="text-445B64 fs-14"> Filter: From Date - To Date & Unit </h6>
                                                    <div class="row">
                                                        <div class="col-12 col-lg-4 d-flex align-items-center mb-lg-0 mb-3">
                                                            <input type="date" name="from_date" class="form-control" required value="{{ request('from_date') ?? \Carbon\Carbon::parse($fromDate)->format('Y-m-d') }}">
                                                        </div>
                                                        <div class="col-12 col-lg-4 d-flex align-items-center mb-lg-0 mb-3">
                                                            <input type="date" name="to_date" class="form-control" required value="{{ request('to_date') ?? \Carbon\Carbon::parse($toDate)->format('Y-m-d') }}">
                                                        </div>
                                                        <div class="col-12 col-lg-4 d-flex align-items-center mb-lg-0 mb-3">
                                                            <select class="form-control @error('unit') is-invalid @enderror"
                                                                name="unit">
                                                                <option value=""> All Unit </option>
                                                                <option value="Tooling"
                                                                    @if (request('unit') == 'Tooling') {{ 'selected' }} @endif>
                                                                    Tooling </option>
                                                                <option value="RMR"
                                                                    @if (request('unit') == 'RMR') {{ 'selected' }} @endif>
                                                                    RMR </option>
                                                                <option value="TMR"
                                                                    @if (request('unit') == 'TMR') {{ 'selected' }} @endif>
                                                                    TMR </option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-12 col-xl-3 d-flex align-items-center mt-4">
                                                <button type="submit"
                                                    class="btn btn-skyBlue btn-sm rounded-2 me-2">Submit</button>
                                                <a href="{{ url()->current() }}" class="btn btn-skyBlue btn-sm rounded-2"
                                                    title="Reset"><i class="fa-solid fa-arrow-rotate-right"></i> </a>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-12 col-xl-12 mb-4">
                        <div class="card bg-white rounded-4 border-0">
                            <div class="card-body">
                                <h6 class="text-445B64">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="16"
                                        viewBox="0 0 12 14" fill="none" class="me-1">
                                        <path
                                            d="M2 13.6668C1.44444 13.6668 0.972222 13.4724 0.583333 13.0835C0.194444 12.6946 0 12.2224 0 11.6668V9.66683H2V0.333496H12V11.6668C12 12.2224 11.8056 12.6946 11.4167 13.0835C11.0278 13.4724 10.5556 13.6668 10 13.6668H2ZM10 12.3335C10.1889 12.3335 10.3472 12.2696 10.475 12.1418C10.6028 12.0141 10.6667 11.8557 10.6667 11.6668V1.66683H3.33333V9.66683H9.33333V11.6668C9.33333 11.8557 9.39722 12.0141 9.525 12.1418C9.65278 12.2696 9.81111 12.3335 10 12.3335ZM4 5.00016V3.66683H10V5.00016H4ZM4 7.00016V5.66683H10V7.00016H4Z"
                                            fill="#445B64" />
                                    </svg>
                                    Production
                                    <i class="fa-solid fa-angle-right ms-2"></i>
                                </h6>
                                <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>

                                <div class="">
                                    <div id="chart" class="my-0"></div>
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
                                            name: 'Total Production',
                                            data: @json($production) // Inject Laravel data directly
                                        }],
                                        xaxis: {
                                            categories: days
                                        }
                                    }

                                    var chart = new ApexCharts(document.querySelector("#chart"), options);
                                    chart.render();
                                </script>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-12 col-xl-12 mb-4">
                        <div class="card bg-white rounded-4 border-0 h-100">
                            <div class="card-body">
                                <h6 class="text-445B64">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="16"
                                        viewBox="0 0 12 14" fill="none" class="me-1">
                                        <path
                                            d="M2 13.6668C1.44444 13.6668 0.972222 13.4724 0.583333 13.0835C0.194444 12.6946 0 12.2224 0 11.6668V9.66683H2V0.333496H12V11.6668C12 12.2224 11.8056 12.6946 11.4167 13.0835C11.0278 13.4724 10.5556 13.6668 10 13.6668H2ZM10 12.3335C10.1889 12.3335 10.3472 12.2696 10.475 12.1418C10.6028 12.0141 10.6667 11.8557 10.6667 11.6668V1.66683H3.33333V9.66683H9.33333V11.6668C9.33333 11.8557 9.39722 12.0141 9.525 12.1418C9.65278 12.2696 9.81111 12.3335 10 12.3335ZM4 5.00016V3.66683H10V5.00016H4ZM4 7.00016V5.66683H10V7.00016H4Z"
                                            fill="#445B64" />
                                    </svg> Breakdown & Maintenance <i class="fa-solid fa-angle-right ms-2"></i>
                                </h6>
                                <div class="">
                                    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>

                                    <div class="">
                                        <div id="lineChart" class="my-0"></div>
                                    </div>
                                    <script>
                                        var days = @json($labels);
                                        var values = @json($values);

                                        var options = {
                                            chart: {
                                                type: 'bar',
                                                height: 235
                                            },
                                            series: [{
                                                name: 'Total Downtime (Minutes)',
                                                data: values
                                            }],
                                            xaxis: {
                                                categories: days
                                            }
                                        }

                                        var chart = new ApexCharts(document.querySelector("#lineChart"), options);
                                        chart.render();
                                    </script>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>
    <!-- Main Content Area End -->
@stop
