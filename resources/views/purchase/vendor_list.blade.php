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
                <h4 class="m-0">Vendor List</h4>
                @if (!in_array(auth()->user()->role_id, [1, 2, 7]))
                    <a href="{{ route('purchase.add_vendor') }}"><button class="listbtn">Add Vendor</button></a>
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
                                <th>Vendor Code</th>
                                <th>Vendor Name</th>
                                <th>Contact Number</th>
                                <th>Mail Id</th>
                                <th>Created on</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($ven_list as $wl)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $wl->vendor_code }}</td>
                                    <td>{{ $wl->name }}</td>
                                    <td>{{ $wl->contact }}</td>
                                    <td>{{ $wl->email }}</td>
                                    <td>{{ date('d-m-Y', strtotime($wl->created_at)) }}</td>
                                    <td>{{ $wl->status }}</td>
                                    <td><a href="{{ route('purchase.vendor_profile', $wl->id) }}"><i class="fa-solid fa-eye"></i></a></td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
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
