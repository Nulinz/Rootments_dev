@extends('layouts.app')

@section('content')
    <div class="sidebodydiv px-5 py-3">
        <div class="sidebodyhead">
            <h4 class="m-0">Individual Attendance List</h4>
        </div>

        <form method="post" id="form" action="{{ route('get_ind_attd') }}">
            @csrf

            <div class="container-fluid maindiv my-3">
                <div class="row">
                    <div class="col-sm-12 col-md-4 col-xl-4 inputs mb-3">
                        <label for="dept">Departments <span>*</span></label>
                        <select class="form-select" name="dept" id="dept" autofocus required>
                            <option value="" selected disabled>Select Department</option>
                            @foreach ($dept as $item)
                                <option value="{{ $item->role_dept }}" {{ old('dept', $selected_dept ?? '') == $item->role_dept ? 'selected' : '' }}>
                                    {{ $item->role_dept }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-sm-12 col-md-4 col-xl-4 inputs mb-3" id="store_div" style="display:{{ old('dept', $selected_dept ?? '') == 'Store' ? 'block' : 'none' }}">
                        <label for="stores">Stores <span>*</span></label>
                        <select class="form-select" name="stores" id="stores" {{ old('dept', $selected_dept ?? '') == 'Store' ? 'required' : '' }}>
                            <option value="" selected disabled>Select Options</option>
                            @foreach ($stores as $store)
                                <option value="{{ $store->id }}" {{ old('stores', $selected_store ?? '') == $store->id ? 'selected' : '' }}>
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
                        <input type="month" class="form-control" name="month" id="month" value="{{ old('month', $selected_month ?? '') }}" required>
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
                    <input type="text" id="customSearch1" class="form-control filterInput" placeholder="Search">
                </div>
                <div class="select1 col-sm-12 col-md-4 mx-auto">
                    <div class="d-flex gap-3">
                        <!-- Add export links if needed -->
                    </div>
                </div>
            </div>

            <div class="table-wrapper">
                <table id="example1" class="table-hover table-striped table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Date</th>
                            <th>Status</th>
                            <th>Leave Type</th>
                            <th>In Location</th>
                            <th>In Time</th>
                            <th>Out Location</th>
                            <th>Out Time</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($att_data ?? [] as $entry)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $entry['date'] }}</td>
                                <td>{{ $entry['type'] }}</td>
                                <td>{{ $entry['leave_type'] }}</td>
                                <td>{{ $entry['in_location'] }}</td>
                                <td>{{ $entry['in_time'] }}</td>
                                <td>{{ $entry['out_location'] }}</td>
                                <td>{{ $entry['out_time'] }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script>
        // Initialize DataTable
        let table = $('#example1').DataTable({
            "order": [[1, "desc"]] // Sort by Date column descending
        });

        // Search functionality
        $('#customSearch1').on('keyup', function() {
            table.search(this.value).draw();
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
                success: function(response) {
                    $('#employee').empty().append('<option value="" selected disabled>Select Options</option>');
                    var selectedEmployee = '{{ old('employee', $selected_employee ?? '') }}';
                    $.each(response, function(index, value) {
                        var selected = selectedEmployee == value.id ? 'selected' : '';
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

        // AJAX form submission
        $('#form').on('submit', function(e) {
            e.preventDefault();

            let formData = new FormData(this);

            $.ajax({
                url: '{{ route('get_ind_attd') }}',
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    table.clear();
                    $.each(response, function(index, value) {
                        table.row.add([
                            index + 1,
                            value.date,
                            value.type,
                            value.leave_type,
                            value.in_location,
                            value.in_time,
                            value.out_location,
                            value.out_time
                        ]);
                    });
                    table.draw();
                },
                error: function(xhr, status, error) {
                    alert('An error occurred: ' + error);
                }
            });
        });
    </script>
@endsection
