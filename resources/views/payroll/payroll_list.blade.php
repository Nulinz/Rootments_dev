@extends ('layouts.app')

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
            <h4 class="m-0">View Salary List</h4>
        </div>

        @if (request()->isMethod('get'))
            <form action="{{ route('salary.list') }}" method="post" id="">
                @csrf
                <div class="container-fluid maindiv my-3 bg-white">
                    <div class="row">
                        <div class="col-sm-12 col-md-4 col-xl-4 inputs mb-3">
                            <label for="dept">Departments</label>
                            <select class="form-select" name="dept" id="dept" autofocus required>
                                @foreach ($dept as $item)
                                    <option value="{{ $item->role_dept }}">{{ $item->role_dept }}</option>
                                @endforeach
                                <option>All</option>
                            </select>
                        </div>
                        <div class="col-sm-12 col-md-4 col-xl-4 inputs mb-3" id="store_div" style="display:none">
                            <label for="store">Store Name</label>
                            <select class="form-select" name="store" id="store" autofocus>
                                <option value="" selected disabled>Select Stores</option>
                                @foreach ($store as $st)
                                    <option value="{{ $st->id }}">{{ $st->store_name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-sm-12 col-md-4 col-xl-4 inputs mb-3">
                            <label for="month">Month</label>
                            <input type="month" class="form-control" name="month" id="month">
                            {{-- <select class="form-select" name="month" id="month">
                            <option value="" selected disabled>Select Options</option>

                        </select> --}}
                        </div>
                    </div>
                </div>

                <div class="col-sm-12 col-md-12 col-xl-12 d-flex justify-content-center align-items-center mt-3">
                    <button type="submit" name="sal_form" class="formbtn">Save</button>
                </div>
            </form>
        @endif
        @if (request()->isMethod('post'))
            <div class="container-fluid listtable mt-4">
                <div class="filter-container row mb-3">
                    <div class="custom-search-container col-sm-12 col-md-8">
                        <select class="headerDropdown form-select filter-option">
                            <option value="All" selected>All</option>
                        </select>
                        <input type="text" id="customSearch" class="form-control filterInput" placeholder=" Search">
                    </div>

                    <div class="select1 col-sm-12 col-md-4 mx-auto">
                        <div class="d-flex gap-3">
                            <a id="printBtn"><img src="{{ asset('assets/images/printer.png') }}" id="print"
                                    alt="" height="28px" data-bs-toggle="tooltip" data-bs-title="Print"></a>
                            <a id="excelBtn"><img src="{{ asset('assets/images/excel.png') }}" id="excel"
                                    alt="" height="30px" data-bs-toggle="tooltip" data-bs-title="Excel"></a>
                        </div>
                    </div>
                </div>
                <div class="table-wrapper">
                    <form action="" method="POST" id="my_form">
                        <table id="example" class="table-hover table-striped table">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th style="width: 100px">Employee</th>
                                    <th style="width: 100px">Salary</th>
                                    <th style="width: 100px">Department</th>
                                    <th>TWD</th>
                                    <th>TPD</th>
                                     <th>Paid Leave</th>
                                    <th>Unpaid Leave</th>
                                    <th>Incentives</th>
                                    <th>OT</th>
                                    <th>Bonus</th>
                                    <th>Deduct</th>
                                    <th>Advance</th>
                                    <th>Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($sal_list as $sal)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $sal->emp_code }} <br> {{ $sal->name }}</td>
                                        <td>{{ $sal->salary }}</td>
                                        <td>{{ $sal->dept }}</td>
                                        <td>{{ $sal->total_work }}</td>
                                        <td>{{ $sal->present }}</td>
                                        <td>{{ $sal->paid_leave ?? 0 }}</td>
                                        <td>{{ $sal->unpaid_leave ?? 0 }}</td>
                                        <td>{{ $sal->incentive }}</td>
                                        <td>{{ $sal->ot }}</td>
                                        <td>{{ $sal->bonus }}</td>
                                        <td>{{ $sal->deduct }}</td>
                                        <td>{{ $sal->advance }}</td>
                                        <td>{{ $sal->total }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        {{-- <div class="col-sm-12 col-md-12 col-xl-12 mt-3 d-flex justify-content-center align-items-center">
                        <button type="submit" class="formbtn">Save</button>
                    </div> --}}
                    </form>
                </div>
            </div>
        @endif
    </div>

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
                        className: 'btn-export-excel',
                        title: 'Employee Salary',
                        exportOptions: {
                            columns: ':visible',
                            format: {
                                body: function(data, row, column, node) {
                                    // 🔍 If there's input, get its value; else return plain text
                                    const input = $('input', node);
                                    return input.length ? input.val() : $(node).text().trim();
                                }
                            }
                        }
                    },
                    {
                        extend: 'print',
                        className: 'btn-export-print',
                        title: 'Employee Salary',
                        exportOptions: {
                            columns: ':visible',
                            format: {
                                body: function(data, row, column, node) {
                                    const input = $('input', node);
                                    return input.length ? input.val() : $(node).text().trim();
                                }
                            }
                        }
                    }
                ]
            });

            // Trigger export buttons manually
            $('#excelBtn').on('click', function(e) {
                e.preventDefault();
                table.button('.btn-export-excel').trigger();
            });

            $('#printBtn').on('click', function(e) {
                e.preventDefault();
                table.button('.btn-export-print').trigger();
            });

            // Search input
            $('#customSearch').on('keyup', function() {
                table.search(this.value).draw();
            });

            // ✅ Corrected filter dropdown selector

            // Fill the dropdown with employee names from column 1
            const staffSet = new Set();
            $('#example tbody tr').each(function() {
                const staff = $(this).find('td:eq(1)').text().trim();
                if (staff) staffSet.add(staff);
            });
            staffSet.forEach(function(staff) {
                $('.headerDropdown').append(`<option value="${staff}">${staff}</option>`);
            });

            // Apply filter on change
            $('.headerDropdown').on('change', function() {
                const value = this.value === "All" ? '' : '^' + $.fn.dataTable.util.escapeRegex(this
                    .value) + '$';
                table.column(1).search(value, true, false).draw(); // Filter by column 1 (Employee)
            });

        });
    </script>
    <script>
        $('#dept').on('change', function() {
            var dept = $(this).find('option:selected').val();
            if (dept === 'Store') {
                $('#store_div').show();
            } else {
                $('#store_div').hide();
            }
        });
    </script>

    <script>
        $('#store1').on('change', function() {
            // Trigger an AJAX request when the page is ready
            var store = $(this).val();
            $.ajax({
                url: '{{ route('payroll.store_list') }}', // Laravel route for the POST request
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}', // CSRF token for security
                    store: store, // Send the selected store ID
                },

                success: function(response) {
                    console.log(response);


                    $('#month').empty(); // Clears all existing options in the select dropdown

                    $.each(response, function(index, value) {
                        var option = $('<option></option>').attr('value', index).text(value);
                        $('#month').append(option);
                    });


                },
                error: function(xhr, status, error) {

                    alert('An error occurred: ' + error);
                }
            });
        });
    </script>


@endsection
