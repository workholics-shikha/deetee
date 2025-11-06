@section('title', 'Sales Order')
@extends('layouts.app')
@section('content')

    <!-- Main Content Area Start -->
    <div class="main-content-area">
        <div class="main-content">
            <div class="row">
                <div class="col-12">
                    <div class="card border-0 rounded-3 mb-1">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-12 col-lg-8 border-end">
                                    <div class="d-flex justify-content-between mb-3 mb-lg-0">
                                        <div class="d-flex align-items-center">
                                            <div class="table-circular-icon bg-F0F5F6 me-3" style="cursor: pointer;">
                                                <a href="{{ route('so-list') }}"> <i class="fa-solid fa-arrows-rotate"></i>
                                                </a>
                                            </div>
                                            <span class="text-0D161A fw-semibold me-1">{{ $data->total() }}</span>
                                            <span class="text-445B64 fw-medium">Items</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12 col-lg-4 d-flex align-items-center">
                                    <div class="d-flex position-relative" style="width: -webkit-fill-available;">
                                        <input class="form-control me-2 shadow-none bg-F0F5F6" style="padding-left: 35px;"
                                            type="search" placeholder="Search" aria-label="Search" id="searchInput"
                                            search-url="{{ route('admin.searchInSo') }}">
                                        <i class="fa-solid fa-magnifying-glass text-445B64 position-absolute top-0 start-0"
                                            style="margin: 11px;"> </i>
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
                    <div class="card border-0 rounded-3">
                        <div class="card-body p-0">
                            <div class="row">
                                <div class="col-12">
                                    <div class="table-responsive">
                                        <table class="table rounded-3" id="myTable">
                                            <thead>
                                                <tr>
                                                    <th scope="col" class="text-445B64 sticky-column p-3 bg-white"
                                                        style="position: sticky; left: 8px; z-index: 2;"> SO No.</th>
                                                    <th scope="col" class="text-445B64 text-left">Industry</th>
                                                    <th scope="col" class="text-445B64">Sale Order Date</th>
                                                    <th scope="col" class="text-445B64 text-center">Group</th>
                                                    <th scope="col" class="text-445B64 text-center">Quantity</th>
                                                    <th scope="col" class="text-445B64 text-center">SCR Status</th>
                                                    <th scope="col" class="text-445B64 text-center">Action</th>
                                                </tr>
                                            </thead>
                                            <tbody id="myTableBody">
                                                @php $i=0; @endphp
                                                @foreach ($data as $order)
                                                    @php $i++; @endphp
                                                    <tr>
                                                        <th scope="row" class="sticky-column p-3 bg-white"
                                                            style="position: sticky; left: 10px;">
                                                            <div class="d-flex align-items-center">
                                                                <span
                                                                    class="table-square-icon bg-F0F5F6 rounded-3 img-zoom">
                                                                    <img width="16" height="16" viewBox="0 0 12 12"
                                                                        fill="none" src="{{ $order->so_qr_code }}"
                                                                        alt="QR Code">
                                                                </span>
                                                                <span class=""> {{ $order->so_no }} </span>
                                                            </div>
                                                        </th>
                                                        <td scope="row" class="sticky-column p-3 bg-white"
                                                            style="position: sticky; left: 10px;"> <b>
                                                                {{ $order->industry ?? 'NA' }} </b> </td>

                                                        <td>
                                                            {{ $order->so_date ? \Carbon\Carbon::parse($order->so_date)->format('d M Y') : 'NA' }}
                                                       </td>

                                                        @php
                                                            $addClass = '';
                                                            $group = $order->so_group;
                                                            if ($group == 'A' || $group == 'F') {
                                                                $addClass = 'D5FFCC text-116600';
                                                            }
                                                            if ($group == 'B') {
                                                                $addClass = 'FFCCF7 text-B20095';
                                                            }
                                                            if ($group == 'C-SB' || $group == 'C-TCOK') {
                                                                $addClass = 'CCCCFF text-0000FF';
                                                            }
                                                            if ($group == 'D' || $group == 'E' || $group == 'L') {
                                                                $addClass = 'FF9898 text-B20095';
                                                            }
                                                            if ($group == 'G') {
                                                                $addClass = 'CCFFCC text-00B200';
                                                            }
                                                            if ($group == 'H' || $group == 'I' || $group == 'J') {
                                                                $addClass = 'CCFFCC text-CC2200';
                                                            }
                                                            if ($group == 'K') {
                                                                $addClass = 'CCFFCC text-006699';
                                                            }
                                                            if ($group == 'M') {
                                                                $addClass = 'CCEEFF text-006699';
                                                            }
                                                        @endphp
                                                        <td class="text-center">
                                                            <span
                                                                class="rounded-pill bg-{{ $addClass }} px-2 py-1 fw-bolder">{{ $group ?? 'NA' }}
                                                            </span>
                                                        </td>
                                                        <td class="text-center"> {{ $order->soquantity }} </td>
                                                        <td class="text-center"> {{ $order->scr_status }} </td>
                                                        <td class="text-445B64 text-center"> <a
                                                                href="{{ route('admin.sales-order-details', ['id' => $order->id]) }}">
                                                                View Details </a> </td>
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
    <!-- Main Content Area End -->
@stop
