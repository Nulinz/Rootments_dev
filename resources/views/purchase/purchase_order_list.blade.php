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

            .fa-regular,
            .fa-solid {
                font-size: 20px !important;
            }
        </style>
        <div class="sidebodydiv px-5 py-3">
            <div class="sidebodyhead">
                <h4 class="m-0">Purchase Order List</h4>

                @if (!in_array(auth()->user()->role_id, [1, 2, 7]))
                    <a href="{{ route('purchase.add_purchase_po') }}"><button class="listbtn">Add Purchase Order</button></a>
                @endif
            </div>

            <div class="container-fluid listtable mt-4">
                <div class="filter-container row mb-3">
                    <div class="custom-search-container col-sm-12 col-md-8">
                        <select class="headerDropdown form-select filter-option" id="columnFilter">
                            <option value="All">All Columns</option>
                        </select>

                        <input type="text" id="customSearch" class="form-control filterInput" placeholder="Search">
                    </div>
                </div>

                <div class="table-wrapper">
                    <table id="example" class="table-hover table-striped table">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Po No</th>
                                <th>Store</th>
                                <th>Vendor Name </th>
                                <th>Products</th>
                                <th>Qty</th>
                                <th>Total Amount</th>
                                <th>Delivery Date</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($po_list as $wl)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $wl->po_id }}</td>
                                    <td>{{ $wl->store_name ?? '-' }}</td>
                                    <td>{{ $wl->vendor }}</td>
                                    <td>{{ $wl->product_count }} Items</td>
                                    <td>{{ $wl->total_qty }}</td>
                                    <td>{{ $wl->overall_total }}</td>
                                    <td>{{ date('d-m-Y', strtotime($wl->delivery_date)) }}</td>
                                    <td>
                                        @if (in_array(auth()->user()->role_id, [1, 2]))
                                            @if ($wl->req_status == 'Pending')
                                                <button class="listtdbtn me-2" data-bs-toggle="modal" data-id="{{ $wl->id }}"
                                                    data-bs-target="#updateMaintenanceApproval">Update</button>
                                            @endif
                                            {!! $wl->esc_status == 'Approved' ? '<i class="fa-solid fa-circle-check text-success"></i>' : '<i class="fa-regular fa-circle-check"></i>' !!}
                                        @elseif (auth()->user()->role_id == 7)
                                             @if ($wl->esc_status == 'Approved')
                                                <i class="fa-solid fa-circle-check text-success"></i>
                                            @elseif ($wl->req_status == 'Pending')
                                                <button class="listtdbtn me-2" data-bs-toggle="modal" data-id="{{ $wl->id }}"
                                                    data-bs-target="#updateMaintenanceApproval">Update</button>

                                                <a class="upd_btn" data-bs-toggle="modal" data-id="{{ $wl->id }}" data-bs-target="#updatepofin">
                                                    <i class="fa-regular fa-circle-check"></i>
                                                </a>
                                            @elseif ($wl->req_status == 'Approved')
                                                <a class="upd_btn" data-bs-toggle="modal" data-id="{{ $wl->id }}" data-bs-target="#updatepofin">
                                                    <i class="fa-regular fa-circle-check"></i>
                                                </a>
                                            @else
                                                <i class="fa-regular fa-circle-check"></i>
                                            @endif
                                        @else
                                            {{ $wl->req_status }}
                                        @endif

                                    </td>
                                    <td><a href="{{ route('purchase.po_profile', $wl->id) }}"><i class="fa-solid fa-eye"></i></a></td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <!-- Update Approval Modal -->
        <div class="modal fade" id="updateMaintenanceApproval" tabindex="-1" aria-labelledby="updateMaintenanceApprovalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title fs-5" id="updateMaintenanceApprovalLabel">Update Purchase Order Approval</h4>
                        <button type="button" class="btn-close bg-white" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form action="{{ route('purchase.update_purchase_po') }}" method="POST" id="">
                            @csrf
                            <input type="hidden" id="rep_id" name="po_id">
                            <div class="col-sm-12 col-md-12 mb-3">
                                <label for="sts" class="col-form-label">Status</label>

                                <select class="form-select sts" name="po_status" id="sts" required>
                                    <option value="" selected disabled>Select Options</option>
                                    <option value="Approved">Approve</option>
                                    <option value="Rejected">Rejected</option>
                                </select>
                            </div>

                            <!-- Move the button inside the form -->
                            <div class="d-flex justify-content-center align-items-center mx-auto">
                                <button type="submit" class="modalbtn btn btn-primary">Update</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="updatepofin" tabindex="-1" aria-labelledby="updatepofin" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title fs-5" id="updatepofin">Update Purchase Order Approval</h4>
                        <button type="button" class="btn-close bg-white" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form action="{{ route('purchase.update_purchase_pofin') }}" method="POST" id="">
                            @csrf
                            <input type="hidden" id="po_id" name="apr_id">
                            <div class="col-sm-12 col-md-12 mb-3">
                                <label for="sts" class="col-form-label">Status</label>
                                <select class="form-select sts" name="apr_status" id="sts" required>
                                    <option value="" selected disabled>Select Options</option>
                                    <option value="Approved">Approve</option>
                                    <option value="Rejected">Rejected</option>
                                </select>
                            </div>
                            <!-- Move the button inside the form -->
                            <div class="d-flex justify-content-center align-items-center mx-auto">
                                <button type="submit" class="modalbtn btn btn-primary">Update</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <script>
            $('.listtdbtn').on('click', function() {
                var rep_id = $(this).data('id');

                $('#rep_id').val(rep_id);

            });
            $('.upd_btn').on('click', function() {
                var po_id = $(this).data('id');

                $('#po_id').val(po_id);

            });
        </script>
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

                // Add table headers as filter options (excluding index column)
                $('#example thead th').each(function(index) {
                    const title = $(this).text().trim();
                    if (index > 0) { // Skip the first "#" column
                        $('#columnFilter').append(`<option value="${index}">${title}</option>`);
                    }
                });

                // Filter rows on dropdown change
                $('#columnFilter').on('change', function() {
                    const selectedIndex = $(this).val();

                    if (selectedIndex === "All") {
                        table.rows().every(function() {
                            $(this.node()).show(); // Show all rows
                        });
                    } else {
                        table.rows().every(function() {
                            const rowData = this.data();
                            const value = rowData[selectedIndex];
                            const shouldShow = value && value.trim() !== '';
                            $(this.node()).toggle(shouldShow);
                        });
                    }
                });

                // Search input (optional)
                $('#customSearch').on('keyup', function() {
                    table.search(this.value).draw();
                });

                // Export buttons
                $('#excelBtn').on('click', function() {
                    table.button(0).trigger();
                });

                $('#printBtn').on('click', function() {
                    table.button(1).trigger();
                });
            });
        </script>
    @endsection
