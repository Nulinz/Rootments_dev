@extends('layouts.app')

@section('content')
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.1/css/buttons.dataTables.min.css">

    <div class="sidebodydiv px-5 py-3">
        <div class="sidebodyhead">
            <h4 class="m-0">Ot/Late Report</h4>
        </div>

        <form method="post" id="form" action="{{ route('attendance.ot.report') }}">
            @csrf

            <div class="container-fluid maindiv my-3">
                <div class="row">
                    <div class="col-sm-12 col-md-4 col-xl-4 inputs mb-3">
                        <label for="dept">Departments <span>*</span></label>
                        <select class="form-select" name="dept" id="dept" autofocus required>
                            <option value="" selected disabled>Select Department</option>
                            @foreach ($dept as $item)
                                <option value="{{ $item->role_dept }}" {{ old('dept', $selected_dept) == $item->role_dept ? 'selected' : '' }}>
                                    {{ $item->role_dept }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-sm-12 col-md-4 col-xl-4 inputs mb-3" id="store_div" style="display:{{ old('dept', $selected_dept) == 'Store' ? 'block' : 'none' }}">
                        <label for="stores">Stores <span>*</span></label>
                        <select class="form-select" name="stores" id="stores" {{ old('dept', $selected_dept) == 'Store' ? 'required' : '' }}>
                            <option value="" selected disabled>Select Options</option>
                            @foreach ($stores as $store)
                                <option value="{{ $store->id }}" {{ old('stores', $selected_store) == $store->id ? 'selected' : '' }}>
                                    {{ $store->store_name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-sm-12 col-md-4 col-xl-4 inputs mb-3">
                        <label for="employee">Employee <span>*</span></label>
                        <select class="form-select" name="employee" id="employee" required>
                            <option value="" selected disabled>Select Options</option>
                            <!-- Employees will be populated via AJAX -->
                        </select>
                    </div>
                    <div class="col-sm-12 col-md-4 col-xl-4 inputs mb-3">
                        <label for="month">Month <span>*</span></label>
                        <input type="month" class="form-control" name="month" id="month" value="{{ old('month', $selected_month) }}" required>
                    </div>
                </div>
            </div>

            <div class="col-sm-12 col-md-12 col-xl-12 w-50 d-flex justify-content-center align-items-center mx-auto mt-3">
                <button type="submit" class="formbtn">Filter</button>
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
                {{-- <div class="select1 col-sm-12 col-md-4 mx-auto">
                    <div class="d-flex gap-3">
                        <a id="printBtn"><img src="{{ asset('assets/images/printer.png') }}" id="print" alt="" height="28px" data-bs-toggle="tooltip"
                                data-bs-title="Print"></a>
                        <a id="excelBtn"><img src="{{ asset('assets/images/excel.png') }}" id="excel" alt="" height="30px" data-bs-toggle="tooltip"
                                data-bs-title="Excel"></a>
                    </div>
                </div> --}}
            </div>

            <div class="table-wrapper">
                <table class="example table-hover table-striped table" id="example1">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Emp Code</th>
                            <th>Emp Name</th>
                            <th>Role</th>
                            <th>Store</th>
                            <th>Category</th>
                            <th>Date</th>
                            <th>Time</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($att_ot as $entry)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $entry->emp_code }}</td>
                                <td>{{ $entry->name }}</td>
                                <td>{{ $entry->role }}</td>
                                <td>{{ $entry->store_name }}</td>
                                <td>{{ $entry->cat }}</td>
                                <td>{{ date('d-m-Y', strtotime($entry->c_on)) }}</td>
                                <td>{{ $entry->time }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Add DataTables Buttons CSS & JS -->
    <script src="https://cdn.datatables.net/buttons/2.4.1/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.print.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>

    <script>
        // Initialize DataTable

        // Initialize DataTable with export buttons (hidden by default)
        let table = $('#example1').DataTable({
            dom: 'Bfrtip',
            buttons: [{
                    extend: 'excelHtml5',
                    title: 'OT_Late_Report'
                },
                {
                    extend: 'print',
                    title: 'OT/Late Report'
                }
            ],
            // Disable built-in buttons UI
            initComplete: function() {
                $('.dt-buttons').hide();
            }
        });

        // Custom search
        $('#customSearch').on('keyup', function() {
            table.search(this.value).draw();
        });

        // Trigger Excel export on click
        $('#excelBtn').on('click', function(e) {
            e.preventDefault();
            table.button('.buttons-excel').trigger();
        });

        // Trigger Print on click
        $('#printBtn').on('click', function(e) {
            e.preventDefault();
            table.button('.buttons-print').trigger();
        });

        // Show/hide store dropdown based on department
        $('#dept').on('change', function() {
            var dept = $(this).val();
            if (dept === 'Store') {
                $('#store_div').show();
                $('#stores').prop('required', true);
            } else {
                $('#store_div').hide();
                $('#stores').prop('required', false).val('');
            }
            // Trigger employee fetch
            fetchEmployees();
        });

        // Fetch employees based on department and store
        function fetchEmployees() {
            var dept = $('#dept').val();
            var store_id = $('#stores').val() || '';

            $.ajax({
                url: '{{ route('get_store_per') }}',
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    store_id: store_id,
                    dept: dept
                },
                // success: function(response) {
                //     $('#employee').empty().append('<option value="" selected disabled>Select Options</option>');
                //     $.each(response, function(index, value) {
                //         var selected = '{{ old('employee', $selected_employee) }}' == value.id ? 'selected' : '';
                //         $('#employee').append('<option value="' + value.id + '" ' + selected + '>' + value.name + '</option>');
                //     });
                // },
                success: function(response) {
                    $('#employee').empty().append('<option value="all" selected>All Employees</option>');
                    $.each(response, function(index, value) {
                        var selected = '{{ old('employee', $selected_employee) }}' == value.id ? 'selected' : '';
                        $('#employee').append('<option value="' + value.id + '" ' + selected + '>' + value.name + '</option>');
                    });
                },
                error: function(xhr, status, error) {
                    console.error('Error fetching employees:', error);
                }
            });
        }

        // Trigger employee fetch on store change or page load
        $('#stores').on('change', fetchEmployees);

        // Load employees on page load if department is pre-selected
        $(document).ready(function() {
            if ($('#dept').val()) {
                fetchEmployees();
            }
        });

        // Client-side form validation
        $('#form').on('submit', function(e) {
            var dept = $('#dept').val();
            var employee = $('#employee').val();
            var month = $('#month').val();
            var stores = $('#stores').val();

            if (!dept || !employee || !month) {
                e.preventDefault();
                alert('Please fill all required fields.');
                return false;
            }
            if (dept === 'Store' && !stores) {
                e.preventDefault();
                alert('Please select a store for the Store department.');
                return false;
            }
        });
    </script>
@endsection
