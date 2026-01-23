@section('title', 'Machine Details')
@extends('layouts.app')
@section('content')

<style>
    @import url(https://fonts.googleapis.com/css?family=Roboto);

    body {
        font-family: Roboto, sans-serif;
    }

    #chart {
        max-width: 100%;
        margin: 35px auto;
    }

    @import url(https://fonts.googleapis.com/css?family=Roboto);

    body {
        font-family: Roboto, sans-serif;
    }

    #radialChart {
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

<div class="main-content-area">

    <div class="">
        <h5 class="text-0077B3 fw-semibold mt-5 ms-3"> <i class="fa-solid fa-long-arrow-left me-1 text-637381"></i>
            Machines /
            <a class="text-637381" style="text-decoration:none;" href="{{ route('admin.machines') }}">
                {{ $machine->machine}} # {{ $machine->id }}</a>
        </h5>
    </div>

    <div class="main-content machineDetail-page">
        <div class="row">
            <div class="col-12 d-block d-xl-flex mb-4">
                <div class="card qr-card border-0 rounded-3 mb-1 me-1">
                    <div class="card-body">
                        <div class="">
                            <div class="position-relative p-2 mx-auto" style="width: fit-content">
                                <img src="{{ $machine->machine_image }}" alt="" class="rounded-2"
                                    style="width: 129px; height: 144px;">
                                <span class="bg-white position-absolute p-2 rounded-2 img-zoom"
                                    style="left: 49px; bottom: -15px;">
                                    <img src="{{ $machine->machine_qr_code }}" style="width: 40px; height:'40px;">
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
                                    {{ $machine->machine }} #{{ $machine->id }}
                                </h5>
                                <p class="text-445B64"> {{ $machine->machine_type }} </p>
                            </div>
                            <div class="d-flex align-items-center">
                                <select id="machineStatus" class="form-select form-select-sm w-auto"
                                    onchange="updateMachineStatus(this)" data-machine-id="{{ $machine->id }}">
                                    <option value="active" {{ $machine->machine_status == 'active' ? 'selected' : '' }}>
                                        Active</option>
                                    <option value="maintenance" {{ $machine->machine_status == 'maintenance' ?
                                        'selected' : '' }}>
                                        Maintenance
                                    </option>
                                    <option value="in-working" {{ $machine->machine_status == 'in-working' ? 'selected'
                                        : '' }}>
                                        In Working
                                    </option>
                                    <option value="breakdown" {{ $machine->machine_status == 'breakdown' ? 'selected' :
                                        '' }}>
                                        Breakdown
                                    </option>
                                </select>

                                <img id="statusIcon" src="{{ asset('assets/images/completeDot.png') }}" alt=""
                                    class="ms-2" style="width: 20px; height: 20px;">
                            </div>
                        </div>

                        <div class="row mb-1">
                            <div class="col-12 col-md-6">
                                <h6 class="text-445B64 fs-14 me-2">Description</h6>
                                <p class="text-0D161A fs-14 lh-1 fw-semibold">
                                    {{ $machine->section }}-{{ $machine->machine_type }}
                                </p>
                            </div>
                            <div class="col-12 col-md-6">
                                <h6 class="text-445B64 fs-14 me-2">Department</h6>
                                <p class="text-0D161A fs-14 lh-1 fw-semibold">
                                    {{ $machine->unit_name }}
                                </p>
                            </div>
                            <div class="col-12">
                                <h6 class="text-445B64 fs-14 me-2">Operations</h6>
                                <div class="">
                                    @foreach ($operations as $operation)
                                    <button class="btn btn-skyBlue btn-sm mb-2 rounded-2">{{
                                        $operation->operation->operation_name }}</button>
                                    &nbsp;
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12">
                <div class="row mb-3">
                    <div class="col-12 col-lg-7 mb-lg-0 mb-3">
                        <div class="card border-0 rounded-4">
                            <div class="card-body p-4">
                                <div class="d-flex d-lg-block flex-wrap">
                                    <div class="d-flex gap-md-3 gap-lg-4 justify-content-around">
                                        <div class="">
                                            <div class="pe-4">
                                                <h6 class="text-445B64 fs-14"> Machine Actual Run Time</h6>
                                                <h5 class="text-0D161A mb-0 fw-bolder"> {{ $machineActualRunTime ?
                                                    round((float) $machineActualRunTime / 60) : 0 }} </h5>
                                            </div>
                                        </div>
                                        <hr class="vertcal-hr">
                                        <div class="">
                                            <div class="border-right-mobile-0 pe-4">
                                                <h6 class="text-445B64 fs-14">Machine Downtime</h6>
                                                <h5 class="text-0D161A mb-0 fw-bolder">
                                                    {{ $machineDowntime ?? '0' }}
                                                </h5>
                                            </div>
                                        </div>
                                        <hr class="vertcal-hr">
                                        <div class="">
                                            <div class="pe-4">
                                                <h6 class="text-445B64 fs-14">Machine Production</h6>
                                                <h5 class="text-0D161A mb-0 fw-bolder">{{ array_sum($count7Days) }}</h5>
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
                                        <div class="col-12 col-xl-7">
                                            <div class="pe-0 pe-lg-4 ">
                                                <h6 class="text-445B64 fs-14">Filter: From Date - To Date</h6>
                                                <div class="row">
                                                    <div class="col-12 col-lg-6 d-flex align-items-center mb-lg-0 mb-3">
                                                        <input type="date" name="from_date" class="form-control"
                                                            required
                                                            value="{{ request('from_date') ?? \Carbon\Carbon::parse($fromDate)->format('Y-m-d') }}">
                                                    </div>
                                                    <div class="col-12 col-lg-6 d-flex align-items-center mb-lg-0 mb-3">
                                                        <input type="date" name="to_date" class="form-control" required
                                                            value="{{ request('to_date') ?? \Carbon\Carbon::parse($toDate)->format('Y-m-d') }}">
                                                    </div>

                                                </div>
                                            </div>
                                        </div>
                                        <hr class="col-1 vertcal-hr d-none d-xl-block border-top-0">
                                        <div class="col-12 col-xl-4 d-flex align-items-center">
                                            <button type="submit"
                                                class="btn btn-skyBlue btn-sm rounded-2 me-2">Submit</button>
                                            <a href="{{ url()->current() }}" class="btn btn-skyBlue btn-sm rounded-2">
                                                <i class="fa-solid fa-arrow-rotate-right"></i>
                                            </a>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-12 col-xl-7 mb-4">
                    <div class="card bg-white rounded-4 border-0">
                        <div class="card-body">
                            <h6 class="text-445B64">
                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="16" viewBox="0 0 12 14"
                                    fill="none" class="me-1">
                                    <path
                                        d="M2 13.6668C1.44444 13.6668 0.972222 13.4724 0.583333 13.0835C0.194444 12.6946 0 12.2224 0 11.6668V9.66683H2V0.333496H12V11.6668C12 12.2224 11.8056 12.6946 11.4167 13.0835C11.0278 13.4724 10.5556 13.6668 10 13.6668H2ZM10 12.3335C10.1889 12.3335 10.3472 12.2696 10.475 12.1418C10.6028 12.0141 10.6667 11.8557 10.6667 11.6668V1.66683H3.33333V9.66683H9.33333V11.6668C9.33333 11.8557 9.39722 12.0141 9.525 12.1418C9.65278 12.2696 9.81111 12.3335 10 12.3335ZM4 5.00016V3.66683H10V5.00016H4ZM4 7.00016V5.66683H10V7.00016H4Z"
                                        fill="#445B64" />
                                </svg> Production <i class="fa-solid fa-angle-right ms-2"></i>
                            </h6>
                            <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>

                            <div class="">
                                <div id="chart" class="my-0"></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-xl-5 mb-4">
                    <div class="card bg-white rounded-4 border-0 h-100">
                        <div class="card-body">
                            <h6 class="text-445B64">
                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="16" viewBox="0 0 12 14"
                                    fill="none" class="me-1">
                                    <path
                                        d="M2 13.6668C1.44444 13.6668 0.972222 13.4724 0.583333 13.0835C0.194444 12.6946 0 12.2224 0 11.6668V9.66683H2V0.333496H12V11.6668C12 12.2224 11.8056 12.6946 11.4167 13.0835C11.0278 13.4724 10.5556 13.6668 10 13.6668H2ZM10 12.3335C10.1889 12.3335 10.3472 12.2696 10.475 12.1418C10.6028 12.0141 10.6667 11.8557 10.6667 11.6668V1.66683H3.33333V9.66683H9.33333V11.6668C9.33333 11.8557 9.39722 12.0141 9.525 12.1418C9.65278 12.2696 9.81111 12.3335 10 12.3335ZM4 5.00016V3.66683H10V5.00016H4ZM4 7.00016V5.66683H10V7.00016H4Z"
                                        fill="#445B64" />
                                </svg> Machine <i class="fa-solid fa-angle-right ms-2"></i>
                            </h6>
                            <div class="row">
                                <div class="col-12 col-lg-7">
                                    <div class="">
                                        <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>

                                        <div class="">
                                            <div id="radialChart" class="my-0"></div>
                                        </div>
                                        <script>
                                            var options = {
                                                    series: [{{ $utilization }}, {{ $oeeRuntime }}, {{ $downtime }}],
                                                    chart: {
                                                        height: 250,
                                                        type: 'radialBar',
                                                    },
                                                    colors: ['#00B200', '#FFBF00', '#CC2200'],
                                                    plotOptions: {
                                                        radialBar: {
                                                            dataLabels: {
                                                                name: {
                                                                    fontSize: '13px',
                                                                    textWrap: 'wrap'
                                                                },
                                                                value: {
                                                                    fontSize: '16px',
                                                                }
                                                            },
                                                            track: {
                                                                background: '#FFFFFF', // Optional: Change track background color
                                                            },
                                                        }
                                                    },
                                                    labels: ['Utilization', 'Runtime (OEE)', 'Downtime'],
                                                };
                                                var chart = new ApexCharts(document.querySelector("#radialChart"), options);
                                                chart.render();
                                        </script>
                                    </div>
                                </div>

                                {{-- // Machine circular graph --}}
                                <div class="col-12 col-lg-5 d-flex align-items-center">
                                    <div class="w-100">
                                        <div class="radialChart-dot bg-00B200 rounded-circle mb-2"
                                            style="width:16px; height:16px;"></div>
                                        <div class="d-flex justify-content-between align-items-center mb-3">
                                            <h6 class="text-445B64 fs-14"> Utilization </h6>
                                            <h6 class="text-0D161A fw-semibold"> {{ $utilization }}%</h6>
                                        </div>
                                        <div class="radialChart-dot bg-FFBF00 rounded-circle mb-2"
                                            style="width:16px; height:16px;"></div>
                                        <div class="d-flex justify-content-between align-items-center mb-3">
                                            <h6 class="text-445B64 fs-14">Runtime (OEE)</h6>
                                            <h6 class="text-0D161A fw-semibold">{{ $oeeRuntime }}%</h6>
                                        </div>
                                        <div class="radialChart-dot bg-CC2200 rounded-circle mb-2"
                                            style="width:16px; height:16px;"></div>
                                        <div class="d-flex justify-content-between align-items-center">
                                            <h6 class="text-445B64 fs-14"> Downtime</h6>
                                            <h6 class="text-0D161A fw-semibold">{{ $downtime }}%</h6>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

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
                                                        <th scope="col" class="text-6C7D83 py-2 px-3 fw-medium"> SO No.
                                                        </th>
                                                        <th scope="col" class="text-6C7D83 py-2 px-3 fw-medium"> Product
                                                        </th>
                                                        <th scope="col" class="text-6C7D83 py-2 px-3 fw-medium">
                                                            Sub-Product </th>
                                                        <th scope="col" class="text-6C7D83 py-2 px-3 fw-medium"> Pass
                                                            Item </th>
                                                        <th scope="col" class="text-6C7D83 py-2 px-3 fw-medium"> Start
                                                            Date </th>
                                                        <th scope="col" class="text-6C7D83 py-2 px-3 fw-medium"> End
                                                            Date </th>
                                                        <th scope="col" class="text-6C7D83 py-2 px-3 fw-medium"> Process
                                                        </th>
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
                                                        <td class="text-445B64 p-3">
                                                            <div class="d-flex align-items-center">
                                                                <span class="table-square-icon bg-F0F5F6 rounded-3"
                                                                    style="font-size: 12px; font-weight: 500;">
                                                                    <svg xmlns="http://www.w3.org/2000/svg" width="14"
                                                                        height="14" viewBox="0 0 12 12" fill="none">
                                                                        <path
                                                                            d="M6.66667 12V10.6667H8V12H6.66667ZM5.33333 10.6667V7.33333H6.66667V10.6667H5.33333ZM10.6667 8.66667V6H12V8.66667H10.6667ZM9.33333 6V4.66667H10.6667V6H9.33333ZM1.33333 7.33333V6H2.66667V7.33333H1.33333ZM0 6V4.66667H1.33333V6H0ZM6 1.33333V0H7.33333V1.33333H6ZM1 3H3V1H1V3ZM0 4V0H4V4H0ZM1 11H3V9H1V11ZM0 12V8H4V12H0ZM9 3H11V1H9V3ZM8 4V0H12V4H8ZM9.33333 12V10H8V8.66667H10.6667V10.6667H12V12H9.33333ZM6.66667 7.33333V6H9.33333V7.33333H6.66667ZM4 7.33333V6H2.66667V4.66667H6.66667V6H5.33333V7.33333H4ZM4.66667 4V1.33333H6V2.66667H7.33333V4H4.66667ZM1.5 2.5V1.5H2.5V2.5H1.5ZM1.5 10.5V9.5H2.5V10.5H1.5ZM9.5 2.5V1.5H10.5V2.5H9.5Z"
                                                                            fill="#445B64" />
                                                                    </svg>
                                                                </span>
                                                                <h6 class="text-445B64 mb-0"> {{
                                                                    $history->soProduct->so_no }} </h6>
                                                            </div>
                                                        </td>

                                                        <td class="text-445B64 p-3"> {{
                                                            optional($history->product)->product_modified_name ?? '-' }}
                                                        </td>

                                                        <td class="text-445B64 p-3"> {{
                                                            $history->subProduct->sub_product_name }} </td>

                                                        <td class="text-445B64 p-3"> {{
                                                            optional($history->pass)->pass_no ?? '-' }} </td>

                                                        <td scope="row" class="p-3">
                                                            <div class="">
                                                                <h6 class="text-445B64 mb-0 fw-medium"> {{
                                                                    \Carbon\Carbon::parse($history->start_date)->format('Y-m-d')
                                                                    }} </h6>
                                                            </div>
                                                        </td>
                                                        <td scope="row" class="p-3">
                                                            <div class="">
                                                                <h6 class="text-445B64 mb-0 fw-medium"> {{
                                                                    \Carbon\Carbon::parse($history->end_date)->format('Y-m-d')
                                                                    }} </h6>
                                                            </div>
                                                        </td>
                                                        <td class="text-445B64 p-3">
                                                            {{ $history->operation->operation_name }}</td>
                                                        <td class="text-445B64 p-3">
                                                            {{ round(($history->time_taken_minutes) / 60, 2) }} in Mins
                                                            <br>
                                                            {{ (($history->time_taken_minutes) ) }} in Seconds
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

            <div class="col-12 col-xl-12">
                <div class="row">
                    <div class="col-12">
                        <div class="card border-0 rounded-4 mb-4 overflow-hidden">
                            <div class="card-header p-3 bg-white border-bottom">
                                <div class="row align-items-center">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div class="">
                                            <h6 class="text-445B64 fw-semibold mb-0"> Maintenance & Breakdown </h6>
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
                                                        <th scope="col" class="text-6C7D83 py-2 px-3 fw-medium">
                                                            Date </th>
                                                        <th scope="col" class="text-6C7D83 py-2 px-3 fw-medium">
                                                            Type </th>
                                                        <th scope="col" class="text-6C7D83 py-2 px-3 fw-medium">
                                                            Start Time </th>
                                                        <th scope="col" class="text-6C7D83 py-2 px-3 fw-medium">
                                                            End Time </th>
                                                        <th scope="col" class="text-6C7D83 py-2 px-3 fw-medium">
                                                            Duration </th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @if (sizeof($breakdowntime) > 0)
                                                    @foreach ($breakdowntime as $breakMain)
                                                    <tr>
                                                        <td class="text-445B64 p-3">
                                                            <div class="d-flexv align-items-center">
                                                                <h6 class="text-445B64 mb-0">
                                                                    {{ $breakMain->start_date_time ?
                                                                    \Carbon\Carbon::parse($breakMain->start_date_time)->format('d
                                                                    M Y') : 'NA' }}
                                                                </h6>
                                                            </div>
                                                        </td>

                                                        <td scope="row" class="p-3">
                                                            <div class="">
                                                                <h6 class="text-6C7D83 mb-0 fs-13">
                                                                    Maintenance</h6>
                                                            </div>
                                                        </td>

                                                        <!-- start time (fixed: use i for minutes) -->
                                                        <td class="text-445B64 p-3">
                                                            <div class="">
                                                                <h6 class="text-6C7D83 mb-0 fs-13">
                                                                    {{ $breakMain->start_date_time ?
                                                                    \Carbon\Carbon::parse($breakMain->start_date_time)->format('h:i
                                                                    a') : 'NA' }}
                                                                </h6>
                                                            </div>
                                                        </td>

                                                        <!-- end time -->
                                                        <td class="text-445B64 p-3">
                                                            {{ $breakMain->end_date_time ?
                                                            \Carbon\Carbon::parse($breakMain->end_date_time)->format('h:i
                                                            a') : 'NA' }}
                                                        </td>

                                                        <!-- diff column -->
                                                        <td class="text-445B64 p-3">
                                                            @php
                                                            if (
                                                            $breakMain->start_date_time &&
                                                            $breakMain->end_date_time
                                                            ) {
                                                            $start = \Carbon\Carbon::parse(
                                                            $breakMain->start_date_time,
                                                            );
                                                            $end = \Carbon\Carbon::parse(
                                                            $breakMain->end_date_time,
                                                            );
                                                            $seconds = $end->diffInSeconds($start);

                                                            // Option A: human parts (e.g. "1h 24m 5s")
                                                            $h = intdiv($seconds, 3600);
                                                            $m = intdiv($seconds % 3600, 60);
                                                            $s = $seconds % 60;
                                                            $parts = [];
                                                            if ($h) {
                                                            $parts[] = $h . 'h';
                                                            }
                                                            if ($m) {
                                                            $parts[] = $m . 'm';
                                                            }
                                                            if ($s) {
                                                            $parts[] = $s . 's';
                                                            }
                                                            $diffPretty = $parts
                                                            ? implode(' ', $parts)
                                                            : '0s';

                                                            // Option B: HH:MM:SS (unlimited hours)
                                                            $hoursFull = floor($seconds / 3600);
                                                            $minutesFull = floor(
                                                            ($seconds % 3600) / 60,
                                                            );
                                                            $secondsFull = $seconds % 60;
                                                            $diffHMS = sprintf(
                                                            '%02d:%02d:%02d',
                                                            $hoursFull,
                                                            $minutesFull,
                                                            $secondsFull,
                                                            );

                                                            // choose which to display:
                                                            $diffToShow = $diffPretty; // or use $diffHMS
                                                            } else {
                                                            $diffToShow = 'NA';
                                                            }
                                                            @endphp

                                                            {{ $diffToShow }}
                                                        </td>
                                                    </tr>
                                                    @endforeach
                                                    @else
                                                    <tr>
                                                        <td colspan="5">
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
</div>
<script>
    function updateMachineStatus(select) {

            const newStatus = select.value;
            const machineId = $(select).data('machine-id'); // assuming you passed data-machine-id
            const oldValue = select.dataset.oldValue; // MUST exist now
 
            $.ajax({
                url: '{{ route('admin.update-machine-status') }}',
                method: 'POST',
                data: {
                    machine_id: machineId,
                    status: newStatus,
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    console.log(response.message);

                    // Update icon
                    let icon = $('#statusIcon');
                    switch (newStatus) {
                        case 'active':
                            icon.attr('src', '{{ asset('assets/images/completeDot.png') }}');
                            break;
                        case 'maintenance':
                            icon.attr('src', '{{ asset('assets/images/notCompleteDot.png') }}');
                            break;
                        case 'in-working':
                            icon.attr('src', '{{ asset('assets/images/progressDot.png') }}');
                            break;
                        case 'breakdown':
                            icon.attr('src', '{{ asset('assets/images/notCompleteDot.png') }}');
                            break;
                    }
                },
                error: function(xhr) {
                     let res;
                        try {
                            res = xhr.responseJSON || JSON.parse(xhr.responseText);
                            alert(res.message || "Something went wrong");
                        } catch (e) {
                            alert("Something went wrong");
                            console.error("Non-JSON response:", xhr.responseText);
                        }
                            setTimeout(() => {
                            window.location.reload();
                            }, 2000);

                            // select.value = oldValue;

                }
            });
        }

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
        var chart = new ApexCharts(document.querySelector("#chart"), options);
        chart.render();
</script>

@stop