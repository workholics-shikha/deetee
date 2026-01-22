@section('title', 'Route Card Details')
@extends('layouts.app')
@section('content')
<style>
    input[type=number]::-webkit-inner-spin-button,
    input[type=number]::-webkit-outer-spin-button {
        -webkit-appearance: none;
        margin: 0;
    }

    input[type=number] {
        -moz-appearance: textfield;
    }
</style>

<div class="main-content-area">

    <div class="">
        <h5 class="text-0077B3 fw-semibold mt-5 ms-3"> <i class="fa-solid fa-long-arrow-left me-1 text-637381"></i>
            {{ $so->so_no }} / {{ $data->item_name }} / {{ $subProductName }} /
            @if (isset($pass_sheet))
            <a class="text-637381" style="text-decoration:none;"
                href="{{ route('admin.route-card-details', [request('sprd_id'), $pass_sheet->id]) }}">
                {{ $pass_sheet->pass_no }} / {{ $getDataFromSubPwise->operation_name }} </a>
            @else
            <a class="text-637381" style="text-decoration:none;"
                href="{{ route('admin.route-card-details', ['id' => $getOperationList->sales_order_product_id]) }}">
                {{ $getDataFromSubPwise->operation_name }} </a>
            @endif
        </h5>
    </div>

    <div class="main-content saleOrdersRouteCard-page">
        <div class="row">
            <div class="col-12 d-flex">

                <div class="card qr-card border-0 rounded-3 mb-1 me-1">
                    <div class="card-body">
                        <div class="d-flex align-items-center img-zoom">
                            @if (!empty($pass_sheet))
                            <img src="{{ $pass_sheet->pass_sheet_qr_code }}" alt="Pass QR" class="w-100">
                            @php $passNo = $pass_sheet->pass_no; @endphp
                            @else
                            <img src="{{ $data->so_product_qr_code }}" alt="SO Product QR" class="w-100">
                            @php $passNo = ''; @endphp
                            @endif
                        </div>

                    </div>
                </div>

                <div class="card border-0 rounded-3 mb-1 w-100">
                    <div class="card-body pb-0">

                        <div class="d-flex align-items-center justify-content-between border-bottom pb-3 mb-3">
                            <div class="">
                                <h5 class="text-0D161A fw-semibold mb-0">
                                    {{ $subProductName }} @if (!empty($pass_sheet))
                                    > {{ $passNo }}
                                    @endif

                                </h5>
                            </div>
                            <div class="d-flex align-items-center">
                                <h6 class="text-0073E5 mb-0 me-2"> Under Progress </h6>
                                <img src="{{ asset('assets/images/progressDot.png') }}" alt="" class=""
                                    style="width: 20px; height: 20px;">
                            </div>

                        </div>

                        <div class="row">
                            <div class="col-12 col-md-4 col-xl-3 col-xxl-2 mb-3">
                                <h6 class="text-445B64 mb-1">Drawing No.</h6>
                                <h6 class="text-0D161A fw-semibold"> {{ $data->drawingno }} </h6>
                            </div>
                            <div class="col-12 col-md-4 col-xl-3 col-xxl-2 mb-3">
                                <h6 class="text-445B64 mb-1">Size Details</h6>
                                <h6 class="text-0D161A fw-semibold"> - </h6>
                            </div>
                            <div class="col-12 col-md-4 col-xl-3 col-xxl-2 mb-3">
                                <h6 class="text-445B64 mb-1">Raw Material</h6>
                                <h6 class="text-0D161A fw-semibold"> {{ $data->material }} </h6>
                            </div>
                            <div class="col-12 col-md-4 col-xl-3 col-xxl-2 mb-3">
                                <h6 class="text-445B64 mb-1">Hardness</h6>
                                <h6 class="text-0D161A fw-semibold"> {{ $data->hardness }} </h6>
                            </div>
                            <div class="col-12 col-md-4 col-xl-3 col-xxl-2 mb-3">
                                <h6 class="text-445B64 mb-1">Quantity</h6>
                                <h6 class="text-0D161A fw-semibold"> {{ $data->soquantity }} </h6>
                            </div>

                            <div class="col-12 col-md-4 col-xl-3 col-xxl-2 mb-3">
                                <h6 class="text-445B64 mb-1">Special Operation</h6>
                                <h6 class="text-0D161A fw-semibold">
                                    {{ $data->operation1 . ', ' . $data->operation2 . ', ' . $data->operation3 }}</h6>
                            </div>
                            <div class="col-12 col-md-4 col-xl-3 col-xxl-2 mb-3">
                                <h6 class="text-445B64 mb-1">Status</h6>
                                <h6 class="text-0D161A fw-semibold"> {{ $data->scr_status }} </h6>
                            </div>

                            @php
                            $parts = explode('-', $so->so_no);
                            $group = implode('-', array_slice($parts, 4));
                            $sizeVals = getSizeValue($group);
                            @endphp

                            <div class="col-12 col-md-4 col-xl-3 col-xxl-2 mb-3">
                                <h6 class="text-445B64 mb-1">Industry</h6>
                                <h6 class="text-0D161A fw-semibold"> {{ $so->industry }} </h6>
                            </div>

                            <div class="col-12 col-md-4 col-xl-3 col-xxl-2 mb-3">
                                <h6 class="text-445B64 mb-1"> Group</h6>
                                <h6 class="text-0D161A fw-semibold"> {{ $group }} </h6>
                            </div>
                            <div class="col-12 col-md-4 col-xl-3 col-xxl-2 mb-3">
                                <h6 class="text-445B64 mb-1"> {{ $sizeVals[0] }} </h6>
                                <h6 class="text-0D161A fw-semibold">
                                    {{ !empty($data->size1) ? $data->size1 : '-' }} </h6>
                            </div>
                            <div class="col-12 col-md-4 col-xl-3 col-xxl-2 mb-3">
                                <h6 class="text-445B64 mb-1"> {{ $sizeVals[1] }} </h6>
                                <h6 class="text-0D161A fw-semibold">
                                    {{ !empty($data->size2) ? $data->size2 : '-' }} </h6>
                            </div>
                            <div class="col-12 col-md-4 col-xl-3 col-xxl-2 mb-3">
                                <h6 class="text-445B64 mb-1"> {{ $sizeVals[2] }} </h6>
                                <h6 class="text-0D161A fw-semibold">
                                    {{ !empty($data->size3) ? $data->size3 : '-' }} </h6>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
            <div class="col-12">
                <div class="card border-0 rounded-3 mb-1">
                    <div class="accordion" id="accordionExample">
                        <div class="responsive-table-wrapper">

                            <table class="table mb-0 border border-1">
                                <thead>
                                    <tr>
                                        <th class="bg-F0F5F6 text-445B64 px-3 fw-normal">
                                            Roll <br> data
                                        </th>
                                        <th class="bg-F0F5F6 text-445B64 px-3 fw-normal">
                                            Operator name
                                        </th>
                                        <th class="bg-F0F5F6 text-445B64 px-3 fw-normal">
                                            Machine
                                        </th>
                                        <th class="bg-F0F5F6 text-445B64 px-3 fw-normal">
                                            Start time
                                        </th>
                                        <th class="bg-F0F5F6 text-445B64 px-3 fw-normal">
                                            End time
                                        </th>
                                        <th class="bg-F0F5F6 text-445B64 px-3 fw-normal">
                                            Time taken
                                        </th>
                                        <th class="bg-F0F5F6 text-445B64 px-3 fw-normal">
                                            Status
                                        </th>
                                        <th class="bg-F0F5F6 text-445B64 px-3 fw-normal">
                                            Action
                                        </th>
                                        <th class="bg-F0F5F6 text-445B64 px-3 fw-normal">
                                            Review
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if (!empty($getOperationData) && count($getOperationData) > 0)
                                    @foreach ($getOperationData as $operation)
                                    @php
                                    $actualCT = 0;
                                    $actualCT += $operation->time_taken;
                                   
 
                                    // Calculate total time taken for this Roll (quantity_processed)
                                    $totalTimeForRoll = $getOperationData
                                    ->where('quantity_processed', $operation->quantity_processed)
                                    ->sum('time_taken');

                                    // Format the total time (assuming time_taken is in seconds)
                                    $hours = floor($totalTimeForRoll / 3600);
                                    $minutes = floor(($totalTimeForRoll % 3600) / 60);
                                    $seconds = $totalTimeForRoll % 60;
                                    $formattedTotal = sprintf(
                                    '%02dh %02dm %02ds',
                                    $hours,
                                    $minutes,
                                    $seconds,
                                    );

                                    $idealCycleTimeMinutes = is_numeric($operation->ideal_cycle_time)
                                    ? (float) $operation->ideal_cycle_time
                                    : 0;
                                    // in minutes
                                    $actualCycleTimeMinutes = $totalTimeForRoll / 60; // in minutes

                                    $ctEfficiency = 0;

                                    if ($operation->ideal_cycle_time === 'NA') {
                                    $ctEfficiency = 'NA';
                                    } else {
                                    $idealCycleTimeMinutes = (float) $operation->ideal_cycle_time;
                                    $actualCycleTimeMinutes = (float) ($totalTimeForRoll / 60);

                                    $ctEfficiency =
                                    $actualCycleTimeMinutes > 0
                                    ? round(
                                    $idealCycleTimeMinutes / $actualCycleTimeMinutes,
                                    2,
                                    )
                                    : 0;
                                    }

                                     
                                        // Convert ideal cycle time (minutes) to seconds
                                        $idealTimeInSeconds = (float) $idealCycleTimeMinutes * 60;

                                        // Difference: + = exceeded, - = faster
                                        $gapInSeconds = (int) $totalTimeForRoll - (int) $idealTimeInSeconds;

                                        $absGap  = abs($gapInSeconds);
                                        $hours   = intdiv($absGap, 3600);
                                        $minutes = intdiv($absGap % 3600, 60);
                                        $seconds = $absGap % 60;

                                        $formattedGap = sprintf('%02d:%02d:%02d', $hours, $minutes, $seconds);

                                        if ($gapInSeconds > 0) {
                                            $differenceText = "Exceeded by $formattedGap";
                                        } elseif ($gapInSeconds < 0) {
                                            $differenceText = "Faster by $formattedGap";
                                        } else {
                                            $differenceText = "On target";
                                        }
                                    @endphp

                                        <tr>
                                            <td class="align-center p-3"
                                                style="border-left border-bottom: 1px solid transparent;">
                                                <h2 class="accordion-header d-flex justify-content-around">
                                                    <span class="fs-6 fw-semibold text-445B64">#{{
                                                        $operation->quantity_processed ?? '1' }}</span>
                                                </h2>
                                            </td>
                                            <td class="border-right">
                                                <div class="d-flex align-items-center">
                                                    <div class="">
                                                        <a class="text-0D161A fw-semibold mb-0">
                                                            {{
                                                            \App\Helpers\MyHelper::getUsername($operation->operator_id)
                                                            }}
                                                        </a>
                                                    </div>
                                                </div>
                                            </td>

                                            <td class="border-right p-3">
                                                {{ \App\Helpers\MyHelper::getMachinename($operation->machine_id) }}
                                            </td>

                                            <td class="border-right p-3">
                                                @if (!empty($operation->start_date_time))
                                                {{ \Carbon\Carbon::parse($operation->start_date_time)->format('d M y')
                                                }}
                                                <br>
                                                {{ \Carbon\Carbon::parse($operation->start_date_time)->format('g:i A')
                                                }}
                                                @else
                                                NA
                                                @endif
                                            </td>

                                            <td class="border-right p-3">
                                                @if (!empty($operation->end_date_time))
                                                {{ \Carbon\Carbon::parse($operation->end_date_time)->format('d M y') }}
                                                <br>
                                                {{ \Carbon\Carbon::parse($operation->end_date_time)->format('g:i A') }}
                                                @else
                                                NA
                                                @endif
                                            </td>

                                            <td class="border-right p-3">
                                                @if (!empty($operation->end_date_time))
                                                @php
                                                $start = \Carbon\Carbon::parse(
                                                $operation->start_date_time,
                                                );
                                                $end = \Carbon\Carbon::parse($operation->end_date_time);
                                                $diffInSeconds = $start->diffInSeconds($end);

                                                $hours = floor($diffInSeconds / 3600);
                                                $minutes = floor(($diffInSeconds % 3600) / 60);
                                                $seconds = $diffInSeconds % 60;
                                                @endphp
                                                {{ $hours }}h {{ $minutes }}m
                                                {{ $seconds }}s
                                                @else
                                                NA
                                                @endif

                                            </td>

                                            <td class="border-right p-3">
                                                <div class="d-flex">
                                                    <span class="text-00B200 mb-0 me-1">
                                                        {{ ucfirst($operation->roll_status) ?? 'pending' }} </span>
                                                    <img src="{{ asset('assets/images/completeDot.png') }}"
                                                        style="width: 20px; height: 20px;">
                                                </div>
                                            </td>

                                            <td class="border-right p-3">
                                                @if ($operation->roll_status == 'completed')
                                                <div class="dropdown">
                                                    <button
                                                        class="table-circular-icon bg-F0F5F6 mx-auto dropdown-toggle"
                                                        type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                                        <i class="fa-solid fa-ellipsis-vertical"></i>
                                                    </button>
                                                    <ul class="dropdown-menu border-0 shadow rounded-3">
                                                        <li class="trackingModal" data-type="approved"
                                                            data-id="{{ $operation->id }}">
                                                            <a class="dropdown-item" href="#">
                                                                <svg xmlns="http://www.w3.org/2000/svg" width="14"
                                                                    height="16" viewBox="0 0 10 12" fill="none"
                                                                    class="me-2">
                                                                    <path
                                                                        d="M1.63922 11.5999C1.23922 11.5999 0.899219 11.4638 0.619219 11.1916C0.339219 10.9193 0.199219 10.5888 0.199219 10.1999V5.18324C0.199219 5.00175 0.232552 4.82675 0.299219 4.65824C0.365885 4.48972 0.472552 4.33416 0.619219 4.19157L4.07922 0.827679C4.3242 0.5895 4.71423 0.589501 4.95922 0.82768C5.11922 0.983236 5.22589 1.16796 5.27922 1.38185C5.33255 1.59574 5.35922 1.81287 5.35922 2.03324L4.99922 4.13324H8.35922C8.75922 4.13324 9.09922 4.26935 9.37922 4.54157C9.65922 4.81379 9.79922 5.14435 9.79922 5.53324V5.80546C9.79922 5.88324 9.79255 5.96101 9.77922 6.03879C9.76589 6.11657 9.74589 6.18787 9.71922 6.25268L8.19922 10.6471C8.09255 10.9323 7.91589 11.1624 7.66922 11.3374C7.42255 11.5124 7.14589 11.5999 6.83922 11.5999H1.63922Z"
                                                                        fill="#445B64" />
                                                                </svg> Approved </a>
                                                        </li>
                                                        <li class="trackingModal" data-type="rework"
                                                            data-id="{{ $operation->id }}">
                                                            <a class="dropdown-item" href="#">
                                                                <svg xmlns="http://www.w3.org/2000/svg" width="18"
                                                                    height="18" viewBox="0 0 14 14" fill="none"
                                                                    class="me-2">
                                                                    <path
                                                                        d="M5.22719 8.79685V5.23304H8.78686V8.79685H5.22719ZM7.00702 13.4001C5.92923 13.4001 4.92313 13.1427 3.98872 12.6279C3.0543 12.1132 2.28057 11.4103 1.66751 10.5194V12.1528H0.599609V8.76715H3.9813V9.83629H2.51293C3.01722 10.6084 3.66241 11.2173 4.44851 11.6627C5.2346 12.1082 6.08744 12.331 7.00702 12.331C8.15403 12.331 9.18733 11.9968 10.1069 11.3286C11.0265 10.6604 11.6791 9.78679 12.0647 8.70775L13.103 8.94534C12.6778 10.2818 11.9065 11.3583 10.7892 12.175C9.67184 12.9917 8.41112 13.4001 7.00702 13.4001ZM0.629273 6.45068C0.688601 5.78741 0.841865 5.1489 1.08906 4.53513C1.33626 3.92137 1.68234 3.36205 2.1273 2.85717L2.88373 3.61448C2.55743 4.03026 2.29046 4.47574 2.08281 4.95091C1.87516 5.42608 1.74662 5.92601 1.69718 6.45068H0.629273ZM3.62533 2.85717L2.88373 2.09987C3.38802 1.65439 3.94669 1.30543 4.55975 1.053C5.1728 0.800562 5.81058 0.649595 6.47307 0.600098V1.66924C5.94901 1.72864 5.4472 1.86228 4.96763 2.07017C4.48806 2.27806 4.04063 2.54039 3.62533 2.85717ZM10.3887 2.85717C9.97342 2.53049 9.52599 2.26568 9.04642 2.06274C8.56685 1.8598 8.06504 1.72864 7.54098 1.66924V0.600098C8.21336 0.649595 8.85608 0.798087 9.46913 1.04557C10.0822 1.29306 10.6409 1.63954 11.1451 2.08502L10.3887 2.85717ZM12.3169 6.45068C12.2575 5.92601 12.1265 5.42608 11.9238 4.95091C11.7211 4.47574 11.4566 4.03026 11.1303 3.61448L11.9016 2.84232C12.3366 3.3472 12.6827 3.90652 12.9398 4.52028C13.1969 5.13405 13.3502 5.77751 13.3996 6.45068H12.3169Z"
                                                                        fill="#445B64" />
                                                                </svg>
                                                                Rework</a>
                                                        </li>
                                                        <li class="trackingModal" data-type="remarks"
                                                            data-id="{{ $operation->id }}">
                                                            <a class="dropdown-item" href="#">
                                                                <svg xmlns="http://www.w3.org/2000/svg" width="16"
                                                                    height="16" viewBox="0 0 12 12" fill="none"
                                                                    class="me-2">
                                                                    <path
                                                                        d="M1.60039 11.5999C1.27039 11.5999 0.987891 11.4824 0.752891 11.2474C0.517891 11.0124 0.400391 10.7299 0.400391 10.3999V1.5999C0.400391 1.2699 0.517891 0.987403 0.752891 0.752403C0.987891 0.517403 1.27039 0.399902 1.60039 0.399902H8.00039L11.6004 3.9999V10.3999C11.6004 10.7299 11.4829 11.0124 11.2479 11.2474C11.0129 11.4824 10.7304 11.5999 10.4004 11.5999H1.60039ZM2.80039 9.1999H9.20039V7.9999H2.80039V9.1999ZM2.80039 6.7999H9.20039V5.5999H2.80039V6.7999ZM2.80039 4.3999H7.60039V3.1999H2.80039V4.3999Z"
                                                                        fill="#445B64" />
                                                                </svg> Remarks </a>
                                                        </li>
                                                    </ul>
                                                </div>
                                                @endif
                                            </td>
                                            <td class="border-right p-3">
                                                @if ($operation->roll_status != 'pending')
                                                <button class="btn btn-primary openReviewModal" data-bs-toggle="modal"
                                                    data-bs-target="#reviewListModal" data-id="{{ $operation->id }}"> <i
                                                        class="fa fa-eye"></i>
                                                </button>
                                                @endif
                                            </td>
                                            </tr>

                                            @if ($operation->roll_status == 'completed')
                                            <tr>
                                                <td colspan="2" class="bg-F0F5F6 text-445B64 px-3 fw-normal">
                                                    Ideal Cycle Time : @if ($operation->roll_status == 'completed')
                                                    {{ $idealCycleTimeMinutes . 'm' }}
                                                    @endif
                                                </td>

                                                <td colspan="2" class="bg-F0F5F6 text-445B64 px-3 fw-normal">
                                                    Actual Cycle Time : @if ($operation->roll_status == 'completed')
                                                    {{ $formattedTotal }}
                                                    @endif
                                                </td>
                                                <td colspan="2" class="bg-F0F5F6 text-445B64 px-3 fw-normal">
                                                    GAP :
                                                    @if ($operation->roll_status == 'completed')
                                                    {{ $differenceText }}
                                                    @endif
                                                </td>
                                                <td colspan="3" class="bg-F0F5F6 text-445B64 px-3 fw-normal">
                                                    CT Efficiency : @if ($operation->roll_status == 'completed')
                                                    {{-- {{ $ctEfficiency * 100 . '%' }} --}}
                                                    {{ ($ctEfficiency === 'NA' || $ctEfficiency === null ||
                                                    $ctEfficiency === '')
                                                    ? 'NA'
                                                    : number_format(((float)$ctEfficiency) * 100, 2) . '%' }}
                                                    @endif
                                                </td>
                                            </tr>
                                            @endif
                                            @endforeach
                                            @else
                                            <tr>
                                                <td colspan="9">
                                                    <center><b>No tracking data found</b></center>
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

