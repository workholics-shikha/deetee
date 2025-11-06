@section('title', 'QR Code')
@extends('layouts.app')
@section('content')

    <!-- Main Content Area Start -->

    <div class="main-content-area">
        <div class="main-content">
            <div class="row">
                <div class="col-12">
                    <div class="card border-0 rounded-3 mb-1">
                        <div class="card-body px-0">

                            <div class="row">
                                <div class="col-12">
                                    <div class="tab-menu pb-0">
                                        <a href="#" class="tab mb-0 active" onclick="openTab(event, 'Operations')">
                                            <h6 class="text-0D161A"> Operations</h6>
                                        </a>
                                        <a href="#" class="tab mb-0" onclick="openTab(event, 'Operator')">
                                            <h6 class="text-445B64"> Operator</h6>
                                        </a>
                                        <a href="#" class="tab mb-0" onclick="openTab(event, 'Machine')">
                                            <h6 class="text-445B64"> Machine</h6>
                                        </a>
                                        <a href="#" class="tab mb-0" onclick="openTab(event, 'Products')">
                                            <h6 class="text-445B64"> Products</h6>
                                        </a>
                                        <a href="#" class="tab mb-0" onclick="openTab(event, 'GenericQR')">
                                            <h6 class="text-445B64"> Generic QR</h6>
                                        </a>
                                    </div>
                                </div>
                            </div>

                            <div class="row px-3 pt-3">
                                <!--------------------------------------------------->
                                <div class="col-12 col-lg-8 border-end">
                                    <div class="d-flex justify-content-between mb-3 mb-lg-0">
                                        <div class="d-flex align-items-center">
                                            <div class="table-circular-icon bg-F0F5F6 me-3" style="cursor: pointer;">
                                                <i class="fa-solid fa-arrows-rotate"></i>
                                            </div>
                                            <span class="text-0D161A fw-semibold me-1 countItems">
                                                {{ $data['operations']->total() }} </span>
                                            <span class="text-445B64 fw-medium">Items</span>
                                        </div>

                                    </div>
                                </div>
                                <div class="col-12 col-lg-4 d-flex align-items-center">
                                    <div class="d-flex position-relative" style="width: -webkit-fill-available;">
                                        <input type="search" class="form-control me-2 shadow-none searchInputQRTab"
                                            style="padding-left: 35px; background-color: #F0F5F6;" placeholder="Search"
                                            aria-label="Search" data-search-url="{{ route('admin.qr-search') }}">
                                        <i class="fa-solid fa-magnifying-glass text-445B64 position-absolute top-0 start-0"
                                            style="margin: 11px;"></i>
                                    </div>
                                    <div class="">
                                        <div class="table-circular-icon bg-F0F5F6" style="cursor: pointer;">
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

                    <!-- First card -->
                    <div class="card border-0 rounded-3 overflow-hidden">
                        <div class="card-body p-0">
                            <div class="row">
                                <div class="col-12">
                                    <div class="table-responsive">

                                        {{-- ==== Operations Listing ==== --}}

                                        <div id="Operations" class="tabcontent" style="display: block;">
                                            <table class="table rounded-3" id="myTable">
                                                <thead>
                                                    <tr>
                                                        <th scope="col" class="text-445B64 p-3"> Name </th>
                                                        <th scope="col" class="text-445B64 p-3"> Unit </th>
                                                        <th scope="col" class="text-445B64 p-3"> Parameter Input</th>
                                                        <th scope="col" class="text-445B64 p-3"> Matrix </th>
                                                        <th scope="col" class="text-445B64 p-3"> Machines(ID-Name)</th>
                                                    </tr>
                                                </thead>
                                                <tbody id="operations-table-data">

                                                    @foreach ($data['operations'] as $record)
                                                        <tr>
                                                            <th scope="row" class="p-3">
                                                                <div class="d-flex align-items-center">
                                                                    <span
                                                                        class="table-square-icon bg-F0F5F6 rounded-3 showModal"
                                                                        data-bs-toggle="modal" data-id="{{ $record->id }}"
                                                                        data-bs-target="#exampleModal"
                                                                        data-type="Operations" style="cursor: pointer">
                                                                        <img width="30" height="30"
                                                                            viewBox="0 0 12 12" fill="none"
                                                                            src="{{ $record->operation_qr_code }}"
                                                                            alt="" class="me-2" />
                                                                    </span>

                                                                    <span class="ps-2"> <a
                                                                            class="text-0D161A fw-semibold mb-0 text-decoration-none">
                                                                            <strong> {{ $record->operation_name }}</strong>
                                                                        </a>
                                                                    </span>
                                                                </div>
                                                            </th>
                                                            <td class="p-3">{{ $record->unit }}</td>
                                                            <td class="p-3">{{ $record->parameter_input }}</td>
                                                            <td class="p-3">{{ $record->matrix }}</td>
                                                            <td>
                                                                @foreach ($record->machines as $machine)
                                                                    <small> {{ $machine->id . '-' . $machine->machine }}
                                                                    </small>,<br>
                                                                    @if (!$loop->last)
                                                                    @endif
                                                                @endforeach
                                                            </td>
                                                        </tr>
                                                    @endforeach

                                                </tbody>
                                            </table>

                                            <div class="paginationQ d-flex justify-content-center"
                                                id="operations-paginate">
                                                {{ $data['operations']->appends(['tab' => 'Operations'])->links() }}
                                            </div>
                                        </div>

                                        {{-- ==== Operator listing ==== --}}

                                        <div id="Operator" class="tabcontent" style="display: none;">
                                            <table class="table rounded-3">
                                                <thead>
                                                    <tr>
                                                        <th scope="col" class="text-445B64 p-3">Name & ID </th>
                                                        <th scope="col" class="text-445B64 p-3">Designation</th>
                                                        <th scope="col" class="text-445B64 p-3">Department</th>
                                                        <th scope="col" class="text-445B64 p-3">Unit </th>
                                                        <th scope="col" class="text-445B64 p-3">Unit Name</th>
                                                        <th scope="col" class="text-445B64 p-3">Employee Group</th>
                                                        <th scope="col" class="text-445B64 p-3">Status</th>
                                                    </tr>
                                                </thead>
                                                <tbody id="operator-table-data">

                                                    @php
                                                    $i = 0; @endphp
                                                    @foreach ($data['operator'] as $operator)
                                                        @php
                                                        $i++; @endphp
                                                        <tr>
                                                            <td class="p-3">
                                                                <div class="d-flex align-items-center">
                                                                    <span
                                                                        class="table-square-icon bg-F0F5F6 rounded-3 showModal"
                                                                        data-bs-toggle="modal"
                                                                        data-id="{{ $operator->id }}"
                                                                        data-bs-target="#exampleModal"
                                                                        data-type="Operator" style="cursor: pointer">
                                                                        <img width="30" height="30"
                                                                            viewBox="0 0 12 12" fill="none"
                                                                            src="{{ $operator->user_qr_code }}"
                                                                            alt="" class="me-2" />
                                                                    </span>
                                                                    <span class="ps-2">
                                                                        <a
                                                                            class="text-0D161A fw-semibold mb-0 text-decoration-none">{{ $operator->name }}<br />
                                                                            <span class="text-445B64"
                                                                                style="font-size: 12px">#{{ $operator->username }}
                                                                            </span>
                                                                        </a>
                                                                    </span>
                                                                </div>
                                                            </td>
                                                            <td class="p-3">{{ $operator->designation }} </td>
                                                            <td class="p-3">{{ $operator->department }} </td>
                                                            <td class="p-3">{{ $operator->unit }} </td>
                                                            <td class="p-3">{{ $operator->unit_name }} </td>
                                                            <td class="p-3">{{ $operator->employee_group }} </td>
                                                            <td class="p-3" style="color: #00b200">
                                                                Present
                                                                <img src="{{ asset('assets/images/completeDot.png') }}"
                                                                    alt="" class=""
                                                                    style="width: 20px; height: 20px" />
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                            <!-- Pagination Section -->
                                            <div class="paginationQ d-flex justify-content-center" id="operator-paginate">
                                                {{ $data['operator']->appends(['tab' => 'Operator'])->links() }}
                                            </div>
                                        </div>

                                        {{-- ==== Machine listing ==== --}}

                                        <div id="Machine" class="tabcontent" style="display: none;">
                                            <table class="table rounded-3">
                                                <thead>
                                                    <tr>
                                                        <th scope="col" class="text-445B64 p-3">Name & Serial No. </th>
                                                        <th scope="col" class="text-445B64 p-3">Description </th>
                                                        <th scope="col" class="text-445B64 p-3">Unit </th>
                                                        <th scope="col" class="text-445B64 p-3">Group</th>
                                                        <th scope="col" class="text-445B64 p-3">Section</th>
                                                        <th scope="col" class="text-445B64 p-3">Status</th>
                                                    </tr>
                                                </thead>
                                                <tbody id="machines-table-data">
                                                    @php $i=0; @endphp
                                                    @foreach ($data['machines'] as $machine)
                                                        @php $i++; @endphp
                                                        <tr>
                                                            <th scope="row" class="p-3">
                                                                <div class="d-flex align-items-center">

                                                                    <span
                                                                        class="table-square-icon bg-F0F5F6 rounded-3 showModal"
                                                                        data-bs-toggle="modal"
                                                                        data-id="{{ $machine->id }}"
                                                                        data-bs-target="#exampleModal" data-type="Machine"
                                                                        style="cursor: pointer">
                                                                        <img width="30" height="30"
                                                                            viewBox="0 0 12 12" fill="none"
                                                                            src="{{ $machine->machine_qr_code }}"
                                                                            alt="Machine">
                                                                    </span>

                                                                    <span class="ps-2"> <a
                                                                            class="text-0D161A fw-semibold mb-0 text-decoration-none">
                                                                            <strong> {{ $machine->machine }} </strong>
                                                                            <br />
                                                                            <span class="text-445B64"
                                                                                style="font-size:12px">#{{ $machine->machine_type }}</span></a>
                                                                    </span>
                                                                </div>
                                                            </th>
                                                            <td class="p-3">{{ $machine->machine }} </td>
                                                            <td class="p-3">{{ $machine->unit_name }}</td>
                                                            <td class="p-3">{{ $machine->sub_section }}</td>
                                                            <td class="p-3">
                                                                <span
                                                                    class="rounded-pill bg-D5FFCC px-2 py-1 text-success fw-bolder">{{ $machine->section }}</span>
                                                            </td>

                                                            @php
                                                                $statusColors = [
                                                                    'breakdown' => '#FF0000',
                                                                    'maintenance' => '#FF8C00',
                                                                    'in-working' => '#007BFF',
                                                                    'active' => '#00B200', // green
                                                                ];

                                                                $textColor =
                                                                    $statusColors[$machine->machine_status] ??
                                                                    '#000000'; // fallback to black
                                                            @endphp

                                                            <td class="p-3" style="color: {{ $textColor }}">
                                                                {{ ucfirst($machine->machine_status) }}
                                                                <img src="{{ asset('assets/images/completeDot.png') }}"
                                                                    alt="" class=""
                                                                    style="width: 20px; height: 20px" />
                                                            </td>
                                                        </tr>
                                                    @endforeach

                                                </tbody>
                                            </table>
                                            <!-- Pagination Section -->
                                            <div class="paginationQ d-flex justify-content-center" id="machines-paginate">
                                                {{ $data['machines']->appends(['tab' => 'Machine'])->links() }}
                                            </div>
                                        </div>

                                        {{-- ==== Product listing ==== --}}

                                        <div id="Products" class="tabcontent" style="display: none;">
                                            <table class="table rounded-3">
                                                <thead>
                                                    <tr>
                                                        <th scope="col" class="text-445B64 p-3">Product Name </th>
                                                        <th scope="col" class="text-445B64 p-3">Sub-Product Name </th>
                                                        <th scope="col" class="text-445B64 p-3">Unit Name</th>
                                                        <th scope="col" class="text-445B64 p-3">Unit Number</th>
                                                        <th scope="col" class="text-445B64 p-3">Group</th>
                                                        <th scope="col" class="text-445B64 p-3">Product Flow</th>
                                                    </tr>
                                                </thead>
                                                <tbody id="products-table-data">
                                                    @php $i=0; @endphp
                                                    @foreach ($data['products'] as $product)
                                                        @php $i++; @endphp
                                                        <tr class="p-3">
                                                            <th scope="row">
                                                                <div class="d-flex align-items-center">
                                                                    <span
                                                                        class="table-square-icon bg-F0F5F6 rounded-3 showModal"
                                                                        data-bs-toggle="modal"
                                                                        data-id="{{ $product->id }}"
                                                                        data-bs-target="#exampleModal" data-type="Machine"
                                                                        style="cursor: pointer">
                                                                        <img width="30" height="30"
                                                                            viewBox="0 0 12 12" fill="none"
                                                                            src="{{ $product->product->product_qr_code }}"
                                                                            alt="QR Code">
                                                                    </span>
                                                                    <span class="ps-2"> <a
                                                                            class="text-0D161A fw-semibold mb-0 text-decoration-none">{{ $product->product->product_modified_name }}<br />
                                                                            <span class="text-445B64"
                                                                                style="font-size: 12px">#{{ $product->product->erp_nomenclature }}
                                                                            </span></a>
                                                                    </span>
                                                                </div>
                                                            </th>
                                                            <td class="p-3">{{ $product->sub_product_name }} </td>
                                                            <td class="p-3">{{ $product->product->unit }}</td>
                                                            <td class="p-3">{{ $product->product->unit_number }}</td>

                                                            @php
                                                                $so_group = $product->product->group;

                                                                if ($so_group == 'A' || $so_group == 'F') {
                                                                    $addClass = 'D5FFCC text-116600';
                                                                }
                                                                if ($so_group == 'B') {
                                                                    $addClass = 'FFCCF7 text-B20095';
                                                                }
                                                                if ($so_group == 'C-SB' || $so_group == 'C-TCOK') {
                                                                    $addClass = 'CCCCFF text-0000FF';
                                                                }
                                                                if (
                                                                    $so_group == 'D' ||
                                                                    $so_group == 'E' ||
                                                                    $so_group == 'L'
                                                                ) {
                                                                    $addClass = 'FF9898 text-B20095';
                                                                }
                                                                if ($so_group == 'G') {
                                                                    $addClass = 'CCFFCC text-00B200';
                                                                }
                                                                if (
                                                                    $so_group == 'H' ||
                                                                    $so_group == 'I' ||
                                                                    $so_group == 'J'
                                                                ) {
                                                                    $addClass = 'CCFFCC text-CC2200';
                                                                }
                                                                if ($so_group == 'K') {
                                                                    $addClass = 'CCFFCC text-006699';
                                                                }
                                                                if ($so_group == 'M') {
                                                                    $addClass = 'CCEEFF text-006699';
                                                                }

                                                            @endphp

                                                            <td class="p-3">
                                                                <span
                                                                    class="rounded-pill bg-{{ $addClass }} px-2 py-1 fw-bolder">
                                                                    {{ $product->product->group }} </span>
                                                            </td>
                                                            <td class="p-3" style="color: #00b200">
                                                                {{ $product->product->status }}
                                                                <img src="{{ asset('assets/images/completeDot.png') }}"
                                                                    alt="" class=""
                                                                    style="width: 20px; height: 20px" />
                                                            </td>
                                                        </tr>
                                                    @endforeach

                                                </tbody>
                                            </table>
                                            <!-- Pagination Section -->
                                            <div class="paginationQ d-flex justify-content-center" id="products-paginate">
                                                {{ $data['products']->appends(['tab' => 'Products'])->links() }}
                                            </div>
                                        </div>

                                        {{-- ==== Operations Listing ==== --}}

                                        <div id="GenericQR" class="tabcontent" style="display: none;">
                                            <div class="card border-0 rounded-3 mb-1">
                                                <div class="card-body">
                                                    <div class="row">
                                                        <div class="col-12 col-md-6 mb-3 mb-lg-0">
                                                            <div class="d-flex align-items-center">
                                                                <h5 class="fw-semibold mb-0"> Create QR Codes </h5>
                                                            </div>
                                                        </div>
                                                        <!-- Trigger Button -->
                                                        <div class="col-12 col-md-6">
                                                            <button type="button"
                                                                class="btn btn-outline-primary showModal rounded-3"
                                                                data-bs-toggle="modal" data-bs-target="#QRCodeModal"
                                                                data-type="Operator">
                                                                Add QR
                                                            </button>
                                                            <button type="button"
                                                                class="btn btn-outline-primary generateQR rounded-3"
                                                                data-bs-toggle="modal" data-bs-target="#QRgenerateModal"
                                                                data-type="Operator">
                                                                Generate QR
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>



                                            <div class="offcanvas offcanvas-end w-50" tabindex="-1" id="offcanvasRight"
                                                aria-labelledby="offcanvasRightLabel">
                                                <div class="offcanvas-header">
                                                    <div class="me-auto d-flex">
                                                        <button type="button"
                                                            class="border-0 bg-transparent ms-0 closePdf"
                                                            data-bs-dismiss="offcanvas" aria-label="Close">
                                                            <i
                                                                class="fa-solid fa-arrow-left-long text-445B64 fs-5 me-3 mt-1"></i>
                                                        </button>
                                                        <h5 class="offcanvas-title text-0D161A" id="offcanvasRightLabel">
                                                            Preview</h5>
                                                    </div>

                                                    <div class="printBtn">
                                                        <button
                                                            class="btn bg-0199E5 text-white rounded-3 px-5 printButton">
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="20"
                                                                height="18" viewBox="0 0 18 16" fill="none"
                                                                class="me-1">
                                                                <path
                                                                    d="M13.9993 3.83333H3.99935V0.5H13.9993V3.83333ZM13.9993 8.41667C14.2355 8.41667 14.4334 8.33681 14.5931 8.17708C14.7528 8.01736 14.8327 7.81944 14.8327 7.58333C14.8327 7.34722 14.7528 7.14931 14.5931 6.98958C14.4334 6.82986 14.2355 6.75 13.9993 6.75C13.7632 6.75 13.5653 6.82986 13.4056 6.98958C13.2459 7.14931 13.166 7.34722 13.166 7.58333C13.166 7.81944 13.2459 8.01736 13.4056 8.17708C13.5653 8.33681 13.7632 8.41667 13.9993 8.41667ZM12.3327 13.8333V10.5H5.66602V13.8333H12.3327ZM13.9993 15.5H3.99935V12.1667H0.666016V7.16667C0.666016 6.45833 0.909071 5.86458 1.39518 5.38542C1.88129 4.90625 2.47157 4.66667 3.16602 4.66667H14.8327C15.541 4.66667 16.1348 4.90625 16.6139 5.38542C17.0931 5.86458 17.3327 6.45833 17.3327 7.16667V12.1667H13.9993V15.5Z"
                                                                    fill="#FFFFFF" />
                                                            </svg>
                                                            Print </button>
                                                    </div>
                                                </div>

                                                <div class="offcanvas-body bg-B0BDC1 routeCardPDFPreview"
                                                    id="printableArea">
                                                </div>
                                            </div>

                                            <table class="table rounded-3" id="myTable">
                                                <thead>
                                                    <tr>
                                                        <th scope="col" class="text-445B64 p-3"> QR Value</th>
                                                        <th scope="col" class="text-445B64 p-3"> QR For </th>
                                                        <th scope="col" class="text-445B64 p-3"> Action </th>
                                                    </tr>
                                                </thead>
                                                <tbody id="genericQR-table-data">

                                                    @foreach ($data['genericQr'] as $datum)
                                                        <tr>
                                                            <th scope="row" class="sticky-column p-3 bg-white"
                                                                style="position: sticky; left: 10px;">
                                                                <div class="d-flex align-items-center">
                                                                    <span
                                                                        class="table-square-icon bg-F0F5F6 rounded-3 img-zoom">
                                                                        <img width="16" height="16"
                                                                            viewBox="0 0 12 12" fill="none"
                                                                            src="{{ asset('storage/generic-qrcodes') . '/' . $datum->qr_code }}"
                                                                            alt="QR Code">
                                                                    </span>
                                                                    <span class=""> {{ $datum->code }} </span>
                                                                </div>
                                                            </th>

                                                            <td class="p-3"> {{ $datum->qr_use_for }} </td>
                                                            <td class="p-3">
                                                                <a onclick="return confirm('Are you sure you want to delete this QR?');"
                                                                    href="{{ route('deleteGenericQR', [$datum->id]) }}">
                                                                    <i
                                                                        class="rounded-pill fw-bolder fa-solid fa-trash"></i>
                                                                </a>
                                                            </td>
                                                        </tr>
                                                    @endforeach

                                                </tbody>
                                            </table>

                                            <div class="paginationQ d-flex justify-content-center"
                                                id="genericQR-paginate">
                                                {{ $data['genericQr']->appends(['tab' => 'GenericQR'])->links() }}
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
    <!-- Main Content Area End -->

    <!-- QR Code Modal -->
    <div class="modal fade" id="QRCodeModal" tabindex="-1" aria-labelledby="QRCodeModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">

                <div class="modal-header">
                    <h5 class="modal-title" id="QRCodeModalLabel">QR Code Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <form action="{{ route('generate.qr-code') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <div class="mb-3">
                            <label for="qr_code" class="form-label">QR Code Value</label>
                            <input type="text" id="qr_code" name="qr_code" class="form-control" required
                                placeholder="Enter QR Code">
                        </div>

                        <div class="mb-3">
                            <label for="qr_use_for" class="form-label">QR Use For</label>
                            <input type="text" id="qr_use_for" name="qr_use_for" class="form-control" required
                                placeholder="Purpose (e.g. Operator)">
                        </div>

                        <button type="submit" class="btn btn-primary">Submit</button>
                    </form>
                </div>

            </div>
        </div>
    </div>

    {{-- Modal Start --}}

    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    </div>

    {{-- ==Modal End== --}}

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const urlParams = new URLSearchParams(window.location.search);
            const activeTab = urlParams.get("tab") || "Operations";

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

            // Update the URL to reflect the active tab and remove the page number
            const urlParams = new URLSearchParams(window.location.search);
            urlParams.set("tab", cityName); // Update the 'tab' parameter
            urlParams.delete("page"); // Remove the 'page' parameter if it exists

            // Use history.replaceState to update the URL without reloading the page
            const newUrl = `${window.location.pathname}?${urlParams.toString()}`;
            window.history.replaceState({}, "", newUrl);

            tabName = cityName;
            val = 0;

            const tabCounts = {
                Operator: "{{ $data['operator']->total() }}",
                Operations: "{{ $data['operations']->total() }}",
                Machine: "{{ $data['machines']->total() }}",
                Products: "{{ $data['products']->total() }}",
                GenericQR: "{{ count($data['genericQr']) }}"
            };

            val = tabCounts[tabName] || 0;

            $('.countItems').html(val);

        }

        // ==== Call model
        $(document).on("click", ".showModal", function() {
            var id = $(this).attr("data-id");
            var type = $(this).attr("data-type");
            var url = "{{ route('admin.fetch-qr-card', ['id']) }}";

            $.ajax({
                url: url,
                type: "GET",
                data: {
                    id: id,
                    type: type
                },
                success: function(response) {
                    $("#exampleModal").css('display', 'block');
                    $("#exampleModal").addClass('modal fade show');
                    $("#exampleModal").html(response);
                },
                error: function(xhr, status, error) {
                    console.error("AJAX Error: " + status + ": " + error);
                },
            });
        });

        $('body').on("click", ".modalCloseBtn", function() {
            $("#exampleModal").removeClass('show').addClass('modal fade');
            $(".modal-backdrop").removeClass('modal-backdrop show').addClass('fade');
            $("#exampleModal").css('display', 'none');
        });

        $('body').on("click", ".btn-regenerate, .btn-delete, .btn-deactivate", function() {

            var id = $(this).attr("data-id");
            var type = $(this).attr("data-type");
            var btnType = $(this).attr("btn-type");

            alert(btnType);

            if (btnType == 'delete') {
                var url = "{{ route('admin.delete-qr-card') }}";
            } else if (btnType == 'deactivate') {
                var url = "{{ route('admin.deactivate-qr-card') }}";
            } else if (btnType == 'regenerate') {
                var url = "{{ route('admin.regenerate-qr-card') }}";
            }

            $.ajax({
                url: url,
                type: "GET",
                data: {
                    id: id,
                    type: type
                },
                success: function(response) {

                    successToaster(response.message);

                    $("#exampleModal").removeClass('show').addClass('modal fade');
                    $(".modal-backdrop").removeClass('modal-backdrop show').addClass('fade');
                    $("#exampleModal").css('display', 'none');
                    setTimeout(function() {
                        location.reload();
                    }, 500);
                },
                error: function(xhr, status, error) {
                    console.error("AJAX Error: " + status + ": " + error);
                },
            });
        });

        $(".generateQR").click(function() {
            $('#offcanvasRight').addClass('show');

            var url = "{{ route('generate.qrPdfPreview') }}";
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
        })

        $(".printButton").click(function() {
            var currentUrl = "{{ URL('admin/qr-pdf-preview/') }}?pdf=true";
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
