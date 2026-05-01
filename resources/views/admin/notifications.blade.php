@section('title', 'Notifications')
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
                                        <a href="#" class="tab mb-0 active"
                                            onclick="openTab(event, 'MaintenanceAlerts')">
                                            <h6 class="text-0D161A"> Maintenance Alerts </h6>
                                        </a>
                                        <a href="#" class="tab mb-0" onclick="openTab(event, 'CycleTimeAlerts')">
                                            <h6 class="text-445B64"> Cycle Time Alerts </h6>
                                        </a>
                                    </div>
                                </div>
                            </div>

                            <div class="row px-3 pt-3">
                                <!--------------------------------------------------->
                                <div class="col-12 col-lg-8">
                                    <div class="d-flex justify-content-between mb-3 mb-lg-0">
                                        <div class="d-flex align-items-center">
                                            <div class="table-circular-icon bg-F0F5F6 me-3" style="cursor: pointer;">
                                                <i class="fa-solid fa-arrows-rotate"></i>
                                            </div>
                                            <span class="text-0D161A fw-semibold me-1 countItems">
                                                {{ $notifications->count() }} </span>
                                            <span class="text-445B64 fw-medium">Items</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- First card -->
                    <div class="card border-0 rounded-3 overflow-hidden">
                        <div class="card-body p-0">
                            <div class="row">
                                <div class="col-12">
                                    <div class="table-responsive">

                                        {{-- ==== Operations Listing ==== --}}
                                        <div id="MaintenanceAlerts" class="tabcontent" style="display: block;">
                                            <table class="table rounded-3" id="myTable">
                                                <thead>
                                                    <tr>
                                                        <th scope="col" class="text-445B64 p-3"> Date </th>
                                                        <th scope="col" class="text-445B64 p-3"> Time </th>
                                                        <th scope="col" class="text-445B64 p-3"> Type </th>
                                                        <th scope="col" class="text-445B64 p-3"> Description </th>
                                                    </tr>
                                                </thead>
                                                <tbody id="operations-table-data">

                                                    @if (!empty($notifications))
                                                        @foreach ($notifications as $notify)
                                                            <tr>
                                                                <td class="p-3">
                                                                    {{ $notify->created_at->format('d-m-Y') }}</td>
                                                                <td class="p-3">
                                                                    {{ $notify->created_at->format('h:i a') }}</td>
                                                                <th scope="row" class="p-3"> {{ $notify->title }}
                                                                </th>
                                                                <td class="p-3"> {{ $notify->message }} </td>
                                                            </tr>
                                                        @endforeach
                                                    @endif
                                                </tbody>
                                            </table>

                                            <div class="paginationQ d-flex justify-content-center" id="operations-paginate">
                                                {{ $notifications->withPath(url('/admin/notification'))->withQueryString()->links() }}
                                            </div>
                                        </div>

                                        {{-- ==== Operator listing ==== --}}

                                        <div id="CycleTimeAlerts" class="tabcontent" style="display: none;">
                                            <table class="table rounded-3">
                                                <thead>
                                                    <tr>
                                                        <th scope="col" class="text-445B64 p-3"> Time </th>
                                                        <th scope="col" class="text-445B64 p-3"> Message </th>
                                                    </tr>
                                                </thead>
                                                <tbody id="operator-table-data">
                                                    @if (!empty($ideal_cycle_time))
                                                        @foreach ($ideal_cycle_time as $cycle_time)
                                                            <tr>
                                                                <td class="p-3">
                                                                    {{ $cycle_time->created_at->format('d-m-Y h:i:s a') }}
                                                                </td>
                                                                <td class="p-3"> {{ $cycle_time->message }}</td>
                                                            </tr>
                                                        @endforeach
                                                    @endif
                                                </tbody>
                                            </table>
                                            <!-- Pagination Section -->
                                            <div class="paginationQ d-flex justify-content-center" id="operator-paginate">
                                                {{ $ideal_cycle_time->withPath(url('/admin/notification'))->withPath(request()->url())->appends(request()->query())->links() }}
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

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const urlParams = new URLSearchParams(window.location.search);
            const activeTab = urlParams.get("tab") || "MaintenanceAlerts";

            openTab({
                currentTarget: document.querySelector(`[onclick="openTab(event, '${activeTab}')"]`)
            }, activeTab);

        });

        function openTab(evt, cityName) {
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
            document.getElementById(cityName).style.display = "block";
            evt.currentTarget.classList.add("active");

            // Update the URL to reflect the active tab but KEEP existing parameters
            const urlParams = new URLSearchParams(window.location.search);
            urlParams.set("tab", cityName);
            // Don't delete page parameter - let it stay for pagination

            // Use history.replaceState to update the URL without reloading the page
            const newUrl = `${window.location.pathname}?${urlParams.toString()}`;
            window.history.replaceState({}, "", newUrl);

            // Update item count
            const tabCounts = {
                MaintenanceAlerts: "{{ $notifications->total() }}",
                CycleTimeAlerts: "{{ $ideal_cycle_time->total() }}",
            };

            const val = tabCounts[cityName] || 0;
            document.querySelector('.countItems').textContent = val;
        }

        //===========
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('.pagination a').forEach(link => {
                console.log(link.href);
            });

            // Monitor clicks on pagination links
            document.addEventListener('click', function(e) {
                if (e.target.closest('.pagination a')) {
                    e.preventDefault();
                    const link = e.target.closest('.pagination a');
                    window.location.href = link.getAttribute('href');
                }
            });
        });
    </script>

@stop
