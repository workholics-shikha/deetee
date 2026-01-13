@section('title', 'Pass Sheet Details')
@extends('layouts.app')
@section('content')

    <div class="main-content-area">

        <div class="">
            <h5 class="text-0077B3 fw-semibold mt-5 ms-3"> <i class="fa-solid fa-long-arrow-left me-1 text-637381"></i>
                {{ $so->so_no }} / {{ $data->item_name }} /
                <a class="text-637381" style="text-decoration:none;"
                    href="{{ route('admin.sales-order-details', ['id' => $so->id]) }}"> {{ $subProductName }} </a>
            </h5>
        </div>

        <div class="main-content saleOrdersRouteCard-page">
            <div class="row">
                <div class="col-12 d-flex">
                    <div class="card qr-card border-0 rounded-3 mb-1 me-1">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <img src="{{ $data->so_product_qr_code }}" alt="" class="w-100">
                            </div>
                        </div>
                    </div>
                    <div class="card border-0 rounded-3 mb-1 w-100">
                        <div class="card-body pb-0">

                            <div class="d-flex align-items-center justify-content-between border-bottom pb-3 mb-3">
                                <div class="">
                                    <h5 class="text-0D161A fw-semibold mb-0"> {{ $subProductName }}
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
                                <div class="col-12 col-md-4 col-xl-3 col-xxl-2 mb-3">
                                    <h6 class="text-445B64 mb-1">Group</h6>
                                    <h6 class="text-0D161A fw-semibold"> {{ $so->so_group }} </h6>
                                </div>

                                @php $sizeVals = getSizeValue($so->so_group); @endphp
                                <div class="col-12 col-md-4 col-xl-3 col-xxl-2 mb-3">
                                    <h6 class="text-445B64 mb-1">{{ $sizeVals[0]}}</h6>
                                    <h6 class="text-0D161A fw-semibold"> {{ !empty($data->size1) ? $data->size1 : '-' }} </h6>
                                </div>
                                <div class="col-12 col-md-4 col-xl-3 col-xxl-2 mb-3">
                                    <h6 class="text-445B64 mb-1">{{ $sizeVals[1] }}</h6>
                                    <h6 class="text-0D161A fw-semibold"> {{ !empty($data->size2) ? $data->size2 : '-' }} </h6>
                                </div>
                                <div class="col-12 col-md-4 col-xl-3 col-xxl-2 mb-3">
                                    <h6 class="text-445B64 mb-1">{{ $sizeVals[2] }}</h6>
                                    <h6 class="text-0D161A fw-semibold"> {{ !empty($data->size3) ? $data->size3 : '-' }} </h6>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-12">
                    <div class="card border-0 rounded-3 mb-1">
                        <div class="table-responsive">
                            <!-- <div class="accordion" id="accordionExample"> -->

                            <table class="table rounded-3" id="myTable">
                                <thead>
                                    <tr>
                                        <th scope="col" style="text-align:left; width:20px;"> Pass item</th>
                                        <th scope="col" style="text-align:center; width:20px;"> Pass number</th>
                                        <th scope="col" style="text-align:center; width:20px;"> OD </th>
                                        <th scope="col" style="text-align:center; width:20px;"> ID </th>
                                        <th scope="col" style="text-align:center; width:20px;"> Thickness </th>
                                        <th scope="col" style="text-align:center; width:20px;"> Qty </th>
                                        <th scope="col" style="text-align:center; width:10px;"> Action </th>
                                    </tr>
                                </thead>
                                <tbody id="myTableBody">
                                    @php $i=0; @endphp
                                    @foreach ($pass_sheet as $pass_data)
                                        @php $i++; @endphp
                                        <tr>
                                            <td class="img-zoom" style="text-align:left; ">
                                                <img width="26" height="26" viewBox="0 0 12 12" fill="none"
                                                    src="{{ $pass_data->pass_sheet_qr_code }}" alt="QR Code"> &nbsp;
                                                <span class=""> {{ $pass_data->pass_no }} </span>
                                            </td>
                                            <td class="" style="text-align:center;">  {{ $pass_data->mrk_pass_no }} </td>
                                            <td class="" style="text-align:center;">  {{ !empty($pass_data->size1) ? $pass_data->size1 : '-' }} </td> 
                                            <td class="" style="text-align:center;">  {{ !empty($pass_data->size2) ? $pass_data->size2 : '-' }} </td>
                                            <td class="" style="text-align:center;">  {{ !empty($pass_data->size3) ? $pass_data->size3 : '-' }} </td>
                                            <td class="" style="text-align:center;">  {{ $pass_data->qty }} </td>
                                            <td style="text-align:center;"><a href="{{ route('admin.route-card-details', [$sop_id, $pass_data->id]) }}"> View route card </a> </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>

                            <!-- </div> -->

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@stop
