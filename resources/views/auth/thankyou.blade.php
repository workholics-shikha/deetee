<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <title> {{ env('APP_NAME') }} - Thank You </title>

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
                            <script> toastr.success("{{ session('success') }}"); </script>
                        @endif

                        <div class="col-lg-7 right">
                            <div class="heading px-4">
                                <h2 class="fw-bold text-0B1A12 text-center mb-3"> Thank you! </h2>

                                <h5 class="text-939997 mb-4 text-center fw-normal"> We've sent password reset instructions to your email address. If no email is received within two minutes, click on resend link below. </h5>

                            </div>

                            <!-- General Login Error -->

                            <div class="forget px-4 mt-4 text-center">
                                <a href="{{ route('login') }}"
                                    class="bg-0199E5 rounded-3 text-white border-0 fw-semibold login-btn btn py-3 px-5">
                                    <p class="text-white fw-semibold mb-0"> Back to login </p>
                                </a>
                            </div>

                            <div class="px-4 mt-4 text-center">
                             
                                <button class="text-0199E5 rounded-3 w-100 text-center border-0 bg-white my-4 fw-semibold" type="button" id="resendButton" style="display:none"> <h5> Resend link </h5> </button>

                                <span class="timer-line"> Resend link in <b> <span id="timer" style="font-size: 20px"> 02:00 </span>  </b> seconds </span>
                                
                            </div>

                            <input type="hidden" id="token" value="{{ request()->segment(2) }}">
                           

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
    <script src="{{ asset('assets/timer.js') }}"></script>
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

            const resendButton  =  $('#resendButton');

            resendButton.on('click', function() { 

            const currentUrl = window.location.href;

            // Create a URL object and extract the token parameter
            const urlParams  = new URLSearchParams(new URL(currentUrl).search);

            const token      = urlParams.get('token'); 

            var url          = "{{ route('admin.resend.submit') }}";
 
                $.ajax({
                    url: url,
                    type: "POST",
                    data: {
                        '_token': $('meta[name="csrf-token"]').attr('content'),
                         token: token,
                    },
                    
                    success: function(response) {
                    if (response.button_disabled) {
                            successToaster(response.message);
                            resendButton.prop('disabled', true);
                        } else {
                            resendButton.prop('disabled', false);
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
