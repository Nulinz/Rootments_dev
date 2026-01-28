@extends('layouts.app')

@section('content')

    <div class="sidebodydiv px-5 py-3 mb-3">
        <div class="sidebodyback mb-3" onclick="goBack()">
            <div class="backhead">
                <h5><i class="fas fa-arrow-left"></i></h5>
                <h6>Add Store Setup Form</h6>
            </div>
        </div>
        <div class="sidebodyhead my-3">
            <h4 class="m-0">Store Setup Details</h4>
        </div>
        <form action="{{ route('setup.store') }}" method="POST" id="c_form">
            @csrf
            <div class="container-fluid maindiv">
                <div class="row">
                    <div class="col-sm-12 col-md-4 col-xl-4 mb-3 inputs">
                        <label for="storename">Store Name <span>*</span></label>
                        <input type="text" class="form-control" name="storename" id="storename"
                            placeholder="Enter Store Name" autofocus required>
                    </div>
                    <div class="col-sm-12 col-md-4 col-xl-4 mb-3 inputs">
                        <label for="address">Address <span>*</span></label>
                        <textarea rows="1" class="form-control" name="address" id="address" placeholder="Enter Address"
                            required></textarea>
                    </div>
                    <div class="col-sm-12 col-md-4 col-xl-4 mb-3 inputs">
                        <label for="city">City <span>*</span></label>
                        <input type="text" class="form-control" name="city" id="city" placeholder="Enter City" required>
                    </div>
                    <div class="col-sm-12 col-md-4 col-xl-4 mb-3 inputs">
                        <label for="state">State <span>*</span></label>
                        <input type="text" class="form-control" name="state" id="state" placeholder="Enter State" required>
                    </div>
                    <div class="col-sm-12 col-md-4 col-xl-4 mb-3 inputs">
                        <label for="pincode">Pincode <span>*</span></label>
                        <input type="number" class="form-control" name="pincode" id="pincode" oninput="validate_pin(this)"
                            min="000000" max="999999" placeholder="Enter Pincode" required>
                    </div>
                    <div class="col-sm-12 col-md-4 col-xl-4 mb-3 inputs">
                        <label for="geolocation">Geolocation <span>*</span></label>
                        <input type="text" class="form-control" name="geolocation" id="geolocation"
                            placeholder="Enter Geolocation" required>
                    </div>
                </div>
            </div>

            <div class="col-sm-12 col-md-12 col-xl-12 mt-3 d-flex justify-content-center align-items-center">
                <button type="submit" class="formbtn" id="sub">Save</button>
            </div>
        </form>
    </div>

<script src="{{ asset('assets/js/form_script.js') }}"></script>

@endsection
