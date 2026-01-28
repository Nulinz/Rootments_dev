@extends('layouts.app')

@section('content')
    <div class="sidebodydiv px-5 py-3">
        <div class="sidebodyhead">
            <h4 class="m-0">Overview</h4>
        </div>

        @include('generaldashboard.tabs')

        <div class="container mt-3 px-0">
            <div class="row">
                <div class="col-sm-12 col-md-6 col-xl-6 cards mb-3">
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

                <div class="col-sm-12 col-md-6 col-xl-6 cards mb-3">
                    <div class="cardsdiv">
                        <div class="cardshead">
                            <h6 class="card1h6 mb-2">Cluster Stores</h6>
                            <select class="form-select mb-2" name="store" id="store">
                                <option value="" selected disabled>Select Options</option>
                                @foreach ($list as $li)
                                    <option value="{{ $li->mc_id }}">{{ $li->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="cardtable">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Store Code</th>
                                        <th>Store Name</th>
                                        <th>Location</th>
                                    </tr>
                                </thead>
                                <tbody id="st_list">
                                    {{-- <tr>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                    </tr> --}}
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="col-sm-12 col-md-4 col-xl-4 cards mb-3">
                    <div class="cardsdiv">
                        <div class="cardshead">
                            <h6 class="card1h6 mb-2">Average Basket Size</h6>
                        </div>
                        <div id="chart1"></div>
                    </div>
                </div>

                <div class="col-sm-12 col-md-4 col-xl-4 cards mb-3">
                    <div class="cardsdiv">
                        <div class="cardshead">
                            <h6 class="card1h6 mb-2">Average Basket Value</h6>
                        </div>
                        <div id="chart2"></div>
                    </div>
                </div>

                <div class="col-sm-12 col-md-4 col-xl-4 cards mb-3">
                    <div class="cardsdiv">
                        <div class="cardshead">
                            <h6 class="card1h6 mb-2">Average Revenue / Product</h6>
                        </div>
                        <div id="chart3"></div>
                    </div>
                </div>

                <div class="col-sm-12 col-md-4 col-xl-4 cards mb-3">
                    <div class="cardsdiv">
                        <div class="cardshead">
                            <h6 class="card1h6 mb-2">Inventory Turnover Rate</h6>
                        </div>
                        <div id="chart4"></div>
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
        $('#store').on('change', function() {
            // Trigger an AJAX request when the page is ready
            var cluster = $(this).find('option:selected').val();
            $.ajax({
                url: '{{ route('get_cluster_store') }}', // Laravel route for the POST request
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}', // CSRF token for security
                    cluster: cluster, // Send the selected store ID
                },

                success: function(response) {
                    // console.log(response);

                    $('#st_list').empty();

                    $.each(response, function(index, value) {
                        // Create a new table row
                        var row = '<tr>' +

                            '<td>' + value.store_code + '</td>' +
                            '<td>' + value.store_name + '</td>' +
                            '<td>' + value.store_geo + '</td>' +
                            '</tr>';

                        // Append the new row to the tbody
                        $('#st_list').append(row);
                    });

                },
                error: function(xhr, status, error) {

                    alert('An error occurred: ' + error);
                }
            });
        });
    </script>

    <script src="https://cdn.jsdelivr.net/npm/apexcharts@latest"></script>

    <!-- Chart 1 -->
    <script>
        var options = {
            series: [{
                name: 'Total Bills',
                data: [44, 55]
            }, {
                name: 'Total Qty Sold',
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
                categories: ['Cluster 1', 'Cluster 2'],
            },
            fill: {
                opacity: 1
            },
            tooltip: {
                y: {
                    formatter: function(val) {
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

        var chart = new ApexCharts(document.querySelector("#chart1"), options);
        chart.render();
    </script>

    <!-- Chart 2 -->
    <script>
        var options = {
            series: [{
                name: 'Total Value',
                data: [44, 55]
            }, {
                name: 'Total Bills',
                data: [76, 85]
            }],
            chart: {
                type: 'bar',
                height: 310
            },
            colors: ['#991f17', '#b3bfd1'],
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
                categories: ['Cluster 1', 'Cluster 2'],
            },
            fill: {
                opacity: 1
            },
            tooltip: {
                y: {
                    formatter: function(val) {
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

        var chart = new ApexCharts(document.querySelector("#chart2"), options);
        chart.render();
    </script>

    <!-- Chart 3 -->
    <script>
        var options = {
            series: [{
                name: 'Total Qty',
                data: [44, 55]
            }, {
                name: 'Total Value',
                data: [76, 85]
            }],
            chart: {
                type: 'bar',
                height: 310
            },
            colors: ['#003f5c', '#ffa600'],
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
                categories: ['Cluster 1', 'Cluster 2'],
            },
            fill: {
                opacity: 1
            },
            tooltip: {
                y: {
                    formatter: function(val) {
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

        var chart = new ApexCharts(document.querySelector("#chart3"), options);
        chart.render();
    </script>

    <!-- Chart 4 -->
    <script>
        var options = {
            series: [{
                name: 'Cost Goods Sold',
                data: [44, 55]
            }, {
                name: 'Avg Inventory Value',
                data: [76, 85]
            }],
            chart: {
                type: 'bar',
                height: 310
            },
            colors: ['#58508d', '#bc5090'],
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
                categories: ['Cluster 1', 'Cluster 2'],
            },
            fill: {
                opacity: 1
            },
            tooltip: {
                y: {
                    formatter: function(val) {
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

        var chart = new ApexCharts(document.querySelector("#chart4"), options);
        chart.render();
    </script>

    <script>
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
    </script>
@endsection
