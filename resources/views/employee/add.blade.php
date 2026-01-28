@extends('layouts.app')

@section('content')

<div class="sidebodydiv px-5 py-3 mb-3">
    <div class="sidebodyback mb-3" onclick="goBack()">
        <div class="backhead">
            <h5><i class="fas fa-arrow-left"></i></h5>
            <h6>Add Employee Form</h6>
        </div>
    </div>
    <div class="sidebodyhead my-3">
        <h4 class="m-0">Basic Details</h4>
    </div>
    <form action="{{ route('employee.store') }}" method="POST" enctype="multipart/form-data" id="c_form">
        @csrf
        <div class="container-fluid maindiv">
            <div class="row">
                <div class="col-sm-12 col-md-4 col-xl-4 mb-3 inputs">
                    <label for="empid">Employee Code <span>*</span></label>
                    <input type="text" class="form-control" name="emp_code" id="empid" placeholder="Enter Employee Code"
                        required readonly value="{{ $emp_no }}">
                </div>
                <div class="col-sm-12 col-md-4 col-xl-4 mb-3 inputs">
                    <label for="fname">Full Name <span>*</span></label>
                    <input type="text" class="form-control" name="name" id="fname" autocomplete="off" placeholder="Enter Full Name"
                       autofocus required>
                </div>
                <div class="col-sm-12 col-md-4 col-xl-4 mb-3 inputs">
                    <label for="password">Password <span>*</span></label>
                    <input type="password" class="form-control" name="password" autocomplete="off" id="password" minlength="6"
                        placeholder="Enter Password" required>
                </div>
                <div class="col-sm-12 col-md-4 col-xl-4 mb-3 inputs">
                    <label for="dob">Date Of Birth</label>
                    <input type="date" class="form-control" name="dob" id="dob" pattern="\d{4}-\d{2}-\d{2}"
                        min="1000-01-01" max="9999-12-31">
                </div>
                <div class="col-sm-12 col-md-4 col-xl-4 mb-3 inputs">
                    <label for="gender">Gender <span>*</span></label>
                    <select name="gender" id="gender" class="form-select" required>
                        <option value="" selected disabled>Select Options</option>
                        <option value="Male">Male</option>
                        <option value="Female">Female</option>
                        <option value="Others">Others</option>
                    </select>
                </div>
                <div class="col-sm-12 col-md-4 col-xl-4 mb-3 inputs">
                    <label for="marital">Marital Status</label>
                    <select name="marital_status" id="marital_status" class="form-select">
                        <option value="" selected disabled>Select Options</option>
                        <option value="Single">Single</option>
                        <option value="Married">Married</option>
                    </select>
                </div>
                <div class="col-sm-12 col-md-4 col-xl-4 mb-3 inputs">
                    <label for="mail">Email ID <span>*</span></label>
                    <input type="email" class="form-control" name="email" id="mail" autocomplete="off" placeholder="Enter Email ID"
                        required>
                    @error('email')
                        <h6 class="errormsg">{{ $message }}</h6>
                    @enderror
                </div>
                <div class="col-sm-12 col-md-4 col-xl-4 mb-3 inputs">
                    <label for="contact">Contact Number <span>*</span></label>
                    <input type="number" class="form-control" name="contact_no" id="contact" autocomplete="off" oninput="validate(this)"
                        min="1000000000" max="9999999999" placeholder="Enter Contact Number" required>
                    @error('contact_no')
                        <h6 class="errormsg">{{ $message }}</h6>
                    @enderror
                </div>
                 <div class="col-sm-12 col-md-4 col-xl-4 mb-3 inputs">
                        <label for="contact">Guardian Contact Number <span>*</span></label>
                        <input type="number" class="form-control" name="guardian_contact_no" id="guardian_contact"
                            autocomplete="off" oninput="validate(this)" min="1000000000" max="9999999999"
                            placeholder="Enter Guardian Contact Number" required>
                        @error('guardian_contact_no')
                            <h6 class="errormsg">{{ $message }}</h6>
                        @enderror
                    </div>
                <div class="col-sm-12 col-md-4 col-xl-4 mb-3 inputs">
                    <label for="aadhar">Aadhar Number <span>*</span></label>
                    <input type="number" class="form-control" name="aadhar_no" id="aadhar" oninput="validate_aadhar(this)"
                        min="000000000000" max="999999999999" placeholder="Enter Aadhar Number" required>
                    @error('aadhar_no')
                        <h6 class="errormsg">{{ $message }}</h6>
                    @enderror
                </div>
                <div class="col-sm-12 col-md-4 col-xl-4 mb-3 inputs">
                    <label for="adrs">Address <span>*</span></label>
                    <textarea rows="1" class="form-control" name="address" id="adrs" placeholder="Enter Address"
                        required></textarea>
                </div>
                <div class="col-sm-12 col-md-4 col-xl-4 mb-3 inputs">
                    <label for="district">District <span>*</span></label>
                    <input type="text" class="form-control" name="district" id="district" placeholder="Enter District"
                        required>
                </div>
                <div class="col-sm-12 col-md-4 col-xl-4 mb-3 inputs">
                    <label for="state">State <span>*</span></label>
                    <input type="text" class="form-control" name="state" id="state" placeholder="Enter State" required>
                </div>
                <div class="col-sm-12 col-md-4 col-xl-4 mb-3 inputs">
                    <label for="pincode">Pincode <span>*</span></label>
                    <input type="number" class="form-control" name="pincode" id="pincode" autocomplete="off" min="000000" max="999999"
                        oninput="validate_pin(this)" placeholder="Enter Pincode" required>
                </div>
                <div class="col-sm-12 col-md-4 col-xl-4 mb-3 inputs">
                    <label for="pfimg">Profile Image (Optional)</label>
                    <input type="file" class="form-control" name="profile_image" id="pfimg">
                    <img class="imagePreview" src="" alt="Image Preview"
                        style="display:none; width:100%; height:300px; object-fit: cover; object-position: center; background-color: #fff; margin-top: 10px;">
                </div>

            </div>
        </div>

        <div class="col-sm-12 col-md-12 col-xl-12 mt-3 d-flex justify-content-center align-items-center">
            <button type="submit" id="sub" class="formbtn">Save</button>
        </div>
    </form>
</div>

<script src="{{ asset('assets/js/form_script.js') }}"></script>
<script>
    document.getElementById("pfimg").addEventListener("change", function (event) {
        const file = event.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function (e) {
                const imagePreview = document.querySelector(".imagePreview");
                imagePreview.src = e.target.result;
                imagePreview.style.display = "block";
            };
            reader.readAsDataURL(file);
        }
    });
</script>

@endsection
