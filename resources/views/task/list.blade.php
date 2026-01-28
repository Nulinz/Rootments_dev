@extends('layouts.app')

@section('content')
    <div class="sidebodydiv px-5 py-3">
        <div class="sidebodyhead">
            <h4 class="m-0">Task List</h4>
            <div class="d-flex justify-content-around">

                {{-- @if ($r_id == 12 && $count > 0) --}}
                {{-- <a href="{{ route('task.add.cluster') }}"><button class="listbtn">+ Cluster Task</button></a> --}}
                {{-- @endif --}}
                <a href="{{ route('task.add') }}"><button class="listbtn">+ Add Task</button></a>
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
                    </select>

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
                            <th>Assign To</th>
                            <th>Status</th>
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
                                <td>{{ $data->end_date ? date('d-m-Y', strtotime($data->end_date)) : 'NA' }}</td>
                                <td>{{ $data->task_assign }}</td>
                                <td>{{ $data->task_status }}</td>

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

    {{-- <script>
        document.getElementById("print").addEventListener("click", function(e) {
            e.preventDefault();

            var table = document.querySelector(".taskTable");
            var clonedTable = table.cloneNode(true);

            clonedTable.querySelectorAll("tr").forEach(row => {
                if (row.lastElementChild) {
                    row.removeChild(row.lastElementChild);
                }
            });

            var printWindow = window.open('', '', 'height=600,width=800');
            printWindow.document.write(`
            <html>
                <head>
                    <title>Task Lists</title>
                    <style>
                        table { width: 100%; border-collapse: collapse; }
                        table, th, td { border: 1px solid black; }
                        th, td { padding: 8px; text-align: left; }
                    </style>
                </head>
                <body>${clonedTable.outerHTML}</body>
            </html>
        `);
            printWindow.document.close();
            printWindow.focus();
            printWindow.print();
            printWindow.close();
        });

        document.getElementById("excel").addEventListener("click", function(e) {
            e.preventDefault();

            var table = document.querySelector(".taskTable");
            var csv = [];
            var rows = table.querySelectorAll("tr");

            rows.forEach(row => {
                var rowData = [];
                var cells = Array.from(row.children);
                cells.slice(0, -1).forEach(cell => {
                    rowData.push('"' + cell.textContent.trim() + '"');
                });
                csv.push(rowData.join(","));
            });

            var csvBlob = new Blob([csv.join("\n")], {
                type: "text/csv"
            });
            var link = document.createElement("a");
            link.href = URL.createObjectURL(csvBlob);
            link.download = "Task-List.csv";
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
        });
    </script> --}}

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
