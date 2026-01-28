@extends('layouts.app')
@section('content')

    <link rel="stylesheet" href="{{ asset('assets/css/dashboard_main.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/dashboard_stages.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/dashboard_strength.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/dashboard_team.css') }}">

    <div class="sidebodydiv px-5 py-3">
        <div class="sidebodyhead">
            <h4 class="m-0">Store Dashboard</h4>
        </div>

        @include('generaldashboard.tabs')

        <div class="container px-0 mt-2">

            <div class="proftabs mt-0 border-0">
                <ul class="nav nav-tabs border-0" id="myTab" role="tablist">
                    @foreach($users as $data)
                        <li class="nav-item" role="presentation">
                            <button class="carddt" id="details-tab-{{$data->user_id}}" role="tab" data-bs-toggle="tab"
                                type="button" data-bs-target="#details-{{$data->user_id}}"
                                aria-controls="details-{{$data->user_id}}" aria-selected="false">
                                <div class="cardcntnt">
                                    <div class="cardimg">
                                        <img src="{{ $data->profile_image ? asset($data->profile_image) : asset('assets/images/avatar.png') }}"
                                            width="75px" height="75px" class="d-flex mx-auto"
                                            style="background-color: #eee; object-fit: contain; object-position: center;"
                                            alt="">
                                    </div>
                                    <div class="cardct mt-2">
                                        <h4 class="mb-1 text-start">{{$data->emp_code}}</h4>
                                        <h5 class="mb-1 text-start">{{$data->name}}</h5>
                                        <h6 class="mb-1 text-start">{{$data->role}}</h6>
                                        <input type="hidden" class="user-select" value="{{$data->user_id}}">
                                    </div>
                                </div>
                            </button>
                        </li>
                    @endforeach
                </ul>
            </div>

            <div class="tab-content mt-2" id="myTabContent">
                <div class="tab-pane fade show active" id="details" role="tabpanel" aria-labelledby="details-tab">
                    <div class="container-fluid px-0 mt-2 stages">
                        <div class="row">
                            <!-- Todo -->
                            <div class="col-sm-12 col-md-3 col-xl-3 px-2">
                                <div class="stagemain">
                                    <div class="todo">
                                        <div class="todoct">
                                            <h6 class="m-0">To Do</h6>
                                        </div>
                                        <div class="todono totalno">
                                            <h6 class="m-0 text-end todonoh6"></h6>
                                        </div>
                                    </div>

                                    <div class="cardmain" id="todalist">
                                        <div class="row drag todo-list">



                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- Inprogress -->
                            <div class="col-sm-12 col-md-3 col-xl-3 px-2">
                                <div class="stagemain">
                                    <div class="inprogress">
                                        <div class="inprgct">
                                            <h6 class="m-0">In Progress</h6>
                                        </div>
                                        <div class="inprgno totalno">
                                            <h6 class="m-0 text-end inprogressnoh6"></h6>
                                        </div>
                                    </div>

                                    <div class="cardmain">
                                        <div class="row drag inprogress-list">



                                        </div>
                                    </div>

                                </div>
                            </div>
                            <!-- On Hold -->
                            <div class="col-sm-12 col-md-3 col-xl-3 px-2">
                                <div class="stagemain">
                                    <div class="onhold">
                                        <div class="onholdct">
                                            <h6 class="m-0">On Hold</h6>
                                        </div>
                                        <div class="onholdno totalno">
                                            <h6 class="m-0 text-end onholdnoh6"></h6>
                                        </div>
                                    </div>

                                    <div class="cardmain">
                                        <div class="row drag onhold-list">



                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- Completed -->
                            <div class="col-sm-12 col-md-3 col-xl-3 px-2">
                                <div class="stagemain">
                                    <div class="completed">
                                        <div class="completedct">
                                            <h6 class="m-0">Completed</h6>
                                        </div>
                                        <div class="completedno totalno">
                                            <h6 class="m-0 text-end completenoh6"></h6>
                                        </div>
                                    </div>

                                    <div class="cardmain">
                                        <div class="row drag complete-list">


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


    <script>
        $(document).ready(function () {
            $(".carddt").on("click", function () {
                var userId = $(this).find(".user-select").val();

                $.ajax({
                    url: "{{route('store.usertaskdashboard')}}",
                    type: "POST",
                    data: {
                        user_id: userId,
                        _token: "{{ csrf_token() }}"
                    },
                    success: function (response) {
                        // Clear all task lists before appending new data
                        $(".todo-list").empty();
                        $(".inprogress-list").empty();
                        $(".onhold-list").empty();
                        $(".complete-list").empty();

                        $(".todonoh6").text(response.tasks_todo_count);
                        $(".inprogressnoh6").text(response.tasks_inprogress_count);
                        $(".onholdnoh6").text(response.tasks_onhold_count);
                        $(".completenoh6").text(response.tasks_complete_count);

                        // Append tasks to respective categories
                        appendTasks(response.tasks_todo, ".todo-list");
                        appendTasks(response.tasks_inprogress, ".inprogress-list");
                        appendTasks(response.tasks_onhold, ".onhold-list");
                        appendTasks(response.tasks_complete, ".complete-list");

                    },
                    error: function (xhr) {
                        alert("Failed to fetch tasks.");
                        console.error(xhr.responseText);
                    }
                });
            });

            // Function to append tasks dynamically
            //     function appendTasks(tasks, container) {
            //     if (tasks && tasks.length > 0) {
            //         tasks.forEach(task => {
            //             let newTask = `
            //                 <div class="col-sm-12 col-md-11 col-xl-11 mb-2 d-block mx-auto draggablecard">
            //                     <div class="taskname mb-2">
            //                         <div class="tasknameleft">
            //                             <i class="fa-solid fa-circle text-primary text-warning text-danger text-${task.priority.toLowerCase()}"></i>
            //                             <h6 class="mb-0">${task.task_title}</h6> <!-- Changed task.title -->
            //                         </div>
            //                         <div class="tasknamefile">
            //                             ${task.task_file ?
            //                                 `<a href="${task.task_file}" data-bs-toggle="tooltip" data-bs-title="Attachment" download>
            //                                     <i class="fa-solid fa-paperclip"></i>
            //                                 </a>`
            //                             : ''}
            //                         </div>
            //                     </div>
            //                     <div class="taskcategory mb-2">
            //                         <h6 class="mb-0"><span class="category">${task.category}</span> /
            //                             <span class="subcat">${task.subcategory}</span>
            //                         </h6>
            //                     </div>
            //                     <div class="taskdescp mb-2">
            //                         <h6 class="mb-0">${task.task_description}</h6> <!-- Changed task.description -->
            //                         <h5 class="mb-0">${task.assigned_by}</h5>
            //                     </div>
            //                     <div class="taskdate">
            //                         <h6 class="m-0 startdate"><i class="fa-regular fa-calendar"></i>&nbsp; ${task.start_date}</h6>
            //                         <h6 class="m-0 enddate"><i class="fas fa-flag"></i>&nbsp; ${task.end_date}</h6>
            //                     </div>
            //                     <div class="taskdate">
            //                      <h6 class="m-0 startdate"><i class="fa-regular fa-calendar"></i>&nbsp; ${ \Carbon\Carbon::createFromFormat('H:i:s', $task->start_time)->format('h:i A') }</h6>
            //                         <h6 class="m-0 enddate"><i class="fas fa-flag"></i>&nbsp; ${ \Carbon\Carbon::createFromFormat('H:i:s', $task->end_time)->format('h:i A') }</h6>

            //                     </div>

            //                 </div>
            //             `;
            //             $(container).append(newTask);
            //         });
            //     } else {
            //         $(container).append('<p class="text-center text-muted">No tasks available.</p>');
            //     }
            // }
            function appendTasks(tasks, container) {
                if (tasks && tasks.length > 0) {
                    tasks.forEach(task => {

                        var color = task.priority;

                        if (color == 'High') {
                            var ch = 'danger';
                        } else if (color == 'Low') {
                            var ch = 'primary';
                        } else {
                            var ch = 'warning';
                        }

                        const formatTime = (timeString) => {
                            if (!timeString) return '';
                            const [hours, minutes] = timeString.split(':');
                            const date = new Date();
                            date.setHours(hours, minutes);
                            return date.toLocaleTimeString('en-US', { hour: '2-digit', minute: '2-digit', hour12: true });
                        };

                        let newTask = `
                        <div class="col-sm-12 col-md-11 col-xl-11 mb-2 d-block mx-auto draggablecard">
                            <div class="taskname mb-2">
                                <div class="tasknameleft">
                                    <i class="fa-solid fa-circle text-${ch}"></i>
                                    <h6 class="mb-0">${task.task_title || 'Untitled Task'}</h6>
                                </div>
                                <div class="tasknamefile">
                                    ${task.task_file ?
                                `<a href="${task.task_file}" data-bs-toggle="tooltip" data-bs-title="Attachment" download>
                                            <i class="fa-solid fa-paperclip"></i>
                                        </a>`
                                : ''}
                                </div>
                            </div>
                            <div class="taskcategory mb-2">
                                <h6 class="mb-0">
                                    <span class="category">${task.category || 'Uncategorized'}</span> /
                                    <span class="subcat">${task.subcategory || 'No Subcategory'}</span>
                                </h6>
                            </div>
                            <div class="taskdescp mb-2">
                                <h6 class="mb-0">${task.task_description || 'No description provided.'}</h6>
                                <h5 class="mb-0">${task.assigned_by || 'Unknown'}</h5>
                            </div>
                            <div class="taskdate mb-2">
                                <h6 class="m-0 startdate"><i class="fa-regular fa-calendar"></i>&nbsp; ${task.start_date || 'No Start Date'}</h6>
                                <h6 class="m-0 enddate"><i class="fas fa-flag"></i>&nbsp; ${task.end_date || 'No End Date'}</h6>
                            </div>
                            <div class="taskdate">
                               <h6 class="m-0 starttime"><i class="fas fa-hourglass-start"></i>&nbsp; ${formatTime(task.start_time)}</h6>
                               <h6 class="m-0 endtime"><i class="fas fa-hourglass-end"></i>&nbsp; ${formatTime(task.end_time)}</h6>

                            </div>
                        </div>
                    `;
                        $(container).append(newTask);
                    });
                } else {
                    $(container).append('<p class="text-center text-muted" style="font-size: 10px;">No tasks available.</p>');
                }
            }


        });
    </script>


@endsection
