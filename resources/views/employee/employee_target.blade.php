@extends('layouts.app')
@section('content')
    <style>
        .dt-buttons {
            display: none;
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
            <h4 class="m-0">DSR-Sale</h4>
        </div>

        <form action="{{ route('employee.emp_target') }}" method="POST">
            @csrf
            <div class="container-fluid maindiv my-3">
                <div class="row">
                    <div class="col-sm-12 col-md-4 col-xl-4 inputs mb-3">
                        <label for="month">Month <span>*</span></label>
                        <input type="month" class="form-control" name="month" id="month" required>
                    </div>
                    <div class="col-sm-12 col-md-4 col-xl-4 inputs mb-3">
                        <label for="store">Employee<span>*</span></label>
                        <select class="form-select" name="emp_id" id="store">
                            <option value="" selected disabled>Select Options</option>
                            @foreach ($emp_list as $st)
                                <option value="{{ $st->id }}">{{ $st->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-sm-12 col-md-4 col-xl-4 inputs mb-3">
                        <label for="target">Shoe Quantity<span>*</span></label>
                        <input type="text" class="form-control" name="shoe_value" required>
                    </div>
                    {{-- <div class="col-sm-12 col-md-4 col-xl-4 inputs mb-3">
                        <label for="target">Shoe Qty <span>*</span></label>
                        <input type="text" class="form-control" name="shoe_qty">
                    </div> --}}
                    <div class="col-sm-12 col-md-4 col-xl-4 inputs mb-3">
                        <label for="target">Shirt Quantity<span>*</span></label>
                        <input type="text" class="form-control" name="shirt_value" required>
                    </div>
                    {{-- <div class="col-sm-12 col-md-4 col-xl-4 inputs mb-3">
                        <label for="target">Shirt Qty <span>*</span></label>
                        <input type="text" class="form-control" name="shirt_qty">
                    </div> --}}
                </div>
            </div>

            <div class="col-sm-12 col-md-12 col-xl-12 w-50 d-flex justify-content-center align-items-center mx-auto mt-3">
                <button type="submit" class="formbtn">Save</button>
            </div>
        </form>

        {{-- @if (request()->isMethod('post')) --}}

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
                            <th>Date</th>
                            <th>Employee Name</th>
                            <th>Store</th>
                            <th>Month/Year</th>
                            <th>Shoe Target</th>
                            {{-- <th>Shoe Qty</th> --}}
                            <th>Shirt Target</th>
                            {{-- <th>Shirt Qty</th> --}}
                            {{-- <th>Created_by</th> --}}

                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($emp_target as $tl)
                            {{-- @php
                                $monthId = str_pad($tl->month, 2, '0', STR_PAD_LEFT);
                                $dateString = now()->year . '-' . $monthId . '-01';
                                $fullDate = \Carbon\Carbon::createFromFormat('Y-m-d', $dateString);
                                $monthName = $fullDate->format('F');
                            @endphp --}}

                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ date('d-m-Y', strtotime($tl->created_at)) }}</td>
                                <td>{{ $tl->user_name }}</td>
                                <td>{{ $tl->store_name }}</td>
                                <td>{{ date('m-Y', strtotime($tl->month)) }}</td>
                                <td>{{ $tl->shoe_value }}</td>
                                {{-- <td>{{ $tl->shoe_qty }}</td> --}}
                                <td>{{ $tl->shirt_value }}</td>
                                {{-- <td>{{ $tl->shirt_qty }}</td> --}}
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        {{-- @endif --}}

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
                [3, 5].forEach(index => {
                    const val = data[index].trim();
                    if (val) filterSet.add(val);
                });
            });

            // Append to the single dropdown
            // filterSet.forEach(function(value) {
            //     $('.headerDropdown').append(`<option value="${value}">${value}</option>`);
            // });

            // Populate dropdown with column names (except #)
            $('#example thead th').each(function(index) {
                const columnName = $(this).text().trim();
                if (columnName !== '#') {
                    $('.headerDropdown').append(`<option value="${index}">${columnName}</option>`);
                }
            });

            // Filter on dropdown change
            $('#columnFilterInput').on('keyup', function() {
                const selectedIndex = $('.headerDropdown').val();
                const searchValue = this.value;

                if (selectedIndex !== null) {
                    table.column(selectedIndex).search(searchValue).draw();
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
