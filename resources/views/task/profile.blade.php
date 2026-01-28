@extends('layouts.app')


<link rel="stylesheet" href="{{ asset('assets/css/profile.css') }}">
<link rel="stylesheet" href="{{ asset('assets/css/tasktimeline.css') }}">

<style>
    .mainbdy {
        margin-top: 10px;
        display: block !important;
    }
</style>

@section('content')
    <div class="sidebodydiv mb-4 px-5">

        <div class="sidebodyhead my-3">
            <h4 class="m-0">Task View</h4>
        </div>
        <div class="mainbdy">
            <div class="rounded-2 maindiv p-3">
                <div class="row">
                    <!-- Left Content -->
                    <div class="col-12 col-sm-12 col-md-12 col-xl-12 left-content">
                        <div class="container pe-2 ps-0" id="timelinecards">
                            <div class="timeline">
                                @foreach ($task as $t)
                                    @if ($loop->first)
                                        @php
                                            $loop_first = $t->assign_by;
                                        @endphp
                                    @endif

                                    <div class="entry completed">
                                        <div class="title">
                                            <h3>{{ $t->assigned_by_name }}</h3>
                                            <h6 class="mb-2">{{ $t->assigned_by_role }}</h6>
                                            <h6>{{ \Carbon\Carbon::parse($t->created_at)->format('h:i A') }}</h6>
                                        </div>
                                        <div class="entrybody">
                                            <div class="taskname mb-1">
                                                <div class="tasknameleft">
                                                    @if ($t->priority == 'High')
                                                        <i class="fa-solid fa-circle text-danger"></i>
                                                    @elseif($t->priority == 'Low')
                                                        <i class="fa-solid fa-circle text-primary"></i>
                                                    @else
                                                        <i class="fa-solid fa-circle text-warning"></i>
                                                    @endif
                                                    <h6 class="mb-0">{{ $t->task_title }}</h6>
                                                </div>
                                                <div class="tasknamefile">
                                                    @if ($t->task_file)
                                                        <a href="{{ asset($t->task_file) }}" data-bs-toggle="tooltip"
                                                            data-bs-title="Attachment" download>
                                                            <i class="fa-solid fa-paperclip"></i>
                                                        </a>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="taskcategory mb-1">
                                                <h6 class="mb-0">
                                                    <span class="category">{{ $t->category }}</span> /
                                                    <span class="subcat">{{ $t->subcategory }}</span>
                                                </h6>
                                            </div>
                                            <div class="taskdescp mb-1">
                                                <h6 class="mb-0">{{ $t->task_description }}</h6>
                                                <div class="d-flex justify-content-between">
                                                    <h5 class="mb-0">{{ $t->assigned_to_name }} -
                                                        {{ $t->assigned_to_role }}
                                                    </h5>
                                                    {{-- 
                                                    @if ($loop->last && !in_array($t->task_status, ['Completed', 'Close']) && $loop_first == auth()->user()->id)
                                                        <button class="listtdbtn" data-bs-toggle="modal"
                                                            data-id="{{ $t->id }}" data-end="{{ $t->end_date }}"
                                                            data-bs-target="#extPopup">Extend</button>
                                                    @endif --}}
                                                </div>
                                            </div>
                                            <div class="taskdate mb-2">
                                                <h6 class="startdate m-0">
                                                    <i class="fa-regular fa-calendar"></i>&nbsp;
                                                    {{ date('d/m/Y', strtotime($t->start_date)) }}
                                                </h6>
                                                <h6 class="enddate m-0">
                                                    <i class="fas fa-flag"></i>&nbsp;
                                                    {{ date('d/m/Y', strtotime($t->end_date)) }}
                                                </h6>
                                            </div>
                                            <div class="taskdate mb-2">
                                                <h6 class="startdate m-0">
                                                    <i class="fas fa-hourglass-start"></i>&nbsp;
                                                    {{ \Carbon\Carbon::parse($t->start_time)->format('h:i A') }}
                                                </h6>
                                                <h6 class="enddate m-0">
                                                    <i class="fas fa-hourglass-end"></i>&nbsp;
                                                    {{ \Carbon\Carbon::parse($t->end_time)->format('h:i A') }}
                                                </h6>
                                            </div>
                                            @if ($t->comment)
                                                <div class="taskdate">
                                                    <h6 class="startdate m-0" style="font-size: 0.8rem">
                                                        <i class="far fa-comment"></i>&nbsp;
                                                        {{ $t->comment }}
                                                    </h6>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <!-- CheckIn Modal -->
    <div class="modal fade" id="checkinModal" tabindex="-1" aria-labelledby="checkinModalLabel" aria-hidden="true"
        data-bs-backdrop="static" data-bs-keyboard="false">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <form>
                    <div class="modal-body">
                        <div class="modal-img mb-3">
                            <img src="{{ asset('assets/images/Check_In_2.png') }}"
                                class="d-flex align-items-center justify-content-center mx-auto">
                        </div>
                        <div class="mb-3">
                            <h2 class="mb-2 text-center">Check In</h2>
                            <h6 class="text-center">Quick, Secure, and Smart Attendance tracking for everyday.</h6>
                        </div>
                    </div>
                    <div class="modal-footer d-flex justify-content-center align-items-center">
                        <button type="button" class="modalbtn" onclick="getLocation()">Check In</button>
                        <a href=""><button type="button" class="modalbtn">Leave Request</button></a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- CheckIn Modal -->
    <div class="modal fade" id="extPopup" tabindex="-1" aria-labelledby="extPopupLabel" aria-hidden="true"
        data-bs-backdrop="static" data-bs-keyboard="false">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title fs-5" id="extPopupLabel">Extend Date</h4>
                    <button type="button" class="btn-close bg-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('task.extend') }}" method="POST">
                    @csrf
                    <input type="hidden" class="form-control" name="task_pop" id="task_pop">
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-sm-12 col-md-12 mb-2">
                                <label for="enddate">End Date</label>
                                <input type="date" class="form-control" name="task_end" id="task_end">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer d-flex justify-content-center align-items-center pb-0">
                        <button type="submit" class="modalbtn">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        $('.listtdbtn').on('click', function() {

            var t_id = $(this).data('id');
            var t_end = $(this).data('end');

            $('#task_pop').val(t_id);
            $('#task_end').val(t_end);


        });
    </script>
@endsection
