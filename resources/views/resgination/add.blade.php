@extends('layouts.app')

@section('content')
    <div class="sidebodydiv mb-3 px-5 py-3">
        <div class="sidebodyback mb-3" onclick="goBack()">
            <div class="backhead">
                <h5><i class="fas fa-arrow-left"></i></h5>
                <h6>Add Resign Request Form</h6>
            </div>
        </div>
        <div class="sidebodyhead my-3">
            <h4 class="m-0">Resign Request Details</h4>
        </div>
        <form action="{{ route('resignation.store') }}" method="post" id="c_form">
            @csrf
            @php
                $user = auth()->user();
                $role = $user->role_id;

            @endphp
            <div class="container-fluid maindiv my-3">
                <div class="row">
                    <div class="col-sm-12 col-md-4 col-xl-4 inputs mb-3">
                        <label for="empid">Employee Code <span>*</span></label>
                        <input type="text" class="form-control" value="{{ $user->emp_code }}" readonly>
                        <input hidden type="text" name="emp_id" value="{{ $user->id }}">
                        {{-- <select class="form-select" name="emp_id" id="empcode" autofocus required>
                            <option value="" selected disabled>Select Options</option>

                        </select> --}}
                    </div>
                    <div class="col-sm-12 col-md-4 col-xl-4 inputs mb-3">
                        <label for="empname">Employee Name <span>*</span></label>
                        <input type="text" class="form-control" name="emp_name" id="empname"
                            value="{{ $user->name }}" placeholder="Enter Employee Name" readonly required>
                    </div>
                    {{-- <div class="col-sm-12 col-md-4 col-xl-4 mb-3 inputs">
                        <label for="store">Store Name <span>*</span></label>
                        <select class="form-select" name="store_id" id="store" required>
                            <option value="" selected disabled>Select Options</option>

                        </select>
                    </div> --}}
                    <div class="col-sm-12 col-md-4 col-xl-4 inputs mb-3">
                        <label for="location">Location <span>*</span></label>
                        <input type="text" class="form-control" name="loc" id="location"
                            placeholder="Enter Location" required>
                    </div>
                    <div class="col-sm-12 col-md-4 col-xl-4 inputs mb-3">
                        <label for="reason">Reason For Leaving <span>*</span></label>
                        <textarea rows="1" class="form-control" name="res_reason" id="reason" placeholder="Enter Reason For Leaving"
                            required></textarea>
                    </div>
                    <div class="col-sm-12 col-md-4 col-xl-4 inputs mb-3">
                        <label for="resigndate">Resign Request Date <span>*</span></label>
                        <input type="date" class="form-control" pattern="\d{4}-\d{2}-\d{2}" min="1000-01-01" max="9999-12-31" name="res_date" id="resigndate" required>
                    </div>


                    <div class="col-sm-12 col-md-4 col-xl-4 mb-3 inputs">
                        <label for="reqtype">Request To <span>*</span></label>
                        <select class="form-select" name="request_to" id="" required>
                            {{-- @foreach ($hr_list as $hr)
                                <option value="{{ $hr->id }}">{{ $hr->name }}</option>
                            @endforeach --}}
                            <option value="2">HR DEPARTMENT</option>
                        </select>
                    </div>

                </div>
            </div>

            <div class="col-sm-12 col-md-12 col-xl-12 d-flex justify-content-center align-items-center mt-3">
                <button type="submit" id="sub" class="formbtn">Request</button>
            </div>
        </form>
    </div>

    <script src="{{ asset('assets/js/form_script.js') }}"></script>

    {{-- <script>
        $('#c_form').submit(function(e) {
            // e.preventDefault();
            $('#sub').prop('disabled', true).text('Saving...');
        });
    </script> --}}
@endsection
