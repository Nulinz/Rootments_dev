@extends('layouts.app')

@section('content')
    <div class="sidebodydiv mb-3 px-5 py-3">
        <div class="sidebodyback mb-3" onclick="goBack()">
            <div class="backhead">
                <h5><i class="fas fa-arrow-left"></i></h5>
                <h6>Add Leave Request Form</h6>
            </div>
        </div>
        <div class="sidebodyhead my-3">
            <h4 class="m-0">Leave Request Details</h4>
            <p id="result"></p>
        </div>
        <form action="{{ route('leave.store') }}" method="post" id="c_form" enctype="multipart/form-data">
            @csrf
            <div class="container-fluid maindiv my-3">
                <div class="row">
                    <div class="col-sm-12 col-md-4 col-xl-4 inputs mb-3">
                        <label for="reqtype">Request Type <span>*</span></label>
                        <select class="form-select" name="request_type" id="reqtype" required>
                            <option value="" selected disabled>Select Options</option>
                            <!--<option value="Permission">Permission</option>-->
                            <option value="Week Off" id="week">Week Off</option>
                            <!--<option value="Casual Leave">Casual Leave</option>-->
                            <option value="Sick leave">Sick Leave</option>
                            <!--<option value="Half Day">HalfDay</option>-->
                             <option value="Annual leave">Annual Leave</option>
                        </select>
                    </div>
                    <div class="col-sm-12 col-md-4 col-xl-4 inputs mb-3">
                        <label for="startdate">Start Date <span>*</span></label>
                        <input type="date" class="form-control" max="9999-12-31" name="start_date" id="startdate" min="{{ date('Y-m-d') }}" required autofocus>
                    </div>
                    <div class="col-sm-12 col-md-4 col-xl-4 inputs mb-3">
                        <label for="enddate">End Date <span>*</span></label>
                        <input type="date" class="form-control" max="9999-12-31" name="end_date" id="enddate" min="{{ date('Y-m-d') }}" required>
                    </div>

                    <div class="col-sm-12 col-md-4 col-xl-4 inputs time-section mb-3" style="display: none;">
                        <label for="starttime">Start Time</label>
                        <input type="time" class="form-control" name="start_time" id="starttime">
                    </div>
                    <div class="col-sm-12 col-md-4 col-xl-4 inputs time-section mb-3" style="display: none;">
                        <label for="endtime">End Time</label>
                        <input type="time" class="form-control" name="end_time" id="endtime">
                    </div>
                    <div class="col-sm-12 col-md-4 col-xl-4 inputs mb-3">
                        <label for="reason">Reason <span>*</span></label>
                        <textarea rows="1" class="form-control" name="reason" id="reason" placeholder="Enter Reason" required></textarea>
                    </div>
                     <div class="col-sm-12 col-md-4 col-xl-4 inputs medical-section d-none mb-3">
                        <label for="medical">Medical Certificate <span>*</span></label>
                        <input type="file" class="form-control" name="medical" id="medical" required>
                    </div>
                </div>
            </div>

            <div class="col-sm-12 col-md-12 col-xl-12 d-flex justify-content-center align-items-center mt-3">
                <button type="submit" id="sub" class="formbtn">Request</button>
            </div>
        </form>
    </div>

    <script src="{{ asset('assets/js/form_script.js') }}"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>

