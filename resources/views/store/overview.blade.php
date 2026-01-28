@extends('layouts.app')
@section('content')
    <link rel="stylesheet" href="{{ asset('assets/css/dashboard_main.css') }}">

    <div class="sidebodydiv px-5 py-3">
        <div class="sidebodyhead">
            <h4 class="m-0">Overview</h4>
        </div>

        @include('generaldashboard.tabs')

        <div class="container mt-2 px-0">
            <div class="row">
                <div class="col-sm-12 col-md-4 col-xl-4 cards mb-3">
                    <div class="cardsdiv">
                        <div class="cardshead">
                            <h6 class="card1h6 mb-2">Today Login</h6>
                        </div>
                        <div class="cardtable">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>In</th>
                                        <th>Out</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($overview as $data)
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center justify-content-start gap-2">
                                                    @if ($data->profile_image)
                                                        <img src="{{ asset($data->profile_image) }}" alt="">
                                                    @else
                                                        <img src="{{ asset('assets/images/avatar.png') }}" alt="">
                                                    @endif
                                                    <div>
                                                        <h5 class="mb-0">{{ $data->name }}</h5>
                                                        <h6 class="mb-0">{{ $data->in_location }}</h6>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                @if (!is_null($data->in_time))
                                                    {{ date('h:i', strtotime($data->in_time)) }}
                                                @endif
                                            </td>
                                            <td>
                                                @if (!is_null($data->out_time))
                                                    {{ date('h:i', strtotime($data->out_time)) }}
                                                @endif
                                            </td>
                                            <td>
                                                @if ($data->status == 'approved')
                                                    <button class="" data-bs-toggle="tooltip"
                                                        data-id="{{ $data->user_id }}" data-bs-title="Approved"><i
                                                            class="fas fa-circle-check text-success"></i></button>
                                                @else
                                                    @if (!empty($data->in_time))
                                                        <button class="approve-attendance" data-bs-toggle="tooltip"
                                                            data-id="{{ $data->user_id }}" data-bs-title="Not Approved"><i
                                                                class="fas fa-circle-check text-warning"></i></button>
                                                    @endif
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="col-sm-12 col-md-4 col-xl-4 cards mb-3">
                    <div class="cardsdiv">
                        <div class="cardshead">
                            <h6 class="card1h6 mb-2">Task Status</h6>
                        </div>
                        <div id="chart1"></div>
                    </div>
                </div>
                  <div class="col-sm-12 col-md-4 col-xl-4 cards mb-3">
                    <div class="cardsdiv">
                        <div class="cardshead">
                            <h6 class="card1h6 mb-2">ABS</h6>
                        </div>
                        <div id="chart2"></div>
                    </div>
                </div>

                <div class="col-sm-12 col-md-4 col-xl-4 cards mb-3">
                    <div class="cardsdiv">
                        <div class="cardshead">
                            <h6 class="card1h6 mb-2">ABV</h6>
                        </div>
                        <div id="chart3"></div>
                    </div>
                </div>
                <div class="col-sm-12 col-md-4 col-xl-4 cards mb-3">
                    <div class="cardsdiv">
                        <div class="cardshead">
                            <h6 class="card1h6 mb-2">Conversion</h6>
                        </div>
                        <div id="chart4"></div>
                    </div>
                </div>
                <div class="col-sm-12 col-md-4 col-xl-4 cards mb-3">
                    <div class="cardsdiv">
                        <div class="cardshead">
                            <h6 class="card1h6 mb-2">Leave Request</h6>
                        </div>
                        <div class="cardtable">
                            <table class="table">
                                <tbody>
                                    @foreach ($pendingLeaves as $data)
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center justify-content-start gap-2">
                                                    @if ($data->profile_image)
                                                        <img src="{{ asset($data->profile_image) }}" alt="">
                                                    @else
                                                        <img src="{{ asset('assets/images/avatar.png') }}" alt="">
                                                    @endif
                                                    <div>
                                                        <h5 class="mb-0">{{ $data->name }}</h5>
                                                        <h6 class="mb-0">
                                                            Requesting for leave
                                                            {{ date('m-d-Y', strtotime($data->start_date)) }} to
                                                            {{ date('m-d-Y', strtotime($data->end_date)) }} -
                                                            {{ $data->reason }}
                                                        </h6>
                                                    </div>
                                                </div>
                                            </td>
                                            <td><a href="{{ route('approve.index') }}"
                                                    class="text-decoration-underline">View</a></td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 col-sm-12 col-xl-4 cards mb-3">
                    <div class="cardsdiv">
                        <div class="cardshead">
                            <h6 class="card1h6 mb-2">Walkin Counts</h6>
                        </div>
                        <form method="GET" class="d-flex justify-content-between">
                            <div>
                                <label class="d-block">From</label>
                                <input type="date" class="form-control-sm" name="fromdate" id="fromdate"
                                    value="{{ date('Y-m-d') }}">
                            </div>
                            <div>
                                <label class="d-block">To</label>
                                <input type="date" class="form-control-sm" name="todate" id="todate"
                                    value="{{ date('Y-m-d') }}">
                            </div>
                            {{-- <div class="align-self-end">
                                <button type="submit" class="btn btn-primary">Filter</button>
                            </div> --}}
                        </form>

                        <div id="chart5"></div>
                    </div>
                </div>

                {{-- task extend --}}
                <div class="col-md-4 col-sm-12 col-xl-4 cards mb-3">
                    <div class="cardsdiv">
                        <div class="cardshead">
                            <h6 class="card1h6 mb-2">Task Extend</h6>
                        </div>

                        <div class="cardtable">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Task</th>
                                        <th>Remarks</th>
                                        <th>date</th>
                                        <th>Cat</th>
                                        <th>by</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($task_ext as $tk)
                                        <tr>
                                            <td>{{ $tk->task_title }}</td>
                                            <td>{{ $tk->c_remarks }}</td>
                                            <td>{{ \Carbon\Carbon::parse($tk->extend_date)->format('d-m-Y') }}</td>
                                            <td>{{ $tk->category }}
                                                @if ($tk->attach)
                                                    <a class=""
                                                        href="{{ asset('assets/images/Task/' . $tk->attach) }}"
                                                        download=""><i class="fa fa-paperclip"></i>
                                                    </a>
                                                @endif
                                            </td>
                                            <td>{{ $tk->name }}</td>
                                            <td>
                                                @if ($tk->status == 'Close Request')
                                                    <button class="listtdbtn bg-danger text-white" data-bs-toggle="modal"
                                                        data-bs-target="#extPopup1"
                                                        data-taskid="{{ $tk->id }}">close</button>
                                                @elseif ($tk->status == 'Pending')
                                                    <button class="listtdbtn bg-dark" data-bs-toggle="modal"
                                                        data-bs-target="#extPopup"
                                                        data-taskid="{{ $tk->id }}">Extend</button>
                                                @endif

                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>

                </div>

                <!-- close task popup -->
                <div class="modal fade" id="extPopup1" tabindex="-1" aria-labelledby="extPopupLabel"
                    aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h4 class="modal-title fs-5" id="extPopupLabel">Close Task</h4>
                                <button type="button" class="btn-close bg-white" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>
                            <form method="POST" action="{{ route('del_task') }}">
                                @csrf

                                <input type="hidden" name="id" id="id">

                                <div class="modal-body">
                                    <div class="row">
                                        {{-- <div class="col-sm-12 col-md-12 mb-2">
                                            <label for="enddate">close Date</label>
                                            <input type="date" class="form-control" name="extend_date">
                                        </div> --}}
                                        <div class="col-sm-12 col-md-12 mb-2">
                                            <label for="">Remarks</label>
                                            <input type="text" class="form-control" name="remarks">
                                        </div>
                                    </div>
                                </div>
                                <div class="modal-footer d-flex justify-content-center align-items-center pb-1">
                                    <button type="submit" class="modalbtn">Save</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                {{-- extend task popup --}}
                <div class="modal fade" id="extPopup" tabindex="-1" aria-labelledby="extPopupLabel"
                    aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h4 class="modal-title fs-5" id="extPopupLabel">Extend Date</h4>
                                <button type="button" class="btn-close bg-white" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>
                            <form method="POST" action="{{ route('task_ext_update') }}">
                                @csrf

                                <input type="hidden" name="id" id="close_id">

                                <div class="modal-body">
                                    <div class="row">
                                        <div class="col-sm-12 col-md-12 mb-2">
                                            <label for="enddate">End Date</label>
                                            <input type="date" class="form-control" name="extend_date">
                                        </div>
                                        <div class="col-sm-12 col-md-12 mb-2">
                                            <label for="">Remarks</label>
                                            <input type="text" class="form-control" name="remarks">
                                        </div>
                                    </div>
                                </div>
                                <div class="modal-footer d-flex justify-content-center align-items-center pb-1">
                                    <button type="submit" class="modalbtn">Save</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/apexcharts@latest"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/axios/1.4.0/axios.min.js"></script>
    {{-- <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script> --}}

    <script>
        // For Extend Task Modal
        const extModal = document.getElementById('extPopup');

        extModal.addEventListener('show.bs.modal', function(event) {
            const button = event.relatedTarget;
            const taskId = button.getAttribute('data-taskid');
            document.getElementById('close_id').value = taskId; // Set ID into hidden input
        });

        // For Close Task Modal
        const closeModal = document.getElementById('extPopup1');

        closeModal.addEventListener('show.bs.modal', function(event) {
            const button = event.relatedTarget;
            const taskId = button.getAttribute('data-taskid');
            document.getElementById('id').value = taskId; // Set ID into hidden input
        });
    </script>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            var taskCounts = @json($tolatask);

            var chartElement = document.querySelector("#chart1");

            var seriesData = [
                Number(taskCounts.todo) || 0,
                Number(taskCounts.in_progress) || 0,
                Number(taskCounts.on_hold) || 0,
                Number(taskCounts.completed) || 0
            ];

            var options = {
                series: seriesData,
                labels: ['To Do', 'In Progress', 'On Hold', 'Completed'],
                colors: ['#003f5c', '#58508d', '#bc5090', '#0427B9'],
                chart: {
                    type: 'donut',
                    height: 315
                },
                legend: {
                    position: 'bottom'
                },
                dataLabels: {
                    enabled: false
                },
                responsive: [{
                    breakpoint: 300,
                    options: {
                        chart: {
                            height: 320
                        },
                        legend: {
                            position: 'bottom'
                        }
                    }
                }]
            };

            var chart = new ApexCharts(chartElement, options);
            chart.render();
        });
    </script>
      <script>
        document.addEventListener("DOMContentLoaded", function() {
            // Fetch dynamic data from Laravel
            var taskCounts = @json($abs_dt);
            var staffNames = @json($dsr_name);

            // Ensure the chart container exists
            var chartElement = document.querySelector("#chart2");


            // Define the ApexCharts options
            var options = {
                series: [{
                    data: taskCounts
                }],
                chart: {
                    height: 300,
                    type: 'bar',
                    events: {
                        click: function(chart, w, e) {
                            console.log("Bar clicked!", w, e);
                        },
                    },
                },
                colors: ['#0427B9'],
                plotOptions: {
                    bar: {
                        columnWidth: '45%',
                        distributed: true,
                        borderRadius: 5,
                    },
                },
                dataLabels: {
                    enabled: false
                },
                legend: {
                    show: false
                },
                xaxis: {
                    categories: staffNames,
                    labels: {
                        style: {
                            fontSize: '6px',
                            fontWeight: 500,
                        },
                    },
                },
            };

            // Render the chart
            var chart = new ApexCharts(chartElement, options);
            chart.render();
        });
    </script>
    <!-- Chart 3 -->
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            // Fetch dynamic data from Laravel
            var taskCounts = @json($abv_dt);
            var staffNames = @json($dsr_name);

            // Ensure the chart container exists
            var chartElement = document.querySelector("#chart3");


            // Define the ApexCharts options
            var options = {
                series: [{
                    data: taskCounts
                }],
                chart: {
                    height: 300,
                    type: 'bar',
                    events: {
                        click: function(chart, w, e) {
                            console.log("Bar clicked!", w, e);
                        },
                    },
                },
                colors: ['#0427B9'],
                plotOptions: {
                    bar: {
                        columnWidth: '45%',
                        distributed: true,
                        borderRadius: 5,
                    },
                },
                dataLabels: {
                    enabled: false
                },
                legend: {
                    show: false
                },
                xaxis: {
                    categories: staffNames,
                    labels: {
                        style: {
                            fontSize: '6px',
                            fontWeight: 500,
                        },
                    },
                },
            };

            // Render the chart
            var chart = new ApexCharts(chartElement, options);
            chart.render();
        });
    </script>
    {{-- chart 4 --}}
    <script>
