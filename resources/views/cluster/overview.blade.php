@extends ('layouts.app')

@section('content')

    <div class="sidebodydiv px-5 py-3">
        <div class="sidebodyhead">
            <h4 class="m-0">Dashboard - Cluster Manager</h4>
        </div>

        <!-- Cluster Tabs -->
        @include('generaldashboard.tabs')

        <div class="container px-0 mt-3">
            <div class="row">
                <div class="col-sm-12 col-md-6 col-xl-6 mb-3 cards">
                    <div class="cardsdiv">
                        <div class="cardshead">
                            <h6 class="card1h6 mb-2">Cluster Stores</h6>
                        </div>
                        <div class="cardtable">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Store Code</th>
                                        <th>Store Name</th>
                                        <th>Location</th>
                                        <!-- {{-- <th>Status</th> --}} -->
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($cl_data as $cl)
                                        <tr>
                                            <td>{{$cl->store_code}}</td>
                                            <td>{{$cl->store_name}}</td>
                                            <td>{{$cl->store_geo}}</td>
                                            <!-- {{-- <td>{{$cl->st_name ?? 'no name'}}</td> --}} -->
                                        </tr>
                                    @endforeach

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="col-sm-12 col-md-6 col-xl-6 mb-3 cards">
                    <div class="cardsdiv">
                        <div class="cardshead">
                            <h6 class="card1h6 mb-2">Base Branch Target Achievement</h6>
                        </div>
                        <div id="chart1"></div>
                    </div>
                </div>
                <!-- <div class="col-sm-12 col-md-12 col-xl-12 mb-3 cards">
                                    <div class="cardsdiv">
                                        <div id="map"></div>
                                    </div>
                                </div> -->
                <div class="col-sm-12 col-md-12 col-xl-12 mb-3 cards">
                    <div class="cardsdiv">
                        <div class="cardshead">
                            <h6 class="card1h6 mb-2">Sales Growth Rate (Previous Month Difference)</h6>
                        </div>
                        <div id="chart2"></div>
                    </div>
                </div>
                <!-- <div class="col-sm-12 col-md-4 col-xl-4 mb-3 cards">
                                    <div class="cardsdiv">
                                        <div class="cardshead">
                                            <h6 class="card1h6 mb-2">Request</h6>
                                        </div>
                                        <div class="cardtable">
                                            <table class="table">
                                                <thead>
                                                    <tr>
                                                        <th>Name</th>
                                                        <th>Reason</th>
                                                        <th>Status</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                        <td>
                                                            <div class="d-flex align-items-center justify-content-start gap-2">
                                                                <img src="./images/avatar.png" alt="">
                                                                <div>
                                                                    <h5 class="mb-0">Sheik</h5>
                                                                    <h6 class="mb-0">Employee</h6>
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td>Sick Leave</td>
                                                        <td>
                                                            <div class="d-flex gap-2">
                                                                <i class="fas fa-circle-check text-success" data-bs-toggle="tooltip"
                                                                    data-bs-title="Approve"></i>
                                                                <i class="fas fa-circle-xmark text-danger" data-bs-toggle="tooltip"
                                                                    data-bs-title="Reject"></i>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>
                                                            <div class="d-flex align-items-center justify-content-start gap-2">
                                                                <img src="./images/avatar.png" alt="">
                                                                <div>
                                                                    <h5 class="mb-0">Sabari</h5>
                                                                    <h6 class="mb-0">Admin</h6>
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td>Transfer</td>
                                                        <td>
                                                            <div class="d-flex gap-2">
                                                                <i class="fas fa-circle-check text-success" data-bs-toggle="tooltip"
                                                                    data-bs-title="Approve"></i>
                                                                <i class="fas fa-circle-xmark text-danger" data-bs-toggle="tooltip"
                                                                    data-bs-title="Reject"></i>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>
                                                            <div class="d-flex align-items-center justify-content-start gap-2">
                                                                <img src="./images/avatar.png" alt="">
                                                                <div>
                                                                    <h5 class="mb-0">Naveen</h5>
                                                                    <h6 class="mb-0">Store Maintanence</h6>
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td>Work From Home</td>
                                                        <td class="text-danger">Rejected</td>
                                                    </tr>
                                                    <tr>
                                                        <td>
                                                            <div class="d-flex align-items-center justify-content-start gap-2">
                                                                <img src="./images/avatar.png" alt="">
                                                                <div>
                                                                    <h5 class="mb-0">Sugan</h5>
                                                                    <h6 class="mb-0">Employee</h6>
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td>Transfer</td>
                                                        <td class="text-success">Approved</td>
                                                    </tr>
                                                    <tr>
                                                        <td>
                                                            <div class="d-flex align-items-center justify-content-start gap-2">
                                                                <img src="./images/avatar.png" alt="">
                                                                <div>
                                                                    <h5 class="mb-0">Venkat</h5>
                                                                    <h6 class="mb-0">Employee</h6>
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td>Transfer</td>
                                                        <td>
                                                            <div class="d-flex gap-2">
                                                                <i class="fas fa-circle-check text-success" data-bs-toggle="tooltip"
                                                                    data-bs-title="Approve"></i>
                                                                <i class="fas fa-circle-xmark text-danger" data-bs-toggle="tooltip"
                                                                    data-bs-title="Reject"></i>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>
                                                            <div class="d-flex align-items-center justify-content-start gap-2">
                                                                <img src="./images/avatar.png" alt="">
                                                                <div>
                                                                    <h5 class="mb-0">Hari</h5>
                                                                    <h6 class="mb-0">Admin</h6>
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td>Casual Leave</td>
                                                        <td class="text-success">Approved</t>
                                                    </tr>
                                                    <tr>
                                                        <td>
                                                            <div class="d-flex align-items-center justify-content-start gap-2">
                                                                <img src="./images/avatar.png" alt="">
                                                                <div>
                                                                    <h5 class="mb-0">Saravanan</h5>
                                                                    <h6 class="mb-0">Admin</h6>
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td>Week Off</td>
                                                        <td>
                                                            <div class="d-flex gap-2">
                                                                <i class="fas fa-circle-check text-success" data-bs-toggle="tooltip"
                                                                    data-bs-title="Approve"></i>
                                                                <i class="fas fa-circle-xmark text-danger" data-bs-toggle="tooltip"
                                                                    data-bs-title="Reject"></i>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>
                                                            <div class="d-flex align-items-center justify-content-start gap-2">
                                                                <img src="./images/avatar.png" alt="">
                                                                <div>
                                                                    <h5 class="mb-0">Bala Krishnan</h5>
                                                                    <h6 class="mb-0">Store Maintanence</h6>
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td>Sick Leave</td>
                                                        <td class="text-danger">Rejected</td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div> -->

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
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>


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
        var options = {
            series: [{
                name: 'Sales Achieved',
                data: [44, 55, 68, 78, 28, 35, 10, 90]
            }, {
                name: 'Sales Target Pending',
                data: [13, 23, 32, 22, 72, 65, 90, 10]
            }],
            colors: ['#55A330', '#FF0000'],
            chart: {
                type: 'bar',
                height: 320,
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
                        offsetX: -10,
                        offsetY: 0
                    }
                }
            }],
            xaxis: {
                categories: ['Store 1', 'Store 2', 'Store 3', 'Store 4', 'Store 5', 'Store 6', 'Store 7', 'Store 8'],
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
                name: 'Cash Flow',
                data: [1.45, 5.42, 5.9, -0.42, -12.6, -18.1, -18.2, -14.16, -11.1, -6.09, 0.34, 3.88]
            }],
            chart: {
                type: 'bar',
                height: 400
            },
            plotOptions: {
                bar: {
                    colors: {
                        ranges: [{
                            from: -100,
                            to: -46,
                            color: '#F15B46'
                        }, {
                            from: -45,
                            to: 0,
                            color: '#FEB019'
                        }]
                    },
                    columnWidth: '80%',
                }
            },
            dataLabels: {
                enabled: false,
            },
            yaxis: {
                title: {
                    text: 'Growth',
                },
                labels: {
                    formatter: function (y) {
                        return y.toFixed(0) + "%";
                    }
                }
            },
            xaxis: {
                categories: [
                    'Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'July', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec',
                ],
                labels: {
                    rotate: -90
                }
            }
        };

        var chart = new ApexCharts(document.querySelector("#chart2"), options);
        chart.render();
    </script>

@endsection