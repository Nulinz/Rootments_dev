@extends('layouts.app')
@section('content')
    <style>
        .dt-buttons {
            display: none !important;
        }

        .dataTables_wrapper .dataTables_paginate .paginate_button {
            font-size: 14px;
        }

        div.dataTables_wrapper div.dataTables_info {
            font-size: 14px;
        }
    </style>
    <div class="sidebodydiv px-5 py-3">
        <div class="sidebodyhead">
            <h4 class="m-0">Leave Report</h4>

        </div>

        <!--<form action="{{ route('employee.leave_report') }}" method="post">-->
        <!--    @csrf-->
        <!--    <div class="container-fluid maindiv my-3">-->
        <!--        <div class="row">-->
        <!--            <div class="col-sm-12 col-md-4 col-xl-4 inputs mb-3">-->
        <!--                <label for="startdate">Start Date <span>*</span></label>-->
        <!--                <input type="date" class="form-control" name="startdate" id="startdate" required value="{{ date('Y-m-d') }}">-->
        <!--            </div>-->
        <!--            <div class="col-sm-12 col-md-4 col-xl-4 inputs mb-3">-->
        <!--                <label for="enddate">End Date <span>*</span></label>-->
        <!--                <input type="date" class="form-control" name="enddate" id="enddate" required value="{{ date('Y-m-d') }}">-->
        <!--            </div>-->
        <!--            <div class="col-sm-12 col-md-4 col-xl-4 inputs mb-3">-->
        <!--                <label for="enddate">Select Employee<span>*</span></label>-->

        <!--                <select class="form-select" name="emp_id" id="employeeDropdown">-->
        <!--                    <option value="" selected disabled>Select Employee</option>-->
        <!--                    @foreach ($employee as $emp)-->
        <!--                        <option value="{{ $emp->id }}">{{ $emp->name }} ({{ $emp->emp_code }})</option>-->
        <!--                    @endforeach-->
        <!--                </select>-->
        <!--            </div>-->
        <!--        </div>-->
        <!--    </div>-->

        <!--    <div class="col-sm-12 col-md-12 col-xl-12 w-50 d-flex justify-content-center align-items-center mx-auto mt-3">-->
        <!--        <button type="submit" class="formbtn">Save</button>-->
        <!--    </div>-->
        <!--</form>-->
        
         <form action="{{ route('employee.leave_report') }}" method="post">
            @csrf
            <div class="container-fluid maindiv my-3">
                <div class="row">
                    <div class="col-sm-12 col-md-4 col-xl-4 inputs mb-3">
                        <label for="startdate">Start Date <span>*</span></label>
                        <input type="date" class="form-control" name="startdate" id="startdate" required value="{{ date('Y-m-d') }}">
                    </div>
                    <div class="col-sm-12 col-md-4 col-xl-4 inputs mb-3">
                        <label for="enddate">End Date <span>*</span></label>
                        <input type="date" class="form-control" name="enddate" id="enddate" required value="{{ date('Y-m-d') }}">
                    </div>
                    <div class="col-sm-12 col-md-4 col-xl-4 inputs mb-3">
                        <label for="enddate">Select Employee<span>*</span></label>

                        <select class="form-select" name="emp_id" id="employeeDropdown">
                            <option value="" disabled selected>Select Employee</option>
                            <option value="all">All Employees</option>
                            @foreach ($employee as $emp)
                                <option value="{{ $emp->id }}" {{ isset($selected_emp) && $selected_emp == $emp->id ? 'selected' : '' }}>
                                    {{ $emp->name }} ({{ $emp->emp_code }})
                                </option>
                            @endforeach
                        </select>

                    </div>
                </div>
            </div>

            <div class="col-sm-12 col-md-12 col-xl-12 w-50 d-flex justify-content-center align-items-center mx-auto mt-3">
                <button type="submit" class="formbtn">Save</button>
            </div>
        </form>

        <div class="container-fluid listtable mt-4">
            <div class="filter-container row mb-3">
                <div class="custom-search-container col-sm-12 col-md-8">
                    <select class="headerDropdown form-select filter-option">
                        <option value="All" selected>All</option>
                    </select>

                    <input type="text" id="customSearch" class="form-control filterInput" placeholder="Search">
                </div>
                <div class="select1 col-sm-12 col-md-4 mx-auto">
                    <div class="d-flex gap-3">
                        <a id="printBtn"><img src="{{ asset('assets/images/printer.png') }}" id="print" alt="" height="28px" data-bs-toggle="tooltip"
                                data-bs-title="Print"></a>
                        <a id="excelBtn"><img src="{{ asset('assets/images/excel.png') }}" id="excel" alt="" height="30px" data-bs-toggle="tooltip"
                                data-bs-title="Excel"></a>
                    </div>
                </div>
            </div>

            <div class="table-wrapper">
                <table id="example" class="table-hover table-striped table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Employee Code</th>
                            <th>Full Name</th>
                            <th>Type</th>
                            <th>Start Date</th>
                            <th>End Date</th>
                            <th>Reason</th>
                            <th width="20%">Status</th>
                            {{-- <th>Action</th> --}}
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($leaves as $item)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $item->emp_code }}</td>
                                <td>{{ $item->name }}</td>
                                <td>{{ $item->request_type }}</td>
                                <td>{{ $item->start_date }}</td>
                                <td>{{ $item->end_date }}</td>
                                <td>{{ $item->reason }}</td>
                                <td>{{ $item->status }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- DataTables + Buttons -->
    <link href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css" rel="stylesheet">
    {{-- <link href="https://cdn.datatables.net/buttons/2.4.1/css/buttons.dataTables.min.css" rel="stylesheet"> --}}

    {{-- <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>/ --}}
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.1/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.print.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>

    <script>
        $(document).ready(function() {
            const table = $('#example').DataTable({
                dom: 'Bfrtip',
                buttons: [{
                        extend: 'excelHtml5',
                        title: 'Leave Report',
                        exportOptions: {
                            columns: ':visible'
                        }
                    },
                    {
                        extend: 'print',
                        title: 'Leave Report',
                        exportOptions: {
                            columns: ':visible'
                        }
                    }
                ]
            });

            // Collect unique values from column 5, 6, 7
            const filterSet = new Set();
            table.rows().every(function() {
                const data = this.data();
                [6, 7].forEach(index => {
                    const val = data[index].trim();
                    if (val) filterSet.add(val);
                });
            });

            // Append to the single dropdown
            filterSet.forEach(function(value) {
                $('.headerDropdown').append(`<option value="${value}">${value}</option>`);
            });

            // Filter on dropdown change
            $('.headerDropdown').on('change', function() {
                const selected = $(this).val();
                if (selected === "All") {
                    table.columns([6, 7]).search('').draw();
                } else {
                    // Filter rows where any of the 3 columns matches the selected value
                    table.rows().every(function() {
                        const row = this.data();
                        const match = [6, 7].some(i => row[i].trim() === selected);
                        $(this.node()).toggle(match);
                    });
                }
            });

            // Search input
            $('#customSearch').on('keyup', function() {
                table.search(this.value).draw();
            });

            // Export triggers
            $('#excelBtn').on('click', function() {
                table.button(0).trigger();
            });

            $('#printBtn').on('click', function() {
                table.button(1).trigger();
            });
        });
    </script>
@endsection
