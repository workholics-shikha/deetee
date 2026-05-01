@section('title', 'Add User Details')
@extends('layouts.app')
@section('content')

<div class="main-content-area">
    <div class="">
        <h5 class="text-0077B3 fw-semibold mt-5 ms-3"> <i class="fa-solid fa-long-arrow-left me-1 text-637381"></i>
            <a class="text-637381" style="text-decoration:none;" href="{{ route('admin.users') }}"> Users</a>
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
                                    <h5 class="fw-semibold mb-0"> Add User </h5>
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

                                    @if ($errors->any())
                                    <div class="alert alert-danger">
                                        <ul>
                                            @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                    @endif

                                    <form id="userForm" method="post" action="{{ route('admin.user.save') }}"
                                        enctype="multipart/form-data">
                                        @csrf

                                        <div class="row">
                                            <div class="col-6 mb-3">
                                                <label for="name" class="form-label">Name</label>
                                                <input type="text"
                                                    class="form-control @error('name') is-invalid @enderror" id="name"
                                                    name="name" value="{{ old('name') }}" placeholder="Enter your name">
                                                @error('name')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>

                                            <div class="col-6 mb-3">
                                                <label for="email" class="form-label">Email</label>
                                                <input type="text"
                                                    class="form-control @error('email') is-invalid @enderror" id="email"
                                                    name="email" placeholder="Enter your email"
                                                    autocomplete="off">
                                                @error('email')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-6 mb-3">
                                                <label for="phone" class="form-label">Phone</label>
                                                <input type="text"
                                                    class="form-control @error('phone') is-invalid @enderror" id="phone"
                                                    name="phone" placeholder="Enter your Phone">
                                                @error('phone')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>

                                            <div class="col-6 mb-3">
                                                <label for="message" class="form-label">Select Role</label>
                                                <select class="form-control @error('role') is-invalid @enderror"
                                                    name="role" id="role">
                                                    <option value=""> Select Role</option>
                                                    @foreach ($role as $roleName)
                                                    <option value="{{ $roleName->id }}">
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
                                                    name="shift">
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
                                                    <option value="1"> Tooling </option>
                                                    <option value="2"> RMR </option>
                                                    <option value="3"> TMR </option>
                                                </select>
                                            </div>

                                            <div class="col-6 mb-3">
                                                <label for="phone" class="form-label"> Department </label>
                                                <select class="form-control @error('department') is-invalid @enderror"
                                                    name="department">
                                                    <option value=""> Select department </option>
                                                    @if (isset($department))
                                                    @foreach ($department as $departmentName)
                                                    <option> {{ ucfirst($departmentName->department) }}
                                                    </option>
                                                    @endforeach
                                                    @endif
                                                </select>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-6 mb-3">
                                                <label for="phone" class="form-label"> Designation </label>
                                                <select class="form-control @error('designation') is-invalid @enderror"
                                                    name="designation">
                                                    <option value=""> Select designation </option>
                                                    @if (isset($designation))
                                                    @foreach ($designation as $designationName)
                                                    <option> {{ ucfirst($designationName->designation) }}
                                                    </option>
                                                    @endforeach
                                                    @endif
                                                </select>
                                            </div>

                                            <div class="col-6 mb-3">
                                                <label for="phone" class="form-label"> Employee group </label>
                                                <select
                                                    class="form-control @error('employee_group') is-invalid @enderror"
                                                    name="employee_group">
                                                    <option value=""> Select employee group </option>
                                                    @if (isset($employee_group))
                                                    @foreach ($employee_group as $group)
                                                    <option> {{ ucfirst($group->employee_group) }}
                                                    </option>
                                                    @endforeach
                                                    @endif
                                                </select>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-6 mb-3">
                                                <label for="password" class="form-label">Password</label>
                                                <input type="password"
                                                    class="form-control @error('password') is-invalid @enderror"
                                                    id="password" name="password"  
                                                    placeholder="Enter Password" autocomplete="off">
                                                @error('password')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>

                                        <button type="submit" class="btn btn-primary" id="userForm1"> Submit </button>
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

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.5/jquery.validate.min.js"></script>

<script>
$(function () {
    const $form = $("#userForm");
    const $role = $("#role");
    const OPERATOR_ROLE_ID = "2";

    $.validator.addMethod("validName", function (value) {
    const v = $.trim(value);

    // 1) length
    if (v.length < 2 || v.length > 50) return false;

    // 2) allowed characters
    // letters + spaces + . ' -
    if (!/^[A-Za-z.'-]+(?:\s+[A-Za-z.'-]+)*$/.test(v)) return false;

    // 3) no double spaces
    if (/\s{2,}/.test(v)) return false;

    return true;
  }, "Enter a valid name (letters and spaces only).");

    function roleVal() {
        return ($role.val() || "").toString();
    }

    function isOperator() {
        return roleVal() === OPERATOR_ROLE_ID;
    }

    function isRoleSelected() {
        return roleVal() !== "";
    }

    // Required only if role is selected AND role is NOT operator
    $.validator.addMethod("requiredForNonOperator", function (value) {
        if (!isRoleSelected()) return true;  // don't block user before role selection
        if (isOperator()) return true;       // operator: not required
        return $.trim(value).length > 0;     // other roles: required
    }, "This field is required.");

    $form.validate({
        rules: {
            name: { required: true, validName: true },
            role: { required: true },
            department: { required: true },
            unit: { required: true },
            designation: { required: true },

            email: { requiredForNonOperator: true, email: true },
            password: { requiredForNonOperator: true, minlength: 6 }
        },
        messages: {
            email: { requiredForNonOperator: "Email is required for this role." },
            password: { requiredForNonOperator: "Password is required for this role." }
        },

        errorElement: "div",
        errorClass: "invalid-feedback",
        highlight: function (el) { $(el).addClass("is-invalid"); },
        unhighlight: function (el) { $(el).removeClass("is-invalid"); },
        errorPlacement: function (error, element) { error.insertAfter(element); }
    });

    $role.on("change", function () {  

         console.log("role val =", $(this).val());
        // If operator, clear email/password and their errors
        if (isOperator()) {
            $("#email, #password").val("").removeClass("is-invalid");
            // Remove only email/password errors, not the whole form
            $("#email-error, #password-error").remove();
        }
        $("#email").valid();
        $("#password").valid();
    });
});
</script>

@stop