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

            table {
                border-collapse: collapse;
                width: 100%;
            }

            table th,
            table td {
                padding: 8px 20px !important;
                /* vertical: 8px, horizontal: 12px */
                text-align: center;
                vertical-align: middle;
                border: 1px solid #888;
                /* Light border between cells */
                font-size: 14px;
            }

            table thead th {
                background-color: #f4f4f4;
                /* Light gray background for headers */
                font-weight: bold;
            }

            .table-wrapper {
                overflow-x: auto;
            }
        </style>
        <div class="sidebodydiv px-5 py-3">
            <div class="sidebodyhead">
                <h4 class="m-0">DSR-Sale Report</h4>
            </div>

            {{-- @if (request()->isMethod('GET')) --}}
            <form action="{{ route('dsr.sale.report') }}" method="POST">
                @csrf
                <div class="container-fluid maindiv my-3">
                    <div class="row">
                        {{-- <div class="col-sm-12 col-md-4 col-xl-4 inputs mb-3">
                            <label for="month">Month</label>
                            <input type="month" class="form-control" name="month" id="month" value="{{ date('Y-m-d') }}">
                        </div> --}}

                        <div class="col-sm-12 col-md-4 col-xl-4 inputs mb-3">
                            <label for="date">Date</label>
                            <input type="date" class="form-control" name="date" id="date">
                        </div>

                        <div class="col-sm-12 col-md-4 col-xl-4 inputs mb-3">
                            <label for="storeDropdownBtn">Store <span>*</span></label>
                            <div class="col-sm-12 col-md-12 col-xl-12">
                                <div class="dropdown w-100" data-bs-auto-close="outside">
                                    <!-- Trigger styled like a <select> -->
                                    <button class="form-select text-start" style="font-size: 14px" type="button" id="storeDropdownBtn" data-bs-toggle="dropdown" aria-expanded="false">
                                        Select Options
                                    </button>

                                    <!-- Dropdown menu with checkboxes -->
                                    <ul class="dropdown-menu w-100 px-2" aria-labelledby="storeDropdownBtn" id="storeDropdown">
                                        @foreach ($store as $st)
                                            <li>
                                                <label class="d-flex align-items-center">
                                                    <input type="checkbox" class="store-checkbox me-2" name="store_list[]" value="{{ $st->stores_id }}"
                                                        {{ auth()->user()->role_id == 12 && auth()->user()->store_id == $st->stores_id ? 'checked' : '' }}>
                                                    {{ $st->stores_name }}
                                                </label>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>

                                <!-- Hidden required field for validation -->
                                <input type="text" id="storeRequired" required hidden>
                            </div>
                        </div>

                        @if (!in_array(auth()->user()->role_id, [1, 2, 6, 11, 66]))
                            <div class="col-sm-12 col-md-4 col-xl-4 inputs mb-3">
                                <label for="employeeDropdownBtn">Employee (Optional)</label>
                                <div class="col-sm-12 col-md-12 col-xl-12">
                                    <div class="dropdown w-100" data-bs-auto-close="outside">
                                        <!-- Trigger styled like a <select> -->
                                        <button class="form-select text-start" style="font-size: 14px" type="button" id="employeeDropdownBtn" data-bs-toggle="dropdown"
                                            aria-expanded="false">
                                            Select Employee
                                        </button>

                                        <!-- Dropdown with checkboxes -->
                                        <ul class="dropdown-menu w-100 px-2" aria-labelledby="employeeDropdownBtn" id="employeeDropdown">
                                            <li><label class="d-flex align-items-center fw-bold">
                                                    <input type="checkbox" id="selectAllEmployees" class="me-2"> Select All
                                                </label></li>
                                            <!-- Employees will be loaded here -->
                                        </ul>
                                    </div>

                                    <!-- Hidden field for validation -->
                                    <input type="text" id="employeeRequired" hidden>
                                </div>
                            </div>
                        @endif

                        <div class="row mb-3">
                            <p class="text-muted mb-0" style="font-size:13.5px;"><span class="text-danger">Note:</span> To view all employee data, do not select any employee from the
                                dropdown.</p>
                        </div>

                    </div>
                </div>
                <div class="col-sm-12 col-md-12 col-xl-12 w-50 d-flex justify-content-center align-items-center mx-auto mt-3">
                    <button type="submit" class="formbtn">Save</button>
                </div>
            </form>
            {{-- @endif --}}
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
                        <table id="example" class="table-hover table-striped mb-0 table">
                            <thead>
                                <tr>
                                    <th rowspan="3" style="vertical-align: middle; text-align: start; border-right: 1px solid #888;">
                                        {{ in_array(auth()->user()->role_id, [1, 2, 6, 11, 66]) ? 'Store Name' : 'Employee Name' }}
                                    </th>
                                    <th colspan="6" style="border-right: 1px solid #888;">Shoe</th>
                                    <th colspan="6" style="border-right: 1px solid #888;">Shirt</th>
                                    <th rowspan="3" style="vertical-align: middle; text-align: start;">Created On</th>
                                </tr>
                                <tr>
                                    <th colspan="2" style="border-right: 1px solid #888;">Bill</th>
                                    <th colspan="2" style="border-right: 1px solid #888;">Qty</th>
                                    <th style="border-right: 1px solid #888;">TGT</th>
                                    <th style="border-right: 1px solid #888;">ACH %</th>
                                    <th colspan="2" style="border-right: 1px solid #888;">Bill</th>
                                    <th colspan="2" style="border-right: 1px solid #888;">Qty</th>
                                    <th style="border-right: 1px solid #888;">TGT</th>
                                    <th style="border-right: 1px solid #888;">ACH %</th>
                                </tr>
                                <tr>
                                    <th style="border-right: 1px solid #888;">Ftd</th>
                                    <th style="border-right: 1px solid #888;">Mtd</th>
                                    <th style="border-right: 1px solid #888;">Ftd</th>
                                    <th style="border-right: 1px solid #888;">Mtd</th>
                                    <th style="border-right: 1px solid #888;">-</th>
                                    <th style="border-right: 1px solid #888;">-</th>
                                    <th style="border-right: 1px solid #888;">Ftd</th>
                                    <th style="border-right: 1px solid #888;">Mtd</th>
                                    <th style="border-right: 1px solid #888;">Ftd</th>
                                    <th style="border-right: 1px solid #888;">Mtd</th>
                                    <th style="border-right: 1px solid #888;">-</th>
                                    <th style="border-right: 1px solid #888;">-</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $total_shoe_b_ftd = 0;
                                    $total_shoe_mtd = 0;
                                    $total_shoe_q_ftd = 0;
                                    $total_shoe_q_mtd = 0;
                                    $total_shoe_tgt = 0;
                                    $total_shoe_ach = 0;

                                    $total_shirt_b_ftd = 0;
                                    $total_shirt_mtd = 0;
                                    $total_shirt_q_ftd = 0;
                                    $total_shirt_q_mtd = 0;
                                    $total_shirt_tgt = 0;
                                    $total_shirt_ach = 0;

                                @endphp
                                @foreach ($list as $li)
                                    <tr>
                                        <td>{{ $li->username }}</td>
                                        <td>{{ $li->shoe_bill_ftd }}</td>
                                        <td>{{ $li->shoe_bill_mtd }}</td>
                                        <td>{{ $li->shoe_qty_ftd }}</td>
                                        <td>{{ $li->shoe_qty_mtd }}</td>
                                        <td>{{ $li->shoe_tgt }}</td>
                                        <td>{{ number_format($li->shoe_ach, 2) }}%</td>
                                        <td>{{ $li->shirt_bill_ftd }}</td>
                                        <td>{{ $li->shirt_bill_mtd }}</td>
                                        <td>{{ $li->shirt_qty_ftd }}</td>
                                        <td>{{ $li->shirt_qty_mtd }}</td>
                                        <td>{{ $li->shirt_tgt }}</td>
                                        <td>{{ number_format($li->shirt_ach, 2) }}%</td>
                                        <td>{{ date('d-m-Y', strtotime($li->created_at)) }}</td>
                                    </tr>
                                    @php
                                        $total_shoe_b_ftd += $li->shoe_bill_ftd;
                                        $total_shoe_mtd += $li->shoe_bill_mtd;
                                        $total_shoe_q_ftd += $li->shoe_qty_ftd;
                                        $total_shoe_q_mtd += $li->shoe_qty_mtd;
                                        $total_shoe_tgt += $li->shoe_tgt;
                                        // $total_shoe_ach += $li->shoe_ach;
                                        $total_shoe_ach = $total_shoe_tgt > 0 ? ($total_shoe_q_mtd / $total_shoe_tgt) * 100 : 0;

                                        $total_shirt_b_ftd += $li->shirt_bill_ftd;
                                        $total_shirt_mtd += $li->shirt_bill_mtd;
                                        $total_shirt_q_ftd += $li->shirt_qty_ftd;
                                        $total_shirt_q_mtd += $li->shirt_qty_mtd;
                                        $total_shirt_tgt += $li->shirt_tgt;
                                        // $total_shirt_ach += $li->shirt_ach;
                                        $total_shirt_ach = $total_shirt_tgt > 0 ? ($total_shirt_q_mtd / $total_shirt_tgt) * 100 : 0;
                                    @endphp
                                @endforeach
                            </tbody>

                            <tfoot>
                                <tr>
                                    <td>Total</td>
                                    {{-- Shoe --}}
                                    <td class="text-center">{{ $total_shoe_b_ftd }}</td>
                                    <td class="text-center">{{ $total_shoe_mtd }}</td>
                                    <td class="text-center">{{ $total_shoe_q_ftd }}</td>
                                    <td class="text-center">{{ $total_shoe_q_mtd }}</td>
                                    <td class="text-center">{{ $total_shoe_tgt }}</td>
                                    <td class="text-center">{{ number_format($total_shoe_ach, 2) }}</td>

                                    {{-- Shirt --}}
                                    <td class="text-center">{{ $total_shirt_b_ftd }}</td>
                                    <td class="text-center">{{ $total_shirt_mtd }}</td>
                                    <td class="text-center">{{ $total_shirt_q_ftd }}</td>
                                    <td class="text-center">{{ $total_shirt_q_mtd }}</td>
                                    <td class="text-center">{{ $total_shirt_tgt }}</td>
                                    <td class="text-center">{{ number_format($total_shirt_ach, 2) }}</td>
                                    <td></td>
                                </tr>
                            </tfoot>
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
            // $(document).ready(function() {
            //     const table = $('#example').DataTable({
            //         dom: 'Bfrtip',
            //         ordering: false, // ✅ Disable sorting
            //         buttons: [{
            //                 extend: 'excelHtml5',
            //                 title: 'DSR-Sale Report',
            //                 footer: true,
            //                 exportOptions: {
            //                     columns: ':visible'
            //                 }
            //             },
            //             {
            //                 extend: 'print',
            //                 title: 'DSR-Sale Report',
            //                 footer: true,
            //                 exportOptions: {
            //                     columns: ':visible'
            //                 }
            //             }
            //         ]
            //     });

            //     // ✅ Removed custom column filter code (filterSet, headerDropdown)

            //     // Search input
            //     $('#customSearch').on('keyup', function() {
            //         table.search(this.value).draw();
            //     });

            //     // Export triggers
            //     $('#excelBtn').on('click', function() {
            //         table.button(0).trigger();
            //     });

            //     $('#printBtn').on('click', function() {
            //         table.button(1).trigger();
            //     });
            // });

            $(document).ready(function() {
                const table = $('#example').DataTable({
                    dom: 'Bfrtip',
                    ordering: false,
                    buttons: [{
                            extend: 'excelHtml5',
                            title: 'DSR-Sale Report',
                            footer: true,
                            exportOptions: {
                                columns: ':visible',
                                format: {
                                    header: function(data, columnIdx) {
                                        // Map each column index to a descriptive header
                                        const headers = {
                                            0: '{{ in_array(auth()->user()->role_id, [1, 2, 11]) ? 'Store Name' : 'Employee Name' }}',
                                            1: 'Shoe - Bill - FTD',
                                            2: 'Shoe - Bill - MTD',
                                            3: 'Shoe - Qty - FTD',
                                            4: 'Shoe - Qty - MTD',
                                            5: 'Shoe - TGT',
                                            6: 'Shoe - ACH %',
                                            7: 'Shirt - Bill - FTD',
                                            8: 'Shirt - Bill - MTD',
                                            9: 'Shirt - Qty - FTD',
                                            10: 'Shirt - Qty - MTD',
                                            11: 'Shirt - TGT',
                                            12: 'Shirt - ACH %',
                                            13: 'Created On'
                                        };
                                        return headers[columnIdx] || data;
                                    }
                                }
                            }
                        },
                        {
                            extend: 'print',
                            title: 'DSR-Sale Report',
                            footer: true,
                            customize: function(win) {
                                // Keep the full 3-row header for printing
                                let theadHtml = $('#example thead').clone();
                                $(win.document.body).find('table thead').replaceWith(theadHtml);
                            }
                        }
                    ]
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
                const userRoleId = {{ auth()->user()->role_id }};
                const userStoreId = {{ auth()->user()->store_id ?? 'null' }};

                // ✅ Load employees dynamically based on store
                function loadEmployees(storeId) {
                    if (!storeId) return;

                    $.ajax({
                        url: "{{ route('get.employees.by.store') }}",
                        type: "GET",
                        data: {
                            store_id: storeId
                        },
                        success: function(data) {
                            const $dropdown = $('#employeeDropdown');
                            $dropdown.find('li:not(:first)').remove(); // keep only "Select All"

                            if (data.length > 0) {
                                data.forEach(emp => {
                                    $dropdown.append(`
                            <li>
                                <label class="d-flex align-items-center">
                                    <input type="checkbox" class="employee-checkbox me-2" name="employee_list[]" value="${emp.id}">
                                    ${emp.name}
                                </label>
                            </li>
                        `);
                                });
                            } else {
                                $dropdown.append('<li><span class="text-muted">No employees found</span></li>');
                            }

                            updateEmployeeButtonText();
                        }
                    });
                }

                // ✅ Handle store checkbox change
                $(document).on('change', '.store-checkbox', function() {
                    const checkedStores = $('.store-checkbox:checked').map(function() {
                        return $(this).val();
                    }).get();

                    if (checkedStores.length === 1) {
                        loadEmployees(checkedStores[0]);
                    } else {
                        $('#employeeDropdown').find('li:not(:first)').remove();
                        $('#employeeDropdownBtn').text('Select Employee');
                    }
                });

                // ✅ Auto-load for role_id = 12
                if (userRoleId === 12 && userStoreId) {
                    loadEmployees(userStoreId);

                    // Auto-check the user's store
                    const $storeCheckbox = $(`.store-checkbox[value="${userStoreId}"]`);
                    if ($storeCheckbox.length) {
                        $storeCheckbox.prop('checked', true);
                        $('#storeDropdownBtn').text($storeCheckbox.closest('label').text().trim());
                    }
                }

                // ✅ Handle "Select All" checkbox
                $(document).on('change', '#selectAllEmployees', function() {
                    const isChecked = $(this).is(':checked');
                    $('.employee-checkbox').prop('checked', isChecked);
                    updateEmployeeButtonText();
                });

                // ✅ When individual employee checkbox changes
                $(document).on('change', '.employee-checkbox', function() {
                    const allChecked = $('.employee-checkbox').length === $('.employee-checkbox:checked').length;
                    $('#selectAllEmployees').prop('checked', allChecked);
                    updateEmployeeButtonText();
                });

                // ✅ Update dropdown button text dynamically
                function updateEmployeeButtonText() {
                    const selected = $('.employee-checkbox:checked');
                    const total = $('.employee-checkbox').length;

                    if (selected.length === 0) {
                        $('#employeeDropdownBtn').text('Select Employee');
                    } else if (selected.length === total) {
                        $('#employeeDropdownBtn').text('All Employees Selected');
                    } else {
                        $('#employeeDropdownBtn').text(selected.length + ' Selected');
                    }

                    // Update hidden required field
                    $('#employeeRequired').val(selected.length ? 'valid' : '');
                }

                // ✅ Store selection validation
                const checkboxes = document.querySelectorAll(".store-checkbox");
                const hiddenRequired = document.getElementById("storeRequired");

                function validateStoreSelection() {
                    const anyChecked = Array.from(checkboxes).some(cb => cb.checked);
                    hiddenRequired.value = anyChecked ? "valid" : "";
                }

                checkboxes.forEach(cb => cb.addEventListener("change", validateStoreSelection));
                validateStoreSelection();
            });
        </script>

    @endsection