document.addEventListener("DOMContentLoaded", function () {
    var taskCounts = @json($conv_dt);
    var staffNames = @json($dsr_name);

    console.log("taskCounts:", taskCounts);
    console.log("staffNames:", staffNames);

    var chartElement = document.querySelector("#chart4");
    if (!chartElement) return;

    // Ensure values are numeric
    taskCounts = taskCounts.map(v => Number(v) || 0);

    var options = {
        series: taskCounts,
        chart: {
            type: 'donut', // change to 'pie' if you prefer
            height: 320,
        },
        labels: staffNames,
        colors: [
            '#0427B9', '#00BFFF', '#FF7F50', '#FFD700', '#8A2BE2',
            '#20B2AA', '#FF1493', '#FF4500', '#32CD32', '#6495ED'
        ],
        legend: {
            position: 'bottom',
            fontSize: '10px'
        },
        dataLabels: {
            enabled: true,
            formatter: function (val, opts) {
                return opts.w.config.series[opts.seriesIndex];
            },
            
        },
        tooltip: {
            y: {
                formatter: val => `${val} tasks`
            }
        },
        plotOptions: {
            pie: {
                donut: {
                    size: '65%',
                    labels: {
                        show: true,
                        total: {
    show: true,
    label: 'Total',
    formatter: function (w) {
        const total = w.globals.seriesTotals.reduce((a, b) => a + b, 0);
        return Number(total.toFixed(3));
    }
}

                    }
                }
            }
        }
    };

    var chart = new ApexCharts(chartElement, options);
    chart.render();
});

    </script>
    <script>
        // document.addEventListener("DOMContentLoaded", function() {
        //     var subcategoryNames = {!! json_encode($subcategoryNames ?? []) !!};
        //     var subtaskCounts = {!! json_encode($subcategorytaskCounts ?? []) !!};

        //     var chartElement = document.querySelector("#chart4");
        //     if (!chartElement) {
        //         console.error("Chart container #chart4 not found!");
        //         return;
        //     }

        //     var options = {
        //         series: subtaskCounts,
        //         labels: subcategoryNames,
        //         colors: ['#0427B9', '#435DCA', '#8192DB', '#9AA8E2', '#C0C8ED'],
        //         chart: {
        //             type: 'donut',
        //             height: 315,
        //         },
        //         legend: {
        //             position: 'bottom'
        //         },
        //         dataLabels: {
        //             enabled: false
        //         },
        //         responsive: [{
        //             breakpoint: 480,
        //             options: {
        //                 chart: {
        //                     height: 320,
        //                 },
        //                 legend: {
        //                     position: 'bottom'
        //                 }
        //             }
        //         }]
        //     };

        //     var chart = new ApexCharts(chartElement, options);
        //     chart.render();
        // });
    </script>
    <script>
        $(document).ready(function() {
            $(document).on("click", ".approve-attendance", function() {
                let userId = $(this).data("id");

                console.log(userId);
                $.ajax({
                    url: "{{ route('attendance.approve') }}",
                    type: "POST",
                    data: {
                        user_id: userId,
                        _token: $('meta[name="csrf-token"]').attr("content")
                    },
                    success: function(response) {
                        if (response.success) {
                            alert("Attendance Approved!");
                            location.reload();
                        } else {
                            alert("Something went wrong!");
                        }
                    },
                    error: function() {
                        alert("Error occurred!");
                    }
                });
            });
        });
    </script>
    {{-- <script>
        document.addEventListener("DOMContentLoaded", function() {

            var walkinStatuses = @json($walkinStatuses);
            var walkinCounts = @json($walkinCounts);

            var options = {
                series: [{
                    name: "Walk-in Count",
                    data: walkinCounts
                }],
                chart: {
                    type: "bar",
                    height: 320,
                },
                plotOptions: {
                    bar: {
                        borderRadius: 0,
                        horizontal: true,
                        barHeight: '80%',
                    },
                },
                dataLabels: {
                    enabled: false,
                    formatter: function(val, opt) {
                        return opt.w.globals.labels[opt.dataPointIndex] + ': ' + val;
                    },
                },
                xaxis: {
                    categories: walkinStatuses,
                },
                legend: {
                    show: false
                },
            };

            var chart = new ApexCharts(document.querySelector("#chart5"), options);
            chart.render();
        });
    </script> --}}

       <script>
        function fetchChartData() {
            const fromdate = document.getElementById("fromdate").value;
            const todate = document.getElementById("todate").value;

            if (!fromdate || !todate) {
                return;
            }

            $.ajax({
                url: '{{ route('chart.walkinStatus') }}',
                method: 'GET',
                data: {
                    fromdate: fromdate,
                    todate: todate
                },
                success: function(data) {
                    console.log(data);

                    // if (Array.isArray(data.statuses) && Array.isArray(data.counts)) {
                    renderWalkinChart(data.statuses, data.counts);
                    // } else {
                    //     console.error("Invalid chart data format", data);
                    // }
                },
                error: function(xhr, status, error) {
                    console.error("Error loading chart data:", error);
                }
            });
        }

        function renderWalkinChart(statuses, counts) {

            const outputJson = JSON.stringify({
                statuses: statuses,
                counts: counts
            }, null, 2);

            const options = {

                series: [{
                    name: 'Walk-in Count',
                    data: counts
                }],
                chart: {
                    type: 'bar',
                    height: 300
                },
                plotOptions: {
                    bar: {
                        horizontal: true
                    }
                },
                xaxis: {
                    categories: statuses
                },
                dataLabels: {
                    enabled: true
                }
            };

            // Clear previous chart and render new one
            document.querySelector("#chart5").innerHTML = "";
            const chart = new ApexCharts(document.querySelector("#chart5"), options);
            chart.render();
        }

        // Attach onchange events after page load
        window.onload = function() {
            fetchChartData();
            document.getElementById("fromdate").onchange = fetchChartData;
            document.getElementById("todate").onchange = fetchChartData;
        };
    </script>
@endsection
