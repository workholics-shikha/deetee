<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <title> {{ env('APP_NAME') }} - @yield('title') </title>

    <link rel="stylesheet" href="{{ asset('assets/style.css') }}" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.1/css/all.min.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" rel="stylesheet">
  
    <!-- Additional Links -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> 
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

    <style>
        /* 1) Sidebar must allow dropdown to paint outside item */
        .sidebar,
        .sidebar-nav,
        #sidebar,
        .navbar-vertical,
        aside {
            /* pick the one you actually have */
            overflow: visible !important;
        }

        /* 2) Make the dropdown sit above other sidebar links */
        .sidebar .nav-item.dropdown {
            position: relative;
            z-index: 1050;
            /* higher than siblings */
        }

        /* 3) Make the dropdown-menu overlay, not get buried */
        .sidebar .dropdown-menu {
            position: absolute;
            z-index: 2000;
            left: 0;
            /* keep aligned to sidebar */
            right: auto;
            top: 100%;
            margin-top: 6px;
        }

        /* 4) If some nav links/cards overlap due to z-index, keep them lower */
        .sidebar .nav-link {
            position: relative;
            z-index: 1;
        }
        .sidebar { overflow-y: auto; }

    </style>
</head>

<body>
    <div className="container-fluid p-0">
        <div className="dashboard-page">
            <!-- Header Start -->
            @include('layouts.include.header')
            <!-- Header End -->

            <!-- sidebar Start -->
            @include('layouts.include.sidebar')
            <!-- sidebar End -->

            @if (session('success'))
            <script>
                toastr.success("{{ session('success') }}");
            </script>
            @endif

            @yield('content')

        </div>
    </div>

    <!-- Additional Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        baseUrl = "{{ url('/') }}";

        function successToaster(message) {
            // toastr.remove();
            toastr.options.closeButton = true;
            toastr.success(message, '', {
                timeOut: 5000
            });
        }

        function errorToaster(message) {
            // toastr.remove();
            toastr.options.closeButton = true;
            toastr.error(message, '', {
                timeOut: 5000
            });
        }

        function showButtonLoader(id, text, action) {
            if (action === 'disable') {
                $('#' + id).prop('disabled', true);
            } else {
                $('#' + id).html(text);
                $('#' + id).prop('disabled', false);
            }
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

    <!-- footer Start -->
    @include('layouts.include.footer')
    <!-- footer End -->
    <script src="{{ asset('assets/custom.js') }}?ver=1.0.0"></script>
</body>

</html>