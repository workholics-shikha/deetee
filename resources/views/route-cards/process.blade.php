@section('title', 'Route Cards')
@extends('layouts.app')
@section('content')
    <div class="main-content-area">
        <div class="">
            <h5 class="text-0077B3 fw-semibold mt-5 ms-3"> <i class="fa-solid fa-long-arrow-left me-1 text-637381"></i>
                <a class="text-637381" style="text-decoration:none;" href="{{ route('admin.route-cards') }}"> Route Cards</a>
            </h5>
        </div>
        <div class="main-content RouteCardProcess-page">
            <div class="row">
                <div class="col-12 d-flex">
                    <div class="card qr-card border-0 rounded-3 mb-1 me-1">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <img width="120" height="120" viewBox="0 0 12 12" fill="none"
                                    src="<?php print_r($product->product->product_qr_code ?? 'No QR Found'); ?>" alt="QR Code">
                            </div>
                        </div>
                    </div>
                    <div class="card border-0 rounded-3 mb-1 w-100">
                        <div class="card-body pb-0">
                            <div class="d-flex align-items-center justify-content-between border-bottom pb-3 mb-3">
                                <div class="">
                                    <h5 class="text-0D161A fw-semibold mb-0"> {{ $product->product->erp_product }} >
                                        {{ $subProductName }} </h5>
                                </div>

                            </div>
                            <div class="row">

                                <div class="col-12 col-md-4 col-xl-3 col-xxl-2 mb-3">
                                    <h6 class="text-445B64 mb-1">Unit</h6>
                                    <h6 class="text-0D161A fw-semibold"> {{ $product->product->unit }}</h6>
                                </div>
                                <div class="col-12 col-md-4 col-xl-3 col-xxl-2 mb-3">
                                    <h6 class="text-445B64 mb-1">Unit Number</h6>
                                    <h6 class="text-0D161A fw-semibold">{{ $product->product->unit_number }}</h6>
                                </div>
                                <div class="col-12 col-md-4 col-xl-3 col-xxl-2 mb-3">
                                    <h6 class="text-445B64 mb-1">Group</h6>
                                    <h6 class="text-0D161A fw-semibold">{{ $product->product->group }}</h6>
                                </div>
                                <div class="col-12 col-md-4 col-xl-3 col-xxl-2 mb-3">
                                    <h6 class="text-445B64 mb-1">Code</h6>
                                    <h6 class="text-0D161A fw-semibold">{{ $product->product->erp_nomenclature }}</h6>
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
                                            <th class="border-right p-3"> S.No. </th>
                                            <th class="border-right p-3"> Stage </th>
                                            <th class="border-right p-3"> Operation </th>
                                            <th class="border-right p-3"> Operation Status </th>
                                        </tr>
                                    </thead>
                                    @php $i=0; @endphp
                                    @if (!empty($operations))
                                        @foreach ($operations as $operation)
                                            @php $i++; @endphp
                                            <tbody>
                                                <tr>
                                                <tr>
                                                    <td class="border-right p-3" style="width: 4%;">
                                                        {{ $i }}.
                                                    </td>
                                                    <td class="border-right p-3" style="width: 24.33%;">
                                                        {{ $operation->operation_name }}
                                                    </td>
                                                    <td colspan="2" class="border-right p-3" style="width: 66.5%;">
                                                        {{ $operation->sub_operations ? $operation->sub_operations : $operation->operation_name }}
                                                    </td>
                                                    <td class="border-right p-3">
                                                        {{ $operation->operation_type }}
                                                    </td>
                                                </tr>
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
