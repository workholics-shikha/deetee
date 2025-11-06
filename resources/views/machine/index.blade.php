@section('title', 'Machines')
@extends('layouts.app')
@section('content')

    <!-- Main Content Area Start -->
    <div class="main-content-area">
        <div class="main-content">
            <!-- Status Container Start -->
            <div class="">
                <div class="card border-0 rounded-4 mb-3">
                    <div class="card-body p-4">
                        <div class="row justify-content-center align-items-center">
                            <!-- Total Operator -->
                            <div class="col-6 col-md-4 mb-4 mb-md-0">
                                <div class="border-right pe-2 pe-lg-0">
                                    <h6 class="text-445B64">Total Machines</h6>
                                    <h4 class="text-0D161A mb-0 fw-semibold">{{ $count['totalMachines'] }}</h4>
                                </div>
                            </div>
                            <!-- Present -->
                            <div class="col-6 col-md-4 mb-4 mb-md-0">
                                <div class="border-right border-right-mobile-0 pe-2 pe-lg-0">
                                    <h6 class="text-445B64">In Working</h6>
                                    <h4 class="text-0D161A mb-0 fw-semibold">{{ $count['machinesInWorking'] }}</h4>
                                </div>
                            </div>
                            <!-- Absent -->
                            <div class="col-6 col-md-4">
                                <div class="border-right pe-2 pe-lg-0">
                                    <h6 class="text-445B64">Under Maintenance/Breakdown</h6>
                                    <h4 class="text-0D161A mb-0 fw-semibold">{{ $count['unavailableMachines'] }}</h4>
                                </div>
                            </div>
                            <!-- Available -->
                            {{-- <div class="col-6 col-md-3">
                                <div class="pe-2 pe-lg-0">
                                    <h6 class="text-445B64">Breakdown</h6>
                                    <h4 class="text-0D161A mb-0 fw-semibold">{{ $count['unavailableProduct'] }}</h4>
                                </div>
                            </div> --}}
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    <div class="card border-0 rounded-3 mb-1">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-12 col-lg-8">
                                    <div class="d-flex justify-content-between mb-3 mb-lg-0">
                                        <div class="d-flex align-items-center">
                                            <div class="table-circular-icon bg-F0F5F6 me-3" style="cursor: pointer">
                                                <i class="fa-solid fa-arrows-rotate"></i>
                                            </div>
                                            <span class="text-0D161A fw-semibold me-1">{{ $count['totalMachines'] }}</span>
                                            <span class="text-445B64 fw-medium">Items</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12 col-lg-4 d-flex align-items-center">
                                    <div class="d-flex position-relative" style="width: -webkit-fill-available">
                                        <input class="form-control me-2 shadow-none"
                                            style="padding-left: 35px; background-color: #f0f5f6;" type="search"
                                            placeholder="Search" aria-label="Search" id="searchInput"
                                            search-url="{{ route('admin.machine-search') }}" />
                                        <i class="fa-solid fa-magnifying-glass text-445B64 position-absolute top-0 start-0"
                                            style="margin: 11px"></i>
                                    </div>
                                    <div class="">
                                        <div class="table-circular-icon bg-F0F5F6" style="cursor: pointer">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="14"
                                                viewBox="0 0 16 14" fill="none">
                                                <path
                                                    d="M2.16667 13.6668V7.8335H0.5V6.16683H5.5V7.8335H3.83333V13.6668H2.16667ZM2.16667 4.50016V0.333496H3.83333V4.50016H2.16667ZM5.5 4.50016V2.8335H7.16667V0.333496H8.83333V2.8335H10.5V4.50016H5.5ZM7.16667 13.6668V6.16683H8.83333V13.6668H7.16667ZM12.1667 13.6668V11.1668H10.5V9.50016H15.5V11.1668H13.8333V13.6668H12.1667ZM12.1667 7.8335V0.333496H13.8333V7.8335H12.1667Z"
                                                    fill="#445B64" />
                                            </svg>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card border-0 rounded-3 overflow-hidden">
                        <div class="card-body p-0">
                            <div class="row">
                                <div class="col-12">
                                    <div class="table-responsive">
                                        <table class="table rounded-3" id="myTable">
                                            <thead>
                                                <tr>
                                                    <th scope="col" class="text-445B64 p-3">Name & Serial No. </th>
                                                    <th scope="col" class="text-445B64 p-3">Machine type </th>
                                                    <th scope="col" class="text-445B64 p-3">Unit </th>
                                                    <th scope="col" class="text-445B64 p-3">Group</th>
                                                    <th scope="col" class="text-445B64 p-3">Status</th>
                                                    <th scope="col" class="text-445B64 p-3">Action</th>
                                                </tr>
                                            </thead>
                                            <tbody id="myTableBody">

                                                @php $i=0; @endphp
                                                @foreach ($data as $machine)
                                                    @php $i++; @endphp
                                                    <tr>
                                                        <th scope="row" class="p-3">
                                                            <div class="d-flex align-items-center">
                                                                <span class="table-square-icon rounded-3 img-zoom">
                                                                    <img width="40" height="40" viewBox="0 0 12 12"
                                                                        fill="none" src="{{ $machine->machine_qr_code }}"
                                                                        alt="Machine">
                                                                </span>
                                                                <span class="ps-2"> <a
                                                                        class="text-0D161A fw-semibold mb-0 text-decoration-none">
                                                                        <strong>
                                                                            {{ $machine->machine }}</strong> <br /> <span
                                                                            class="text-445B64" style="font-size: 12px">
                                                                            {{ $machine->section }}</span></a>
                                                                </span>
                                                            </div>
                                                        </th>

                                                        <td class="p-3">{{ $machine->machine_type }} </td>
                                                        <td class="p-3">{{ $machine->unit_name }}</td>
                                                        <td class="p-3">{{ $machine->sub_section }}</td>

                                                        @php
                                                            $statusColors = [
                                                                'breakdown' => '#FF0000',
                                                                'maintenance' => '#FF8C00',
                                                                'in-working' => '#007BFF',
                                                                'active' => '#00B200', // green
                                                            ];

                                                            $textColor =
                                                                $statusColors[$machine->machine_status] ?? '#000000'; // fallback to black
                                                        @endphp

                                                        <td class="p-3" style="color: {{ $textColor }}">
                                                            {{ ucfirst($machine->machine_status) }}
                                                            <img src="{{ asset('assets/images/completeDot.png') }}"
                                                                alt="" class=""
                                                                style="width: 20px; height: 20px" />
                                                        </td>
                                                        <td class="p-3"> 
                                                            <a class="rounded-pill" href="{{ route('admin.machine-details', ['id' => $machine->id]) }}">
                                                                View Details </a>

                                                            <button type="button"
                                                                class="btn generateQR"
                                                                data-bs-toggle="modal" data-bs-target="#QRgenerateModal"
                                                                data-type="Operator" data-id="{{ $machine->id }}">
                                                                View PDF
                                                            </button>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                        <!-- Pagination Section -->
                                        <div class="pagination d-flex justify-content-center" id="paginationLinks">
                                            {{ $data->links() }}
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
                <button class="btn bg-0199E5 text-white rounded-3 px-5 printButton" data-id="0"> <svg
                        xmlns="http://www.w3.org/2000/svg" width="20" height="18" viewBox="0 0 18 16"
                        fill="none" class="me-1">
                        <path
                            d="M13.9993 3.83333H3.99935V0.5H13.9993V3.83333ZM13.9993 8.41667C14.2355 8.41667 14.4334 8.33681 14.5931 8.17708C14.7528 8.01736 14.8327 7.81944 14.8327 7.58333C14.8327 7.34722 14.7528 7.14931 14.5931 6.98958C14.4334 6.82986 14.2355 6.75 13.9993 6.75C13.7632 6.75 13.5653 6.82986 13.4056 6.98958C13.2459 7.14931 13.166 7.34722 13.166 7.58333C13.166 7.81944 13.2459 8.01736 13.4056 8.17708C13.5653 8.33681 13.7632 8.41667 13.9993 8.41667ZM12.3327 13.8333V10.5H5.66602V13.8333H12.3327ZM13.9993 15.5H3.99935V12.1667H0.666016V7.16667C0.666016 6.45833 0.909071 5.86458 1.39518 5.38542C1.88129 4.90625 2.47157 4.66667 3.16602 4.66667H14.8327C15.541 4.66667 16.1348 4.90625 16.6139 5.38542C17.0931 5.86458 17.3327 6.45833 17.3327 7.16667V12.1667H13.9993V15.5Z"
                            fill="#FFFFFF" />
                    </svg>
                    Print </button>
            </div>
        </div>

        <div class="offcanvas-body bg-B0BDC1 routeCardPDFPreview" id="printableArea"> </div>

    </div>

    <script>
        $(document).on('click', '.generateQR', function() {
            $('#offcanvasRight').addClass('show');

            var getId = $(this).data('id'); // better syntax for data attributes

            var url = "{{ route('admin.machinePdfPreview', '') }}/" + getId;

             $('.printButton').attr('data-id',getId);

            $.ajax({
                url: url,
                type: "GET",
                success: function(response) {
                    $(".routeCardPDFPreview").html(response);
                },
                error: function(xhr, status, error) {
                    console.error("AJAX Error:", status, error);
                },
            });
        });

        $(document).on('click', '.printButton', function() {
            var getId = $(this).data('id'); // safer than .attr
            var baseUrl = "{{ url('admin/machine-pdf-preview') }}";
            var currentUrl = baseUrl + '/' + getId + '?pdf=true';
            window.open(currentUrl, '_blank');
        });

        $('.closePdf').on('click', function() {
            $('.offcanvas-backdrop').removeClass('show').addClass('hide');
            $('.routeCardPDFPreview').hide();
            $('#offcanvasRight').removeClass('show').addClass('hide');
            location.reload();
        });
    </script>

@stop
