@section('title', 'Route Cards')
@extends('layouts.app')
@section('content')

    <div class="main-content-area">
        <div class="main-content RouteCardProcess-page">
            <div class="row">
                <div class="col-12 d-flex">
                    <div class="card qr-card border-0 rounded-3 mb-1 me-1">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <img src="{{$data->product->product_qr_code}}" alt="" class="w-100">
                            </div> 
                        </div>
                    </div>
                    <div class="card border-0 rounded-3 mb-1 w-100">
                        <div class="card-body pb-0">
                            <div class="d-flex align-items-center justify-content-between border-bottom pb-3 mb-3">
                                <div class="">
                                    <h5 class="text-0D161A fw-semibold mb-0"> {{ $data->product->erp_product }} </h5>
                                </div>
                                 
                            </div>
                            
                            <div class="row">

                                <div class="col-12 col-md-4 col-xl-3 col-xxl-2 mb-3">
                                    <h6 class="text-445B64 mb-1">Unit</h6>
                                    <h6 class="text-0D161A fw-semibold"> {{ $data->product->unit }}</h6>
                                </div>
                                <div class="col-12 col-md-4 col-xl-3 col-xxl-2 mb-3">
                                    <h6 class="text-445B64 mb-1">Unit Number</h6>
                                    <h6 class="text-0D161A fw-semibold">{{ $data->product->unit_number }}</h6>
                                </div>
                                <div class="col-12 col-md-4 col-xl-3 col-xxl-2 mb-3">
                                    <h6 class="text-445B64 mb-1">Group</h6>
                                    <h6 class="text-0D161A fw-semibold">{{ $data->product->group }}</h6>
                                </div>
                                <div class="col-12 col-md-4 col-xl-3 col-xxl-2 mb-3">
                                    <h6 class="text-445B64 mb-1">Code</h6>
                                    <h6 class="text-0D161A fw-semibold">{{ $data->product->erp_nomenclature }}</h6>
                                </div>
                                <div class="col-12 col-md-4 col-xl-3 col-xxl-2 mb-3">
                                    <h6 class="text-445B64 mb-1">Lab No.</h6>
                                    <h6 class="text-0D161A fw-semibold">-</h6>
                                </div>
                                <div class="col-12 col-md-4 col-xl-3 col-xxl-2 mb-3">
                                    <h6 class="text-445B64 mb-1">Special Operation</h6>
                                    <h6 class="text-0D161A fw-semibold">-</h6>
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
                                            <th colspan="2" scope="col" class="text-445B64 border-right p-3" style="width: 28.33%;">Process </th>
                                            <th colspan="2" scope="col" class="text-445B64 p-3" style="width: 66.5%;"> </th>
                                        </tr>
                                    </thead>
                                    @if (!empty($data->routeCard))
                                        @foreach ($data->routeCard as $operation)
                                            <tbody>
                                                <tr>

                                                    <td colspan="4" class="p-0">
                                                        <div class="accordion-item border-0">
                                                            <h2 class="accordion-header" id="headingOne">
                                                                <table class="table mb-0">
                                                                    <tr>
                                                                        <td class="border-right p-3" style="width: 4%;">
                                                                            <button
                                                                                class="accordion-button table-checkbox bg-white mx-auto"
                                                                                type="button" data-bs-toggle="collapse"
                                                                                data-bs-target="#collapse{{ $operation->id }}"
                                                                                aria-expanded="false"
                                                                                aria-controls="collapse{{ $operation->id }}">
                                                                                <i class="fa-solid fa-plus text-445B64"></i>
                                                                            </button>
                                                                        </td>
                                                                        <td class="border-right p-3" style="width: 24.33%;">
                                                                            <div class="d-flex align-items-center">
                                                                                <span
                                                                                    class="table-square-icon bg-F0F5F6 rounded-3">
                                                                                    <svg xmlns="http://www.w3.org/2000/svg"
                                                                                        width="14" height="14"
                                                                                        viewBox="0 0 12 12" fill="none">
                                                                                        <path
                                                                                            d="M6.66667 12V10.6667H8V12H6.66667ZM5.33333 10.6667V7.33333H6.66667V10.6667H5.33333ZM10.6667 8.66667V6H12V8.66667H10.6667ZM9.33333 6V4.66667H10.6667V6H9.33333ZM1.33333 7.33333V6H2.66667V7.33333H1.33333ZM0 6V4.66667H1.33333V6H0ZM6 1.33333V0H7.33333V1.33333H6ZM1 3H3V1H1V3ZM0 4V0H4V4H0ZM1 11H3V9H1V11ZM0 12V8H4V12H0ZM9 3H11V1H9V3ZM8 4V0H12V4H8ZM9.33333 12V10H8V8.66667H10.6667V10.6667H12V12H9.33333ZM6.66667 7.33333V6H9.33333V7.33333H6.66667ZM4 7.33333V6H2.66667V4.66667H6.66667V6H5.33333V7.33333H4ZM4.66667 4V1.33333H6V2.66667H7.33333V4H4.66667ZM1.5 2.5V1.5H2.5V2.5H1.5ZM1.5 10.5V9.5H2.5V10.5H1.5ZM9.5 2.5V1.5H10.5V2.5H9.5Z"
                                                                                            fill="#445B64" />
                                                                                    </svg>
                                                                                </span>
                                                                                <h6 class="text-0D161A fw-bolder mb-0"> {{$operation->operation_stage}} </h6>
                                                                            </div>
                                                                        </td>
                                                                        <td colspan="2" class="border-right p-3"
                                                                            style="width: 66.5%;"> </td>
                                                                    </tr>
                                                                </table>

                                                            </h2>

                                                            <div id="collapse{{ $operation->id }}" class="accordion-collapse collapse"
                                                                aria-labelledby="headingOne"
                                                                data-bs-parent="#accordionExample">
                                                                <div class="accordion-body py-0 pe-0"
                                                                    style="padding-left: 63px;">
                                                                    <div class="py-2">
                                                                        <h6 class="text-0D161A mb-1 ps-3"> {{ $operation->operation_name }} </h6>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                        </div>
                                                    </td>

                                                </tr>
                                            </tbody>
                                        @endforeach
                                    @endif
                                </table>


                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@stop
