<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <title> {{ env('APP_NAME') }} - Forgot Password </title>

    <link rel="stylesheet" href="{{ asset('assets/style.css') }}" />

    <!-- Additional Links -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.1/css/all.min.css" />
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js"></script>

    <style>
        .error {
            color: red;
            font-size: 0.8em;
        }

        /* Style for disabled form elements */
        button:disabled,
        input:disabled {
            background-color: #ccc; 
            color: #666; 
            border: 1px solid #999;
            cursor: not-allowed; 
            opacity: 0.7; 
        }
    </style>
</head>

<body>
    <div class="container-fluid signin-page p-0">
        <nav class="navbar bg-transparent fixed-top py-3">
            <div class="">
                <a class="navbar-brand px-4" href="#">
                    <img src="{{ asset('assets/images/DeeTee Logo Mark.png') }}" alt="DeeTee" />
                </a>
            </div>
        </nav>
        <div class="row h-100">
            <div class="col-12 col-lg-6 d-none d-lg-block h-100">
                <img src="{{ asset('assets/images/signin left.png') }}" alt="" class="w-100 h-100" />
            </div>
            <div class="col-12 col-lg-6 h-100 position-relative">

                <form method="POST" action={{ route('admin.forgot.submit') }} class="h-100">
                    @csrf
                    <div class="row justify-content-center align-items-center h-100">

                        @if (session('success'))
                            <script>
                                toastr.success("{{ session('success') }}");
                            </script>
                        @endif

                        <div class="col-lg-7 right">
                            <div class="heading px-4">
                                <h5 class="fw-bold text-0D161A"> Forgot Password </h5>
                                <p class="text-445B64 mb-4">
                                    Please enter your registered email
                                </p>
                            </div>

                            <div class="mb-4 px-4">
                                <input type="text"
                                    class="form-control bg-F0F5F6 text-445B64 p-3 rounded-3 fw-normal shadow-none"
                                    id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Email ID"
                                    name="email" value="{{ old('email') }}" />
                                @error('email')
                                    <span class="error">{{ $message }}</span>
                                @enderror
                                @if ($errors->has('login'))
                                    <span class="error"> {{ $errors->first('login') }} </span>
                                @endif
                            </div>

                            <!-- General Login Error -->

                            <div class="log-btn px-4 mt-4">
                                <button class="bg-0199E5 login-btn rounded-3 text-white w-100 py-3 border-0 fw-semibold"
                                    type="submit" id="sbt-btn"> Submit </button>
                            </div>

                            <div class="forget px-4 mt-4 text-center">
                                <a href="{{ route('login') }}" class="text-decoration-none">
                                    <p class="text-0199E5 fw-semibold">Back to login</p>
                                </a>
                            </div>

                        </div>
                    </div>
                </form>

                <div class="text-center position-absolute start-0 bottom-0 end-0">
                    <a href="" class="text-decoration-none">
                        <p class="text-445B64"> Terms & Conditions • Privacy Policy </p>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <script>
        function successToaster(message) {
            toastr.options.closeButton = true;
            toastr.success(message, '', {
                timeOut: 5000
            });
        }

        function errorToaster(message) {
            toastr.options.closeButton = true;
            toastr.error(message, '', {
                timeOut: 5000
            });
        }
    </script>

    @if (\Session::has('redirectWithErrors'))
        <script>
            errorToaster("{!! \Session::get('redirectWithErrors') !!}");
        </script>
    @endif

    @if (\Session::has('redirectWithSuccess'))
        <script>
            successToaster("{!! \Session::get('redirectWithSuccess') !!}");
        </script>
    @endif

    <script>
        $(document).ready(function() {
            // Toggle password visibility on button click
            $('#togglePassword').click(function() {
                const passwordField = $('#password');
                const fieldType = passwordField.attr('type') === 'password' ? 'text' : 'password';

                passwordField.attr('type', fieldType);

                // Update button icon and styles
                $(this)
                    .toggleClass('fa-eye fa-eye-slash') // Toggle between eye and eye-slash icons
                    .toggleClass('text-445B64'); // Ensure consistent styling
            });

            $('body').on('keyup', '#exampleInputEmail1', function() {

                var email = $(this).val();
                var url = "{{ route('admin.check.email') }}";

                $.ajax({
                    url: url,
                    type: "POST",
                    data: {
                        '_token': $('meta[name="csrf-token"]').attr('content'),
                        email: email,
                    },
                    success: function(response) {
                        if (response.button_disabled) {
                            $('#sbt-btn').prop('disabled', true);
                            $('#exampleInputEmail1').val('');
                            successToaster(response.message);
                        } else {
                            $('#sbt-btn').prop('disabled', false);
                        }
                    },
                    error: function(xhr, status, error) {
                        errorToaster(response.message);
                    },
                });
            });

        });
    </script>
</body>

</html>
