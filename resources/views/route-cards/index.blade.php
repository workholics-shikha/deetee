@section('title', 'Route Cards')
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
                                <div class="col-12 col-lg-8">
                                    <div class="d-flex justify-content-between mb-3 mb-lg-0">
                                        <div class="d-flex align-items-center">
                                            <div class="table-circular-icon bg-F0F5F6 me-3" style="cursor: pointer" >
                                                <i class="fa-solid fa-arrows-rotate"></i>
                                            </div>
                                            <span class="text-0D161A fw-semibold me-1">{{ $data->total() }}</span>
                                            <span class="text-445B64 fw-medium">Items</span>
                                        </div>

                                    </div>
                                </div>
                                <div class="col-12 col-lg-4 d-flex align-items-center">
                                    <div class="d-flex position-relative" style="width: -webkit-fill-available">
                                       
                                        <input class="form-control me-2 shadow-none" style="padding-left: 35px; background-color: #f0f5f6;" type="search" placeholder="Search" aria-label="Search" id="searchInput" search-url="{{route('admin.route-card-search')}}" />
                                        
                                        <i class="fa-solid fa-magnifying-glass text-445B64 position-absolute top-0 start-0" style="margin: 11px"></i>

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
                                        <table class="table rounded-3">
                                            <thead>
                                                <tr>
                                                    {{-- <th scope="col" class="text-center">
                                                        <input class="form-check-input table-checkbox" type="checkbox" value="" id="flexCheckDefault" />
                                                    </th> --}}
                                                    <th scope="col" class="text-445B6 p-3" >
                                                        Product Name
                                                    </th>
                                                    <th scope="col" class="text-445B64 p-3"> Sub-Product Name </th>
                                                    <th scope="col" class="text-445B64 p-3"> Unit Name</th>
                                                    <th scope="col" class="text-445B64 p-3"> Group</th>
                                                    <th scope="col" class="text-445B64 p-3"> Action </th>
                                                </tr>
                                            </thead>
                                            <tbody id="myTableBody">

                                                @php $i=0; @endphp
                                                @foreach ($data as $product)
                                                    @php $i++; @endphp
                                                    <tr>
                                                        {{-- <td class="text-center">
                                                            <input class="form-check-input table-checkbox" type="checkbox" value="{{ $product->id }}" id="flexCheckDefault" />
                                                        </td> --}}
                                                        <th scope="row" class="p-3">
                                                            <div class="d-flex align-items-center">
                                                                <span class="">
                                                                    <a class="text-0D161A fw-semibold mb-0 text-decoration-none">{{ $product->product->product_modified_name }}<br/>
                                                                        <span class="text-445B64" style="font-size: 12px">#{{ $product->product->erp_nomenclature }}</span>
                                                                    </a>
                                                                </span>
                                                            </div>
                                                        </th>

                                                        <td class="p-3">{{ $product->sub_product_name }} </td>
                                                        <td class="p-3">{{ $product->product->unit }}</td>

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
                                                            <span class="rounded-pill bg-{{$addClass}} px-2 py-1 fw-bolder">{{$product->product->group}}</span>
                                                        </td>

                                                        <td class="p-3">
                                                            <a href="{{ route('admin.process', $product->id) }}"><i class="fa-solid fa-eye text-445B64"></i></a>
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
    <!-- Main Content Area End -->

@stop
