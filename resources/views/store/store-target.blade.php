@extends('layouts.app')

@section('content')
    <link href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css" rel="stylesheet">

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
            <h4 class="m-0">Store Target List</h4>
        </div>

        @if (!in_array(auth()->user()->role_id, [1, 2, 10, 66]))
            <form action="" method="POST" id="v_form">
                @csrf
                <div class="container-fluid maindiv my-3">
                    <div class="row">
                        <div class="col-sm-12 col-md-4 col-xl-4 inputs mb-3">
                            <label for="month">Month <span>*</span></label>
                            <input type="month" class="form-control" name="month" id="month" required>
                        </div>
                        <div class="col-sm-12 col-md-4 col-xl-4 inputs mb-3">
                            <label for="store">Store<span>*</span></label>
                            <select class="form-select" name="store_id" id="store" required>
                                <option value="" selected disabled>Select Options</option>
                                @foreach ($stores as $st => $id)
                                    <option value="{{ $id }}">{{ $st }}</option>
                                @endforeach

                            </select>
                        </div>
                        <div class="col-sm-12 col-md-4 col-xl-4 inputs mb-3">
                            <label for="store">Target Quantity</label>
                            <input type="text" class="form-control" name="target_qty" id="target_qty">
                        </div>
                        <div class="col-sm-12 col-md-4 col-xl-4 inputs mb-3">
                            <label for="store">Target Value</label>
                            <input type="text" class="form-control" name="target_val" id="target_val">
                        </div>
                    </div>
                </div>

                <div class="col-sm-12 col-md-12 col-xl-12 w-50 d-flex justify-content-center align-items-center mx-auto mt-3">
                    <button type="submit" class="formbtn">Save</button>
                </div>
            </form>
        @endif

        {{-- @if (request()->isMethod('post')) --}}

        <div class="container-fluid listtable mt-4">
            <div class="filter-container row mb-3">
                <div class="custom-search-container col-sm-12 col-md-8">
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
                            <th>Store</th>
                            <th>Month</th>
                            <th>Target Qunatity</th>
                            <th>Target Value</th>
                            <th>Created_by</th>

                        </tr>
                    </thead>
                    <tbody>
                        @php
                            use Carbon\Carbon;
                        @endphp
                        @foreach ($tr_list as $tl)
                            @php
                                $monthId = $tl->month; // '08', '01', etc.
                                $fullDate = Carbon::createFromFormat('Y-m-d', now()->year . '-' . $monthId . '-01');

                                $monthName = $fullDate->format('F'); // "August"
                            @endphp
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ date('d-m-Y', strtotime($tl->created_at)) }}</td>
                                <td>{{ $tl->store_name }}</td>
                                <td>{{ $monthName }}</td>
                                <td>{{ $tl->target_qty }}</td>
                                <td>{{ $tl->target }}</td>
                                <td>{{ $tl->cby_name }}</td>

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
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.1/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.print.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>

    <script>
        document.getElementById("v_form").addEventListener("submit", function(e) {
            let qty = document.getElementById("target_qty").value.trim();
            let val = document.getElementById("target_val").value.trim();
            let submitBtn = this.querySelector("button[type=submit]");

            if (qty === "" && val === "") {
                e.preventDefault(); // stop form submit
                alert("Please enter either Target Quantity or Target Value.");
                return false;
            }

            submitBtn.disabled = true;
            submitBtn.innerText = "Saving..."; // optional feedback
        });
    </script>

    <script>
        $(document).ready(function() {
            const table = $('#example').DataTable({
                dom: 'Bfrtip',
                buttons: [{
                        extend: 'excelHtml5',
                        title: 'Store Target Report',
                        exportOptions: {
                            columns: ':visible'
                        }
                    },
                    {
                        extend: 'print',
                        title: 'Store Target Report',
                        exportOptions: {
                            columns: ':visible'
                        }
                    }
                ]
            });

            // Custom search input
            $('#customSearch').on('keyup', function() {
                table.search(this.value).draw();
            });

            // OPTIONAL: If you want dropdown filter working, but properly with DataTables
            // Example: filter by store name (column index 2), and target type (index 4)

            // 1. Build dropdown dynamically for target type (column 4)
            const targetTypes = new Set();
            table.column(4).data().each(function(value) {
                targetTypes.add(value.trim());
            });

            targetTypes.forEach(function(val) {
                $('#targetTypeDropdown').append(`<option value="${val}">${val}</option>`);
            });

            // 2. On change filter using column().search()
            $('#targetTypeDropdown').on('change', function() {
                const val = $(this).val();
                table.column(4).search(val !== '' ? '^' + val + '$' : '', true, false).draw();
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
