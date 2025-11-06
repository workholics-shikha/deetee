@if (!empty($getOperationData))
    @foreach ($getOperationData as $operation)
        <tr>

            <td class="align-top p-3" style="border-bottom: 1px solid transparent;">
                <h2 class="accordion-header d-flex justify-content-around"> 
                    <span class="fs-6 fw-semibold text-445B64">#{{ $operation->quantity_processed ?? '1' }}</span>
                </h2>
            </td>
            <td class="border-right">
                <div class="d-flex align-items-center">
                    <img src="{{ asset('assets/images/User.png') }}" alt=""
                        class="me-2" style="width: 30px; height: 30px;">
                    <div class="">
                        <a class="text-0D161A fw-semibold mb-0">
                            {{ \App\Helpers\MyHelper::getUsername($operation->operator_id) }}
                        </a>
                    </div>
                </div>
            </td>
            <td class="border-right p-3">
                {{ \App\Helpers\MyHelper::getMachinename($operation->machine_id) }}
            </td>
            <td class="border-right p-3">
                {{ \Carbon\Carbon::parse($operation->start_date_time)->format('d M, g:i A') }}
            </td>
            <td class="border-right p-3">
                @if (!empty($operation->end_date_time))
                    {{ \Carbon\Carbon::parse($operation->end_date_time)->format('d M, g:i A') }}
                @else
                    NA
                @endif
            </td>
            <td class="border-right p-3">
               @if (!empty($operation->end_date_time))
                    @php
                        $start = \Carbon\Carbon::parse($operation->start_date_time);
                        $end = \Carbon\Carbon::parse($operation->end_date_time);
                        $diffInSeconds = $start->diffInSeconds($end);

                        $hours = floor($diffInSeconds / 3600);
                        $minutes = floor(($diffInSeconds % 3600) / 60);
                        $seconds = $diffInSeconds % 60;
                    @endphp
                    {{ $hours }}h {{ $minutes }}m {{ $seconds }}s
                @else
                    NA
                @endif

            </td>

            <td class="border-right p-3">
                {{ $operation->reason ?? 'NA' }}
            </td>
            <td class="border-right p-3">
                <div class="d-flex align-items-center justify-content-end">
                    <span class="text-00B200 mb-0 me-1">
                        {{ $operation->roll_status ?? 'pending' }}
                    </span>
                    <img src="{{ asset('assets/images/completeDot.png') }}" alt=""
                        class="" style="width: 20px; height: 20px;">
                </div>
            </td>
            <td class="text-center p-3">
                <div class="dropdown">
                    <button class="table-circular-icon bg-F0F5F6 mx-auto dropdown-toggle"
                        type="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="fa-solid fa-ellipsis-vertical"></i>
                    </button>
                    <ul class="dropdown-menu border-0 shadow rounded-3">
                        <li class="trackingModal" data-type="approved" data-id="{{ $operation->id }}" >
                            <a class="dropdown-item" href="#">
                                <svg xmlns="http://www.w3.org/2000/svg" width="14"
                                    height="16" viewBox="0 0 10 12" fill="none"
                                    class="me-2">
                                    <path
                                        d="M1.63922 11.5999C1.23922 11.5999 0.899219 11.4638 0.619219 11.1916C0.339219 10.9193 0.199219 10.5888 0.199219 10.1999V5.18324C0.199219 5.00175 0.232552 4.82675 0.299219 4.65824C0.365885 4.48972 0.472552 4.33416 0.619219 4.19157L4.07922 0.827679C4.3242 0.5895 4.71423 0.589501 4.95922 0.82768C5.11922 0.983236 5.22589 1.16796 5.27922 1.38185C5.33255 1.59574 5.35922 1.81287 5.35922 2.03324L4.99922 4.13324H8.35922C8.75922 4.13324 9.09922 4.26935 9.37922 4.54157C9.65922 4.81379 9.79922 5.14435 9.79922 5.53324V5.80546C9.79922 5.88324 9.79255 5.96101 9.77922 6.03879C9.76589 6.11657 9.74589 6.18787 9.71922 6.25268L8.19922 10.6471C8.09255 10.9323 7.91589 11.1624 7.66922 11.3374C7.42255 11.5124 7.14589 11.5999 6.83922 11.5999H1.63922Z"
                                        fill="#445B64" />
                                </svg>
                                Approved</a>
                        </li>
                        <li class="trackingModal" data-type="rework" data-id="{{ $operation->id }}" >
                            <a class="dropdown-item" href="#">
                                <svg xmlns="http://www.w3.org/2000/svg" width="18"
                                    height="18" viewBox="0 0 14 14" fill="none"
                                    class="me-2">
                                    <path
                                        d="M5.22719 8.79685V5.23304H8.78686V8.79685H5.22719ZM7.00702 13.4001C5.92923 13.4001 4.92313 13.1427 3.98872 12.6279C3.0543 12.1132 2.28057 11.4103 1.66751 10.5194V12.1528H0.599609V8.76715H3.9813V9.83629H2.51293C3.01722 10.6084 3.66241 11.2173 4.44851 11.6627C5.2346 12.1082 6.08744 12.331 7.00702 12.331C8.15403 12.331 9.18733 11.9968 10.1069 11.3286C11.0265 10.6604 11.6791 9.78679 12.0647 8.70775L13.103 8.94534C12.6778 10.2818 11.9065 11.3583 10.7892 12.175C9.67184 12.9917 8.41112 13.4001 7.00702 13.4001ZM0.629273 6.45068C0.688601 5.78741 0.841865 5.1489 1.08906 4.53513C1.33626 3.92137 1.68234 3.36205 2.1273 2.85717L2.88373 3.61448C2.55743 4.03026 2.29046 4.47574 2.08281 4.95091C1.87516 5.42608 1.74662 5.92601 1.69718 6.45068H0.629273ZM3.62533 2.85717L2.88373 2.09987C3.38802 1.65439 3.94669 1.30543 4.55975 1.053C5.1728 0.800562 5.81058 0.649595 6.47307 0.600098V1.66924C5.94901 1.72864 5.4472 1.86228 4.96763 2.07017C4.48806 2.27806 4.04063 2.54039 3.62533 2.85717ZM10.3887 2.85717C9.97342 2.53049 9.52599 2.26568 9.04642 2.06274C8.56685 1.8598 8.06504 1.72864 7.54098 1.66924V0.600098C8.21336 0.649595 8.85608 0.798087 9.46913 1.04557C10.0822 1.29306 10.6409 1.63954 11.1451 2.08502L10.3887 2.85717ZM12.3169 6.45068C12.2575 5.92601 12.1265 5.42608 11.9238 4.95091C11.7211 4.47574 11.4566 4.03026 11.1303 3.61448L11.9016 2.84232C12.3366 3.3472 12.6827 3.90652 12.9398 4.52028C13.1969 5.13405 13.3502 5.77751 13.3996 6.45068H12.3169Z"
                                        fill="#445B64" />
                                </svg>
                                Rework</a>
                        </li>
                        <li class="trackingModal" data-type="remarks" data-id="{{ $operation->id }}" >
                            <a class="dropdown-item" href="#">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16"
                                    height="16" viewBox="0 0 12 12" fill="none"
                                    class="me-2">
                                    <path
                                        d="M1.60039 11.5999C1.27039 11.5999 0.987891 11.4824 0.752891 11.2474C0.517891 11.0124 0.400391 10.7299 0.400391 10.3999V1.5999C0.400391 1.2699 0.517891 0.987403 0.752891 0.752403C0.987891 0.517403 1.27039 0.399902 1.60039 0.399902H8.00039L11.6004 3.9999V10.3999C11.6004 10.7299 11.4829 11.0124 11.2479 11.2474C11.0129 11.4824 10.7304 11.5999 10.4004 11.5999H1.60039ZM2.80039 9.1999H9.20039V7.9999H2.80039V9.1999ZM2.80039 6.7999H9.20039V5.5999H2.80039V6.7999ZM2.80039 4.3999H7.60039V3.1999H2.80039V4.3999Z"
                                        fill="#445B64" />
                                </svg> Remarks </a>
                        </li>
                    </ul>
                </div>
            </td>

            
        <tr>
    @endforeach
@endif