<!-- Review List Modal (ONLY ONE) -->
<div class="modal fade" id="reviewListModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title">Review List</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">
                <table class="table table-bordered">
                    <thead class="table-light">
                        <tr>
                            <th>Sr No</th>
                            <th>Review</th>
                            <th>Review Given Date</th>
                            <th>Review Type</th>
                        </tr>
                    </thead>
                    <tbody id="reviewTableBody">
                        <!-- Dynamic rows here -->
                    </tbody>
                </table>
            </div>

            <div class="modal-footer">
                <button class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>

        </div>
    </div>
</div>

{{-- Review Modal --}}
<div class="modal fade" id="reviewModal" tabindex="-1" role="dialog" aria-labelledby="reviewModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="reviewModalLabel">Add Review</h5>
                <button type="button" class="btn-close btn-secondary" data-bs-dismiss="modal" aria-label="Close">
                </button>
            </div>
            <form id="reviewForm" method="post" action="{{ route('admin.operation-review') }}">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <input type="hidden" name="routecardid" id="routecardid" value="{{ request()->route('id') }}">
                        <input type="hidden" name="odid" id="odid" value="">
                        <input type="hidden" name="type" id="optype" value="">
                        {{-- <label for="reviewText">Review</label> --}}
                        <textarea class="form-control" name="reviewText" id="reviewText" rows="4"
                            placeholder="Enter your review here..."></textarea>
                        <span class="text-danger" id="reviewError" style="display: none;">Please enter a
                            review.</span>
                    </div>
                    <div class="form-group">
                        <div class="unitForm">
                            <!-- Additional form elements if needed -->
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Submit Review</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    $(document).on('click', '.openReviewModal', function() {

            let id = $(this).data('id'); // Get ID from button

            $("#reviewTableBody").html(`
        <tr>
            <td colspan="4" class="text-center text-muted">
                Loading...
            </td>
        </tr>
    `);

            $.ajax({
                url: "{{ url('admin/get-reviews-list') }}/" + id,
                type: "GET",
                success: function(response) {

                    console.log(response); // Debugging line

                    let rows = "";

                    // Handle no data
                    if (!response || response.length === 0) {
                        rows = `
                    <tr>
                        <td colspan="4" class="text-center text-muted">
                            No Data Found
                        </td>
                    </tr>
                `;
                    } else {
                        response.forEach((item, index) => {
                            rows += `
                        <tr>
                            <td>${index + 1}</td>
                            <td>${item.review ?? '-'}</td>
                            <td>${item.created_at ?? '-'}</td>
                            <td>${item.review_for ?? '-'}</td>
                        </tr>
                    `;
                        });
                    }

                    $("#reviewTableBody").html(rows);
                },
                error: function() {
                    $("#reviewTableBody").html(`
                <tr>
                    <td colspan="4" class="text-center text-danger">
                        Error loading data
                    </td>
                </tr>
            `);
                }
            });

        });
