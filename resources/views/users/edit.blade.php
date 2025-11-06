@section('title', 'Update User Details')
@extends('layouts.app')
@section('content')

    <div class="main-content-area">
        <div class="">
            <h5 class="text-0077B3 fw-semibold mt-5 ms-3"> <i class="fa-solid fa-long-arrow-left me-1 text-637381"></i>
                Users / <a class="text-637381" style="text-decoration:none;" href="{{ route('admin.users') }}">
                    {{ $user->name }} # {{ $user->id }}</a>
            </h5>
        </div>
        <div class="main-content">
            <div class="row">
                <div class="col-12">
                    <div class="card border-0 rounded-3 mb-1">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-12 col-md-6 mb-3 mb-lg-0">
                                    <div class="d-flex align-items-center">
                                        <h5 class="fw-semibold mb-0"> Update User </h5>
                                    </div>
                                </div>
                                <div class="col-12 col-md-6">

                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card border-0 rounded-3">
                        <div class="card-body">
                            <div class="row mb-4">
                                <div class="col-12">

                                    <div class="container mt-6">

                                        <form method="post" action="{{ route('admin.user.update') }}"
                                            enctype="multipart/form-data">
                                            @csrf
                                            <input type="hidden" name="id" value="{{ $user->id }}">

                                            <div class="row">
                                                <div class="col-6 mb-3">
                                                    <label for="name" class="form-label">Name</label>
                                                    <input type="text"
                                                        class="form-control @error('name') is-invalid @enderror"
                                                        id="name" name="name" value="{{ $user->name }}"
                                                        placeholder="Enter your name">
                                                    @error('name')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>

                                                <div class="col-6 mb-3">
                                                    <label for="email" class="form-label">Email</label>
                                                    <input type="text"
                                                        class="form-control @error('email') is-invalid @enderror"
                                                        id="email" name="email" placeholder="Enter your email"
                                                        value="{{ $user->email }}" autocomplete="off">
                                                    @error('email')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-6 mb-3">
                                                    <label for="phone" class="form-label">Phone</label>
                                                    <input type="text"
                                                        class="form-control @error('phone') is-invalid @enderror"
                                                        id="phone" name="phone" placeholder="Enter your Phone"
                                                        value="{{ $user->phone }}">
                                                    @error('phone')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>

                                                <div class="col-6 mb-3">
                                                    <label for="message" class="form-label">Select Role</label>
                                                    <select class="form-control @error('role') is-invalid @enderror"
                                                        name="role">
                                                        <option value=""> Select Role</option>
                                                        @foreach ($role as $roleName)
                                                            <option value="{{ $roleName->id }}"
                                                                @if ($roleName->id == $user->role) {{ 'selected' }} @endif>
                                                                {{ ucfirst($roleName->name) }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                @error('role')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>

                                            <div class="row">
                                                <div class="col-6 mb-3">
                                                    <label for="phone" class="form-label">Shift</label>
                                                    <input type="text" class="form-control" placeholder="Enter Shift"
                                                        name="shift" value="{{ $user->shift }}">
                                                </div>

                                                <div class="col-6 mb-3">
                                                    <label for="phone" class="form-label">Upload Profile Image</label>
                                                    <input type="file" class="form-control" name="image">
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-6 mb-3">
                                                    <label for="message" class="form-label">Select Unit</label>
                                                    <select class="form-control @error('unit') is-invalid @enderror"
                                                        name="unit">
                                                        <option value=""> Select Unit </option>
                                                        <option value="1" @if($user->unit_name == 'Tooling'){{'selected'}}@endif> Tooling </option>
                                                        <option value="2" @if($user->unit_name == 'RMR'){{'selected'}}@endif> RMR </option>
                                                        <option value="3" @if($user->unit_name == 'TMR'){{'selected'}}@endif> TMR </option>
                                                    </select>

                                                </div>

                                                <div class="col-6 mb-3">
                                                    <label for="phone" class="form-label"> Department </label>
                                                    <select class="form-control @error('department') is-invalid @enderror"
                                                        name="department">
                                                        <option value=""> Select department </option>
                                                        @if (isset($department))
                                                            @foreach ($department as $departmentName)
                                                                <option
                                                                    @if ($departmentName->department == $user->department) {{ 'selected' }} @endif>
                                                                    {{ ucfirst($departmentName->department) }} </option>
                                                            @endforeach
                                                        @endif
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="row">
                                                {{-- <div class="col-6 mb-3">
                                                    <label for="phone" class="form-label"> Designation </label>
                                                    <select class="form-control @error('designation') is-invalid @enderror"
                                                        name="designation">
                                                        <option value=""> Select designation </option>
                                                        @if (isset($designation))
                                                            @foreach ($designation as $designationName)
                                                                <option
                                                                    @if ($designationName->designation == $user->designation) {{ 'selected' }} @endif>
                                                                    {{ ucfirst($designationName->designation) }}
                                                                </option>
                                                            @endforeach
                                                        @endif
                                                    </select>
                                                </div> --}}

                                                <div class="col-6 mb-3">
                                                    <label for="phone" class="form-label"> Employee group </label>
                                                    <select
                                                        class="form-control @error('employee_group') is-invalid @enderror"
                                                        name="employee_group">
                                                        <option value=""> Select employee group </option>
                                                        @if (isset($employee_group))
                                                            @foreach ($employee_group as $group)
                                                                <option
                                                                    @if ($group->employee_group == $user->employee_group) {{ 'selected' }} @endif>
                                                                    {{ ucfirst($group->employee_group) }}
                                                                </option>
                                                            @endforeach
                                                        @endif
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="d-flex justify-content-center w-100">
                                                 <button type="submit" class="btn btn-primary rounded-3 px-5">
                                                        Submit </button> 
                                            </div>
                                        </form>

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
