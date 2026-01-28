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

        .bt-fs {
            font-size: 11px;
        }
    </style>
    <div class="sidebodydiv px-5 py-3">
        <div class="sidebodyhead">
            <h4 class="m-0">Salary Generation List</h4>
        </div>

        @if (request()->isMethod('get'))
            <form action="{{ route('payroll.listPerson') }}" method="post" id="">
                @csrf
                <div class="container-fluid maindiv my-3 bg-white">
                    <div class="row">
                        <div class="col-sm-12 col-md-4 col-xl-4 inputs mb-3">
                            <label for="dept">Departments</label>
                            <select class="form-select" name="dept" id="dept" autofocus required>
                                @foreach ($dept as $item)
                                    <option value="{{ $item->role_dept }}">{{ $item->role_dept }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-sm-12 col-md-4 col-xl-4 inputs mb-3" id="store_div" style="display:none">
                            <label for="store">Store Name</label>
                            <select class="form-select store" name="store" id="store" autofocus>
                                <option value="" selected disabled>Select Stores</option>
                                @foreach ($stores as $store)
                                    <option value="{{ $store->id }}" {{ old('stores') == $store->id ? 'selected' : '' }}>
                                        {{ $store->store_name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-sm-12 col-md-4 col-xl-4 inputs mb-3">
                            <label for="month">Month</label>
                            <input type="month" class="form-control" name="month" id="month">
                        </div>
                        <div class="col-sm-12 col-md-4 col-xl-4 inputs mb-3">
                            <label for="twd">Total Working Days</label>
                            <input type="number" class="form-control" name="twd" id="twd" min="0" placeholder="Enter Total Working Days">
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
                        <input type="text" id="customSearch" class="form-control filterInput" placeholder="Search">
                    </div>

                    <div class="select1 col-sm-12 col-md-4 mx-auto">
                        <div class="d-flex gap-3">
                            <a id="printBtn"><img src="{{ asset('assets/images/printer.png') }}" id="print" alt="" height="28px" data-bs-toggle="tooltip" data-bs-title="Print"></a>
                            <a id="excelBtn"><img src="{{ asset('assets/images/excel.png') }}" id="excel" alt="" height="30px" data-bs-toggle="tooltip" data-bs-title="Excel"></a>
                        </div>
                    </div>
                </div>
                <div class="table-wrapper">
                    <form action="{{ route('payroll.insert') }}" method="POST" id="c_form">
                        @csrf
                        <input type="hidden" class="form-control" name="month" id="" value="{{ $post_mon }}">
                        <input type="hidden" class="form-control" name="store" id="" value="{{ $post_store }}">
                        <table id="example" class="table-hover table-striped table">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th style="width: 100px">Employee</th>
                                    <th style="width: 100px">Salary</th>
                                    <th>TWD</th>
                                    <th>TPD</th>
                                    <!--<th>Leaves</th>-->
                                    <th>Paid Leave</th>
                                    <th>Unpaid Leave</th>
                                    <th>Incentives</th>
                                    <th>OT</th>
                                    <th>Bonus</th>
                                    <th>Deduction</th>
                                    <th>Advance</th>
                                    <th>Status</th>
                                    <th>Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($u_list as $ul)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $ul->emp_code }} <br> {{ $ul->name }}</td>
                                        <td>
                                            <input hidden type="text" class="form-control" name="empId[]" value="{{ $ul->emp_id }}">
                                            <input type="number" class="form-control" id="base" name="salary[]" value="{{ $ul->base_salary }}" readonly>
                                        </td>
                                        <td><input type="number" class="form-control" id="twd" name="totalWork[]" value="{{ $twd }}"></td>
                                        <td><input type="number" class="form-control" id="tpd" name="present[]" value="{{ $ul->present_day ?? 0 }}"></td>
                                        <!--<td><input type="number" class="form-control" id="lop" name="lop[]" value="{{ $twd - $ul->present_day }}"></td>-->
                                        <!--<td><input type="number" class="form-control" id="paid_leave" name="paidLeave[]" value="{{ $ul->paid_leaves ?? 0 }}" readonly></td>-->
                                        <!--<td><input type="number" class="form-control" id="unpaid_leave" name="unpaidLeave[]" value="{{ $ul->unpaid_leaves ?? 0 }}" readonly></td>-->
                                        <td><input type="number" class="form-control" name="paidLeave[]" value="{{ $ul->paid_leaves ?: 0 }}" readonly></td>
                                        <td><input type="number" class="form-control" name="unpaidLeave[]" value="{{ $ul->unpaid_leaves ?: 0 }}" readonly></td>
                                        <td><input type="number" class="form-control" id="incentives" name="incentive[]" value="0"></td>
                                        <td><input type="number" class="form-control" id="over_time" name="ot[]" value="{{ $ul->total_ot ?? 0 }}"></td>
                                        <td><input type="number" class="form-control" id="bonus" name="bonus[]" value="0"></td>
                                        <td><input type="number" class="form-control" id="deduct" name="deduct[]" value="{{ $ul->total_late ?? 0 }}"></td>
                                        <td><input type="number" class="form-control" id="advance" name="advance[]" value="0"></td>
                                        <td>
                                            @if ($ul->hold_status == 'OnHold')
                                                <span class="badge bg-danger bt-fs mb-0">Hold</span>
                                                <input type="hidden" name="salary_status[]" value="OnHold">
                                            @else
                                                <span class="badge bg-success bt-fs mb-0">Active</span>
                                                <input type="hidden" name="salary_status[]" value="Active">
                                            @endif
                                        </td>
                                        <td><input type="number" class="form-control" id="total" name="total[]" value="0" readonly></td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <div class="col-sm-12 col-md-12 col-xl-12 d-flex justify-content-center align-items-center mt-3">
                            <button type="submit" id="sub" class="formbtn">Save</button>
                        </div>
                    </form>
                </div>
            </div>
        @endif
    </div>

    <script src="{{ asset('assets/js/form_script.js') }}"></script>

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
                paging: false, 
                pageLength: -1,
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
    function calculateRowTotal($row) {
    let twd = parseFloat($row.find('#twd').val()) || 0;
    let inc = parseFloat($row.find('#incentives').val()) || 0;
    let bonus = parseFloat($row.find('#bonus').val()) || 0;
    let adv = parseFloat($row.find('#advance').val()) || 0;
    let ded = parseFloat($row.find('#deduct').val()) || 0;
    let ot = parseFloat($row.find('#over_time').val()) || 0;
    let tpd = parseFloat($row.find('#tpd').val()) || 0;
    let base = parseFloat($row.find('#base').val()) || 0;
    let paidLeaves = parseFloat($row.find('#paid_leave').val()) || 0; // Get paid leaves

    // Calculate per day salary
    let per_day = (twd !== 0) ? parseFloat((base / twd).toFixed(3)) : 0;

    // Calculate salary components
    let presentDaySalary = per_day * tpd; // Salary for present days
    let paidLeaveSalary = per_day * paidLeaves; // Salary for paid leave days
    
    // Total positive amounts (salary + incentives + overtime + bonus + paid leave salary)
    let plus = presentDaySalary + paidLeaveSalary + inc + ot + bonus;
    
    // Total deductions (deductions + advance)
    let minus = ded + adv;

    // Calculate final total
    let total = plus - minus;

    // Round to nearest integer (0.5 and above rounds up)
    let roundedTotal = (total % 1 >= 0.5) ? Math.ceil(total) : Math.floor(total);

    // Update fields
    $row.find('#lop').val(twd - tpd);
    $row.find('#total').val(roundedTotal);
}

$(document).ready(function() {
    // Calculate for all rows on page load
    $('#example tbody tr').each(function() {
        calculateRowTotal($(this));
    });

    // Recalculate on change of any relevant inputs
    $(document).on('change', '#incentives, #bonus, #advance, #deduct, #over_time, #lop, #tpd, #twd, #paid_leave', 
        function() {
            let $currentRow = $(this).closest('tr');
            calculateRowTotal($currentRow);
        });
});
</script>

    <script>
        // $(document).on('change', '#incentives, #bonus, #advance, #deduct, #over_time, #lop, #tpd, #twd', function() {
        // Get the current row
        // let $currentRow = $(this).closest('tr');

        // Get the values of inputs within the current row
        // let twd = parseFloat($currentRow.find('#twd').val()) || 0;
        // let inc = parseFloat($currentRow.find('#incentives').val()) || 0;
        // let bonus = parseFloat($currentRow.find('#bonus').val()) || 0;
        // let adv = parseFloat($currentRow.find('#advance').val()) || 0;
        // let ded = parseFloat($currentRow.find('#deduct').val()) || 0;
        // let ot = parseFloat($currentRow.find('#over_time').val()) || 0;
        // let lop = parseFloat($currentRow.find('#lop').val()) || 0;
        // let tpd = parseFloat($currentRow.find('#tpd').val()) || 0;
        // let base = parseFloat($currentRow.find('#base').val()) || 0;

        // let per_day = parseInt(base / twd);

        // let per_day = parseFloat((base / twd).toFixed(3));

        // console.log(per_day);


        // let plus = parseFloat(per_day * tpd) + parseFloat(inc) + parseFloat(ot) + parseFloat(bonus);
        // let minus = parseFloat(ded) + parseFloat(adv);

        // Ensure that the calculation doesn't result in NaN
        // let total = parseFloat(plus - minus);

        // console.log(total);


        // Round total based on decimal part
        // let roundedTotal = (total % 1 >= 0.5) ? Math.ceil(total) : Math.floor(total);

        // console.log(roundedTotal);



        // console.log(plus);

        // $currentRow.find('#lop').val(twd - tpd)


        // Set the total in the current row's `.total` input
        // $currentRow.find('#total').val(roundedTotal)



        //mnnn let add = parseInt(base / 26);
        // let lop_amt = one * lop;
        // let per_amt = 50;
        // let per_amt1 = per * per_amt;
        // let total = (((base) - (lop_amt + per_amt1)))
        // let total1 = parseFloat(total) + parseInt(inc) + parseInt(add); mmmm

        //   $(".total").val(total1); mmm

        // $currentRow.find('.total').val(total1); mmmm


        // console.log("Inc Value: " + inc); mmmm
        // console.log("Per Value: " + per); mmmm
        // console.log("Lop Value: " + base); mmmm

        // You can perform additional actions here with the retrieved values
        // });
    </script>

    <script>
        $('#month1').on('change', function() {
            // Trigger an AJAX request when the page is ready
            var mon = $(this).val();
            $.ajax({
                url: '{{ route('payroll.drop') }}', // Laravel route for the POST request
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}', // CSRF token for security
                    sal_mon: mon, // Send the selected store ID
                },

                success: function(response) {
                    console.log(response);


                    $('#store').empty(); // Clears all existing options in the select dropdown

                    $.each(response, function(index, value) {
                        var option = $('<option></option>').attr('value', value.id).text(value
                            .store_name + ' - ' + value.store_code);
                        $('#store').append(option);
                    });
                    // $('#store').append('<option value="4-office">Office</option>');

                },
                error: function(xhr, status, error) {

                    alert('An error occurred: ' + error);
                }
            });
        });

        // $('.store').on('change', function() {
        //     // Trigger an AJAX request when the page is ready
        //     var store = $(this).val();
        //     var mon = $('#month').val();

        //     $.ajax({
        //         url: '{{ route('payroll.listPerson') }}', // Laravel route for the POST request
        //         type: 'POST',
        //         data: {
        //             _token: '{{ csrf_token() }}', // CSRF token for security
        //             mon: mon, // Send the selected store ID
        //             store: store, // Send the selected store ID
        //         },

        //         success: function(response) {
        //             // console.log(response);


        //             $.each(response, function(index, value) {
        //                 // Dynamically create an option element for each store
        //                 var option = $('<option></option>').attr('value', value.id + '-Store')
        //                     .text(value
        //                         .store_name + '-' + value.store_code);

        //                 // Append the option to the select element
        //                 $('#store').append(option);

        //             });
        //             $('#store').append('<option value="4-office">Office</option>');

        //         },
        //         error: function(xhr, status, error) {

        //             alert('An error occurred: ' + error);
        //         }
        //     });
        // });
    </script>

    {{-- <script>
$(document).ready(function () {
    let table = $('#example').DataTable({
        dom: 'frtip',
        buttons: [
            {
                extend: 'excelHtml5',
                title: 'Employee salary',
                className: 'btn-export-excel',
                text: '<a id="excelLink"><img src="{{ asset('assets/images/excel.png') }}" id="excel" alt="Excel" height="30px" data-bs-toggle="tooltip" data-bs-title="Excel"></a>', // Custom Excel icon image', // Font Awesome Excel icon
                exportOptions: {
                    columns: ':not(:last-child)' // Exclude last column (e.g., actions)
                }
            },
            {
                extend: 'print',
                title: 'Employee salary',
                className: 'btn-export-print',
                text: '<a id="pdfLink"><img src="{{ asset('assets/images/printer.png') }}" id="excel" alt="Excel" height="30px" data-bs-toggle="tooltip" data-bs-title="Excel"></a>', // Custom Excel icon image
                exportOptions: {
                    columns: ':not(:last-child)'
                }
            }
        ],
        // initComplete: function() {
        //     // Move the buttons inside the custom div
        //     var buttonContainer = $('.select1 .d-flex');
        //     buttonContainer.empty(); // Clear the div
        //     // Append the buttons to the div
        //     table.buttons().container().appendTo(buttonContainer);
        // }
    });


     // Trigger export on custom image icon click (Excel)
     $('#excel').on('click', function (e) {
        e.preventDefault(); // Prevent default link behavior
        table.button('.btn-export-excel').trigger(); // Trigger DataTable's export for Excel
    });

    // Trigger export on custom image icon click (Print)
    $('#print').on('click', function (e) {
        e.preventDefault(); // Prevent default link behavior
        table.button('.btn-export-print').trigger(); // Trigger DataTable's print
    });
});



</script> --}}

@endsection
