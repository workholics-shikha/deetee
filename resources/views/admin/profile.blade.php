@section('title', 'My Profile')
@extends('layouts.app')
@section('content')

    <!-- Main Content Area Start -->

    <div class="main-content-area">
        <div class="main-content profile-page">
            <div class="row">
                <div class="col-12">
                    <div class="card border-0 rounded-3 mb-4">
                        
                    </div>
                    <!-- First card -->
                    <div class=" border-0 rounded-3">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-12">

                                    {{-- <div class="table-responsive"> --}}
                                    <div class="">
                                        {{-- About --}}
                                        <div id="About" class="tabcontent" style="display: none;">
                                            <div class="row">
                                                <div class="col-12 col-lg-6">
                                                    <div class="d-flex aligh-items-center">
                                                        <div class="profile-img border">
                                                            <img src="{{$data->profile_image}}" alt=""
                                                                class="w-100">
                                                            <img src="./public/assets/CamerainputImg.png" alt=""
                                                                class="cameraInputImg">
                                                        </div>
                                                        <div class="ps-3 pt-2">
                                                            <h6 class="mb-3 fs-14">
                                                                <span class="text-5C5E66 fw-normal">User ID:</span>
                                                                <span class="ms-2 fw-semibold text-black">{{$data->id}}</span>
                                                            </h6>
                                                            <h4 class="text-black">{{$data->name}}</h4>
                                                            <h6 class="d-flex align-items-center">
                                                                <svg xmlns="http://www.w3.org/2000/svg" width="7"
                                                                    height="7" viewBox="0 0 7 7" fill="none">
                                                                    <ellipse cx="3.38542" cy="3.54287" rx="2.88542" ry="2.84268" fill="#00C48C" />
                                                                </svg>
                                                                <span class="ms-1 text-00C48C">Active</span>
                                                            </h6>
                                                        </div>
                                                    </div>
                                                    <div class="pt-5">
                                                        <div class="table-responsive">
                                                            <table class="table">
                                                                <tbody>
                                                                    <tr>
                                                                        <th>Name</th>
                                                                        <td>{{$data->name}}</td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td>Email</td>
                                                                        <td>{{$data->email}}</td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td>Phone Number</td>
                                                                        <td>{{$data->phone}}</td>
                                                                    </tr> 
                                                                    <tr>
                                                                        <td>Unit</td>
                                                                        <td>{{$data->unit_name}}</td>
                                                                    </tr> 
                                                                    <tr>
                                                                        <td>Department</td>
                                                                        <td>{{$data->department}}</td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td>Designation</td>
                                                                        <td>{{$data->designation}}</td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td>Status</td>
                                                                        <td>Active</td>
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
            const activeTab = urlParams.get("tab") || "About";

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

            // Update the URL to reflect the active tab and remove the page number
            const urlParams = new URLSearchParams(window.location.search);
            urlParams.set("tab", cityName); // Update the 'tab' parameter
            urlParams.delete("page"); // Remove the 'page' parameter if it exists

            // Use history.replaceState to update the URL without reloading the page
            const newUrl = `${window.location.pathname}?${urlParams.toString()}`;
            window.history.replaceState({}, "", newUrl);
        }
    </script>
@stop
