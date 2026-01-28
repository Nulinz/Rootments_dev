@extends('layouts.app')

@section('content')
    <div class="sidebodydiv px-5 py-3">
        <div class="sidebodyhead">
            <h4 class="m-0">Overtime / Late List</h4>
        </div>

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
                        <!--<a href="" id="pdfLink"><img src="{{ asset('assets/images/printer.png') }}" id="print" alt=""-->
                        <!--        height="28px" data-bs-toggle="tooltip" data-bs-title="Print"></a>-->
                        <!--<a href="" id="excelLink"><img src="{{ asset('assets/images/excel.png') }}" id="excel" alt=""-->
                        <!--        height="30px" data-bs-toggle="tooltip" data-bs-title="Excel"></a>-->
                    </div>
                </div>
            </div>

            <div class="table-wrapper">
 <table class="example table-hover table-striped table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Emp Code</th>
                            <th>Emp Name</th>
                            <th>Role</th>
                            <th>Store</th>
                            <th>Category</th>
                            <th>Date</th>
                            <th>Checkin / Checkout</th>
                            <th>Time</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($ot_lists as $entry)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $entry->emp_code }}</td>
                                <td>{{ $entry->name }}</td>
                                <td>{{ $entry->role }}</td>
                                <td>{{ $entry->store_name }}</td>
                                <td>{{ $entry->cat }}</td>
                                <td>{{ date('d-m-Y', strtotime($entry->c_on)) }}</td>
                                <td>{{ $entry->in_time }} - {{ $entry->out_time }}</td>
                                <td>{{ $entry->time }}</td>
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        <button class="approve-ot listtdicon" data-bs-toggle="modal" data-bs-target="#updateLeaveApproval" data-id="{{ $entry->id }}"
                                            data-time="{{ $entry->formatted_ot_time }}" data-status="{{ $entry->cat }}" data-monthtotal="{{ $entry->formatted_total_ot }}"
                                            data-weeklytotal="{{ $entry->formatted_weekly_ot }}" data-weeklyexceeded="{{ $entry->weekly_ot_exceeded }}"
                                            data-weeklyexcess="{{ $entry->formatted_weekly_excess }}" data-bs-title="Approved">
                                            <i class="fas fa-circle-check text-success"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                </div>
        </div>
    </div>

    <!-- Update Approval Modal -->
    <div class="modal fade" id="updateLeaveApproval" tabindex="-1" aria-labelledby="updateLeaveApprovalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title fs-5" id="updateLeaveApprovalLabel">Update OverTime/Late Approval</h4>
                    <button type="button" class="btn-close bg-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('ot.approve') }}" method="POST" id="">
                        @csrf
                        
                        <!-- Monthly OT Total (Always show) -->
                        <!-- <div class="col-sm-12 col-md-12 mb-3">
                            <label class="col-form-label">Monthly OT (Total)</label>
                            <input type="text" class="form-control" id="modal_ot_monthly_total" readonly>
                        </div> -->

                        <!-- Weekly OT Total (Show only for OT category) -->
                        <div class="col-sm-12 col-md-12 mb-3" id="weekly_ot_section" style="display: none;">
                            <label class="col-form-label">Weekly OT (Total)</label>
                            <input type="text" class="form-control" id="modal_ot_weekly_total" readonly>
                        </div>

                        <!-- Weekly OT Exceeded Alert -->
                        <div class="alert alert-warning" id="weekly_exceeded_alert" style="display: none;">
                            <strong>Warning:</strong> Weekly OT limit exceeded by <span id="weekly_excess_hours"></span> hours!(1 Week only 8 hours OT)
                        </div>

                        <input type="hidden" id="ot_id" name="ot_id">
                        <div class="col-sm-12 col-md-12 mb-3">
                            <label for="sts" class="col-form-label">OverTime/Late amount</label>
                            <input type="number" class="form-control" name="ot_amount" step="0.01">
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
        $('.approve-ot').on("click", function() {
            let otId = $(this).data("id");
            let otTime = $(this).data("time");
            let otStatus = $(this).data("status");
            let otMonthTotal = $(this).data("monthtotal");
            let otWeeklyTotal = $(this).data("weeklytotal");
            let weeklyExceeded = $(this).data("weeklyexceeded");
            let weeklyExcess = $(this).data("weeklyexcess");

            // Set form values
            $('#ot_id').val(otId);
            $('#modal_ot_monthly_total').val(otMonthTotal);
            
            // Show/hide weekly OT section based on category
            if (otStatus === 'ot') {
                $('#weekly_ot_section').show();
                $('#modal_ot_weekly_total').val(otWeeklyTotal);
                
                // Show warning if weekly OT exceeded
                if (weeklyExceeded) {
                    $('#weekly_exceeded_alert').show();
                    $('#weekly_excess_hours').text(weeklyExcess);
                } else {
                    $('#weekly_exceeded_alert').hide();
                }
            } else {
                $('#weekly_ot_section').hide();
                $('#weekly_exceeded_alert').hide();
            }
        });

        // Reset modal when closed
        $('#updateLeaveApproval').on('hidden.bs.modal', function () {
            $('#weekly_ot_section').hide();
            $('#weekly_exceeded_alert').hide();
        });
    </script>
@endsection