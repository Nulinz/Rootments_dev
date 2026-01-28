@extends('layouts.app')
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
                <div class="col-md-4 col-sm-12 col-xl-4 cards mb-3">
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
                                                            class="text-success fa-circle-check fas"></i></button>
                                                @else
                                                    @if (!empty($data->in_time))
                                                        <button class="approve-attendance" data-bs-toggle="tooltip"
                                                            data-id="{{ $data->user_id }}" data-bs-title="Not Approved"><i
                                                                class="text-warning fa-circle-check fas"></i></button>
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

                <div class="col-md-4 col-sm-12 col-xl-4 cards mb-3">
                    <div class="cardsdiv">
                        <div class="cardshead">
                            <h6 class="card1h6 mb-2">Team Counts</h6>
                        </div>
                        <div id="chart2"></div>
                    </div>
                </div>

                <div class="col-md-4 col-sm-12 col-xl-4 cards mb-3">
                    <div class="cardsdiv">
                        <div class="cardshead">
                            <h6 class="card1h6 mb-2">Request</h6>
                        </div>
                        <div class="cardtable">
                            <table class="table">
                                <tbody>
                                    @foreach ($pendingRequests as $data)
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center justify-content-start gap-2">
                                                    @if ($data->profile_image)
                                                        <img src="{{ asset($data->profile_image) }}" alt=""
                                                            width="40" height="40">
                                                    @else
                                                        <img src="{{ asset('assets/images/avatar.png') }}" alt="">
                                                    @endif
                                                    <div class="d-flex align-items-center">
                                                         <div>
                                                        <h5 class="mb-0">{{ $data->name }}</h5>
                                                        <h6 class="mb-0">
                                                            Requesting for
                                                            <strong>{{ $data->request_type }}</strong>

                                                            @if ($data->request_type == 'Leave' && isset($data->start_date, $data->end_date))
                                                                ({{ date('m-d-Y', strtotime($data->start_date)) }} to
                                                                {{ date('m-d-Y', strtotime($data->end_date)) }})
                                                                - {{ $data->reason }}
                                                            @endif
                                                        </h6>
                                                         </div>
                                                         <div>
                                                            <p class="mb-0 ms-3">{{ 'F: ' . $data->start_date }}</p>
                                                            <p class="mb-0 ms-3">{{ 'T: ' . $data->end_date }}</p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <a href="{{ route('approveleave.index') }}"
                                                    class="text-decoration-underline">
                                                    View
                                                </a>
                                            </td>
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
                            <h6 class="card1h6 mb-2">Employee Login</h6>
                        </div>
                        <div class="cardtable">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>Login</th>
                                        <th>Logout</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($hr_emp as $hr)
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center justify-content-start gap-2">
                                                    {{-- @if ($hr->profile_image) --}}
                                                    <img src="{{ asset($hr->profile_image ?? 'assets/images/avatar.png') }}"
                                                        alt="">
                                                    {{-- @endif --}}
                                                    <div>
                                                        <h5 class="mb-0">{{ $hr->name }}</h5>
                                                        <h6 class="mb-0">{{ $hr->in_location ?? 'No location' }}</h6>
                                                    </div>
                                                </div>
                                            </td>

                                            <td>
                                                @if (!is_null($hr->in_time))
                                                    {{ date('h:i', strtotime($hr->in_time)) }}
                                                @endif
                                            </td>
                                            <td>
                                                @if (!is_null($hr->out_time))
                                                    {{ date('h:i', strtotime($hr->out_time)) }}
                                                @endif
                                            </td>
                                            <td>
                                                @if ($hr->status == 'approved')
                                                    <button class="" data-bs-toggle="tooltip"
                                                        data-id="{{ $hr->user_id }}" data-bs-title="Approved"><i
                                                            class="text-success fa-circle-check fas"></i></button>
                                                @else
                                                    @if (!empty($hr->in_time))
                                                        <button class="approve-attendance1" data-bs-toggle="tooltip"
                                                            data-id="{{ $hr->user_id }}" data-bs-title="Not Approved"><i
                                                                class="text-warning fa-circle-check fas"></i></button>
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

                <div class="col-md-4 col-sm-12 col-xl-4 cards mb-3">
                    <div class="cardsdiv">
                        <div class="cardshead">
                            <h6 class="card1h6 mb-2">Approved Leave List</h6>
                        </div>
                        <div class="cardtable">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>Role</th>
                                    </tr>
                                </thead>
                                <tbody>
                                     @foreach ($absent as $ab)
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center justify-content-start gap-2">
                                                    <img src="{{ asset($ab->profile_image ?? 'assets/images/avatar.png') }}"
                                                        alt="">
                                                    <div>
                                                        <h5 class="mb-0">{{ $ab->name }}</h5>
                                                        <h6 class="mb-0">{{ $ab->role_dept }}</h6>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>{{ $ab->role }}</td>
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
                            <h6 class="card1h6 mb-2">Absent List</h6>
                        </div>
                        @foreach ($known as $index => $kn)
                            <div class="accordion" id="accordion1">
                                <div class="accordion-item border-0">
                                    <h2 class="accordion-header" id="heading{{ $kn['store_code'] }}">
                                        <button class="accordion-button rounded-0 collapsed p-2" type="button"
                                            data-bs-toggle="collapse" data-bs-target="#collapse{{ $kn['store_code'] }}"
                                            aria-expanded="false" aria-controls="collapse{{ $index }}">
                                            {{ $kn['store_code'] }} - {{ $kn['store_name'] }}
                                        </button>
                                    </h2>
                                    <div id="collapse{{ $kn['store_code'] }}" class="accordion-collapse collapse"
                                        aria-labelledby="heading{{ $kn['store_code'] }}" data-bs-parent="#accordion1">
                                        <div class="accordion-body p-2">
                                            <div class="cardtable">
                                                <table class="table">
                                                    <tbody>
                                                        @foreach ($kn['users'] as $us)
                                                            <tr>
                                                                <td>
                                                                    <div
                                                                        class="d-flex align-items-center justify-content-start gap-2">
                                                                        <img src="{{ asset('assets/images/avatar.png') }}"
                                                                            alt="">
                                                                        <div>
                                                                            <h5 class="mb-0">{{ $us['user_name'] }}</h5>
                                                                            <h6 class="mb-0">{{ $us['role'] }}</h6>
                                                                        </div>
                                                                    </div>
                                                                </td>
                                                                {{-- <td>Manager</td> --}}
                                                            </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                        @foreach ($unknown as $dept => $users)
                            <div class="accordion" id="">
                                <div class="accordion-item border-0">
                                    <h2 class="accordion-header" id="heading{{ $dept }}">
                                        <button class="accordion-button rounded-0 collapsed p-2" type="button"
                                            data-bs-toggle="collapse" data-bs-target="#collapse{{ $dept }}"
                                            aria-expanded="false" aria-controls="collapse{{ $dept }}">
                                            {{ Str::upper($dept) }}
                                        </button>
                                    </h2>
                                    <div id="collapse{{ $dept }}" class="accordion-collapse collapse"
                                        aria-labelledby="heading{{ $dept }}" data-bs-parent="#accordion1">
                                        <div class="accordion-body p-2">
                                            <div class="cardtable">
                                                <table class="table">
                                                    <tbody>
                                                        @foreach ($users as $user)
                                                            <tr>
                                                                <td>
                                                                    <div
                                                                        class="d-flex align-items-center justify-content-start gap-2">
                                                                        <img src="{{ asset('assets/images/avatar.png') }}"
                                                                            alt="">
                                                                        <div>
                                                                            <h5 class="mb-0">{{ $user['user_name'] }}
                                                                            </h5>
                                                                            <h6 class="mb-0">{{ $user['role'] }}</h6>
                                                                        </div>
                                                                    </div>
                                                                </td>

                                                            </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <div class="col-md-4 col-sm-12 col-xl-4 cards mb-3">
                    <div class="cardsdiv">
                        <div class="cardshead">
                            <h6 class="card1h6 mb-2">Today Store Status</h6>
                        </div>
                        <div class="cardtable">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Code</th>
                                        <th>Name</th>
                                        <th>Total</th>
                                        <th>Present</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($store_per as $sp)
                                        <tr>
                                            <td>{{ $sp->store_code }}</td>
                                            <td>{{ $sp->store_name }}</td>
                                            <td>{{ $sp->members_count }}</td>
                                            <td>{{ $sp->present_today_count }}</td>
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
                            <h6 class="card1h6 mb-2">Today Event</h6>
                        </div>

                        <div class="cardtable">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>Code</th>
                                        <th>Day</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($bday as $bd)
                                        <tr>
                                            <td>{{ $bd->name }}</td>
                                            <td>{{ $bd->emp_code }}</td>
                                            <td>{{ $bd->type }}</td>
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

                        {{-- user leave list --}}
                <div class="col-md-4 col-sm-12 col-xl-4 cards mb-3">
                    <div class="cardsdiv">
                        <div class="cardshead">
                            <h6 class="card1h6 mb-2">Leave List</h6>
                        </div>
                        @foreach ($user_leave_list as $index => $ul)
                            @php
                                // $store_name = $ul->first()->value('store_name');
                                // \Log::info($data);
                                $data = $ul->first();
                                // \Log::info($data['store_name']);
                                // dd($ul->toArray());
                            @endphp
                            <div class="accordion" id="accordion1">
                                <div class="accordion-item border-0">
                                    <h2 class="accordion-header" id="heading{{ $index . '-' . $index }}">
                                        <button class="accordion-button rounded-0 collapsed p-2" type="button"
                                            data-bs-toggle="collapse" data-bs-target="#collapse{{ $index . '-' . $index }}"
                                            aria-expanded="false" aria-controls="collapse{{ $index . '-' . $index }}">
                                            {{ $data['store_name'] }}
                                        </button>
                                    </h2>
                                    <div id="collapse{{ $index . '-' . $index }}" class="accordion-collapse collapse"
                                        aria-labelledby="heading{{ $index . '-' . $index }}" data-bs-parent="#accordion1">
                                        <div class="accordion-body p-2">
                                            <div class="cardtable">
                                                <table class="table">
                                                    <tbody>
                                                        @foreach ($ul as $us)
                                                            <tr>
                                                                <td>
                                                                    <div
                                                                        class="d-flex align-items-center justify-content-start gap-2">
                                                                        <img src="{{ asset('assets/images/avatar.png') }}"
                                                                            alt="">
                                                                        <div>
                                                                            <h5 class="mb-1">{{ $us['user_name'] }}</h5>
                                                                            <h6 class="mb-0">{{ $us['role_name'] }}</h6>
                                                                            <div class="my-2">
                                                                                <h5 class="mb-1">
                                                                                    <span>Annual: </span> {{ 20 - (int) $us['Annual_Leave_Days'] }} / 20
                                                                                </h5>
                                                                                <h5 class="mb-0">
                                                                                <span>Week Off: </span>     {{ 4 - (int) $us['Week_Off_Days'] }} / 4</h5>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </td>
                                                                {{-- <td>Manager</td> --}}
                                                            </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach

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


        <script>
            document.addEventListener("DOMContentLoaded", function() {
                var roleNames = @json($roleNames);
                var userCounts = @json($userCounts);

                var options = {
                    series: [{
                        name: "Users Count",
                        data: userCounts
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
                        categories: roleNames,
                    },
                    legend: {
                        show: false
                    },
                };

                var chart = new ApexCharts(document.querySelector("#chart2"), options);
                chart.render();
            });
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


            $('.approve-attendance1').on("click", function() {
                let userId = $(this).data("id");

                console.log(userId);
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
