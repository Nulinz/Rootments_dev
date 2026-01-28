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
                <h4 class="m-0">Walk-In Report</h4>
            </div>

              <form action="{{ route('store.walkinlist') }}" method="POST">
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

                    <!-- Store Dropdown -->
                    <div class="col-sm-12 col-md-4 col-xl-4 inputs mb-3">
                        <label for="staffname">Store<span>*</span></label>
                        @php
                            $user = auth()->user();
                        @endphp

                        <select class="form-select" name="store_list" id="storeDropdown" {{ $user->role_id == 12 ? 'disabled' : '' }}>
                            <option value="" disabled {{ $user->role_id != 12 ? 'selected' : '' }}>Select Store</option>
                            @foreach ($store as $st)
                                <option value="{{ $st->stores_id }}" @if ($user->role_id == 12 && $user->store_id == $st->stores_id) selected @endif>
                                    {{ $st->stores_name }}
                                </option>
                            @endforeach
                        </select>

                        {{-- Hidden field to still send store_id in POST request when disabled --}}
                        @if ($user->role_id == 12)
                            <input type="hidden" name="store_list" value="{{ $user->store_id }}">
                        @endif

                    </div>

                    <!-- Employee Checkbox Dropdown -->
                    <div class="col-sm-12 col-md-4 col-xl-4 inputs mb-3">
                        <label for="employee">Employees (Optional)</label>
                        <div class="dropdown-center tble-dpd">
                            <button class="w-100 form-select checkdrp text-start" type="button" data-bs-toggle="dropdown" id="employeeDropdownBtn" aria-expanded="false">
                                Select Employees
                            </button>
                            <ul class="dropdown-menu w-100 px-1" id="employeeDropdownMenu">
                                <li class="select-all-item border-bottom px-2 py-1">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="selectAllEmployees">
                                        <label class="form-check-label" for="selectAllEmployees">
                                            Select All Employees
                                        </label>
                                    </div>
                                </li>
                                <ul class="list-unstyled ms-2" id="employeeList">
                                    <!-- Employee checkboxes will be appended via JS -->
                                </ul>
                            </ul>
                        </div>
                    </div>

                    <div class="col-sm-12 col-md-4 col-xl-4 inputs mb-3">
                        <label for="status">Status <span>*</span></label>
                        <div class="col-sm-12 col-md-12 col-xl-12">
                            <div class="dropdown-center tble-dpd">
                                <button class="w-100 form-select checkdrp text-start" type="button" data-bs-toggle="dropdown" id="subcategory" aria-expanded="false">
                                    Select Options
                                </button>
                                <ul class="dropdown-menu w-100 px-1" id="subCatDropdownMenu">
                                    <li class="select-all-item border-bottom px-2 py-1">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="selectAllStatus">
                                            <label class="form-check-label" for="selectAllStatus">
                                                Select All Status
                                            </label>
                                        </div>
                                    </li>
                                    <ul class="list-unstyled ms-2" id="sub_cat">
                                        @php
                                            $st = [
                                                'Booked',
                                                'Rentout',
                                                'Return',
                                                'Trial',
                                                'Loss',
                                                'Enquiry',
                                                'Booking & Rentout',
                                                'Reissue',
                                                'New Booking',
                                                'Revisit Booking',
                                                'Revisit Loss',
                                                'New Walkin',
                                            ];
                                        @endphp
                                        @foreach ($st as $s)
                                            <li class="d-flex justify-content-start align-items-center gap-1">
                                                <input type="checkbox" class="status-checkbox me-2" name="walk_status[]" value="{{ $s }}">
                                                <label for="walkin" class="mb-0">{{ $s }}</label>
                                            </li>
                                        @endforeach
                                    </ul>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row mb-3">
                    <p class="mb-0 text-muted" style="font-size:13.5px;"><span class="text-danger">Note:</span> To view all employee data, do not select any employee from the dropdown.</p>
                </div>
            </div>
            <div class="col-sm-12 col-md-12 col-xl-12 w-50 d-flex justify-content-center align-items-center mx-auto mt-3">
                <button type="submit" class="formbtn">Save</button>
            </div>

        </form>
           
            @if (request()->isMethod('post'))
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
                                    <th>Customer Name</th>
                                    <th>Contact</th>
                                    <th>Function Date</th>
                                    <th>Staff</th>
                                    <th>Status</th>
                                    <th>Category</th>
                                    <th>Sub Category</th>
                                    <th>Repeat count</th>
                                    <th>Remarks</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($list as $wl)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ date('d-m-Y', strtotime($wl->created_at)) }}</td>
                                        <td>{{ $wl->name }}</td>
                                        <td>{{ $wl->contact }}</td>
                                        <td>{{ date('d-m-Y', strtotime($wl->f_date)) }}</td>
                                        <td>{{ $wl->createdBy->name ?? 'nill' }}</td>
                                        <td>{{ $wl->walk_status }}</td>
                                        <td>{{ $wl->cat ?? '-' }}</td>
                                        <td>{{ $wl->sub ?? '-' }}</td>
                                        <td>{{ $wl->repeat_count ?? '-' }}</td>
                                        <td>{{ $wl->remark }}</td>
                                         
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @endif
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
                            title: 'Walk-In Report',
                            exportOptions: {
                                columns: ':visible'
                            }
                        },
                        {
                            extend: 'print',
                            title: 'Walk-In Report',
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

     
         <script>
        $(document).ready(function() {

            // ✅ Common function to fetch employees
            function loadEmployeesByStore(storeId) {
                if (storeId) {
                    $.ajax({
                        url: "{{ route('get.employees.by.store') }}",
                        type: "GET",
                        data: {
                            store_id: storeId
                        },
                        success: function(data) {
                            $('#employeeList').empty();

                            if (data.length > 0) {
                                $.each(data, function(key, value) {
                                    $('#employeeList').append(`
                                    <li class="d-flex justify-content-start align-items-center gap-1">
                                        <input type="checkbox" class="me-2 employee-checkbox" name="employees[]" value="${value.id}">
                                        <label class="mb-0">${value.name}</label>
                                    </li>
                                `);
                                });
                                $('#selectAllEmployees').prop('checked', false);
                            } else {
                                $('#employeeList').append('<li>No employees found</li>');
                            }
                        }
                    });
                } else {
                    $('#employeeList').empty();
                }
            }

            // ✅ Trigger employee load when store changes
            $(document).ready(function() {
                const storeId = $('#storeDropdown').val(); // get selected store on page load
                loadEmployeesByStore(storeId); // load employees immediately
            });

            // also load when user changes store
            $('#storeDropdown').on('change', function() {
                const storeId = $(this).val();
                loadEmployeesByStore(storeId);
            });

            // ✅ Auto-load employees on page load if store is already selected
            const initialStoreId = $('#storeDropdown').val();
            if (initialStoreId) {
                loadEmployeesByStore(initialStoreId);
            }

            // ✅ Select All Employees checkbox
            $(document).on('change', '#selectAllEmployees', function() {
                const isChecked = $(this).is(':checked');
                $('#employeeList input[type="checkbox"]').prop('checked', isChecked);
            });

            // ✅ Sync Select All when individual boxes change
            $(document).on('change', '#employeeList input[type="checkbox"]', function() {
                const total = $('#employeeList input[type="checkbox"]').length;
                const checked = $('#employeeList input[type="checkbox"]:checked').length;
                $('#selectAllEmployees').prop('checked', total > 0 && checked === total);
            });

            // ✅ Select All Status functionality
            $(document).on('change', '#selectAllStatus', function() {
                const isChecked = $(this).is(':checked');
                $('.status-checkbox').prop('checked', isChecked);
            });

            $(document).on('change', '.status-checkbox', function() {
                const total = $('.status-checkbox').length;
                const checked = $('.status-checkbox:checked').length;
                $('#selectAllStatus').prop('checked', total > 0 && checked === total);
            });
        });
    </script>
    @endsection
