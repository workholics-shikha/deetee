@section('title', 'Sales Order')
@extends('layouts.app')
@section('content')
    <style>
        .modal-backdrop {
            z-index: 1040 !important;
        }

        .modal {
            z-index: 1050 !important;
        }
    </style>
    <div class="main-content-area">

        <div class="">
            <h5 class="text-0077B3 fw-semibold mt-5 ms-3"> <i class="fa-solid fa-long-arrow-left me-1 text-637381"></i>
                <a class="text-637381" style="text-decoration:none;" href="{{ route('admin.sales-order') }}">
                    {{ $data->so_no }}</a>
            </h5>
        </div>

        <div class="main-content">
            <div class="row">
                <div class="col-12">
                    <div class="card border-0 rounded-3 mb-1">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-12 col-md-6 mb-3 mb-lg-0">
                                    <div class="d-flex align-items-center">
                                        <span class="table-square-icon bg-F0F5F6 rounded-3"
                                            style="width: 35px; height: 35px;">
                                            <img width="26" height="26" viewBox="0 0 12 12" fill="none"
                                                src="{{ $data->so_qr_code }}" alt="QR Code">
                                        </span>
                                        <h5 class="fw-semibold mb-0"> {{ $data->so_no }}</h5>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>

                    @php
                        $orderType = $orderType1 = $orderType2 = 'NA';
                        $parts = explode('-', $data->so_no);
                        $group = implode('-', array_slice($parts, 4));

                        if ($parts[0] == 'SE') {
                            $orderType1 = 'Export';
                        } elseif ($parts[0] == 'SD') {
                            $orderType1 = 'Domestic';
                        }

                        if ($parts[1] == 'I') {
                            $orderType2 = 'Tooling';
                        } elseif ($parts[1] == 'II') {
                            $orderType2 = 'RMR';
                        } elseif ($parts[1] == 'IV') {
                            $orderType2 = 'TMR';
                        }

                        if ($parts[2] == 'N') {
                            $orderType = 'New Order';
                        } elseif ($parts[2] == 'J') {
                            $orderType = 'Job Work Order';
                        } elseif ($parts[2] == 'R') {
                            $orderType = 'Rectification Order';
                        }

                    @endphp

                    <div class="card border-0 rounded-3">
                        <div class="card-body">
                            <div class="row mb-4">
                                <div class="col-12">
                                    <h6 class="section-heading text-005986 fw-semibold mb-3"> Sales Order Details </h6>
                                    <div class="card border rounded-4 mb-3">
                                        <div class="card-body mb-1">
                                            <div class="row">

                                                <div class="col-12 col-md-4 col-xl-3 col-xl-2 mb-3">
                                                    <h6 class="text-445B64">Customer Name</h6>
                                                    <h6 class="text-0D161A fw-semibold">{{ $data->so_customername }}</h6>
                                                </div>

                                                <div class="col-12 col-md-4 col-xl-3 col-xxl-2 mb-3">
                                                    <h6 class="text-445B64">Unit</h6>
                                                    <h6 class="text-0D161A fw-semibold">{{ $orderType2 }}</h6>
                                                </div>

                                                <div class="col-12 col-md-4 col-xl-3 col-xxl-2 mb-3">
                                                    <h6 class="text-445B64">Group</h6>
                                                    <h6 class="text-0D161A fw-semibold">{{ $group }}</h6>
                                                </div>

                                                <div class="col-12 col-md-4 col-xl-3 col-xxl-2 mb-3">
                                                    <h6 class="text-445B64">SO Date</h6>
                                                    <h6 class="text-0D161A fw-semibold"> {{ $data->so_date }} </h6>
                                                </div>

                                                <div class="col-12 col-md-4 col-xl-3 col-xl-2 mb-3">
                                                    <h6 class="text-445B64">Order Type</h6>
                                                    <h6 class="text-0D161A fw-semibold">
                                                        {{ $orderType2 . '-' . $orderType1 . '-' . $orderType }} </h6>
                                                </div>

                                                <div class="col-12 col-md-4 col-xl-3 col-xxl-2 mb-3">
                                                    <h6 class="text-445B64">Scrutiny Status</h6>
                                                    <h6 class="text-0D161A fw-semibold">{{ $data->scr_status }}</h6>
                                                </div>

                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <h6 class="section-heading text-005986 fw-semibold mb-3"> Item Details </h6>

                                @if (!empty($items))
                                    @foreach ($items as $key => $item)
                                        @php  
                                            $btnDisable = $disableDiv = '';
                                            if (
                                                $item['product_status'] != 'Available' ||
                                                $item['scr_status'] != 'Scrutinized'  
                                            ) {
                                                $btnDisable = 'disabled';
                                                $disableDiv = 'disabled-div';
                                            }

                                            $sizeVals = getSizeValue($group);
                                             
                                        @endphp

                                        <div class="col-12 col-md-6 col-xl-4 mt-1 {{ $disableDiv }}">
                                            <div class="card border rounded-4 overflow-hidden">
                                                <div class="card-header bg-F0F5F6">
                                                    <div class="d-flex align-items-center justify-content-between">
                                                        <div class="d-flex align-items-center">
                                                            <span class="table-square-icon rounded-3 img-zoom">
                                                                <img width="40" height="40" viewbox="0 0 12 12"
                                                                    fill="none" src="{{ $item['so_product_qr_code'] }}"
                                                                    alt="Machine">
                                                            </span>
                                                            <span class="ps-2"> <a
                                                                    class="text-0D161A fw-semibold mb-0 text-decoration-none">
                                                                    <strong> #0{{ $key + 1 }} &nbsp;
                                                                        {{ $item->product->erp_product }} (
                                                                        {{ $item->product->product_modified_name }}
                                                                        )</strong>
                                                                    <br>
                                                                    <span class="text-445B64" style="font-size: 12px">
                                                                        @if (!is_null($item['sub_product_id']))
                                                                            {{ getSubProductname($item['sub_product_id']) }}
                                                                        @endif
                                                                    </span> </a>
                                                            </span>
                                                        </div>
                                                        <div class="d-flex align-items-center">
                                                            <h6 class="text-0073E5 mb-0 me-2"> Under Progress </h6>
                                                            <img src="{{ asset('assets/images/progressDot.png') }}"
                                                                style="width: 20px; height: 20px;">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="card-body pt-4">
                                                    <div class="row">
                                                        <div class="col-4">
                                                            <div class="mb-4">
                                                                <h6 class="text-445B64">UQM</h6>
                                                                <h6 class="text-0D161A fw-semibold">
                                                                    {{ $item['measureunit'] }} </h6>
                                                            </div>
                                                            <div class="mb-4">
                                                                <h6 class="text-445B64">SO QTY.</h6>
                                                                <h6 class="text-0D161A fw-semibold">
                                                                    {{ $item['soquantity'] }}
                                                                </h6>
                                                            </div>
                                                            <div class="mb-4">
                                                                <h6 class="text-445B64"> {{ $sizeVals[0] }} </h6>
                                                                <h6 class="text-0D161A fw-semibold">
                                                                  {{ !empty($item['size1']) ? $item['size1'] : '-' }} </h6>
                                                            </div>
                                                        </div>

                                                        <div class="col-4 border-left border-right">
                                                            <div class="mb-4">
                                                                <h6 class="text-445B64">Material</h6>
                                                                <h6 class="text-0D161A fw-semibold">
                                                                    {{ $item['material'] }}
                                                                </h6>
                                                            </div>
                                                            <div class="mb-4">
                                                                <h6 class="text-445B64">Drawing No.</h6>
                                                                <h6 class="text-0D161A fw-semibold">
                                                                    {{ $item['drawingno'] }}
                                                                </h6>
                                                            </div>
                                                            <div class="mb-4">
                                                                <h6 class="text-445B64"> {{ $sizeVals[1] }}</h6>
                                                                <h6 class="text-0D161A fw-semibold">
                                                                    {{ !empty($item['size2']) ? $item['size2'] : '-' }}
                                                                </h6>
                                                            </div>
                                                        </div>
                                                        <div class="col-4">

                                                            <div class="mb-4">
                                                                <h6 class="text-445B64">Hardness</h6>
                                                                <h6 class="text-0D161A fw-semibold">
                                                                    {{ $item['hardness'] }}
                                                                </h6>
                                                            </div>
                                                            <div class="mb-4">
                                                                <h6 class="text-445B64">Scr status</h6>
                                                                <h6 class="text-0D161A fw-semibold">
                                                                    {{ $item['scr_status'] }}
                                                                </h6>
                                                            </div>
                                                            <div class="mb-4">
                                                                <h6 class="text-445B64"> {{ $sizeVals[2] }} </h6>
                                                                <h6 class="text-0D161A fw-semibold">
                                                                     {{ !empty($item['size3']) ? $item['size3'] : '-' }}
                                                                </h6>
                                                            </div>
                                                        </div>

                                                    </div>
                                                    <div class="row mt-3">

                                                        @php $class = ''; @endphp

                                                        @if ($item['measureunit'] == 'SET')
                                                            <div class="col-6 col-md-3 mb-2 mb-md-0">
                                                            @else
                                                                <div class="col-4 col-md-4">
                                                                    @php $class='py-3'; @endphp
                                                        @endif

                                                        <button type="button" data-bs-toggle="offcanvas"
                                                            id="showPDFPreview" {{ $btnDisable }}
                                                            data-id="{{ route('admin.route-card-preview', ['id' => $item['id']]) }}"
                                                            data-bs-target="#offcanvasRight"
                                                            aria-controls="offcanvasRight" data-type="routeCard"
                                                            class="btn bg-F0F5F6 border rounded-3 text-0D161A w-100 {{ $class }} showPreview">

                                                            <svg xmlns="http://www.w3.org/2000/svg" width="22"
                                                                height="20" viewBox="0 0 18 16" fill="none">
                                                                <path
                                                                    d="M13.9993 3.83333H3.99935V0.5H13.9993V3.83333ZM13.9993 8.41667C14.2355 8.41667 14.4334 8.33681 14.5931 8.17708C14.7528 8.01736 14.8327 7.81944 14.8327 7.58333C14.8327 7.34722 14.7528 7.14931 14.5931 6.98958C14.4334 6.82986 14.2355 6.75 13.9993 6.75C13.7632 6.75 13.5653 6.82986 13.4056 6.98958C13.2459 7.14931 13.166 7.34722 13.166 7.58333C13.166 7.81944 13.2459 8.01736 13.4056 8.17708C13.5653 8.33681 13.7632 8.41667 13.9993 8.41667ZM12.3327 13.8333V10.5H5.66602V13.8333H12.3327ZM13.9993 15.5H3.99935V12.1667H0.666016V7.16667C0.666016 6.45833 0.909071 5.86458 1.39518 5.38542C1.88129 4.90625 2.47157 4.66667 3.16602 4.66667H14.8327C15.541 4.66667 16.1348 4.90625 16.6139 5.38542C17.0931 5.86458 17.3327 6.45833 17.3327 7.16667V12.1667H13.9993V15.5Z"
                                                                    fill="#0D161A" />
                                                            </svg> RC
                                                        </button>
                                                    </div>

                                                    @if ($item['measureunit'] == 'SET')
                                                        <div class="col-6 col-md-3">
                                                            <button type="button" data-bs-toggle="offcanvas"
                                                                id="showPassPreview" {{ $btnDisable }}
                                                                data-id="{{ route('admin.pass-sheet-preview', ['id' => $item['id']]) }}"
                                                                data-bs-target="#offcanvasRight"
                                                                aria-controls="offcanvasRight" data-type="passSheet"
                                                                class="btn bg-F0F5F6 border rounded-3 text-0D161A w-100 showPreview">
                                                                <svg xmlns="http://www.w3.org/2000/svg" width="22"
                                                                    height="20" viewBox="0 0 18 16" fill="none">
                                                                    <path
                                                                        d="M13.9993 3.83333H3.99935V0.5H13.9993V3.83333ZM13.9993 8.41667C14.2355 8.41667 14.4334 8.33681 14.5931 8.17708C14.7528 8.01736 14.8327 7.81944 14.8327 7.58333C14.8327 7.34722 14.7528 7.14931 14.5931 6.98958C14.4334 6.82986 14.2355 6.75 13.9993 6.75C13.7632 6.75 13.5653 6.82986 13.4056 6.98958C13.2459 7.14931 13.166 7.34722 13.166 7.58333C13.166 7.81944 13.2459 8.01736 13.4056 8.17708C13.5653 8.33681 13.7632 8.41667 13.9993 8.41667ZM12.3327 13.8333V10.5H5.66602V13.8333H12.3327ZM13.9993 15.5H3.99935V12.1667H0.666016V7.16667C0.666016 6.45833 0.909071 5.86458 1.39518 5.38542C1.88129 4.90625 2.47157 4.66667 3.16602 4.66667H14.8327C15.541 4.66667 16.1348 4.90625 16.6139 5.38542C17.0931 5.86458 17.3327 6.45833 17.3327 7.16667V12.1667H13.9993V15.5Z"
                                                                        fill="#0D161A" />
                                                                </svg> PS
                                                            </button>
                                                        </div>
                                                    @endif

                                                    @if ($item['measureunit'] == 'SET')
                                                        <div class="col-12 col-md-6">
                                                        @else
                                                            <div class="col-8 col-md-8">
                                                    @endif

                                                    {{-- <div class="col-12 col-md-6"> --}}
                                                    <button
                                                        class="btn bg-F0F5F6 border rounded-3 text-0D161A fw-bolder w-100 py-3"
                                                        {{ $btnDisable }}>

                                                        @if ($item['sub_product_id'] == null)
                                                            <a class="showModal " data-bs-toggle="modal"
                                                                data-id="{{ $item['id'] }}"
                                                                data-bs-target="#exampleModal">
                                                                Select Sub-Product </a>
                                                        @else
                                                            @if ($item['measureunit'] == 'SET')
                                                                <a href="{{ route('admin.pass-sheet', $item['id']) }}"
                                                                    style="{{ $btnDisable }}">
                                                                    Pass sheet details </a>
                                                            @else
                                                                <a href="{{ route('admin.route-card-details', $item['id']) }}"
                                                                    style="{{ $btnDisable }}">
                                                                    Route card details </a>
                                                            @endif
                                                        @endif

                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                            </div>
                        </div>
                        @endforeach
                        @endif

                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>
    </div>

    <div class="offcanvas offcanvas-end w-50" tabindex="-1" id="offcanvasRight" aria-labelledby="offcanvasRightLabel">
        <div class="offcanvas-header">
            <div class="me-auto d-flex">
                <button type="button" class="border-0 bg-transparent ms-0 closePdf" data-bs-dismiss="offcanvas"
                    aria-label="Close">
                    <i class="fa-solid fa-arrow-left-long text-445B64 fs-5 me-3 mt-1"></i>
                </button>
                <h5 class="offcanvas-title text-0D161A" id="offcanvasRightLabel">Preview</h5>
            </div>

            <div class="printBtn">

            </div>
        </div>

        <div class="offcanvas-body bg-B0BDC1 routeCardPDFPreview" id="printableArea">
        </div>
    </div>

    {{-- Modal Start --}}
    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    </div>
    {{-- Modal End --}}

    <script>
        $(document).on("click", "#printButton", function() {
            var dataId = $("#searchInput").data("id");
            var currentUrl = "{{ URL('admin/pdf-document/') }}/" + dataId;
            window.open(currentUrl, '_blank');
        });

        $(document).on("click", ".printButton", function() {
            var dataId = $("#searchInput").data("id");
            var currentUrl = "{{ URL('admin/printPassSheet/') }}/" + dataId;
            window.open(currentUrl, '_blank');

        });

        $(document).on("click", ".showPreview", function() {

            var url = $(this).attr("data-id");
            var btnType = $(this).attr("data-type");

            console.log(btnType);

            if (btnType == 'passSheet') {
                $('.printBtn').html(
                    '<button class="btn bg-0199E5 text-white rounded-3 px-5 printButton"> <svg xmlns="http://www.w3.org/2000/svg" width="20" height="18" viewBox="0 0 18 16" fill="none" class="me-1"> <path d="M13.9993 3.83333H3.99935V0.5H13.9993V3.83333ZM13.9993 8.41667C14.2355 8.41667 14.4334 8.33681 14.5931 8.17708C14.7528 8.01736 14.8327 7.81944 14.8327 7.58333C14.8327 7.34722 14.7528 7.14931 14.5931 6.98958C14.4334 6.82986 14.2355 6.75 13.9993 6.75C13.7632 6.75 13.5653 6.82986 13.4056 6.98958C13.2459 7.14931 13.166 7.34722 13.166 7.58333C13.166 7.81944 13.2459 8.01736 13.4056 8.17708C13.5653 8.33681 13.7632 8.41667 13.9993 8.41667ZM12.3327 13.8333V10.5H5.66602V13.8333H12.3327ZM13.9993 15.5H3.99935V12.1667H0.666016V7.16667C0.666016 6.45833 0.909071 5.86458 1.39518 5.38542C1.88129 4.90625 2.47157 4.66667 3.16602 4.66667H14.8327C15.541 4.66667 16.1348 4.90625 16.6139 5.38542C17.0931 5.86458 17.3327 6.45833 17.3327 7.16667V12.1667H13.9993V15.5Z" fill="#FFFFFF" /> </svg> Print </button>'
                );
            } else {
                $('.printBtn').html(
                    '<button id="printButton" class="btn bg-0199E5 text-white rounded-3 px-5"> <svg xmlns="http://www.w3.org/2000/svg" width="20" height="18" viewBox="0 0 18 16" fill="none" class="me-1"> <path d="M13.9993 3.83333H3.99935V0.5H13.9993V3.83333ZM13.9993 8.41667C14.2355 8.41667 14.4334 8.33681 14.5931 8.17708C14.7528 8.01736 14.8327 7.81944 14.8327 7.58333C14.8327 7.34722 14.7528 7.14931 14.5931 6.98958C14.4334 6.82986 14.2355 6.75 13.9993 6.75C13.7632 6.75 13.5653 6.82986 13.4056 6.98958C13.2459 7.14931 13.166 7.34722 13.166 7.58333C13.166 7.81944 13.2459 8.01736 13.4056 8.17708C13.5653 8.33681 13.7632 8.41667 13.9993 8.41667ZM12.3327 13.8333V10.5H5.66602V13.8333H12.3327ZM13.9993 15.5H3.99935V12.1667H0.666016V7.16667C0.666016 6.45833 0.909071 5.86458 1.39518 5.38542C1.88129 4.90625 2.47157 4.66667 3.16602 4.66667H14.8327C15.541 4.66667 16.1348 4.90625 16.6139 5.38542C17.0931 5.86458 17.3327 6.45833 17.3327 7.16667V12.1667H13.9993V15.5Z" fill="#FFFFFF" /> </svg> Print </button>'
                );
            }

            $.ajax({
                url: url,
                type: "GET",
                success: function(response) {
                    $(".routeCardPDFPreview").html(response);
                },
                error: function(xhr, status, error) {
                    console.error("AJAX Error: " + status + ": " + error);
                },
            });
        });

        $('.closePdf').on('click', function() {
            // Close the offcanvas and hide related elements
            $('.offcanvas-backdrop').removeClass('show').addClass('hide');
            $('.routeCardPDFPreview').hide(); // Hide the PDF preview
            $('#offcanvasRight').removeClass('show').addClass('hide'); // Hide the offcanvas
            location.reload(); // Reload the page
        });

        // === Modal call ===

        $(document).on("click", ".showModal", function() {
            var id = $(this).attr("data-id");

            var url = "{{ route('admin.fetch-sub-product') }}";

            $.ajax({
                url: url,
                type: "GET",
                data: {
                    id: id,
                },
                success: function(response) {
                    $("#exampleModal").css('display', 'block');
                    $("#exampleModal").addClass('modal fade show');
                    const myModal = new bootstrap.Modal(document.getElementById('exampleModal'));
                    myModal.show();
                    $("#exampleModal").html(response);
                },
                error: function(xhr, status, error) {
                    console.error("AJAX Error: " + status + ": " + error);
                },
            });
        });

        $('body').on("click", ".btn-close", function() {
            $("#exampleModal").removeClass('show').addClass('modal fade');
            $(".modal-backdrop").removeClass('modal-backdrop show').addClass('fade');
            $("#exampleModal").css('display', 'none');
        });
    </script>

@stop
