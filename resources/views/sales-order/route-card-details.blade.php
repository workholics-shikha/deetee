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
                {{ $so->so_no }} / {{ $data->item_name }} /

                @if (count($getOperationList) > 0)
                    @if (!empty($pass_sheet))
                        <a class="text-637381" style="text-decoration:none;"
                            href="{{ route('admin.pass-sheet', ['id' => $getOperationList[0]->sales_order_product_id]) }}">
                            {{ $subProductName }} / {{ $pass_sheet->pass_no }} </a>
                    @else
                        <a class="text-637381" style="text-decoration:none;"
                            href="{{ route('admin.sales-order-details', ['id' => $so->id]) }}"> {{ $subProductName }} </a>
                    @endif
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
                                    <h5 class="text-0D161A fw-semibold mb-0"> {{ $subProductName }} @if (!empty($pass_sheet)) > {{ $passNo }} @endif </h5>
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

                                @endphp

                                <div class="col-12 col-md-4 col-xl-3 col-xxl-2 mb-3">
                                    <h6 class="text-445B64 mb-1">Industry</h6>
                                    <h6 class="text-0D161A fw-semibold"> {{ $so->industry }} </h6>
                                </div>

                                <div class="col-12 col-md-4 col-xl-3 col-xxl-2 mb-3">
                                    <h6 class="text-445B64 mb-1"> Group</h6>
                                    <h6 class="text-0D161A fw-semibold"> {{ $group }} </h6>
                                </div>

                            </div>

                        </div>
                    </div>
                </div>
                <div class="col-12">
                    <div class="card border-0 rounded-3 mb-1">
                        <div class="table-responsive">
                            <div class="accordion" id="accordionExample">
                                <table class="table mb-0">
                                    <thead>
                                        <tr>
                                            <th scope="col" class="text-445B64 border-right p-3" style="width: 25%;"> Stage</th>
                                            <th scope="col" class="text-445B64 border-right p-3" style="width: 45%;"> Parameter Details </th>
                                            <th scope="col" class="text-445B64" style="width: 15%;">
                                                <button class="btn btn-sm bg-F0F5F6 border rounded-3 text-0D161A fw-bolder w-100 editOperation"> Edit</button>
                                            </th>
                                            <th scope="col" class="text-445B64" style="width: 15%;">
                                                <button class="btn btn-sm bg-F0F5F6 border rounded-3 text-0D161A fw-bolder w-100"> Lock </button>
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody id="operationList">
                                        @include('sales-order.snippets.route_card_operations')
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
 
    <script>
        $(".editOperation").click(function() {
            var btn = $(this);
            if (!toggled) {
                $(".parentDiv").removeClass('disabled-div');
                $(".parentDiv input").attr('disabled', false);
                $(this).removeClass("saveOperationCycleTime");
                $(this).text('Save');
            } else {
                $(".parentDiv").addClass('disabled-div');
                $(".parentDiv input").attr('disabled', true);
                $(this).addClass("saveOperationCycleTime");
                $(this).text('Edit');
            }
            toggled = !toggled;
        })

        $(document).on("click", ".saveOperationCycleTime", function() {
            var operationData = [];
            var token = $("meta[name='csrf-token']").attr("content");

            $(".routeCardOperation").each(function() {
                var rcOperationID = $(this).data("id");
                var operationtype = $(this).data("operation_type");

                if (operationtype === "Manual") {
                    var manualCycleTime = $(this).find("input").val();
                    operationData.push({
                        id: rcOperationID,
                        operationType: operationtype,
                        cycleTime: manualCycleTime,
                        params: ""
                    });
                }
            });

            $.ajax({
                url: "{{ url('admin/updateRouteCardOperationCycleTime') }}",
                method: "POST",
                data: {
                    _token: token,
                    operations: operationData
                },
                success: function(response) {
                    successToaster(response.message);
                },
                error: function(error) {}
            });
        });
    </script>

    <script>
        $(document).ready(function() {

            // Push a new state into the history
            history.pushState(null, null, location.href);

            // Listen for back/forward navigation
            window.onpopstate = function() {
                history.pushState(null, null, location.href);
                alert("Back navigation is disabled.");
            }; // your code here
        });
    </script>
@stop
