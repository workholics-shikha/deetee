@section('title', 'Operators')
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
                        <div class="col-6 col-md-3 mb-4 mb-md-0">
                            <div class="border-right pe-2 pe-lg-0">
                                <h6 class="text-445B64">Total Operator</h6>
                                <h4 class="text-0D161A mb-0 fw-semibold">{{$data->total()}}</h4>
                            </div>
                        </div>

                        <!-- Present -->
                        <div class="col-6 col-md-3 mb-4 mb-md-0">
                            <div class="border-right border-right-mobile-0 pe-2 pe-lg-0">
                                <h6 class="text-445B64">Present</h6>
                                <h4 class="text-0D161A mb-0 fw-semibold">{{$data->count()}}</h4>
                            </div>
                        </div>

                        <!-- Absent -->
                        <div class="col-6 col-md-3">
                            <div class="border-right pe-2 pe-lg-0">
                                <h6 class="text-445B64">Absent</h6>
                                <h4 class="text-0D161A mb-0 fw-semibold">0</h4>
                            </div>
                        </div>

                        <!-- Available -->
                        <div class="col-6 col-md-3">
                            <div class="pe-2 pe-lg-0">
                                <h6 class="text-445B64">Available</h6>
                                <h4 class="text-0D161A mb-0 fw-semibold">{{$data->count()}}</h4>
                            </div>
                        </div>

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
                                        <span class="text-0D161A fw-semibold me-1">{{ $data->total() }}</span>
                                        <span class="text-445B64 fw-medium">Items</span>
                                    </div>

                                </div>
                            </div>
                            <div class="col-12 col-lg-4 d-flex align-items-center">
                                <div class="d-flex position-relative" style="width: -webkit-fill-available">
                                    <input class="form-control me-2 shadow-none"
                                        style="padding-left: 35px; background-color: #f0f5f6;" type="search"
                                        placeholder="Search" aria-label="Search" id="searchInput" search-url="{{route('admin.operator-search')}}"/>
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
                                    <table class="table rounded-3">
                                        <thead>
                                            <tr>  
                                                <th scope="col" class="text-445B64 p-3">Name & ID</th>
                                                <th scope="col" class="text-445B64 p-3">Designation</th>
                                                <th scope="col" class="text-445B64 p-3">Department</th>
                                                <th scope="col" class="text-445B64 p-3">Unit </th>
                                                <th scope="col" class="text-445B64 p-3">Unit Name</th>
                                                <th scope="col" class="text-445B64 p-3">Employee Group</th>
                                                {{-- <th scope="col" class="text-445B64 p-3">Email Address</th> --}}
                                                <th scope="col" class="text-445B64 p-3">Status</th>
                                                <th scope="col" class="text-445B64 p-3">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody id="myTableBody">

                                          @php $i=0; @endphp
                                           @foreach ($data as $operator)
                                            @php $i++; @endphp
                                            <tr> 
                                                <td class="p-3">
                                                    <div class="d-flex align-items-center">
                                                        <div class="img-zoom">
                                                           <img src="{{$operator->user_qr_code}}" alt="" class="me-2" style="width: 30px; height: 30px" viewBox="0 0 12 12" fill="none" />
                                                        </div>
                                                        <div class="">
                                                            <a class="text-0D161A fw-semibold mb-0 text-decoration-none">{{ $operator->name }}<br/><span class="text-445B64" style="font-size: 12px">#{{ $operator->username }}</span></a>
                                                        </div>
                                                    </div>
                                                </td>

                                                <td class="p-3">{{ $operator->designation }} </td>
                                                <td class="p-3">{{ $operator->department }} </td>
                                                <td class="p-3">{{ $operator->unit }} </td>
                                                <td class="p-3">{{ $operator->unit_name }} </td>
                                                <td class="p-3">{{ $operator->employee_group }} </td>
                                                {{-- <td class="p-3">{{ $operator->email }} </td> --}}
                                                {{-- <td class="p-3">{{ $operator->status }} </td> --}}
                                                      
                                                <td class="p-3" style="color: #00b200">
                                                    Present
                                                    <img src="{{ asset('assets/images/completeDot.png') }}"
                                                        alt="" class=""
                                                        style="width: 20px; height: 20px" />
                                                </td>

                                                <td> <a class="rounded-pill" type="submit" href="{{ route('admin.operator-details', ['id' => $operator->id]) }}">View Details</a> </td>
                                                    
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