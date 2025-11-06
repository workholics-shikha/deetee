@section('title', 'Operator')
@extends('layouts.app')
@section('content')

    <div class="main-content-area">
        <div class="main-content operatorDetail-page">
            <div class="row">
                <div class="col-12 d-block d-xl-flex mb-4">

                    <div class="card qr-card border-0 rounded-3 mb-1 me-1">
                        <div class="card-body">
                            <div class="">
                                <div class="position-relative p-3 mx-auto" style="width: fit-content">
                                     <img src="{{ $data->profile_image }}" alt="" class="" style="width: 113px; height: 113px; border-radius: 80px;">
                                     <span class="bg-white position-absolute p-1 rounded-2"
                                        style="left: 49px; bottom: -4px;">
                                        <img src="{{$data->user_qr_code}}" alt="" style="width: 40px; height:'40px;">
                                     </span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card border-0 rounded-3 mb-1 w-100">
                        <div class="card-body p-4 pb-0">
                            <div class="d-flex align-items-start justify-content-between border-bottom mb-3">
                                <div class="">
                                    <h5 class="text-0D161A fw-semibold mb-0">
                                        {{ $data->name }}
                                    </h5>
                                    <p class="text-445B64"># {{ $data->id }}</p>
                                </div>
                                <div class="d-flex align-items-center">
                                    <h6 class="text-00B200 mb-0 me-2">Present
                                    </h6>
                                    <img src="{{ asset('assets/images/completeDot.png') }}" alt="" class=""
                                        style="width: 20px; height: 20px;">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12 col-md-4 col-xl-3 mb-3">
                                    <h6 class="text-445B64 mb-1">Role</h6>
                                    <h6 class="text-0D161A fw-medium">Lathe Operator</h6>
                                </div>
                                <div class="col-12 col-md-4 col-xl-3 mb-3">
                                    <h6 class="text-445B64 mb-1">Shift</h6>
                                    <h6 class="text-0D161A fw-medium">Day (10 am - 6 pm)</h6>
                                </div>
                                <div class="col-12 col-md-4 col-xl-3 mb-3">
                                    <h6 class="text-445B64 mb-1">Unit</h6>
                                    <h6 class="text-0D161A fw-medium">RMR</h6>
                                </div>
                                <div class="col-12 col-md-4 col-xl-3 mb-3">
                                    <h6 class="text-445B64 mb-2">Group</h6>
                                    <h6 class="text-0D161A fw-medium">
                                        <span class="rounded-pill bg-D5FFCC text-success fw-normal px-2 py-1 ms-2">
                                            B </span>
                                    </h6>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{--  --}}
 
                <div class="col-12 mb-3">
                    <div class="card border-0 rounded-4">
                        <div class="card-body p-4">
                            <div class="row justify-content-center align-items-center">
                                <div class="col-6 col-md-3 mb-4 mb-lg-0">
                                    <div class="border-right pe-2 pe-lg-0">
                                        <h6 class="text-445B64 fs-14">Log in Time</h6>
                                        <h6 class="text-445B64 mb-0 fw-bolder">
                                            28/10/2024, 08:00 AM
                                        </h6>
                                    </div>
                                </div>

                                <div class="col-6 col-md-3 mb-4 mb-lg-0">
                                    <div class="border-right border-right-mobile-0 pe-2 pe-lg-0">
                                        <h6 class="text-445B64 fs-14">Log Out Time</h6>
                                        <h6 class="text-445B64 mb-0 fw-bolder">
                                            28/10/2024, 04:30 PM
                                        </h6>
                                    </div>
                                </div>

                                <div class="col-6 col-md-3">
                                    <div class="border-right pe-2 pe-lg-0">
                                        <h6 class="text-445B64 fs-14">Working Time</h6>
                                        <h6 class="text-445B64 mb-0 fw-bolder">
                                            8 Hours 0 Minutes
                                        </h6>
                                    </div>
                                </div>

                                <div class="col-6 col-md-3">
                                    <div class=" pe-2 pe-lg-0">
                                        <h6 class="text-445B64 fs-14">Idle Time</h6>
                                        <h6 class="text-445B64 mb-0 fw-bolder">
                                            1 Hour 30 minutes
                                        </h6>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                {{-- ========== --}}

                <div class="col-12 col-xl-7">
                    <div class="row">
                        <div class="col-12 col-md-6">
                            <div class="card border-0 rounded-4 mb-4 overflow-hidden">
                                <div class="card-header p-3 bg-white border-bottom">
                                    <div class="row align-items-center">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div class="">
                                                <h6 class="text-445B64 fw-semibold mb-0">Breakdown</h6>
                                            </div>
                                            <div class="">
                                                <div class="dropdown">
                                                    <button class="btn dropdown-toggle bg-F0F5F6" type="button"
                                                        id="dropdownMenuButton" data-bs-toggle="dropdown"
                                                        aria-expanded="false">
                                                        Filter
                                                    </button>
                                                    <ul class="dropdown-menu dropdown-menu-end shadow border-0 rounded-3"
                                                        aria-labelledby="dropdownMenuButton">
                                                        <li><a class="dropdown-item" href="#">Option 1</a></li>
                                                        <li><a class="dropdown-item" href="#">Option 2</a></li>
                                                        <li><a class="dropdown-item" href="#">Option 3</a></li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!------------------------------------------------->
                                <div class="card-body p-0">
                                    <div class="row">
                                        <div class="col-12">
                                            <div class="table-responsive">
                                                <table class="table rounded-3 mb-0">
                                                    <thead>
                                                        <!-- Updated by swechha  -->
                                                        <tr>
                                                            <th scope="col" class="text-6C7D83 py-2 px-3 fw-medium">Date
                                                            </th>
                                                            <th scope="col" class="text-6C7D83 py-2 px-3 fw-medium">Type
                                                            </th>
                                                            <th scope="col" class="text-6C7D83 py-2 px-3 fw-medium">
                                                                Duration
                                                            </th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <tr>
                                                            <td scope="row" class="p-3">
                                                                <div class="">
                                                                    <h6 class="text-445B64 mb-0 fw-medium">24 Oct 2024</h6>
                                                                    <h6 class="text-6C7D83 mb-0 fs-13">06:30 AM</h6>
                                                                </div>
                                                            </td>
                                                            <td class="text-445B64 p-3 fw-semibold">No load</td>
                                                            <td class="text-445B64 p-3 fw-semibold">24 Mins</td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-md-6">
                            <div class="card border-0 rounded-4 mb-4 overflow-hidden">
                                <div class="card-header p-3 bg-white border-bottom">
                                    <div class="row align-items-center">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div class="">
                                                <h6 class="text-445B64 fw-semibold mb-0">Maintenance</h6>
                                            </div>
                                            <div class="">
                                                <div class="dropdown">
                                                    <button class="btn dropdown-toggle bg-F0F5F6" type="button"
                                                        id="dropdownMenuButton" data-bs-toggle="dropdown"
                                                        aria-expanded="false">
                                                        Filter
                                                    </button>
                                                    <ul class="dropdown-menu dropdown-menu-end shadow border-0 rounded-3"
                                                        aria-labelledby="dropdownMenuButton">
                                                        <li><a class="dropdown-item" href="#">Option 1</a></li>
                                                        <li><a class="dropdown-item" href="#">Option 2</a></li>
                                                        <li><a class="dropdown-item" href="#">Option 3</a></li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!------------------------------------------------->
                                <div class="card-body p-0">
                                    <div class="row">
                                        <div class="col-12">
                                            <div class="table-responsive">
                                                <table class="table rounded-3 mb-0">
                                                    <thead>
                                                        <!-- Updated by swechha  -->
                                                        <tr>
                                                            <th scope="col" class="text-6C7D83 py-2 px-3 fw-medium">
                                                                Date
                                                            </th>
                                                            <th scope="col" class="text-6C7D83 py-2 px-3 fw-medium">
                                                                Type
                                                            </th>
                                                            <th scope="col" class="text-6C7D83 py-2 px-3 fw-medium">
                                                                Cause
                                                            </th>
                                                            <th scope="col" class="text-6C7D83 py-2 px-3 fw-medium">
                                                                Duration
                                                            </th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <tr>
                                                            <td scope="row" class="p-3">
                                                                <div class="">
                                                                    <h6 class="text-445B64 mb-0 fw-medium">24 Oct 2024</h6>
                                                                    <h6 class="text-6C7D83 mb-0 fs-13">06:30 AM</h6>
                                                                </div>
                                                            </td>
                                                            <td class="text-445B64 p-3 fw-semibold">No load</td>
                                                            <td class="text-445B64 p-3 fw-semibold">Scheduled</td>
                                                            <td class="text-445B64 p-3 fw-semibold">24 Mins</td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-12 col-xl-5">
                    <div class="row">
                        <div class="col-12">
                            <div class="card border-0 rounded-4 mb-4 overflow-hidden">
                                <div class="card-header p-3 bg-white border-bottom">
                                    <div class="row align-items-center">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div class="">
                                                <h6 class="text-445B64 fw-semibold mb-0">SO History</h6>
                                            </div>
                                            <div class="">
                                                <div class="dropdown">
                                                    <button class="btn dropdown-toggle bg-F0F5F6" type="button"
                                                        id="dropdownMenuButton" data-bs-toggle="dropdown"
                                                        aria-expanded="false">
                                                        Filter
                                                    </button>
                                                    <ul class="dropdown-menu dropdown-menu-end shadow border-0 rounded-3"
                                                        aria-labelledby="dropdownMenuButton">
                                                        <li><a class="dropdown-item" href="#">Option 1</a></li>
                                                        <li><a class="dropdown-item" href="#">Option 2</a></li>
                                                        <li><a class="dropdown-item" href="#">Option 3</a></li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!------------------------------------------------->
                                <div class="card-body p-0">
                                    <div class="row">
                                        <div class="col-12">
                                            <div class="table-responsive">
                                                <table class="table rounded-3 mb-0">
                                                    <thead>
                                                        <!-- Updated by swechha  -->
                                                        <tr>
                                                            <th scope="col" class="text-6C7D83 py-2 px-3 fw-medium">
                                                                SO No.
                                                            </th>
                                                            <th scope="col" class="text-6C7D83 py-2 px-3 fw-medium">
                                                                Date
                                                            </th>
                                                            <th scope="col" class="text-6C7D83 py-2 px-3 fw-medium">
                                                                Process
                                                            </th>
                                                            <th scope="col" class="text-6C7D83 py-2 px-3 fw-medium">
                                                                Duration
                                                            </th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <tr>
                                                            <td class="text-445B64 p-3 fw-semibold">
                                                                <div class="d-flex align-items-center">
                                                                    <span class="table-square-icon bg-F0F5F6 rounded-3"
                                                                        style="font-size: 12px; font-weight: 500;">
                                                                        <svg xmlns="http://www.w3.org/2000/svg"
                                                                            width="14" height="14"
                                                                            viewBox="0 0 12 12" fill="none">
                                                                            <path
                                                                                d="M6.66667 12V10.6667H8V12H6.66667ZM5.33333 10.6667V7.33333H6.66667V10.6667H5.33333ZM10.6667 8.66667V6H12V8.66667H10.6667ZM9.33333 6V4.66667H10.6667V6H9.33333ZM1.33333 7.33333V6H2.66667V7.33333H1.33333ZM0 6V4.66667H1.33333V6H0ZM6 1.33333V0H7.33333V1.33333H6ZM1 3H3V1H1V3ZM0 4V0H4V4H0ZM1 11H3V9H1V11ZM0 12V8H4V12H0ZM9 3H11V1H9V3ZM8 4V0H12V4H8ZM9.33333 12V10H8V8.66667H10.6667V10.6667H12V12H9.33333ZM6.66667 7.33333V6H9.33333V7.33333H6.66667ZM4 7.33333V6H2.66667V4.66667H6.66667V6H5.33333V7.33333H4ZM4.66667 4V1.33333H6V2.66667H7.33333V4H4.66667ZM1.5 2.5V1.5H2.5V2.5H1.5ZM1.5 10.5V9.5H2.5V10.5H1.5ZM9.5 2.5V1.5H10.5V2.5H9.5Z"
                                                                                fill="#445B64" />
                                                                        </svg>
                                                                    </span>
                                                                    <h6 class="text-445B64 mb-0">SE-I-740230-A</h6>
                                                                </div>
                                                            </td>
                                                            <td scope="row" class="p-3">
                                                                <div class="">
                                                                    <h6 class="text-445B64 mb-0 fw-medium">24 Oct 2024</h6>
                                                                    <h6 class="text-6C7D83 mb-0 fs-13">06:30 AM</h6>
                                                                </div>
                                                            </td>
                                                            <td class="text-445B64 p-3 fw-semibold">Blanking</td>
                                                            <td class="text-445B64 p-3 fw-semibold">24 Mins</td>
                                                        </tr>
                                                    </tbody>
                                                </table>
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

@stop
