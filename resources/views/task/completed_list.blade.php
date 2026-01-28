@extends('layouts.app')

@section('content')
    <div class="sidebodydiv px-5 py-3">
        <div class="sidebodyhead">
            <h4 class="m-0">Completed Task List</h4>
            <div class="d-flex justify-content-around">
            </div>
        </div>

        <div class="container-fluid listtable mt-4">
            <div class="filter-container row mb-3">
                <div class="custom-search-container col-sm-12 col-md-8">
                    @php
                        $categories = $task->pluck('category')->unique()->filter();
                    @endphp

                    <select class="headerDropdown form-select filter-option">
                        <option value="All" selected>All</option>
                        @foreach ($categories as $cat)
                            <option value="{{ $cat }}">{{ $cat }}</option>
                        @endforeach
                        <input type="text" id="customSearch" class="form-control filterInput" placeholder=" Search">
                </div>

                <div class="select1 col-sm-12 col-md-4 mx-auto">
                    <div class="d-flex gap-1">
                        <div class="col">
                            <input type="date" id="enddate1" class="form-control" style="font-size: 12px">
                        </div>
                        <div class="col">
                            <input type="date" id="enddate2" class="form-control" style="font-size: 12px">
                        </div>
                    </div>
                </div>
            </div>

            <div class="table-wrapper">
                <table class="taskTable table-hover table-striped table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Task Title</th>
                            <th>Category</th>
                            <th>Sub-Category</th>
                            <th>Priority</th>
                            <th>Start Date</th>
                            <th>End Date</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($task as $data)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $data->task_title }}</td>
                                <td>{{ $data->category }}</td>
                                <td>{{ $data->subcategory }}</td>
                                <td>{{ $data->priority }}</td>
                                <td>{{ date('d-m-Y', strtotime($data->start_date)) }}</td>
                                <td>{{ date('d-m-Y', strtotime($data->end_date)) }}</td>

                                <td>
                                    <div class="d-flex gap-3">
                                        <a href="{{ route('task.view', ['id' => $data->id]) }}" data-bs-toggle="tooltip"
                                            data-bs-title="View Profile"><i class="fa-solid fa-eye"></i></a>
                                    </div>
                                </td>
                            </tr>
                        @endforeach

                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script>
        messaging.requestPermission()
            .then(() => {
                return messaging.getToken({
                    vapidKey: 'YOUR_VAPID_PUBLIC_KEY'
                });
            })
            .then((token) => {
                console.log('FCM Token:', token);
                saveTokenToServer(token);
            })
            .catch((err) => {
                console.log('Permission denied or error occurred:', err);
            });
    </script>
    <script>
        $(document).ready(function() {
            $.fn.dataTable.ext.search.push(function(settings, data, dataIndex) {
                const min = $('#enddate1').val();
                const max = $('#enddate2').val();
                const endDateStr = data[6];
                if (!endDateStr) return false;

                // dd-mm-yyyy → yyyy-mm-dd
                const parts = endDateStr.split("-");
                const formatted = `${parts[2]}-${parts[1]}-${parts[0]}`;
                const rowDate = new Date(formatted);
                const minDate = min ? new Date(min) : null;
                const maxDate = max ? new Date(max) : null;
                if ((minDate === null || rowDate >= minDate) &&
                    (maxDate === null || rowDate <= maxDate)) {
                    return true;
                }
                return false;
            });


            // Initialize DataTable
            var table = $('.taskTable').DataTable({
                paging: true,
                searching: true,
                ordering: true,
                bDestroy: true,
                info: false,
                responsive: true,
                pageLength: 10,
                dom: '<"top"f>rt<"bottom"lp><"clear">',
            });

            $('#enddate1, #enddate2').on('change', function() {
                table.draw();
            });
            // table.column(2).search(value, true, false).draw();

            // search
            $('#customSearch').on('keyup', function() {
                table.search(this.value).draw();
            });

            // filter   
            $('.headerDropdown').on('change', function() {
                const value = this.value === "All" ? "" : '^' + this.value + '$';
                table.column(2).search(value, true, false).draw(); // true = regex
            });
        });
    </script>
@endsection
