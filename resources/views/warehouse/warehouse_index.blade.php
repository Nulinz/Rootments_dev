@extends ('layouts.app')

@section('content')
    <link rel="stylesheet" href="{{ asset('assets/css/dashboard_main.css') }}">

    <div class="sidebodydiv px-5 py-3">
        <div class="sidebodyhead">
            <h4 class="m-0">Overview</h4>
        </div>

        <!-- Tabs -->
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
                                    @foreach ($ware_emp as $ware)
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center justify-content-start gap-2">
                                                    <img src="{{ asset($ware->profile_image ?? 'assets/images/avatar.png') }}"
                                                        alt="">
                                                    <div>
                                                        <h5 class="mb-0">{{ $ware->name }}</h5>
                                                        <h6 class="mb-0">{{ $ware->in_location ?? 'No location' }}</h6>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                @if (!is_null($ware->in_time))
                                                    {{ date('h:i', strtotime($ware->in_time)) }}
                                                @endif
                                            </td>
                                            <td>
                                                @if (!is_null($ware->out_time))
                                                    {{ date('h:i', strtotime($ware->out_time)) }}
                                                @endif
                                            </td>
                                            <td>
                                                @if ($ware->status == 'approved')
                                                    <button class="" data-bs-toggle="tooltip"
                                                        data-id="{{ $ware->user_id }}" data-bs-title="Approved"><i
                                                            class="fas fa-circle-check text-success"></i></button>
                                                @else
                                                    @if (!empty($ware->in_time))
                                                        <button class="approve-attendance" data-bs-toggle="tooltip"
                                                            data-id="{{ $ware->user_id }}" data-bs-title="Not Approved"><i
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
                            <h6 class="card1h6 mb-2">Team Performance</h6>
                        </div>
                        <div id="chart2"></div>
                    </div>
                </div>
                <div class="col-sm-12 col-md-4 col-xl-4 cards mb-3">
                    <div class="cardsdiv">
                        <div class="cardshead">
                            <h6 class="card1h6 mb-2">Category Task</h6>
                        </div>
                        <div id="chart3"></div>
                    </div>
                </div>
                <div class="col-sm-12 col-md-4 col-xl-4 cards mb-3">
                    <div class="cardsdiv">
                        <div class="cardshead">
                            <h6 class="card1h6 mb-2">Sub Category Task</h6>
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
                                                            {{ $data->request_type }}
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
                <div class="modal fade" id="extPopup1" tabindex="-1" aria-labelledby="extPopupLabel" aria-hidden="true"
                    data-bs-backdrop="static" data-bs-keyboard="false">
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

    <!-- Chart 1 -->
    <script>
        var taskCounts = @json($task);

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
                height: 315,
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
                        height: 320,
                    },
                    legend: {
                        position: 'bottom'
                    }
                }
            }]
        };

        var chart = new ApexCharts(document.querySelector("#chart1"), options);
        chart.render();
    </script>

    <!-- Chart 2 -->
    <script>
        var taskCounts = @json($taskCounts);
        var staffNames = @json($staffNames);

        // Ensure the chart container exists
        var chartElement = document.querySelector("#chart2");

        var options = {
            series: [{
                data: taskCounts
            }],
            chart: {
                height: 300,
                type: 'bar',
                events: {
                    click: function(chart, w, e) {},
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

        var chart = new ApexCharts(document.querySelector("#chart2"), options);
        chart.render();
    </script>

    <!-- Chart 3 -->
    <script>
        var categoryNames = @json($categoryNames);
        var taskCounts = @json($categorytaskCounts);

        var chartElement = document.querySelector("#chart3");


        var options = {
            series: taskCounts,
            labels: categoryNames,
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
        var subcategoryNames = {!! json_encode($subcategoryNames ?? []) !!};
        var subtaskCounts = {!! json_encode($subcategorytaskCounts ?? []) !!};

        var chartElement = document.querySelector("#chart4");
        var options = {
            series: subtaskCounts,
            labels: subcategoryNames,
            colors: ['#0427B9', '#435DCA', '#8192DB', '#9AA8E2', '#C0C8ED'],
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

        var chart = new ApexCharts(document.querySelector("#chart4"), options);
        chart.render();
    </script>


    <script>
        $('.approve-attendance').on("click", function() {
            let userId = $(this).data("id");

            // console.log(userId);
            $.ajax({
                url: "{{ route('attendance.approve') }}",
                type: "POST",
                data: {
                    user_id: userId,
                    _token: '{{ csrf_token() }}'
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
    </script>
@endsection
