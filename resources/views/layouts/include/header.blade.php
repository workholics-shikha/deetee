<div class="header position-relative">
    <nav class="navbar fixed-top navbar-expand-lg shadow-sm bg-white p-0">
        <div class="container-fluid">
            <a class="navbar-brand p-3 mx-0" href="{{ route('admin.dashboard') }}">
                <img src="{{ asset('assets/images/deeTee-Logo.png') }}" alt="" class="">
            </a>

            <h5 class="me-auto ms-3 mb-0 text-0D161A fw-bold d-none d-lg-block"> @yield('title') </h5>

            <div class="d-flex justify-content-between" id="navbarNav">
                <ul class="navbar-nav ms-auto" style="display: contents;">

                    <li class="nav-item d-flex align-items-center me-2">
                        <div class="dropdown me-2 me-lg-0 pt-0">
                            <button class="p-0 border-0 bg-transparent dropdown-toggle headerNotificationBtn"
                                type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <div class="header-right-icon bg-F0F5F6 position-relative">
                                    <img src="{{ asset('assets/images/bell-icon.png') }}" alt="" class="">
                                    <p class="position-absolute bg-FF4842 text-white rounded-5 mb-0 fs-14"
                                        style="width: 20px; height:20px; top: -6px; right:-6px;">
                                        {{ getNotificationCount() }}</p>
                                </div>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end border-0 shadow">
                                <li>
                                    <a class="dropdown-item bg-white pt-2 px-3" href="#">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div class="me-0 me-lg-5">
                                                <h6 class="text-212B36 mb-0">Notifications</h6>

                                                @php
                                                    $count = getNotificationCount();
                                                    $newNotification = getNotificationInDrop();

                                                @endphp
                                                <h6 class="text-637381 fw-normal mb-0">
                                                    You have
                                                    {{ $count > 0 ? $count . ' unread ' . Str::plural('message', $count) : 'no unread messages' }}
                                                </h6>

                                            </div>
                                            <div class="ms-0 ms-lg-5">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="12"
                                                    viewBox="0 0 20 12" fill="none">
                                                    <path d="M19 1L9.375 11L8.56124 10.1545M5 6.45455L6 7.5"
                                                        stroke="#1F1F26" stroke-linecap="round"
                                                        stroke-linejoin="round" />
                                                    <path d="M15 1L5.375 11L1 6.45455" stroke="#1F1F26"
                                                        stroke-linecap="round" stroke-linejoin="round" />
                                                </svg>
                                            </div>
                                        </div>
                                    </a>
                                </li>
                                <hr class="my-2">
                                {{-- <h6 class="text-637381 fw-semibold px-3 mb-2"> NEW </h6> --}}
                                @if (!empty($newNotification) && count($newNotification) > 0)
                                    @foreach ($newNotification as $newNotify)
                                        @php
                                        $sentence = $newNotify->message;
                                            $parts = preg_split('/\s+(is|has)\s+/i', $sentence, 2);

                                            // Re-add the keyword to the second part
                                            if (
                                                count($parts) == 2 &&
                                                preg_match('/\s+(is|has)\s+/i', $sentence, $match)
                                            ) {
                                                $parts[1] = $match[1] . ' ' . $parts[1];
                                            }
                                        @endphp
                                        <li>
                                            <a class="dropdown-item py-2 bg-F1F3F5 mb-1" href="#">
                                                <div class="d-flex align-items-center notificationMsg">
                                                    <div class="notificationMsg-img border bg-F0F5F6 me-3">
                                                        <img src="{{ asset('assets/images/machine-dummy.png') }}" alt="Notification Icon">
                                                    </div>
                                                    <div>
                                                        <h6 class="mb-1">
                                                            <span class="text-0D161A fw-bolder">{{ $parts[0] }}</span>
                                                            <span class="text-637381">{{ $parts[1] ?? '' }}</span>
                                                        </h6>
                                                        <p class="text-919EAB fs-14 mb-0"> <i class="fa-regular fa-clock fs-14"></i> {{ \Carbon\Carbon::parse($newNotify->created_at)->format('d M Y') }} </p>
                                                    </div>
                                                </div>
                                            </a>
                                        </li>
                                    @endforeach
                                @else
                                    <li class="dropdown-item text-center py-2">No new message found</li>
                                @endif

                                <hr class="my-2">
                                <li><a class="dropdown-item text-8773FF py-2 text-center bg-white fw-semibold"
                                        href="{{ route('admin.notification') }}">View All</a></li>
                            </ul>
                        </div>
                    </li>

                    <li class="nav-item d-none d-lg-block  pe-0 pe-xl-3">
                        <div class="dropdown">
                            <button class="p-0 border-0 bg-transparent dropdown-toggle headerNotificationBtn"
                                type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <div class="header-right-icon bg-F0F5F6">
                                    <img src="{{ asset('assets/images/user-icon.png') }}" alt=""
                                        class="">
                                </div>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end border-0 shadow">
                                <li>
                                    <a class="dropdown-item py-2" href="{{ route('admin.profile') }}">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="18"
                                            viewBox="0 0 16 18" fill="none">
                                            <path
                                                d="M14.8569 16.7147V15.0004C14.8569 14.0911 14.4956 13.219 13.8527 12.5761C13.2097 11.9331 12.3376 11.5718 11.4283 11.5718H4.57115C3.66184 11.5718 2.78977 11.9331 2.14678 12.5761C1.5038 13.219 1.14258 14.0911 1.14258 15.0004V16.7147M11.4283 4.7147C11.4283 6.60825 9.89327 8.14328 7.99972 8.14328C6.10617 8.14328 4.57115 6.60825 4.57115 4.7147C4.57115 2.82116 6.10617 1.28613 7.99972 1.28613C9.89327 1.28613 11.4283 2.82116 11.4283 4.7147Z"
                                                stroke="#1F1F26" stroke-linecap="round" stroke-linejoin="round" />
                                        </svg>
                                        <span class="ms-2"> My Profile </span>
                                    </a>
                                </li>

                                <li>
                                    <a class="dropdown-item py-2" href="{{ route('admin.logout') }}">
                                        <button class="btn btn-outline-danger w-100 rounded-5">Logout</button>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </li>

                    <li class="nav-item d-block d-lg-none">
                        <a class="nav-link" data-bs-toggle="offcanvas" href="#offcanvasExample" role="button"
                            aria-controls="offcanvasExample">
                            <div class="header-right-icon bg-F0F5F6">
                                <i class="fa-solid fa-bars"></i>
                            </div>
                        </a>
                    </li>
                </ul>

                <div class="offcanvas offcanvas-start w-75 d-lg-none" tabindex="-1" id="offcanvasExample"
                    aria-labelledby="offcanvasExampleLabel">
                    <div class="offcanvas-header border-bottom">
                        <a class="navbar-brand mx-0 border-0" href="#" style="padding: 0 !important;">
                            <img src="{{ asset('assets/images/deeTee-Logo.png') }}" alt="" class="">
                        </a>
                        <button type="button" class="btn-close shadow-none border-0" data-bs-dismiss="offcanvas"
                            aria-label="Close"></button>
                    </div>
                    <div class="offcanvas-body mobile-sidebar">
                        <nav class="nav nav-pills flex-column">
                            <a class="flex-sm-fill text-start nav-link mb-2 {{ request()->segment(2) == 'dashboard' ? 'active' : '' }}"
                                aria-current="page" href="{{ route('admin.dashboard') }}">
                                <span class="sidebar-icon">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18"
                                        viewBox="0 0 16 16" fill="none">
                                        <path
                                            d="M8.83333 5.5V0.5H15.5V5.5H8.83333ZM0.5 8.83333V0.5H7.16667V8.83333H0.5ZM8.83333 15.5V7.16667H15.5V15.5H8.83333ZM0.5 15.5V10.5H7.16667V15.5H0.5Z" />
                                    </svg>
                                </span>
                                <span class="">Dashboard</span>
                            </a>
                            <a class="flex-sm-fill text-start nav-link mb-2 {{ request()->segment(2) == 'sales-order' ? 'active' : '' }} {{ request()->segment(2) == 'sales-order-details' ? 'active' : '' }}"
                                aria-current="page" href="{{ route('admin.sales-order') }}">
                                <span class="sidebar-icon">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="20"
                                        viewBox="0 0 16 18" fill="none">
                                        <path
                                            d="M3 17.3332C2.30556 17.3332 1.71528 17.0901 1.22917 16.604C0.743056 16.1179 0.5 15.5276 0.5 14.8332V12.3332H3V0.666504L4.25 1.9165L5.5 0.666504L6.75 1.9165L8 0.666504L9.25 1.9165L10.5 0.666504L11.75 1.9165L13 0.666504L14.25 1.9165L15.5 0.666504V14.8332C15.5 15.5276 15.2569 16.1179 14.7708 16.604C14.2847 17.0901 13.6944 17.3332 13 17.3332H3ZM13 15.6665C13.2361 15.6665 13.434 15.5866 13.5938 15.4269C13.7535 15.2672 13.8333 15.0693 13.8333 14.8332V3.1665H4.66667V12.3332H12.1667V14.8332C12.1667 15.0693 12.2465 15.2672 12.4062 15.4269C12.566 15.5866 12.7639 15.6665 13 15.6665ZM5.5 6.49984V4.83317H10.5V6.49984H5.5ZM5.5 8.99984V7.33317H10.5V8.99984H5.5ZM12.1667 6.49984C11.9306 6.49984 11.7326 6.41998 11.5729 6.26025C11.4132 6.10053 11.3333 5.90262 11.3333 5.6665C11.3333 5.43039 11.4132 5.23248 11.5729 5.07275C11.7326 4.91303 11.9306 4.83317 12.1667 4.83317C12.4028 4.83317 12.6007 4.91303 12.7604 5.07275C12.9201 5.23248 13 5.43039 13 5.6665C13 5.90262 12.9201 6.10053 12.7604 6.26025C12.6007 6.41998 12.4028 6.49984 12.1667 6.49984ZM12.1667 8.99984C11.9306 8.99984 11.7326 8.91998 11.5729 8.76025C11.4132 8.60053 11.3333 8.40261 11.3333 8.1665C11.3333 7.93039 11.4132 7.73248 11.5729 7.57275C11.7326 7.41303 11.9306 7.33317 12.1667 7.33317C12.4028 7.33317 12.6007 7.41303 12.7604 7.57275C12.9201 7.73248 13 7.93039 13 8.1665C13 8.40261 12.9201 8.60053 12.7604 8.76025C12.6007 8.91998 12.4028 8.99984 12.1667 8.99984Z" />
                                    </svg>
                                </span>
                                <span class="">Sale Orders</span>
                            </a>
                            <a class="flex-sm-fill text-start nav-link mb-2 {{ request()->segment(2) == 'machines' ? 'active' : '' }}"
                                aria-current="page" href="{{ route('admin.machines') }}">
                                <span class="sidebar-icon">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18"
                                        viewBox="0 0 16 16" fill="none">
                                        <path
                                            d="M1.31185 15.5002V13.0002H3.89518L1.77018 6.04185C1.39518 5.83352 1.08615 5.52796 0.843099 5.12518C0.600043 4.7224 0.478516 4.29185 0.478516 3.83352C0.478516 3.13907 0.721571 2.54879 1.20768 2.06268C1.69379 1.57657 2.28407 1.33352 2.97852 1.33352C3.52018 1.33352 4.00282 1.48977 4.42643 1.80227C4.85004 2.11477 5.14518 2.51407 5.31185 3.00018H7.97852V2.16685C7.97852 1.93074 8.05838 1.73282 8.2181 1.5731C8.37782 1.41338 8.57574 1.33352 8.81185 1.33352C8.93685 1.33352 9.05838 1.36129 9.17643 1.41685C9.29449 1.4724 9.39518 1.55574 9.47852 1.66685L10.8952 0.333515C11.0202 0.208515 11.1695 0.128654 11.3431 0.0939319C11.5167 0.0592097 11.6868 0.0835152 11.8535 0.166849L15.1035 1.66685C15.2702 1.75018 15.3848 1.87171 15.4473 2.03143C15.5098 2.19115 15.5063 2.3474 15.4368 2.50018C15.3535 2.66685 15.232 2.77449 15.0723 2.8231C14.9125 2.87171 14.7563 2.86129 14.6035 2.79185L11.6035 1.41685L9.64518 3.25018V4.41685L11.6035 6.20852L14.6035 4.83352C14.7563 4.76407 14.916 4.75713 15.0827 4.81268C15.2493 4.86824 15.3674 4.9724 15.4368 5.12518C15.5202 5.29185 15.5271 5.45157 15.4577 5.60435C15.3882 5.75713 15.2702 5.87518 15.1035 5.95852L11.8535 7.50018C11.6868 7.58352 11.5167 7.60782 11.3431 7.5731C11.1695 7.53838 11.0202 7.45852 10.8952 7.33352L9.47852 6.00018C9.39518 6.08352 9.29449 6.1599 9.17643 6.22935C9.05838 6.29879 8.93685 6.33352 8.81185 6.33352C8.57574 6.33352 8.37782 6.25365 8.2181 6.09393C8.05838 5.93421 7.97852 5.73629 7.97852 5.50018V4.66685H5.31185C5.27018 4.77796 5.22504 4.88213 5.17643 4.97935C5.12782 5.07657 5.06185 5.18074 4.97852 5.29185L9.14518 13.0002H12.1452V15.5002H1.31185ZM2.97852 4.66685C3.21463 4.66685 3.41254 4.58699 3.57227 4.42727C3.73199 4.26754 3.81185 4.06963 3.81185 3.83352C3.81185 3.5974 3.73199 3.39949 3.57227 3.23977C3.41254 3.08004 3.21463 3.00018 2.97852 3.00018C2.7424 3.00018 2.54449 3.08004 2.38477 3.23977C2.22504 3.39949 2.14518 3.5974 2.14518 3.83352C2.14518 4.06963 2.22504 4.26754 2.38477 4.42727C2.54449 4.58699 2.7424 4.66685 2.97852 4.66685Z" />
                                    </svg>
                                </span>
                                <span class="">Machines</span>
                            </a>
                            <a class="flex-sm-fill text-start nav-link mb-2 {{ request()->segment(2) == 'operators' ? 'active' : '' }}"
                                aria-current="page" href="{{ route('admin.operators') }}">
                                <span class="sidebar-icon">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14"
                                        viewBox="0 0 12 12" fill="none">
                                        <path
                                            d="M6 6C5.16667 6 4.45833 5.70833 3.875 5.125C3.29167 4.54167 3 3.83333 3 3C3 2.16667 3.29167 1.45833 3.875 0.875C4.45833 0.291667 5.16667 0 6 0C6.83333 0 7.54167 0.291667 8.125 0.875C8.70833 1.45833 9 2.16667 9 3C9 3.83333 8.70833 4.54167 8.125 5.125C7.54167 5.70833 6.83333 6 6 6ZM0 12V10C0 9.68056 0.0868056 9.37847 0.260417 9.09375C0.434028 8.80903 0.673611 8.56944 0.979167 8.375C1.74306 7.93056 2.55208 7.59028 3.40625 7.35417C4.26042 7.11806 5.125 7 6 7C6.875 7 7.73958 7.11806 8.59375 7.35417C9.44792 7.59028 10.2569 7.93056 11.0208 8.375C11.3264 8.55556 11.566 8.79167 11.7396 9.08333C11.9132 9.375 12 9.68056 12 10V12H0Z" />
                                    </svg>
                                </span>
                                <span class="">Operators</span>
                            </a>
                            <a class="flex-sm-fill text-start nav-link mb-2 {{ request()->segment(2) == 'reports' ? 'active' : '' }}"
                                aria-current="page" href="{{ route('admin.reports') }}">
                                <span class="sidebar-icon">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="20"
                                        viewBox="0 0 16 18" fill="none">
                                        <path
                                            d="M3 17.3332C2.30556 17.3332 1.71528 17.0901 1.22917 16.604C0.743056 16.1179 0.5 15.5276 0.5 14.8332V12.3332H3V0.666504H15.5V14.8332C15.5 15.5276 15.2569 16.1179 14.7708 16.604C14.2847 17.0901 13.6944 17.3332 13 17.3332H3ZM13 15.6665C13.2361 15.6665 13.434 15.5866 13.5938 15.4269C13.7535 15.2672 13.8333 15.0693 13.8333 14.8332V2.33317H4.66667V12.3332H12.1667V14.8332C12.1667 15.0693 12.2465 15.2672 12.4062 15.4269C12.566 15.5866 12.7639 15.6665 13 15.6665ZM5.5 6.49984V4.83317H13V6.49984H5.5ZM5.5 8.99984V7.33317H13V8.99984H5.5Z" />
                                    </svg>
                                </span>
                                <span class="">Reports</span>
                            </a>
                            <a class="flex-sm-fill text-start nav-link mb-2 {{ request()->segment(2) == 'products' ? 'active' : '' }}"
                                aria-current="page" href="{{ route('admin.products') }}">
                                <span class="sidebar-icon">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18"
                                        viewBox="0 0 18 18" fill="none">
                                        <path d="M0 8V0H8V8H0ZM0 18V10H8V18H0ZM10 8V0H18V8H10ZM10 18V10H18V18H10Z" />
                                    </svg>
                                </span>
                                <span class="">Products</span>
                            </a>
                            <a class="flex-sm-fill text-start nav-link mb-2 {{ request()->segment(2) == 'qr-codes-list' ? 'active' : '' }}"
                                aria-current="page" href="{{ route('admin.qr-codes-list') }}">
                                <span class="sidebar-icon">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18"
                                        viewBox="0 0 18 18" fill="none">
                                        <path
                                            d="M10 18V16H12V18H10ZM8 16V11H10V16H8ZM16 13V9H18V13H16ZM14 9V7H16V9H14ZM2 11V9H4V11H2ZM0 9V7H2V9H0ZM9 2V0H11V2H9ZM1.5 4.5H4.5V1.5H1.5V4.5ZM0 6V0H6V6H0ZM1.5 16.5H4.5V13.5H1.5V16.5ZM0 18V12H6V18H0ZM13.5 4.5H16.5V1.5H13.5V4.5ZM12 6V0H18V6H12ZM14 18V15H12V13H16V16H18V18H14ZM10 11V9H14V11H10ZM6 11V9H4V7H10V9H8V11H6ZM7 6V2H9V4H11V6H7ZM2.25 3.75V2.25H3.75V3.75H2.25ZM2.25 15.75V14.25H3.75V15.75H2.25ZM14.25 3.75V2.25H15.75V3.75H14.25Z" />
                                    </svg>
                                </span>
                                <span class="">QR Code</span>
                            </a>
                            <a class="flex-sm-fill text-start nav-link mb-2 {{ request()->segment(2) == 'route-cards' ? 'active' : '' }}"
                                aria-current="page" href="{{ route('admin.route-cards') }}">
                                <span class="sidebar-icon">
                                    <i class="fa-regular fa-credit-card fs-5"></i>
                                </span>
                                <span class="">Route Cards</span>
                            </a>
                            <a class="flex-sm-fill text-start nav-link mb-2 {{ request()->segment(2) == 'users' ? 'active' : '' }}"
                                aria-current="page" href="{{ route('admin.users') }}">
                                <span class="sidebar-icon">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18"
                                        viewBox="0 0 18 18" fill="none">
                                        <path
                                            d="M10.666 8.1665C11.3605 8.1665 11.9507 7.92345 12.4368 7.43734C12.923 6.95123 13.166 6.36095 13.166 5.6665C13.166 4.97206 12.923 4.38178 12.4368 3.89567C11.9507 3.40956 11.3605 3.1665 10.666 3.1665C9.97157 3.1665 9.38129 3.40956 8.89518 3.89567C8.40907 4.38178 8.16602 4.97206 8.16602 5.6665C8.16602 6.36095 8.40907 6.95123 8.89518 7.43734C9.38129 7.92345 9.97157 8.1665 10.666 8.1665ZM5.66602 13.9998C5.20768 13.9998 4.81532 13.8366 4.48893 13.5103C4.16254 13.1839 3.99935 12.7915 3.99935 12.3332V2.33317C3.99935 1.87484 4.16254 1.48248 4.48893 1.15609C4.81532 0.829698 5.20768 0.666504 5.66602 0.666504H15.666C16.1243 0.666504 16.5167 0.829698 16.8431 1.15609C17.1695 1.48248 17.3327 1.87484 17.3327 2.33317V12.3332C17.3327 12.7915 17.1695 13.1839 16.8431 13.5103C16.5167 13.8366 16.1243 13.9998 15.666 13.9998H5.66602ZM2.33268 17.3332C1.87435 17.3332 1.48199 17.17 1.1556 16.8436C0.82921 16.5172 0.666016 16.1248 0.666016 15.6665V3.99984H2.33268V15.6665H13.9993V17.3332H2.33268ZM5.66602 12.3332H15.666C15.0827 11.5415 14.35 10.9269 13.4681 10.4894C12.5862 10.0519 11.6521 9.83317 10.666 9.83317C9.6799 9.83317 8.74588 10.0519 7.86393 10.4894C6.98199 10.9269 6.24935 11.5415 5.66602 12.3332Z" />
                                    </svg>
                                </span>
                                <span class="">Users</span>
                            </a>
                            <a class="flex-sm-fill text-center nav-link px-0 position-relative sidebar-helpCenter"
                                aria-current="page" href="#">
                                <img src="{{ asset('assets/images/HelpCenter.png') }}" alt=""
                                    class="w-100 mb-3">
                                <div class="position-absolute bottom-0 start-0 pb-4">
                                    <h5 class="text-white">Help Center</h5>
                                    <p class="text-white">Having Trouble in Learning.
                                        Please contact us for more questions.</p>
                                    <button class="btn btn-light fw-semibold">Go To Help Center</button>
                                </div>
                            </a>
                        </nav>
                    </div>
                </div>

            </div>
        </div>
    </nav>

    <h5 class="me-auto ms-3 mb-0 text-0D161A fw-bold d-block d-lg-none position-absolute"
        style="top: 80px; left: 15px;"> @yield('title') </h5>
</div>
