@section('title', 'Machine Details')
@extends('layouts.app')
@section('content')

    <div class="main-content-area">
        <div class="main-content machineDetail-page">
            <div class="row">
                <div class="col-12 d-block d-xl-flex mb-4">
                    <div class="card qr-card border-0 rounded-3 mb-1 me-1">
                        <div class="card-body">
                            <div class="">
                                <div class="position-relative p-2 mx-auto" style="width: fit-content">
                                    <img src="{{$machine->machine_image}}" alt="" class="rounded-2" style="width: 129px; height: 144px;">
                                    <span class="bg-white position-absolute p-2 rounded-2 img-zoom" style="left: 49px; bottom: -15px;">
                                        <img src="{{ $machine->machine_qr_code }}" style="width: 40px; height:'40px;">
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
                                        {{ $machine->machine }} #{{ $machine->id }}
                                    </h5>
                                    <p class="text-445B64"> {{ $machine->machine_type }} </p>
                                </div>
                                <!--<div class="d-flex align-items-center">-->
                                <!--    <h6 class="text-00B200 mb-0 me-2"> Working </h6>-->
                                <!--    <img src="{{ asset('assets/images/completeDot.png') }}" alt="" class=""-->
                                <!--        style="width: 20px; height: 20px;">-->
                                <!--</div>-->
                                <div class="d-flex align-items-center">
                                    <select 
                                        id="machineStatus" 
                                        class="form-select form-select-sm w-auto" 
                                        onchange="updateMachineStatus(this)" 
                                        data-machine-id="{{ $machine->id }}"
                                    >
                                        <option value="active" {{ $machine->machine_status == 'active' ? 'selected' : '' }}>Active</option>
                                        <option value="maintenance" {{ $machine->machine_status == 'maintenance' ? 'selected' : '' }}>Maintenance</option>
                                        <option value="in-working" {{ $machine->machine_status == 'in-working' ? 'selected' : '' }}>In Working</option>
                                        <option value="breakdown" {{ $machine->machine_status == 'breakdown' ? 'selected' : '' }}>Breakdown</option>
                                    </select>

                                
                                    <img id="statusIcon" src="{{ asset('assets/images/completeDot.png') }}" alt=""
                                        class="ms-2" style="width: 20px; height: 20px;">
                                </div>

                            </div>

                            <div class="row mb-1">
                                <div class="col-12 col-md-6">
                                    <h6 class="text-445B64 fs-14 me-2">Description</h6>
                                    <p class="text-0D161A fs-14 lh-1 fw-semibold">
                                        {{ $machine->section }}-{{ $machine->machine_type }}
                                    </p>
                                </div>
                                <div class="col-12 col-md-6">
                                    <h6 class="text-445B64 fs-14 me-2">Department</h6>
                                    <p class="text-0D161A fs-14 lh-1 fw-semibold">
                                        {{ $machine->unit_name }}
                                    </p>
                                </div>
                                <div class="col-12">
                                    <h6 class="text-445B64 fs-14 me-2">Operations</h6>
                                    <div class="">
                                        @foreach( $operations as $operation)
                                          <button class="btn btn-sm btn-skyBlue mb-2 rounded-2">{{ $operation->operation->operation_name }}</button> &nbsp;
                                        @endforeach
                                    </div>
                                </div>
                              
                            </div>
                        </div>
                    </div>
                </div>

            <div class="row mb-3">    
                <div class="col-12">
                    <div class="card border-0 rounded-4">
                        <div class="card-body p-4">
                            <div class="row justify-content-center">
                                <div class="col-6 col-md-3 mb-4 mb-lg-0">
                                    <div class="border-right pe-2 pe-lg-0">
                                        <h6 class="text-445B64 fs-14">Machine Run Time</h6>
                                        <h5 class="text-0D161A mb-0 fw-bolder">
                                            1440
                                        </h5>
                                    </div>
                                </div>

                                <div class="col-6 col-md-3 mb-4 mb-lg-0">
                                    <div class="border-right border-right-mobile-0 pe-2 pe-lg-0">
                                        <h6 class="text-445B64 fs-14">Machine Downtime</h6>
                                        <h5 class="text-0D161A mb-0 fw-bolder">
                                            40
                                        </h5>
                                    </div>
                                </div>

                                <div class="col-6 col-md-3">
                                    <div class="border-right pe-2 pe-lg-0">
                                        <h6 class="text-445B64 fs-14">Machine Idle Time</h6>
                                        <h5 class="text-0D161A mb-0 fw-bolder">
                                            200
                                        </h5>
                                    </div>
                                </div>

                                <div class="col-6 col-md-3">
                                    <div class="pe-2 pe-lg-0">
                                        <h6 class="text-445B64 fs-14">Operational Hours</h6>
                                        <h5 class="text-0D161A mb-0 fw-bolder">
                                            1200
                                        </h5>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div> 
                
            <div class="row">
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
                                                        <tr>
                                                            <th scope="col" class="text-6C7D83 py-2 px-3 fw-medium"> SO No. </th>
                                                            <th scope="col" class="text-6C7D83 py-2 px-3 fw-medium"> Date </th>
                                                            <th scope="col" class="text-6C7D83 py-2 px-3 fw-medium"> Process </th>
                                                            <th scope="col" class="text-6C7D83 py-2 px-3 fw-medium"> Duration </th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @if(!empty($soHistory)) @foreach($soHistory as $history)
                                                        <tr>
                                                            <td class="text-445B64 p-3 fw-semibold">
                                                                <div class="d-flex align-items-center">
                                                                    <span class="table-square-icon bg-F0F5F6 rounded-3"
                                                                        style="font-size: 12px; font-weight: 500;">
                                                                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 12 12" fill="none">
                                                                            <path
                                                                                d="M6.66667 12V10.6667H8V12H6.66667ZM5.33333 10.6667V7.33333H6.66667V10.6667H5.33333ZM10.6667 8.66667V6H12V8.66667H10.6667ZM9.33333 6V4.66667H10.6667V6H9.33333ZM1.33333 7.33333V6H2.66667V7.33333H1.33333ZM0 6V4.66667H1.33333V6H0ZM6 1.33333V0H7.33333V1.33333H6ZM1 3H3V1H1V3ZM0 4V0H4V4H0ZM1 11H3V9H1V11ZM0 12V8H4V12H0ZM9 3H11V1H9V3ZM8 4V0H12V4H8ZM9.33333 12V10H8V8.66667H10.6667V10.6667H12V12H9.33333ZM6.66667 7.33333V6H9.33333V7.33333H6.66667ZM4 7.33333V6H2.66667V4.66667H6.66667V6H5.33333V7.33333H4ZM4.66667 4V1.33333H6V2.66667H7.33333V4H4.66667ZM1.5 2.5V1.5H2.5V2.5H1.5ZM1.5 10.5V9.5H2.5V10.5H1.5ZM9.5 2.5V1.5H10.5V2.5H9.5Z"
                                                                                fill="#445B64" />
                                                                        </svg>
                                                                    </span>
                                                                    <h6 class="text-445B64 mb-0"> {{ $history->soProduct->so_no }} </h6>
                                                                </div>
                                                            </td>
                                                            <td scope="row" class="p-3">
                                                                <div class="">
                                                                    <h6 class="text-445B64 mb-0 fw-medium">24 Oct 2024</h6>
                                                                    <h6 class="text-6C7D83 mb-0 fs-13">06:30 AM</h6>
                                                                </div>
                                                            </td>
                                                            <td class="text-445B64 p-3 fw-semibold">{{ $history->operation->operation_name }}</td>
                                                            <td class="text-445B64 p-3 fw-semibold">24 Mins</td>
                                                        </tr>
                                                        @endforeach @endif
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
    </div>
    
<script>
  function updateMachineStatus(select) {
    const newStatus = select.value;
    const machineId = $(select).data('machine-id'); // assuming you passed data-machine-id

    $.ajax({
        url: '{{ route("admin.updateMachineStatus") }}',
        method: 'POST',
        data: {
            machine_id: machineId,
            status: newStatus,
            _token: '{{ csrf_token() }}'
        },
        success: function (response) {
            console.log(response.message);

            // Update icon
            let icon = $('#statusIcon');
            switch (newStatus) {
                case 'active':
                    icon.attr('src', '{{ asset("assets/images/completeDot.png") }}');
                    break;
                case 'maintenance':
                    icon.attr('src', '{{ asset("assets/images/notCompleteDot.png") }}');
                    break;
                case 'in-working':
                    icon.attr('src', '{{ asset("assets/images/progressDot.png") }}');
                    break;
                case 'breakdown':
                    icon.attr('src', '{{ asset("assets/images/notCompleteDot.png") }}');
                    break;
            }
        },
        error: function (xhr) {
            alert('Failed to update status.');
            console.error(xhr.responseText);
        }
    });
  }

</script>

@stop