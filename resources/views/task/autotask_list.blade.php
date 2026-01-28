@extends('layouts.app')

@section('content')
    <div class="sidebodydiv px-5 py-3">
        <div class="sidebodyhead">
            <h4 class="m-0">Auto Task List</h4>
            <div class="d-flex justify-content-around">

                @if (in_array(auth()->user()->role_id, [1, 2]))
                    <a href="{{ route('add_auto_task') }}"><button class="listbtn">+ Add Auto Task</button></a>
                @endif
            </div>
        </div>

        <div class="container-fluid listtable mt-4">
            <div class="filter-container row mb-3">
                <div class="custom-search-container col-sm-12 col-md-8">

                    <input type="text" id="customSearch" class="form-control filterInput" placeholder=" Search">
                </div>

                {{-- <div class="select1 col-sm-12 col-md-4 mx-auto">
                    <div class="d-flex gap-1">
                        <div class="col">
                            <input type="date" id="enddate1" class="form-control" style="font-size: 12px">
                        </div>
                        <div class="col">
                            <input type="date" id="enddate2" class="form-control" style="font-size: 12px">
                        </div>
                    </div>
                </div> --}}
            </div>

            <div class="table-wrapper">
                <table class="taskTable table-hover table-striped table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Task Title</th>
                            <th>Category</th>
                            <th>Sub-Category</th>
                            <th>Description</th>
                            <th>Start Date</th>
                            <th>Assign To</th>
                            <th>Created On</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($task as $data)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $data->task_title }}</td>
                                <td>{{ $data->category }}</td>
                                <td>{{ $data->subcategory }}</td>
                                <td>{{ $data->task_description }}</td>
                                <td>{{ date('d-m-Y', strtotime($data->start_date)) }}</td>
                                <td>{{ $data->task_assign }}</td>
                                <td>{{ date('d-m-Y', strtotime($data->created_at)) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <script>
        $(document).ready(function() {
         
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
