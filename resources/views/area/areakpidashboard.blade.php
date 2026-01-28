@extends('layouts.app')

@section('content')

    <div class="sidebodydiv px-5 py-3">
        <div class="sidebodyhead">
            <h4 class="m-0">KPI Dashboard</h4>
        </div>

        @include('generaldashboard.tabs')

        <div class="container px-0 mt-3">
            <div class="row">

                <div class="sidebodyhead mb-3">
                    <h4 class="m-0">Overall Cluster Performance</h4>
                </div>
                <!-- Overall Cluster Performance -->
                <div class="col-sm-12 col-md-6 col-xl-4 mb-3 cards">
                    <div class="cardsdiv">
                        <div class="cardshead">
                            <h6 class="card1h6 mb-2">Cluster Target Achievement Average</h6>
                        </div>
                        <div id="chart1"></div>
                    </div>
                </div>
                <div class="col-sm-12 col-md-6 col-xl-4 mb-3 cards">
                    <div class="cardsdiv">
                        <div class="cardshead">
                            <h6 class="card1h6 mb-2">Revenue Growth Rate</h6>
                        </div>
                        <div id="chart2"></div>
                    </div>
                </div>
                <div class="col-sm-12 col-md-4 col-xl-4 mb-3 cards">
                    <div class="cardsdiv">
                        <div class="cardshead">
                            <h6 class="card1h6 mb-2">Cluster Variance Management</h6>
                        </div>
                        <div id="chart3"></div>
                    </div>
                </div>

                <div class="sidebodyhead mb-3">
                    <h4 class="m-0">Operational Oversight</h4>
                </div>
                <!-- Operational Oversight -->
                <div class="col-sm-12 col-md-4 col-xl-4 mb-3 cards">
                    <div class="cardsdiv">
                        <div class="cardshead">
                            <h6 class="card1h6 mb-2">Audit Compliance</h6>
                        </div>
                        <div id="chart4"></div>
                    </div>
                </div>
                <div class="col-sm-12 col-md-4 col-xl-4 mb-3 cards">
                    <div class="cardsdiv">
                        <div class="cardshead">
                            <h6 class="card1h6 mb-2">Stock Utilization Rate</h6>
                        </div>
                        <div id="chart5"></div>
                    </div>
                </div>
                <div class="col-sm-12 col-md-4 col-xl-4 mb-3 cards">
                    <div class="cardsdiv">
                        <div class="cardshead">
                            <h6 class="card1h6 mb-2">Operational Task Completion</h6>
                        </div>
                        <div id="chart6"></div>
                    </div>
                </div>

                <div class="sidebodyhead mb-3">
                    <h4 class="m-0">Team and Training Management</h4>
                </div>
                <!-- Team and Training Management -->
                <div class="col-sm-12 col-md-4 col-xl-4 mb-3 cards">
                    <div class="cardsdiv">
                        <div class="cardshead">
                            <h6 class="card1h6 mb-2">Cluster Manager Performance</h6>
                        </div>
                        <div id="chart7"></div>
                    </div>
                </div>
                <div class="col-sm-12 col-md-4 col-xl-4 mb-3 cards">
                    <div class="cardsdiv">
                        <div class="cardshead">
                            <h6 class="card1h6 mb-2">Training Completion for Cluster Managers</h6>
                        </div>
                        <div id="chart8"></div>
                    </div>
                </div>

                <div class="sidebodyhead mb-3">
                    <h4 class="m-0">Attrition Monitoring</h4>
                </div>
                <!-- Attrition Monitoring -->
                <div class="col-sm-12 col-md-4 col-xl-4 mb-3 cards">
                    <div class="cardsdiv">
                        <div class="cardshead">
                            <h6 class="card1h6 mb-2">Overall Staff Attrition</h6>
                        </div>
                        <div id="chart9"></div>
                    </div>
                </div>
                <div class="col-sm-12 col-md-4 col-xl-4 mb-3 cards">
                    <div class="cardsdiv">
                        <div class="cardshead">
                            <h6 class="card1h6 mb-2">Retention Rate</h6>
                        </div>
                        <div id="chart10"></div>
                    </div>
                </div>

                <div class="sidebodyhead mb-3">
                    <h4 class="m-0">Customer Focus</h4>
                </div>
                <!-- Customer Focus -->
                <div class="col-sm-12 col-md-4 col-xl-4 mb-3 cards">
                    <div class="cardsdiv">
                        <div class="cardshead">
                            <h6 class="card1h6 mb-2">Customer Conversion Rate</h6>
                        </div>
                        <div id="chart11"></div>
                    </div>
                </div>
                <div class="col-sm-12 col-md-4 col-xl-4 mb-3 cards">
                    <div class="cardsdiv">
                        <div class="cardshead">
                            <h6 class="card1h6 mb-2">Report Timeliness</h6>
                        </div>
                        <div id="chart12"></div>
                    </div>
                </div>

            </div>

        </div>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/apexcharts@latest"></script>

    <!-- Chart 1 -->
    <script>
        var options = {
            series: [{
                name: 'All Cluster Achievement Sum',
                data: [44, 80]
            }, {
                name: 'Total Clusters',
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

        var chart = new ApexCharts(document.querySelector("#chart1"), options);
        chart.render();
    </script>

    <!-- Chart 2 -->
    <script>
        var options = {
            series: [{
                name: 'All Cluster Achievement Sum',
                data: [44, 80]
            }, {
                name: 'Total Clusters',
                data: [13, 55]
            }],
            colors: ['#003F5C', '#FFA600'],
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

        var chart = new ApexCharts(document.querySelector("#chart2"), options);
        chart.render();
    </script>

    <!-- Chart 3 -->
    <script>
        var options = {
            series: [110, 100, 220, 350, 190],
            labels: ['A', 'B', 'C', 'D', 'F'],
            colors: ['#991f17', '#b04238', '#c86558', '#b3bfd1', '#d7e1ee'],
            chart: {
                type: 'donut',
                height: 315,
            },
            legend: {
                position: 'bottom'
            },
            dataLabels: {
                enabled: false
            },
            responsive: [{
                breakpoint: 480,
                options: {
                    chart: {
                        height: 320,
                    },
                    legend: {
                        position: 'bottom'
                    }
                }
            }]
        };

        var chart = new ApexCharts(document.querySelector("#chart3"), options);
        chart.render();
    </script>

    <!-- Chart 4 -->
    <script>
        var options = {
            series: [{
                name: 'Total Audits Passed',
                data: [44, 80]
            }, {
                name: 'Total Audits Conducted',
                data: [13, 55]
            }],
            colors: ['#991F17', '#B3BFD1'],
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

        var chart = new ApexCharts(document.querySelector("#chart4"), options);
        chart.render();
    </script>

    <!-- Chart 5 -->
    <script>
        var options = {
            series: [{
                name: 'Sales Value',
                data: [44, 80]
            }, {
                name: 'Inventory Value',
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

        var chart = new ApexCharts(document.querySelector("#chart5"), options);
        chart.render();
    </script>

    <!-- Chart 6 -->
    <script>
        var options = {
            series: [{
                name: 'Tasks Completed',
                data: [44, 80]
            }, {
                name: 'Total Tasks Assigned',
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

        var chart = new ApexCharts(document.querySelector("#chart6"), options);
        chart.render();
    </script>

    <!-- Chart 7 -->
    <script>
        var options = {
            series: [{
                name: 'Sum of cluster manager KPIs Achieved',
                data: [44, 80]
            }, {
                name: 'Total KPIs Assigned',
                data: [13, 55]
            }],
            colors: ['#002DBB', '#7A90D4'],
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

        var chart = new ApexCharts(document.querySelector("#chart7"), options);
        chart.render();
    </script>

    <!-- Chart 8 -->
    <script>
        var options = {
            series: [{
                name: 'Completed',
                data: [44, 55]
            }, {
                name: 'Assigned Training',
                data: [76, 85]
            }],
            chart: {
                type: 'bar',
                height: 310
            },
            colors: ['#58508D', '#B3BFD1'],
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

        var chart = new ApexCharts(document.querySelector("#chart8"), options);
        chart.render();
    </script>

    <!-- Chart 9 -->
    <script>
        var options = {
            series: [{
                name: 'Total Exits',
                data: [44, 80]
            }, {
                name: 'Avg. Headcount in Area',
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

        var chart = new ApexCharts(document.querySelector("#chart9"), options);
        chart.render();
    </script>

    <!-- Chart 10 -->
    <script>
        var options = {
            series: [{
                name: 'Retained Staff',
                data: [44, 80]
            }, {
                name: 'Total Staff',
                data: [13, 55]
            }],
            colors: ['#003F5C', '#FFA600'],
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

        var chart = new ApexCharts(document.querySelector("#chart10"), options);
        chart.render();
    </script>

    <!-- Chart 11 -->
    <script>
        var options = {
            series: [{
                name: 'Total cluster bills',
                data: [44, 55]
            }, {
                name: 'Total attended customers in the cluster',
                data: [76, 85]
            }],
            chart: {
                type: 'bar',
                height: 310
            },
            colors: ['#58508D', '#B3BFD1'],
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

        var chart = new ApexCharts(document.querySelector("#chart11"), options);
        chart.render();
    </script>

    <!-- Chart 12 -->
    <script>
        var options = {
            series: [{
                name: 'Reports Submitted on Time',
                data: [44, 80]
            }, {
                name: 'Total Reports Required',
                data: [13, 55]
            }],
            colors: ['#003F5C', '#FFA600'],
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

        var chart = new ApexCharts(document.querySelector("#chart12"), options);
        chart.render();
    </script>


@endsection