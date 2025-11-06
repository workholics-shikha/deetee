@section('title', 'Dashboard')
@extends('layouts.app')
@section('content')

    <style>
        #radialChart .apexcharts-radial-series path {
            stroke-linecap: round !important;
            /* Force rounded ends */
        }
    </style>

    <!-- Main Content Area Start -->
    <div class="main-content-area">
        <div class="main-content">

            <!-- Status Container Start -->
            <div class="">
                <div class="card border-0 rounded-4 mb-4">
                    <div class="card-body p-4">
                        <div class="row justify-content-center">
                            <div class="col-6 col-md-3 mb-4 mb-md-0">
                                <div class="border-right pe-2 pe-lg-0">
                                    <h6 class="text-445B64">Sale Order</h6>
                                    <h4 class="text-0D161A fw-semibold">{{ $sales_order }}</h4>
                                    <h6 class="fs-14 text-445B64 mb-0"><span class="fw-semibold">33</span> New Sale Orders
                                    </h6>
                                </div>
                            </div>

                            <div class="col-6 col-md-3 mb-4 mb-md-0">
                                <div class="border-right border-right-mobile-0 pe-2 pe-lg-0">
                                    <h6 class="text-445B64">Machines</h6>
                                    <h4 class="text-0D161A fw-semibold">{{ $total_machines }}</h4>
                                    <h6 class="fs-14 text-445B64 mb-0"><span class="fw-semibold">12</span> New Machines</h6>
                                </div>
                            </div>

                            <div class="col-6 col-md-3">
                                <div class="border-right pe-2 pe-lg-0">
                                    <h6 class="text-445B64">Operators</h6>
                                    <h4 class="text-0D161A fw-semibold">{{ $operators }}</h4>
                                    <h6 class="fs-14 text-445B64 mb-0"><span class="fw-semibold">52</span> New Operators
                                    </h6>
                                </div>
                            </div>

                            <div class="col-6 col-md-3">
                                <div class="pe-2 pe-lg-0">
                                    <h6 class="text-445B64">Reports</h6>
                                    <h4 class="text-0D161A fw-semibold">{{ $reports }}</h4>
                                    <h6 class="fs-14 text-445B64 mb-0"><span class="fw-semibold">4</span> New Reports</h6>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12 col-xl-7 mb-4">
                        <div class="card bg-white rounded-4 border-0">
                            <div class="card-body">
                                <h6 class="text-445B64">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="16"
                                        viewBox="0 0 12 14" fill="none" class="me-1">
                                        <path
                                            d="M2 13.6668C1.44444 13.6668 0.972222 13.4724 0.583333 13.0835C0.194444 12.6946 0 12.2224 0 11.6668V9.66683H2V0.333496H12V11.6668C12 12.2224 11.8056 12.6946 11.4167 13.0835C11.0278 13.4724 10.5556 13.6668 10 13.6668H2ZM10 12.3335C10.1889 12.3335 10.3472 12.2696 10.475 12.1418C10.6028 12.0141 10.6667 11.8557 10.6667 11.6668V1.66683H3.33333V9.66683H9.33333V11.6668C9.33333 11.8557 9.39722 12.0141 9.525 12.1418C9.65278 12.2696 9.81111 12.3335 10 12.3335ZM4 5.00016V3.66683H10V5.00016H4ZM4 7.00016V5.66683H10V7.00016H4Z"
                                            fill="#445B64" />
                                    </svg>
                                    Production
                                    <i class="fa-solid fa-angle-right ms-2"></i>
                                </h6>
                                <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
                                <style>
                                    @import url(https://fonts.googleapis.com/css?family=Roboto);

                                    body {
                                        font-family: Roboto, sans-serif;
                                    }

                                    #chart {
                                        max-width: 100%;
                                        margin: 35px auto;
                                    }
                                </style>
                                <div class="">
                                    <div id="chart" class="my-0"></div>
                                </div>
                                <script>
                                    var options = {
                                        chart: {
                                            type: 'bar',
                                            height: 235
                                        },
                                        series: [{
                                            name: 'sales',
                                            data: [30, 40, 45, 50, 49, 60, 70, 91, 125]
                                        }],
                                        xaxis: {
                                            categories: [1991, 1992, 1993, 1994, 1995, 1996, 1997, 1998, 1999]
                                        }
                                    }

                                    var chart = new ApexCharts(document.querySelector("#chart"), options);

                                    chart.render();
                                </script>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-xl-5 mb-4">
                        <div class="card bg-white rounded-4 border-0 h-100">
                            <div class="card-body">
                                <h6 class="text-445B64">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="16"
                                        viewBox="0 0 12 14" fill="none" class="me-1">
                                        <path
                                            d="M2 13.6668C1.44444 13.6668 0.972222 13.4724 0.583333 13.0835C0.194444 12.6946 0 12.2224 0 11.6668V9.66683H2V0.333496H12V11.6668C12 12.2224 11.8056 12.6946 11.4167 13.0835C11.0278 13.4724 10.5556 13.6668 10 13.6668H2ZM10 12.3335C10.1889 12.3335 10.3472 12.2696 10.475 12.1418C10.6028 12.0141 10.6667 11.8557 10.6667 11.6668V1.66683H3.33333V9.66683H9.33333V11.6668C9.33333 11.8557 9.39722 12.0141 9.525 12.1418C9.65278 12.2696 9.81111 12.3335 10 12.3335ZM4 5.00016V3.66683H10V5.00016H4ZM4 7.00016V5.66683H10V7.00016H4Z"
                                            fill="#445B64" />
                                    </svg>
                                    Operator
                                    <i class="fa-solid fa-angle-right ms-2"></i>
                                </h6>
                                <div class="row">
                                    <div class="col-12 col-lg-7">
                                        <div class="">
                                            <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
                                            <style>
                                                @import url(https://fonts.googleapis.com/css?family=Roboto);

                                                body {
                                                    font-family: Roboto, sans-serif;
                                                }

                                                #radialChart {
                                                    max-width: 100%;
                                                    margin: 35px auto;
                                                }
                                            </style>
                                            <div class="">
                                                <div id="radialChart" class="my-0"></div>
                                            </div>
                                            <script>
                                                var options = {
                                                    series: [86, 85, 87],
                                                    chart: {
                                                        height: 250,
                                                        type: 'radialBar',
                                                    },
                                                    colors: ['#00B200', '#FFBF00', '#0073E5'],
                                                    plotOptions: {
                                                        radialBar: {
                                                            dataLabels: {
                                                                name: {
                                                                    fontSize: '13px',
                                                                    textWrap: 'wrap'
                                                                },
                                                                value: {
                                                                    fontSize: '16px',
                                                                },
                                                                // total: {
                                                                //     show: true,
                                                                //     label: 'Total',
                                                                //     formatter: function(w) {
                                                                //         // By default this function returns the average of all series. The below is just an example to show the use of custom formatter function
                                                                //         return 249
                                                                //     }
                                                                // }
                                                            },
                                                            track: {
                                                                background: '#FFFFFF', // Optional: Change track background color
                                                            },
                                                        }
                                                    },
                                                    labels: ['CT Adherence', 'Operator Efficiency', 'Machine Utilization'],
                                                };

                                                var chart = new ApexCharts(document.querySelector("#radialChart"), options);
                                                chart.render();
                                            </script>
                                        </div>
                                    </div>
                                    <div class="col-12 col-lg-5 d-flex align-items-center">
                                        <div class="w-100">
                                            <div class="radialChart-dot bg-00B200 rounded-circle mb-2"
                                                style="width:16px; height:16px;"></div>
                                            <div class="d-flex justify-content-between align-items-center mb-3">
                                                <h6 class="text-445B64 fs-14">CT Adherence</h6>
                                                <h6 class="text-0D161A fw-semibold">86%</h6>
                                            </div>
                                            <div class="radialChart-dot bg-FFBF00 rounded-circle mb-2"
                                                style="width:16px; height:16px;"></div>
                                            <div class="d-flex justify-content-between align-items-center mb-3">
                                                <h6 class="text-445B64 fs-14">Operator Efficiency</h6>
                                                <h6 class="text-0D161A fw-semibold">85%</h6>
                                            </div>
                                            <div class="radialChart-dot bg-0073E5 rounded-circle mb-2"
                                                style="width:16px; height:16px;"></div>
                                            <div class="d-flex justify-content-between align-items-center">
                                                <h6 class="text-445B64 fs-14">Machine Utilization</h6>
                                                <h6 class="text-0D161A fw-semibold">87%</h6>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-xl-5 mb-4">
                        <div class="card bg-white rounded-4 border-0 h-100">
                            <div class="card-body">
                                <h6 class="text-445B64">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="16"
                                        viewBox="0 0 12 14" fill="none" class="me-1">
                                        <path
                                            d="M2 13.6668C1.44444 13.6668 0.972222 13.4724 0.583333 13.0835C0.194444 12.6946 0 12.2224 0 11.6668V9.66683H2V0.333496H12V11.6668C12 12.2224 11.8056 12.6946 11.4167 13.0835C11.0278 13.4724 10.5556 13.6668 10 13.6668H2ZM10 12.3335C10.1889 12.3335 10.3472 12.2696 10.475 12.1418C10.6028 12.0141 10.6667 11.8557 10.6667 11.6668V1.66683H3.33333V9.66683H9.33333V11.6668C9.33333 11.8557 9.39722 12.0141 9.525 12.1418C9.65278 12.2696 9.81111 12.3335 10 12.3335ZM4 5.00016V3.66683H10V5.00016H4ZM4 7.00016V5.66683H10V7.00016H4Z"
                                            fill="#445B64" />
                                    </svg>
                                    Machine Utilisation
                                    <i class="fa-solid fa-angle-right ms-2"></i>
                                </h6>
                                <div class="">
                                    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
                                    <style>
                                        @import url(https://fonts.googleapis.com/css?family=Roboto);

                                        body {
                                            font-family: Roboto, sans-serif;
                                        }

                                        #chart2 {
                                            max-width: 100%;
                                            margin: 35px auto;
                                        }
                                    </style>
                                    <div class="">
                                        <div id="chart2" class="my-0"></div>
                                    </div>
                                    <script>
                                        var options = {
                                            chart: {
                                                type: 'bar',
                                                height: 235
                                            },
                                            series: [{
                                                name: 'sales',
                                                data: [30, 40, 45, 50, 49, 60, 70, 91, 125]
                                            }],
                                            xaxis: {
                                                categories: [1991, 1992, 1993, 1994, 1995, 1996, 1997, 1998, 1999]
                                            }
                                        }

                                        var chart = new ApexCharts(document.querySelector("#chart2"), options);

                                        chart.render();
                                    </script>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-xl-7">
                        <div class="row">
                            <div class="col-12 col-xl-6 mb-4">
                                <div class="card bg-white rounded-4 border-0 h-100">
                                    <div class="card-body">
                                        <h6 class="text-445B64">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="16"
                                                viewBox="0 0 12 14" fill="none" class="me-1">
                                                <path
                                                    d="M2 13.6668C1.44444 13.6668 0.972222 13.4724 0.583333 13.0835C0.194444 12.6946 0 12.2224 0 11.6668V9.66683H2V0.333496H12V11.6668C12 12.2224 11.8056 12.6946 11.4167 13.0835C11.0278 13.4724 10.5556 13.6668 10 13.6668H2ZM10 12.3335C10.1889 12.3335 10.3472 12.2696 10.475 12.1418C10.6028 12.0141 10.6667 11.8557 10.6667 11.6668V1.66683H3.33333V9.66683H9.33333V11.6668C9.33333 11.8557 9.39722 12.0141 9.525 12.1418C9.65278 12.2696 9.81111 12.3335 10 12.3335ZM4 5.00016V3.66683H10V5.00016H4ZM4 7.00016V5.66683H10V7.00016H4Z"
                                                    fill="#445B64" />
                                            </svg>
                                            Breakdown
                                            <i class="fa-solid fa-angle-right ms-2"></i>
                                        </h6>
                                        <div class="">
                                            <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
                                            <style>
                                                @import url(https://fonts.googleapis.com/css?family=Roboto);

                                                body {
                                                    font-family: Roboto, sans-serif;
                                                }

                                                #lineChart {
                                                    max-width: 100%;
                                                    margin: 35px auto;
                                                }
                                            </style>
                                            <div class="">
                                                <div id="lineChart" class="my-0"></div>
                                            </div>
                                            <script>
                                                var options = {
                                                    series: [{
                                                        name: "Desktops",
                                                        data: [10, 41, 35, 51, 49, 62, 69, 91, 148]
                                                    }],
                                                    chart: {
                                                        height: 235,
                                                        type: 'line',
                                                        zoom: {
                                                            enabled: false
                                                        }
                                                    },
                                                    dataLabels: {
                                                        enabled: false
                                                    },
                                                    stroke: {
                                                        curve: 'straight'
                                                    }, 
                                                    xaxis: {
                                                        categories: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep'],
                                                    }
                                                };

                                                var chart = new ApexCharts(document.querySelector("#lineChart"), options);
                                                chart.render();
                                            </script>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 col-xl-6 mb-4">
                                <div class="card bg-white rounded-4 border-0 h-100">
                                    <div class="card-body">
                                        <h6 class="text-445B64">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="16"
                                                viewBox="0 0 12 14" fill="none" class="me-1">
                                                <path
                                                    d="M2 13.6668C1.44444 13.6668 0.972222 13.4724 0.583333 13.0835C0.194444 12.6946 0 12.2224 0 11.6668V9.66683H2V0.333496H12V11.6668C12 12.2224 11.8056 12.6946 11.4167 13.0835C11.0278 13.4724 10.5556 13.6668 10 13.6668H2ZM10 12.3335C10.1889 12.3335 10.3472 12.2696 10.475 12.1418C10.6028 12.0141 10.6667 11.8557 10.6667 11.6668V1.66683H3.33333V9.66683H9.33333V11.6668C9.33333 11.8557 9.39722 12.0141 9.525 12.1418C9.65278 12.2696 9.81111 12.3335 10 12.3335ZM4 5.00016V3.66683H10V5.00016H4ZM4 7.00016V5.66683H10V7.00016H4Z"
                                                    fill="#445B64" />
                                            </svg>
                                            Machine OEE
                                            <i class="fa-solid fa-angle-right ms-2"></i>
                                        </h6>
                                        <div class="">
                                            <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
                                            <style>
                                                @import url(https://fonts.googleapis.com/css?family=Roboto);

                                                body {
                                                    font-family: Roboto, sans-serif;
                                                }

                                                #chart3 {
                                                    max-width: 100%;
                                                    margin: 35px auto;
                                                }
                                            </style>
                                            <div class="">
                                                <div id="chart3" class="my-0"></div>
                                            </div>
                                            <script>
                                                var options = {
                                                    chart: {
                                                        type: 'bar',
                                                        height: 235
                                                    },
                                                    series: [{
                                                        name: 'sales',
                                                        data: [30, 40, 45, 50, 49, 60, 70, 91, 125]
                                                    }],
                                                    xaxis: {
                                                        categories: [1991, 1992, 1993, 1994, 1995, 1996, 1997, 1998, 1999]
                                                    }
                                                }
                                                var chart = new ApexCharts(document.querySelector("#chart3"), options);
                                                chart.render();
                                            </script>
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
@stop