<script>
    // Function to calculate the number of days between two dates
    function calculateLeaveDays(startDate, endDate) {
        if (!startDate || !endDate) return 0;
        
        const start = new Date(startDate);
        const end = new Date(endDate);
        
        if (isNaN(start) || isNaN(end)) return 0;
        
        const timeDiff = end.getTime() - start.getTime();
        const diffInDays = Math.floor(timeDiff / (1000 * 60 * 60 * 24)) + 1; // +1 to include both start and end dates
        return diffInDays;
    }

    // Function to check 4-day limit for specific leave types
    function checkFourDayLimit() {
        let requestType = $('#reqtype').val();
        let startDate = $('#startdate').val();
        let endDate = $('#enddate').val();

        // Remove any existing 4-day limit message
        $('#four_day_limit_msg').remove();
        
        // Check if this is a leave type that has 4-day limit
        const limitedLeaveTypes = ['Casual Leave', 'Sick leave', 'Annual leave'];
        
        if (limitedLeaveTypes.includes(requestType) && startDate && endDate) {
            const leaveDays = calculateLeaveDays(startDate, endDate);
            
            if (leaveDays > 4) {
                $('#enddate').after(`<p id="four_day_limit_msg" class="text-danger mt-1">Continuous ${requestType.toLowerCase()} cannot exceed 4 days. Current selection: ${leaveDays} days.</p>`);
                $('#sub').prop('disabled', true);
                return false;
            }
        }
        return true;
    }

    $('#reqtype, #startdate, #enddate').on('change', function () {
        let requestType = $('#reqtype').val();
        let startDate = $('#startdate').val();
        let endDate = $('#enddate').val();
        
        
            if (requestType === 'Sick leave') {
                $('#medical').attr('required', true);
                $('.medical-section').removeClass('d-none').show();
            } else {
                $('#medical').attr('required', false);
                $('.medical-section').hide();
            }

        // Clear any old errors
        $('#leave_limit_msg').remove();
        $('#four_day_limit_msg').remove();
        $('#sub').prop('disabled', false);

        // Check 4-day limit first
        const fourDayCheck = checkFourDayLimit();

        // Check annual + sick leave limit (existing functionality)
        if ((requestType === 'Annual leave' || requestType === 'Sick leave') && startDate && endDate) {
            $.ajax({
                url: "{{ route('leave.checkLimit') }}",
                method: 'POST',
                data: {
                    _token: "{{ csrf_token() }}",
                    type: requestType,
                    start_date: startDate,
                    end_date: endDate
                },
                success: function (data) {
                    if (data.exceeds_limit) {
                        $('#reqtype').after(`<p id="leave_limit_msg" class="text-danger mt-1">You have the annual + sick leave limit of 20 days. (${data.total_taken} days already used)</p>`);
                        $('#sub').prop('disabled', true);
                    } else {
                        // Only enable submit if no 4-day limit violation
                        if (!$('#four_day_limit_msg').length) {
                            $('#sub').prop('disabled', false);
                        }
                    }
                },
                error: function (xhr) {
                    console.log("Validation error", xhr.responseJSON);
                }
            });
        }
    });

    // Additional validation on form submit
    $('#c_form').on('submit', function(e) {
        if (!checkFourDayLimit()) {
            e.preventDefault();
            return false;
        }
    });
</script>

    <script>
        $(document).ready(function() {

            var store_per = {!! json_encode(auth()->user()->store_id) !!};
            console.log("Store ID:", store_per);

            function calculateLeaveDaysInternal() {
                const start = new Date($('#startdate').val());
                const end = new Date($('#enddate').val());

                // Ensure both dates are selected
                if (!start || !end || isNaN(start) || isNaN(end)) return 0;

                const timeDiff = end.getTime() - start.getTime();
                const diffInDays = Math.floor(timeDiff / (1000 * 60 * 60 * 24)) + 1; // +1 to include both days
                return diffInDays;
            }

            function checkLeaveEligibility() {
                var st_date = $('#startdate').val();
                if (!st_date) return;

                var request = $('#reqtype').val();

                $.ajax({
                    url: '{{ route('get_leave_emp') }}',
                    type: 'POST',
                    data: {
                        st_date: st_date,
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(data) {
                        console.log(data);

                        if ((store_per != null)) {
                            if (data.leave === true) {
                                $('#sub').hide();
                                $('#result').text(
                                    'Leave Is Not Eligible For today Based on Per Day Attendance Store'
                                );
                            } else {
                                $('#sub').show();
                                $('#result').text('');
                            }
                        }

                        if (request == 'Week Off') {
                            $(".time-section").hide();

                            if ((data.week_off > 4) || (data.week_off == 0)) {
                                $('#result').text(
                                    'You cannot take more than 4 Week Off in a month.');
                                $('#sub').hide();
                            } else {
                                $('#enddate').attr('min', st_date);
                                $('#enddate').attr('max', data.max_date);
                                $('#enddate').val(data.max_date);
                                $('#sub').show();
                                $('#result').text('');
                            }
                        } else if (request === 'Half Day' || request === 'Permission') {
                            $(".time-section").toggle(request === 'Permission');

                            var start = new Date($('#startdate').val());
                            var today = new Date();

                            var startFormatted = start.toISOString().split('T')[0];
                            var todayFormatted = today.toISOString().split('T')[0];

                            var minDate = (startFormatted > todayFormatted) ? startFormatted :
                                todayFormatted;

                            $('#enddate').attr('min', minDate);
                            $('#enddate').attr('max', startFormatted);
                            $('#enddate').val(startFormatted);

                        } else {
                            $('#startdate').attr('min', st_date);
                            $('#startdate').attr('max', '');
                            $('#enddate').attr('min', st_date);
                            $('#enddate').attr('max', '');
                            $('#enddate').val('');
                            $(".time-section").hide();
                        }

                        // After setting up the form, check the 4-day limit
                        setTimeout(checkFourDayLimit, 100);
                    },
                    error: function() {
                        alert('Failed to check leave status.');
                    }
                });
            }

            $('#reqtype,#startdate').on('change', function() {
                checkLeaveEligibility();
            });
        });
    </script>
@endsection