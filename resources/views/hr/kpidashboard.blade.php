@extends('layouts.app')
@section('content')
    <link rel="stylesheet" href="{{ asset('assets/css/dashboard_main.css') }}">

    <div class="sidebodydiv px-5 py-3">
        <div class="sidebodyhead">
            <h4 class="m-0">KPI Dashboard</h4>
        </div>

        <!-- Tabs -->
        @include('generaldashboard.tabs')

        <div class="container px-0 mt-2">
            <div class="row">
                <div class="sidebodyhead mt-2 mb-3">
                    <h4 class="m-0">HR Manager</h4>
                </div>
                <div class="col-sm-12 col-md-6 col-xl-4 mb-3 cards">
                    <div class="cardsdiv">
                        <div class="cardshead">
                            <h6 class="card1h6 mb-2">Attrition Rate</h6>
                        </div>
                        <div id="mang1"></div>
                    </div>
                </div>
                <div class="col-sm-12 col-md-6 col-xl-4 mb-3 cards">
                    <div class="cardsdiv">
                        <div class="cardshead">
                            <h6 class="card1h6 mb-2">Training Completion Rate</h6>
                        </div>
                        <div id="mang2"></div>
                    </div>
                </div>
                <div class="col-sm-12 col-md-4 col-xl-4 mb-3 cards">
                    <div class="cardsdiv">
                        <div class="cardshead">
                            <h6 class="card1h6 mb-2">HR Audit Score</h6>
                        </div>
                        <div id="mang3"></div>
                    </div>
                </div>

                <div class="sidebodyhead my-3">
                    <h4 class="m-0">HR Generalist</h4>
                </div>
                <div class="col-sm-12 col-md-4 col-xl-4 mb-3 cards">
                    <div class="cardsdiv">
                        <div class="cardshead">
                            <h6 class="card1h6 mb-2">Offer Acceptance Rate</h6>
                        </div>
                        <div id="gen1"></div>
                    </div>
                </div>
                <div class="col-sm-12 col-md-4 col-xl-4 mb-3 cards">
                    <div class="cardsdiv">
                        <div class="cardshead">
                            <h6 class="card1h6 mb-2">Onboarding Completion Rate</h6>
                        </div>
                        <div id="gen2"></div>
                    </div>
                </div>
                <div class="col-sm-12 col-md-4 col-xl-4 mb-3 cards">
                    <div class="cardsdiv">
                        <div class="cardshead">
                            <h6 class="card1h6 mb-2">Grievance Handling Success Rate</h6>
                        </div>
                        <div id="gen3"></div>
                    </div>
                </div>
                <div class="col-sm-12 col-md-4 col-xl-4 mb-3 cards">
                    <div class="cardsdiv">
                        <div class="cardshead">
                            <h6 class="card1h6 mb-2">Error Rate in HR Records</h6>
                        </div>
                        <div id="gen4"></div>
                    </div>
                </div>
                <div class="col-sm-12 col-md-4 col-xl-4 mb-3 cards">
                    <div class="cardsdiv">
                        <div class="cardshead">
                            <h6 class="card1h6 mb-2">Reports Preparation Timelines</h6>
                        </div>
                        <div id="gen5"></div>
                    </div>
                </div>

                <div class="sidebodyhead my-3">
                    <h4 class="m-0">HR Assistant</h4>
                </div>
                <div class="col-sm-12 col-md-4 col-xl-4 mb-3 cards">
                    <div class="cardsdiv">
                        <div class="cardshead">
                            <h6 class="card1h6 mb-2">Data Entry Accuracy</h6>
                        </div>
                        <div id="asst1"></div>
                    </div>
                </div>
                <div class="col-sm-12 col-md-4 col-xl-4 mb-3 cards">
                    <div class="cardsdiv">
                        <div class="cardshead">
                            <h6 class="card1h6 mb-2">Timeliness of Reports</h6>
                        </div>
                        <div id="asst2"></div>
                    </div>
                </div>
                <div class="col-sm-12 col-md-4 col-xl-4 mb-3 cards">
                    <div class="cardsdiv">
                        <div class="cardshead">
                            <h6 class="card1h6 mb-2">Training Schedule Adherence</h6>
                        </div>
                        <div id="asst3"></div>
                    </div>
                </div>
                <div class="col-sm-12 col-md-4 col-xl-4 mb-3 cards">
                    <div class="cardsdiv">
                        <div class="cardshead">
                            <h6 class="card1h6 mb-2">Compliance in Record Updates</h6>
                        </div>
                        <div id="asst4"></div>
                    </div>
                </div>

                <div class="sidebodyhead my-3">
                    <h4 class="m-0">Staff Management</h4>
                </div>
                <div class="col-sm-12 col-md-4 col-xl-4 mb-3 cards">
                    <div class="cardsdiv">
                        <div class="cardshead">
                            <h6 class="card1h6 mb-2">Attrition Rate</h6>
                        </div>
                        <div id="staff1"></div>
                    </div>
                </div>
                <div class="col-sm-12 col-md-4 col-xl-4 mb-3 cards">
                    <div class="cardsdiv">
                        <div class="cardshead">
                            <h6 class="card1h6 mb-2">Training Completion</h6>
                        </div>
                        <div id="staff2"></div>
                    </div>
                </div>
                <div class="col-sm-12 col-md-4 col-xl-4 mb-3 cards">
                    <div class="cardsdiv">
                        <div class="cardshead">
                            <h6 class="card1h6 mb-2">Team Attendance and Punctuality</h6>
                        </div>
                        <div id="staff3"></div>
                    </div>
                </div>

            </div>

        </div>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/apexcharts@latest"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/axios/1.4.0/axios.min.js"></script>

    <!-- HR Manager -->
    <!-- Manager 1 -->
    <script>
        var options = {
            series: [{
                name: 'Average Headcount',
                data: [44, 80]
            }, {
                name: 'Total Exits',
                data: [13, 55]
            }],
            colors: ['#58508D', '#BC5090'],
            chart: {
                type: 'bar',
                height: 310,
                // width: 768,
                stacked: true,
                stackType: '100%'
            },
            plotOptions: {
                bar: {
                    columnWidth: '60%',
                    borderRadiusApplication: 'end'
                },
            },
            responsive: [{
                breakpoint: 480,
                options: {
                    legend: {
                        position: 'bottom',
                    }
                }
            }],
            xaxis: {
                categories: ['1', '2'],
            },
            fill: {
                opacity: 1
            },
            legend: {
                position: 'bottom',
                offsetX: 0,
            },
            dataLabels: {
                enabled: true,
                style: {
                    fontSize: '9px',
                    fontWeight: 'lighter'
                }
            },
        };

        var chart = new ApexCharts(document.querySelector("#mang1"), options);
        chart.render();
    </script>

    <!-- Manager 2 -->
    <script>
        var options = {
            series: [{
                name: 'Completed Progress',
                data: [44, 55]
            }, {
                name: 'Required Progress',
                data: [76, 85]
            }],
            chart: {
                type: 'bar',
                height: 310
            },
            colors: ['#002DBB', '#7A90D4'],
            plotOptions: {
                bar: {
                    horizontal: false,
                    columnWidth: '60%',
                    borderRadiusApplication: 'end'
                },
            },
            dataLabels: {
                enabled: false
            },
            // legend: {
            //     show: false
            // },
            stroke: {
                show: true,
                width: 2,
                colors: ['transparent']
            },
            xaxis: {
                categories: ['1', '2'],
            },
            fill: {
                opacity: 1
            },
            tooltip: {
                y: {
                    formatter: function (val) {
                        return val
                    }
                }
            },
            responsive: [{
                breakpoint: 1024,
                options: {
                    legend: {
                        show: false,
                    }
                }
            }]
        };

        var chart = new ApexCharts(document.querySelector("#mang2"), options);
        chart.render();
    </script>

    <!-- Manager 3 -->
    <script>
        var options = {
            series: [{
                name: 'Audit Compliance Ratings',
                data: [44, 55]
            }, {
                name: 'External Sources',
                data: [76, 85]
            }],
            chart: {
                type: 'bar',
                height: 310
            },
            colors: ['#003F5C', '#FFA600'],
            plotOptions: {
                bar: {
                    horizontal: false,
                    columnWidth: '60%',
                    borderRadiusApplication: 'end'
                },
            },
            dataLabels: {
                enabled: false
            },
            // legend: {
            //     show: false
            // },
            stroke: {
                show: true,
                width: 2,
                colors: ['transparent']
            },
            xaxis: {
                categories: ['1', '2'],
            },
            fill: {
                opacity: 1
            },
            tooltip: {
                y: {
                    formatter: function (val) {
                        return val
                    }
                }
            },
            responsive: [{
                breakpoint: 1024,
                options: {
                    legend: {
                        show: false,
                    }
                }
            }]
        };

        var chart = new ApexCharts(document.querySelector("#mang3"), options);
        chart.render();
    </script>

    <!-- HR Generalist -->
    <!-- Generalist 1 -->
    <script>
        var options = {
            series: [{
                name: 'Acceptance Offers',
                data: [44, 55]
            }, {
                name: 'Total Offers',
                data: [76, 85]
            }],
            chart: {
                type: 'bar',
                height: 310
            },
            colors: ['#003F5C', '#FFA600'],
            plotOptions: {
                bar: {
                    horizontal: false,
                    columnWidth: '60%',
                    borderRadiusApplication: 'end'
                },
            },
            dataLabels: {
                enabled: false
            },
            // legend: {
            //     show: false
            // },
            stroke: {
                show: true,
                width: 2,
                colors: ['transparent']
            },
            xaxis: {
                categories: ['1', '2'],
            },
            fill: {
                opacity: 1
            },
            tooltip: {
                y: {
                    formatter: function (val) {
                        return val
                    }
                }
            },
            responsive: [{
                breakpoint: 1024,
                options: {
                    legend: {
                        show: false,
                    }
                }
            }]
        };

        var chart = new ApexCharts(document.querySelector("#gen1"), options);
        chart.render();
    </script>

    <!-- Generalist 2 -->
    <script>
        var options = {
            series: [{
                name: 'Completed Onboarding',
                data: [44, 55]
            }, {
                name: 'Total Hires',
                data: [76, 85]
            }],
            chart: {
                type: 'bar',
                height: 310
            },
            colors: ['#58508D', '#BC5090'],
            plotOptions: {
                bar: {
                    horizontal: false,
                    columnWidth: '60%',
                    borderRadiusApplication: 'end'
                },
            },
            dataLabels: {
                enabled: false
            },
            // legend: {
            //     show: false
            // },
            stroke: {
                show: true,
                width: 2,
                colors: ['transparent']
            },
            xaxis: {
                categories: ['1', '2'],
            },
            fill: {
                opacity: 1
            },
            tooltip: {
                y: {
                    formatter: function (val) {
                        return val
                    }
                }
            },
            responsive: [{
                breakpoint: 1024,
                options: {
                    legend: {
                        show: false,
                    }
                }
            }]
        };

        var chart = new ApexCharts(document.querySelector("#gen2"), options);
        chart.render();
    </script>

    <!-- Generalist 3 -->
    <script>
        var options = {
            series: [{
                name: 'Resolved Grievances',
                data: [44, 55]
            }, {
                name: 'Total Grievances',
                data: [76, 85]
            }],
            chart: {
                type: 'bar',
                height: 310
            },
            colors: ['#002DBB', '#7A90D4'],
            plotOptions: {
                bar: {
                    horizontal: false,
                    columnWidth: '60%',
                    borderRadiusApplication: 'end'
                },
            },
            dataLabels: {
                enabled: false
            },
            // legend: {
            //     show: false
            // },
            stroke: {
                show: true,
                width: 2,
                colors: ['transparent']
            },
            xaxis: {
                categories: ['1', '2'],
            },
            fill: {
                opacity: 1
            },
            tooltip: {
                y: {
                    formatter: function (val) {
                        return val
                    }
                }
            },
            responsive: [{
                breakpoint: 1024,
                options: {
                    legend: {
                        show: false,
                    }
                }
            }]
        };

        var chart = new ApexCharts(document.querySelector("#gen3"), options);
        chart.render();
    </script>

    <!-- Generalist 4 -->
    <script>
        var options = {
            series: [{
                name: 'Completed Onboarding',
                data: [44, 55]
            }, {
                name: 'Total Hires',
                data: [76, 85]
            }],
            chart: {
                type: 'bar',
                height: 310
            },
            colors: ['#991F17', '#B3BFD1'],
            plotOptions: {
                bar: {
                    horizontal: false,
                    columnWidth: '60%',
                    borderRadiusApplication: 'end'
                },
            },
            dataLabels: {
                enabled: false
            },
            // legend: {
            //     show: false
            // },
            stroke: {
                show: true,
                width: 2,
                colors: ['transparent']
            },
            xaxis: {
                categories: ['1', '2'],
            },
            fill: {
                opacity: 1
            },
            tooltip: {
                y: {
                    formatter: function (val) {
                        return val
                    }
                }
            },
            responsive: [{
                breakpoint: 1024,
                options: {
                    legend: {
                        show: false,
                    }
                }
            }]
        };

        var chart = new ApexCharts(document.querySelector("#gen4"), options);
        chart.render();
    </script>

    <!-- Generalist 5 -->
    <script>
        var options = {
            series: [{
                name: 'Average Headcount',
                data: [44, 80]
            }, {
                name: 'Total Exits',
                data: [13, 55]
            }],
            colors: ['#003F5C', '#B3BFD1'],
            chart: {
                type: 'bar',
                height: 310,
                // width: 768,
                stacked: true,
                stackType: '100%'
            },
            plotOptions: {
                bar: {
                    columnWidth: '60%',
                    borderRadiusApplication: 'end'
                },
            },
            responsive: [{
                breakpoint: 480,
                options: {
                    legend: {
                        position: 'bottom',
                    }
                }
            }],
            xaxis: {
                categories: ['1', '2'],
            },
            fill: {
                opacity: 1
            },
            legend: {
                position: 'bottom',
                offsetX: 0,
            },
            dataLabels: {
                enabled: true,
                style: {
                    fontSize: '9px',
                    fontWeight: 'lighter'
                }
            },
        };

        var chart = new ApexCharts(document.querySelector("#gen5"), options);
        chart.render();
    </script>

    <!-- HR Assistant -->
    <!-- Assistant 1 -->
    <script>
        var options = {
            series: [{
                name: 'Records Entered',
                data: [44, 80]
            }, {
                name: 'Total Records',
                data: [13, 55]
            }],
            colors: ['#B3BFD1', '#BC5090'],
            chart: {
                type: 'bar',
                height: 310,
                // width: 768,
                stacked: true,
                stackType: '100%'
            },
            plotOptions: {
                bar: {
                    columnWidth: '60%',
                    borderRadiusApplication: 'end'
                },
            },
            responsive: [{
                breakpoint: 480,
                options: {
                    legend: {
                        position: 'bottom',
                    }
                }
            }],
            xaxis: {
                categories: ['1', '2'],
            },
            fill: {
                opacity: 1
            },
            legend: {
                position: 'bottom',
                offsetX: 0,
            },
            dataLabels: {
                enabled: true,
                style: {
                    fontSize: '9px',
                    fontWeight: 'lighter'
                }
            },
        };

        var chart = new ApexCharts(document.querySelector("#asst1"), options);
        chart.render();
    </script>

    <!-- Assistant 2 -->
    <script>
        var options = {
            series: [{
                name: 'Reports Submitted On Time',
                data: [44, 55]
            }, {
                name: 'Total Reports',
                data: [76, 85]
            }],
            chart: {
                type: 'bar',
                height: 310
            },
            colors: ['#002DBB', '#7A90D4'],
            plotOptions: {
                bar: {
                    horizontal: false,
                    columnWidth: '60%',
                    borderRadiusApplication: 'end'
                },
            },
            dataLabels: {
                enabled: false
            },
            // legend: {
            //     show: false
            // },
            stroke: {
                show: true,
                width: 2,
                colors: ['transparent']
            },
            xaxis: {
                categories: ['1', '2'],
            },
            fill: {
                opacity: 1
            },
            tooltip: {
                y: {
                    formatter: function (val) {
                        return val
                    }
                }
            },
            responsive: [{
                breakpoint: 1024,
                options: {
                    legend: {
                        show: false,
                    }
                }
            }]
        };

        var chart = new ApexCharts(document.querySelector("#asst2"), options);
        chart.render();
    </script>

    <!-- Assistant 3 -->
    <script>
        var options = {
            series: [{
                name: 'Sessions Conducted',
                data: [44, 55]
            }, {
                name: 'Sessions Planned',
                data: [76, 85]
            }],
            chart: {
                type: 'bar',
                height: 310
            },
            colors: ['#003F5C', '#FFA600'],
            plotOptions: {
                bar: {
                    horizontal: false,
                    columnWidth: '60%',
                    borderRadiusApplication: 'end'
                },
            },
            dataLabels: {
                enabled: false
            },
            // legend: {
            //     show: false
            // },
            stroke: {
                show: true,
                width: 2,
                colors: ['transparent']
            },
            xaxis: {
                categories: ['1', '2'],
            },
            fill: {
                opacity: 1
            },
            tooltip: {
                y: {
                    formatter: function (val) {
                        return val
                    }
                }
            },
            responsive: [{
                breakpoint: 1024,
                options: {
                    legend: {
                        show: false,
                    }
                }
            }]
        };

        var chart = new ApexCharts(document.querySelector("#asst3"), options);
        chart.render();
    </script>

    <!-- Assistant 4 -->
    <script>
        var options = {
            series: [{
                name: 'Updated Records',
                data: [44, 80]
            }, {
                name: 'Total Records',
                data: [13, 55]
            }],
            colors: ['#58508D', '#B3BFD1'],
            chart: {
                type: 'bar',
                height: 310,
                // width: 768,
                stacked: true,
                stackType: '100%'
            },
            plotOptions: {
                bar: {
                    columnWidth: '60%',
                    borderRadiusApplication: 'end'
                },
            },
            responsive: [{
                breakpoint: 480,
                options: {
                    legend: {
                        position: 'bottom',
                    }
                }
            }],
            xaxis: {
                categories: ['1', '2'],
            },
            fill: {
                opacity: 1
            },
            legend: {
                position: 'bottom',
                offsetX: 0,
            },
            dataLabels: {
                enabled: true,
                style: {
                    fontSize: '9px',
                    fontWeight: 'lighter'
                }
            },
        };

        var chart = new ApexCharts(document.querySelector("#asst4"), options);
        chart.render();
    </script>

    <!-- Staff Management -->
    <!-- Staff 1 -->
    <script>
        var options = {
            series: [{
                name: 'Number of exits in the cluster',
                data: [44, 80]
            }, {
                name: 'Avg. headcount in cluster',
                data: [13, 55]
            }],
            colors: ['#58508D', '#BC5090'],
            chart: {
                type: 'bar',
                height: 310,
                // width: 768,
                stacked: true,
                stackType: '100%'
            },
            plotOptions: {
                bar: {
                    columnWidth: '60%',
                    borderRadiusApplication: 'end'
                },
            },
            responsive: [{
                breakpoint: 480,
                options: {
                    legend: {
                        position: 'bottom',
                    }
                }
            }],
            xaxis: {
                categories: ['1', '2'],
            },
            fill: {
                opacity: 1
            },
            legend: {
                position: 'bottom',
                offsetX: 0,
            },
            dataLabels: {
                enabled: true,
                style: {
                    fontSize: '9px',
                    fontWeight: 'lighter'
                }
            },
        };

        var chart = new ApexCharts(document.querySelector("#staff1"), options);
        chart.render();
    </script>

    <!-- Staff 2 -->
    <script>
        var options = {
            series: [{
                name: 'No. of completed staff trainings',
                data: [44, 80]
            }, {
                name: 'Total required trainings',
                data: [13, 55]
            }],
            colors: ['#003F5C', '#B3BFD1'],
            chart: {
                type: 'bar',
                height: 310,
                // width: 768,
                stacked: true,
                stackType: '100%'
            },
            plotOptions: {
                bar: {
                    columnWidth: '60%',
                    borderRadiusApplication: 'end'
                },
            },
            responsive: [{
                breakpoint: 480,
                options: {
                    legend: {
                        position: 'bottom',
                    }
                }
            }],
            xaxis: {
                categories: ['1', '2'],
            },
            fill: {
                opacity: 1
            },
            legend: {
                position: 'bottom',
                offsetX: 0,
            },
            dataLabels: {
                enabled: true,
                style: {
                    fontSize: '9px',
                    fontWeight: 'lighter'
                }
            },
        };

        var chart = new ApexCharts(document.querySelector("#staff2"), options);
        chart.render();
    </script>

    <!-- Staff 3 -->
    <script>
        var options = {
            series: [{
                name: 'No. of on-time arrivals',
                data: [44, 55]
            }, {
                name: 'Total staff days',
                data: [76, 85]
            }],
            chart: {
                type: 'bar',
                height: 310
            },
            colors: ['#003F5C', '#FFA600'],
            plotOptions: {
                bar: {
                    horizontal: false,
                    columnWidth: '60%',
                    borderRadiusApplication: 'end'
                },
            },
            dataLabels: {
                enabled: false
            },
            // legend: {
            //     show: false
            // },
            stroke: {
                show: true,
                width: 2,
                colors: ['transparent']
            },
            xaxis: {
                categories: ['1', '2'],
            },
            fill: {
                opacity: 1
            },
            tooltip: {
                y: {
                    formatter: function (val) {
                        return val
                    }
                }
            },
            responsive: [{
                breakpoint: 1024,
                options: {
                    legend: {
                        show: false,
                    }
                }
            }]
        };

        var chart = new ApexCharts(document.querySelector("#staff3"), options);
        chart.render();
    </script>
@endsection
