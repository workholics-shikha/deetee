@section('title', 'Users')
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
                                        <a href="#" class="tab mb-0 active" onclick="openTab(event, 'Operators')">
                                            <h6 class="text-0D161A">Operators</h6>
                                        </a>
                                        <a href="#" class="tab mb-0" onclick="openTab(event, 'Supervisors')">
                                            <h6 class="text-0D161A">Supervisors</h6>
                                        </a>
                                        <a href="#" class="tab mb-0" onclick="openTab(event, 'Planning-Team')">
                                            <h6 class="text-0D161A">Planning Team</h6>
                                        </a>
                                        <a href="#" class="tab mb-0" onclick="openTab(event, 'Unit-Head')">
                                            <h6 class="text-0D161A">Unit Head</h6>
                                        </a>
                                        <a href="#" class="tab mb-0" onclick="openTab(event, 'Executive-Management')">
                                            <h6 class="text-0D161A">Executive Management</h6>
                                        </a>
                                        <a href="#" class="tab mb-0" onclick="openTab(event, 'Administrators')">
                                            <h6 class="text-0D161A">Administrators</h6>
                                        </a>
                                        <div style="padding-left: 485px;">
                                            <a class="btn btn-primary" href="{{ route('admin.user.add') }}">
                                                Add User
                                            </a>
                                            {{-- <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                                                data-bs-target="#formModal"> Add User </button> --}}
                                        </div>
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
                                            <span class="text-0D161A fw-semibold me-1 countItems"> </span>
                                            <span class="text-445B64 fw-medium">Items</span>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-12 col-lg-4 d-flex align-items-center">
                                    <div class="d-flex position-relative" style="width: -webkit-fill-available;">
                                        <input type="search" class="form-control me-2 shadow-none searchInputUserTab"
                                            style="padding-left: 35px; background-color: #F0F5F6;" placeholder="Search"
                                            aria-label="Search" search-url="{{ route('admin.user-search') }}">
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
                    <div class="card border-0 rounded-3 overflow-auto">
                        <div class="card-body p-0">
                            <div class="row">
                                <div class="col-12">

                                    <div class="table-responsive">
                                        {{-- Operators --}}
                                        <div id="Operators" class="tabcontent" style="display: none;">
                                            <table class="table rounded-3">
                                                <thead>
                                                    <tr>
                                                        <th scope="col" class="text-445B64 p-3">Name & ID</th>
                                                        <th scope="col" class="text-445B64 p-3">Designation</th>
                                                        <th scope="col" class="text-445B64 p-3">Department</th>
                                                        <th scope="col" class="text-445B64 p-3">Unit </th>
                                                        <th scope="col" class="text-445B64 p-3">Unit Name</th>
                                                        <th scope="col" class="text-445B64 p-3">Employee Group</th>
                                                        <th scope="col" class="text-445B64 p-3">Status</th>
                                                        <th scope="col" class="text-445B64 p-3">Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody id="operator-table-data">

                                                    @php $i=0; @endphp
                                                    @foreach ($data['operator'] as $operator)
                                                        @php $i++; @endphp
                                                        <tr>
                                                            <td class="p-3">
                                                                <div class="d-flex align-items-center img-zoom">
                                                                    <img width="30" height="30" viewBox="0 0 12 12"
                                                                        fill="none" src="{{ $operator->user_qr_code }}"
                                                                        alt="" class="me-2" />
                                                                    <span class="ps-2">
                                                                        <a
                                                                            class="text-0D161A fw-semibold mb-0 text-decoration-none">
                                                                            {{ $operator->name }} <br /> <span
                                                                                class="text-445B64"
                                                                                style="font-size: 12px">#{{ $operator->username }}
                                                                            </span> </a>
                                                                    </span>
                                                                </div>
                                                            </td>
                                                            <td class="p-3">{{ $operator->designation }} </td>
                                                            <td class="p-3">{{ $operator->department }} </td>
                                                            <td class="p-3">{{ $operator->unit }} </td>
                                                            <td class="p-3">{{ $operator->unit_name }} </td>
                                                            <td class="p-3">{{ $operator->employee_group }} </td>

                                                            <td class="p-3" style="color: #00b200"> Present
                                                                <img src="{{ asset('assets/images/completeDot.png') }}"
                                                                    style="width: 20px; height: 20px" />
                                                            </td>
                                                            <td>
                                                                <a href="{{ route('admin.user.edit', [$operator->id]) }}">
                                                                    <i
                                                                        class="rounded-pill fw-bolder fa-solid fa-pencil"></i>
                                                                </a> &nbsp;

                                                                <a onclick="return confirm('Are you sure you want to delete this user?');"
                                                                    href="{{ route('admin.user.delete', [$operator->id]) }}">
                                                                    <i
                                                                        class="rounded-pill fw-bolder fa-solid fa-trash"></i>
                                                                </a>
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                            <!-- Pagination Section -->
                                            <div class="paginationU d-flex justify-content-center" id="operator-paginate">
                                                {{ $data['operator']->appends(['tab' => 'Operator'])->links() }}
                                            </div>
                                        </div>

                                        {{-- Supervisors --}}
                                        <div id="Supervisors" class="tabcontent" style="display: none;">
                                            <table class="table rounded-3">
                                                <thead>
                                                    <tr>
                                                        <th scope="col" class="text-445B64 p-3">
                                                            Name & ID
                                                        </th>
                                                        <th scope="col" class="text-445B64 p-3">Role</th>
                                                        <th scope="col" class="text-445B64 p-3">Email</th>
                                                        <th scope="col" class="text-445B64 p-3">Phone</th>
                                                        <th scope="col" class="text-445B64 p-3">Status</th>
                                                        <th scope="col" class="text-445B64 p-3">Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody id="supervisors-table-data">

                                                    @php $i=0; @endphp
                                                    @foreach ($data['supervisor'] as $supervisor)
                                                        @php $i++; @endphp
                                                        <tr>
                                                            <td class="p-3">

                                                                <div class="d-flex align-items-center img-zoom">
                                                                    <img width="30" height="30"
                                                                        viewBox="0 0 12 12" fill="none"
                                                                        src="{{ $supervisor->user_qr_code }}"
                                                                        alt="" class="me-2" />
                                                                    <span class="ps-2">
                                                                        <a
                                                                            class="text-0D161A fw-semibold mb-0 text-decoration-none">{{ $supervisor->name }}<br /><span
                                                                                class="text-445B64"
                                                                                style="font-size: 12px">#{{ $supervisor->id }}</span></a>
                                                                    </span>
                                                                </div>
                                                            </td>
                                                            <td class="p-3"> {{ $supervisor->roleName->name }}</td>
                                                            <td class="p-3"> {{ $supervisor->email }}</td>
                                                            <td class="p-3"> {{ $supervisor->phone }}</td>
                                                            <td class="p-3" style="color: #00b200">
                                                                Present
                                                                <img src="{{ asset('assets/images/completeDot.png') }}"
                                                                    style="width: 20px; height: 20px" />
                                                            </td>
                                                            <td>
                                                                <a
                                                                    href="{{ route('admin.user.edit', [$supervisor->id]) }}">
                                                                    <i
                                                                        class="rounded-pill fw-bolder fa-solid fa-pencil"></i>
                                                                </a> &nbsp;

                                                                <a onclick="return confirm('Are you sure you want to delete this user?');"
                                                                    href="{{ route('admin.user.delete', [$supervisor->id]) }}">
                                                                    <i
                                                                        class="rounded-pill fw-bolder fa-solid fa-trash"></i>
                                                                </a>
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                            <!-- Pagination Section -->
                                            <div class="paginationU d-flex justify-content-center"
                                                id="supervisors-paginate">
                                                {{ $data['supervisor']->appends(['tab' => 'Supervisors'])->links() }}
                                            </div>
                                        </div>

                                        {{-- Planning Team --}}
                                        <div id="Planning-Team" class="tabcontent" style="display: none;">
                                            <table class="table rounded-3">
                                                <thead>
                                                    <tr>
                                                        <th scope="col" class="text-445B64 p-3">
                                                            Name & ID
                                                        </th>
                                                        <th scope="col" class="text-445B64 p-3">Role</th>
                                                        <th scope="col" class="text-445B64 p-3">Email</th>
                                                        <th scope="col" class="text-445B64 p-3">Phone</th>
                                                        <th scope="col" class="text-445B64 p-3">Status</th>
                                                        <th scope="col" class="text-445B64 p-3">Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody id="planning-table-data">

                                                    @php $i=0; @endphp
                                                    @foreach ($data['ceo'] as $ceo)
                                                        @php $i++; @endphp
                                                        <tr>
                                                            <td class="p-3">

                                                                <div class="d-flex align-items-center img-zoom">
                                                                    <img width="30" height="30"
                                                                        viewBox="0 0 12 12" fill="none"
                                                                        src="{{ $ceo->user_qr_code }}" class="me-2" />
                                                                    <span class="ps-2">
                                                                        <a
                                                                            class="text-0D161A fw-semibold mb-0 text-decoration-none">{{ $ceo->name }}<br /><span
                                                                                class="text-445B64"
                                                                                style="font-size: 12px">#{{ $ceo->id }}</span></a>
                                                                    </span>
                                                                </div>
                                                            </td>
                                                            <td class="p-3"> {{ $ceo->roleName->name }}</td>
                                                            <td class="p-3"> {{ $ceo->email }}</td>
                                                            <td class="p-3"> {{ $ceo->phone }}</td>
                                                            <td class="p-3" style="color: #00b200">
                                                                Present
                                                                <img src="{{ asset('assets/images/completeDot.png') }}"
                                                                    style="width: 20px; height: 20px" />
                                                            </td>
                                                            <td>
                                                                <a href="{{ route('admin.user.edit', [$ceo->id]) }}"> <i
                                                                        class="rounded-pill fw-bolder fa-solid fa-pencil"></i>
                                                                </a> &nbsp;

                                                                <a onclick="return confirm('Are you sure you want to delete this user?');"
                                                                    href="{{ route('admin.user.delete', [$ceo->id]) }}">
                                                                    <i
                                                                        class="rounded-pill fw-bolder fa-solid fa-trash"></i>
                                                                </a>
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                            <!-- Pagination Section -->
                                            <div class="paginationU d-flex justify-content-center" id="planning-paginate">
                                                {{ $data['ceo']->appends(['tab' => 'Planning-Team'])->links() }}
                                            </div>
                                        </div>

                                        {{-- Unit Head --}}
                                        <div id="Unit-Head" class="tabcontent" style="display: none;">
                                            <table class="table rounded-3">
                                                <thead>
                                                    <tr>
                                                        <th scope="col" class="text-445B64 p-3">
                                                            Name & ID
                                                        </th>
                                                        <th scope="col" class="text-445B64 p-3">Role</th>
                                                        <th scope="col" class="text-445B64 p-3">Email</th>
                                                        <th scope="col" class="text-445B64 p-3">Phone</th>
                                                        <th scope="col" class="text-445B64 p-3">Status</th>
                                                        <th scope="col" class="text-445B64 p-3">Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody id="unitHead-table-data">

                                                    @php $i=0; @endphp
                                                    @foreach ($data['unit_head'] as $unit_head)
                                                        @php $i++; @endphp
                                                        <tr>
                                                            <td class="p-3">
                                                                <div class="d-flex align-items-center img-zoom">
                                                                    <img width="30" height="30"
                                                                        viewBox="0 0 12 12" fill="none"
                                                                        src="{{ $unit_head->user_qr_code }}"
                                                                        alt="" class="me-2" />
                                                                    <span class="ps-2">
                                                                        <a
                                                                            class="text-0D161A fw-semibold mb-0 text-decoration-none">{{ $unit_head->name }}<br /><span
                                                                                class="text-445B64"
                                                                                style="font-size: 12px">#{{ $unit_head->id }}</span></a>
                                                                    </span>
                                                                </div>
                                                            </td>
                                                            <td class="p-3"> {{ $unit_head->roleName->name }}</td>
                                                            <td class="p-3"> {{ $unit_head->email }}</td>
                                                            <td class="p-3"> {{ $unit_head->phone }}</td>
                                                            <td class="p-3" style="color: #00b200">
                                                                Present
                                                                <img src="{{ asset('assets/images/completeDot.png') }}"
                                                                    alt="" class=""
                                                                    style="width: 20px; height: 20px" />
                                                            </td>
                                                            <td>
                                                                <a
                                                                    href="{{ route('admin.user.edit', [$unit_head->id]) }}">
                                                                    <i
                                                                        class="rounded-pill fw-bolder fa-solid fa-pencil"></i>
                                                                </a> &nbsp;

                                                                <a onclick="return confirm('Are you sure you want to delete this user?');"
                                                                    href="{{ route('admin.user.delete', [$unit_head->id]) }}">
                                                                    <i
                                                                        class="rounded-pill fw-bolder fa-solid fa-trash"></i>
                                                                </a>
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                            <!-- Pagination Section -->
                                            <div class="paginationU d-flex justify-content-center" id="unitHead-paginate">
                                                {{ $data['unit_head']->appends(['tab' => 'Unit-Head'])->links() }}
                                            </div>
                                        </div>

                                        {{-- Executive Management --}}
                                        <div id="Executive-Management" class="tabcontent" style="display: none;">
                                            <table class="table rounded-3">
                                                <thead>
                                                    <tr>
                                                        <th scope="col" class="text-445B64 p-3">
                                                            Name & ID
                                                        </th>
                                                        <th scope="col" class="text-445B64 p-3">Role</th>
                                                        <th scope="col" class="text-445B64 p-3">Email</th>
                                                        <th scope="col" class="text-445B64 p-3">Phone</th>
                                                        <th scope="col" class="text-445B64 p-3">Status</th>
                                                        <th scope="col" class="text-445B64 p-3">Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody id="executive-table-data">

                                                    @php $i=0; @endphp
                                                    @foreach ($data['hod'] as $hod)
                                                        @php $i++; @endphp
                                                        <tr>
                                                            <td class="p-3">
                                                                <div class="d-flex align-items-center img-zoom">
                                                                    <img width="30" height="30"
                                                                        viewBox="0 0 12 12" fill="none"
                                                                        src="{{ $hod->user_qr_code }}" alt=""
                                                                        class="me-2" />
                                                                    <span class="ps-2">
                                                                        <a
                                                                            class="text-0D161A fw-semibold mb-0 text-decoration-none">{{ $hod->name }}
                                                                            <br />
                                                                            <span class="text-445B64"
                                                                                style="font-size: 12px">#{{ $hod->id }}</span>
                                                                        </a>
                                                                    </span>
                                                                </div>
                                                            </td>
                                                            <td class="p-3"> {{ $hod->roleName->name }}</td>
                                                            <td class="p-3"> {{ $hod->email }}</td>
                                                            <td class="p-3"> {{ $hod->phone }}</td>

                                                            <td class="p-3" style="color: #00b200"> Present <img
                                                                    src="{{ asset('assets/images/completeDot.png') }}"
                                                                    style="width: 20px; height: 20px" />
                                                            </td>
                                                            <td>
                                                                <a href="{{ route('admin.user.edit', [$hod->id]) }}"> <i
                                                                        class="rounded-pill fw-bolder fa-solid fa-pencil"></i>
                                                                </a> &nbsp;

                                                                <a onclick="return confirm('Are you sure you want to delete this user?');"
                                                                    href="{{ route('admin.user.delete', [$hod->id]) }}">
                                                                    <i
                                                                        class="rounded-pill fw-bolder fa-solid fa-trash"></i>
                                                                </a>
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                            <!-- Pagination Section -->
                                            <div class="paginationU d-flex justify-content-center"
                                                id="executive-paginate">
                                                {{ $data['hod']->appends(['tab' => 'Executive-Management'])->links() }}
                                            </div>
                                        </div>

                                        {{-- Administrators --}}
                                        <div id="Administrators" class="tabcontent" style="display: none;">
                                            <table class="table rounded-3">
                                                <thead>
                                                    <tr>
                                                        <th scope="col" class="text-445B64 p-3">Name & ID </th>
                                                        <th scope="col" class="text-445B64 p-3">Role</th>
                                                        <th scope="col" class="text-445B64 p-3">Email</th>
                                                        <th scope="col" class="text-445B64 p-3">Phone</th>
                                                        <th scope="col" class="text-445B64 p-3">Status</th>
                                                        <th scope="col" class="text-445B64 p-3">Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody id="administrators-table-data">

                                                    @php $i=0; @endphp
                                                    @foreach ($data['admin'] as $admin)
                                                        @php $i++; @endphp
                                                        <tr>
                                                            <td class="p-3">

                                                                <div class="d-flex align-items-center img-zoom">

                                                                    <span class="ps-2"> <a
                                                                            class="text-0D161A fw-semibold mb-0 text-decoration-none">{{ $admin->name }}
                                                                            <br /> <span class="text-445B64"
                                                                                style="font-size: 12px">#{{ $admin->id }}</span></a>
                                                                    </span>
                                                                </div>
                                                            </td>

                                                            <td class="p-3">{{ $admin->roleName->name }}</td>
                                                            <td class="p-3">{{ $admin->email }}</td>
                                                            <td class="p-3">{{ $admin->phone }}</td>

                                                            <td class="p-3" style="color: #00b200">
                                                                Present
                                                                <img src="{{ asset('assets/images/completeDot.png') }}"
                                                                    alt="" class=""
                                                                    style="width: 20px; height: 20px" />
                                                            </td>
                                                            <td>
                                                                <a href="{{ route('admin.user.edit', [$admin->id]) }}"> <i
                                                                        class="rounded-pill fw-bolder fa-solid fa-pencil"></i>
                                                                </a> &nbsp;

                                                                @if ($admin->email != 'deetee_industries@gmail.com')
                                                                    <a onclick="return confirm('Are you sure you want to delete this user?');"
                                                                        href="{{ route('admin.user.delete', [$admin->id]) }}">
                                                                        <i
                                                                            class="rounded-pill fw-bolder fa-solid fa-trash"></i>
                                                                    </a>
                                                                @endif
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                            <!-- Pagination Section -->
                                            <div class="paginationU d-flex justify-content-center"
                                                id="administrators-paginate">
                                                {{ $data['admin']->appends(['tab' => 'Administrators'])->links() }}
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

    <!-- Modal NOT USING -->
    <div class="modal fade" id="formModal" tabindex="-1" aria-labelledby="formModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="formModalLabel">Add User Modal</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <!-- Form -->
                    <form method="post" action="{{ route('admin.user.add') }}" encytpe="multipart/form-data">
                        @csrf
                        <div class="mb-3">
                            <label for="name" class="form-label">Name</label>
                            <input type="text" class="form-control" id="name" name="name"
                                placeholder="Enter your name">
                            @error('name')
                                <span class="error">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="email" name="email"
                                placeholder="Enter your email">
                            @error('email')
                                <span class="error">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="phone" class="form-label">Phone</label>
                            <input type="text" class="form-control" id="phone" name="phone"
                                placeholder="Enter your Phone">
                        </div>

                        <div class="mb-3">
                            <label for="message" class="form-label">Select Role</label>
                            <select class="form-control" name="role">
                                <option value=""> Select Role</option>
                                <option value="operator"> Operator</option>
                                <option value="supervisor"> Supervisors</option>
                                <option value="HOD"> Planning-Team</option>
                                <option value="unit_head"> Unit-Head</option>
                                <option value="CEO"> Executive-Management</option>
                                <option value="admin"> Administrators</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="phone" class="form-label">Shift</label>
                            <input type="text" class="form-control" placeholder="Enter Shift" name="shift">
                        </div>

                        <div class="mb-3">
                            <label for="phone" class="form-label">Upload Profile Image</label>
                            <input type="file" class="form-control" name="image">
                        </div>

                        <div class="mb-3">
                            <label for="phone" class="form-label">Password</label>
                            <input type="password" class="form-control" placeholder="Enter Shift" name="password">
                        </div>

                        <button type="submit" class="btn btn-primary"> Submit </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    </div>

    {{-- Modal end --}}

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const urlParams = new URLSearchParams(window.location.search);
            const activeTab = urlParams.get("tab") || "Operators";

            openTab({
                currentTarget: document.querySelector(`[onclick="openTab(event, '${activeTab}')"]`)
            }, activeTab);

        });

        function openTab(evt, tabName) {
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
            document.getElementById(tabName).style.display = "block";
            evt.currentTarget.classList.add("active");

            // Update the URL to reflect the active tab and remove the page number
            const urlParams = new URLSearchParams(window.location.search);
            urlParams.set("tab", tabName); // Update the 'tab' parameter
            urlParams.delete("page"); // Remove the 'page' parameter if it exists

            // Use history.replaceState to update the URL without reloading the page
            const newUrl = `${window.location.pathname}?${urlParams.toString()}`;
            window.history.replaceState({}, "", newUrl);

            // Operators,  Supervisors, Planning-Team, Unit-Head, Executive-Management, Administrators
            val = 0;
            if (tabName == 'Operators') {
                val = "{{ $data['operator']->total() }}";
            } else if (tabName == 'Supervisors') {
                val = "{{ $data['supervisor']->total() }}";
            } else if (tabName == 'Planning-Team') {
                val = "{{ $data['ceo']->total() }}";
            } else if (tabName == 'Unit-Head') {
                val = "{{ $data['unit_head']->total() }}";
            } else if (tabName == 'Executive-Management') {
                val = "{{ $data['hod']->total() }}";
            } else if (tabName == 'Administrators') {
                val = "{{ $data['admin']->total() }}";
            }

            $('.countItems').html(val);
        }
    </script>

@stop
