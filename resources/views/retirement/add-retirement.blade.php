@extends('layouts.app')

@section('content')
    @php
        // dd($store);
    @endphp

    <div class="sidebodydiv mb-3 px-5 py-3">
        <div class="sidebodyback mb-3" onclick="goBack()">
            <div class="backhead">
                <h5><i class="fas fa-arrow-left"></i> Retirement Form</h5>
                <h6></h6>
            </div>
        </div>
        <div class="sidebodyhead my-3">
            <h4 class="m-0">Request Details</h4>
        </div>
        <form action="{{ route('retirement.store_retire') }}" method="POST">
            @csrf
            <div class="container-fluid maindiv">
                <div class="row">
                    <div class="col-sm-12 col-md-4 col-xl-4 inputs mb-3">
                        <label for="">Employee Code</label>
                        <input type="text" class="form-control" name="emp_code" id="emp_code" value="{{ auth()->user()->emp_code }}" readonly>
                    </div>
                    <div class="col-sm-12 col-md-4 col-xl-4 inputs mb-3">
                        <label for="">Employee Name</label>
                        <input type="text" class="form-control" name="emp_name" id="emp_name" value="{{ auth()->user()->name }}" readonly>
                    </div>
                    <div class="col-sm-12 col-md-4 col-xl-4 inputs mb-3">
                        <label for="">Request Date <span>*</span></label>
                        <input type="date" class="form-control" name="req_date" id="req_date" required>
                    </div>
                    <div class="col-sm-12 col-md-4 col-xl-4 inputs mb-3">
                        <label for="">Request Type <span>*</span></label>
                        <select class="form-select" name="req_type" id="req_type" required autofocus>
                            <option value="" selected disabled>Select Options</option>
                            <option value="Regular">Regular</option>
                            <option value="Voluntary">Voluntary</option>
                            <option value="Medical">Medical</option>
                        </select>
                    </div>

                    <div class="col-sm-12 col-md-4 col-xl-4 inputs mb-3">
                        <label for="contact">Reason <span>*</span></label>
                        <input type="text" class="form-control" name="reason" id="reason" required>
                    </div>
                    <div class="col-sm-12 col-md-4 col-xl-4 inputs mb-3">
                        <label for="">Request To <span>*</span></label>
                        <select class="form-select" name="req_to" id="req_to" required autofocus>
                            <option value="" selected disabled>Select Options</option>
                            @foreach ($retire as $ret)
                                <option value="{{ $ret->id }}">{{ $ret->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>

            <div class="col-sm-12 col-md-12 col-xl-12 d-flex justify-content-center align-items-center mt-3">
                <a href="">
                    <button type="submit" class="formbtn">Save</button>
                </a>
            </div>
        </form>
    </div>
    <script src="{{ asset('assets/js/form_script.js') }}"></script>
@endsection