</script>

<script>
    $(document).ready(function() {
            let backNavigationEnabled = true;

            function enableBackNavigation() {
                backNavigationEnabled = true;
                history.pushState(null, null, location.href);
            }

            function disableBackNavigation() {
                backNavigationEnabled = false;
            }

            // Initial state
            history.pushState(null, null, location.href);

            // Listen for back/forward navigation
            window.onpopstate = function() {
                if (backNavigationEnabled) {
                    history.pushState(null, null, location.href);
                    alert("Back navigation is disabled.");
                } else {
                    // Allow the back navigation for popup close
                    disableBackNavigation();
                }
            };

            // When opening popup - disable back navigation prevention
            $('.trackingModal').on('click', function() {
                disableBackNavigation();
            });

            // When popup closes - re-enable back navigation prevention
            $(document).on('click', '.popup-close, .overlay', function() {
                setTimeout(enableBackNavigation, 100);
            });
        });

        // Simple test version
        $(document).ready(function() {
            $('#reviewForm').on('submit', function(e) {
                e.preventDefault();

                var reviewText = $("#reviewText").val().trim();
                $("#reviewError").hide();

                // Validation
                if (reviewText === '') {
                    $("#reviewError").show();
                    return false;
                }

                // Get the form data
                var formData = $(this).serialize();

                // Add loading state
                var $submitBtn = $(this).find('button[type="submit"]');
                var originalText = $submitBtn.html();
                $submitBtn.prop('disabled', true).html(
                    '<i class="fa fa-spinner fa-spin"></i> Submitting...');

                // AJAX request
                $.ajax({
                    url: $(this).attr('action'),
                    method: 'POST',
                    data: formData,
                    success: function(response) {
                        if (response.status === 'success') {
                            successToaster(response.message);
                            $("#reviewModal").modal("hide");
                            $("#reviewForm")[0].reset();

                            setTimeout(() => {
                                location.reload();
                            }, 90);
                        } else {
                            errorToaster(response.message || 'An error occurred');
                        }
                    },
                    error: function(xhr, status, error) {
                        if (xhr.status === 422) {
                            // Laravel validation errors
                            var errors = xhr.responseJSON.errors;
                            console.log("Validation errors:", errors);

                            var errorMessage = 'Please fix the following errors: \n';
                            $.each(errors, function(field, messages) {
                                errorMessage += '• ' + messages[0] + '\n';
                            });

                            errorToaster(errorMessage);

                            // Show specific field errors if needed
                            if (errors.reviewText) {
                                $("#reviewError").text(errors.reviewText[0]).show();
                            }

                        } else {
                            errorToaster('Error: ' + xhr.status + ' - ' + xhr.statusText);
                        }
                    },
                    complete: function() {
                        // Re-enable button
                        $submitBtn.prop('disabled', false).html(originalText);
                    }
                });
            });
        });
</script>

@stop