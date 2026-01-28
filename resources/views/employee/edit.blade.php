@extends('layouts.app')

@section('content')

<div class="sidebodydiv px-5 py-3 mb-3">
    <div class="sidebodyback mb-3" onclick="goBack()">
        <div class="backhead">
            <h5><i class="fas fa-arrow-left"></i></h5>
            <h6>Edit Employee Form</h6>
        </div>
    </div>
    <div class="sidebodyhead my-3">
        <h4 class="m-0">Basic Details</h4>
    </div>
    <form action="{{ route('employee.update', ['id' => $employee->id]) }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="container-fluid maindiv">
            <div class="row">
                <div class="col-sm-12 col-md-4 col-xl-4 mb-3 inputs">
                    <label for="empid">Employee Code <span>*</span></label>
                    <input type="text" class="form-control" name="emp_code" id="empid" placeholder="Enter Employee Code"
                        autofocus required value="{{ $employee->emp_code }}">
                </div>
                <div class="col-sm-12 col-md-4 col-xl-4 mb-3 inputs">
                    <label for="fname">Full Name <span>*</span></label>
                    <input type="text" class="form-control" name="name" id="fname" placeholder="Enter Full Name"
                        required value="{{ $employee->name }}">
                </div>

                <div class="col-sm-12 col-md-4 col-xl-4 mb-3 inputs">
                    <label for="dob">Date Of Birth</label>
                    <input type="date" class="form-control" name="dob" id="dob" pattern="\d{4}-\d{2}-\d{2}"
                        min="1000-01-01" max="9999-12-31" value="{{ $employee->dob }}">
                </div>
                <div class="col-sm-12 col-md-4 col-xl-4 mb-3 inputs">
                    <label for="gender">Gender <span>*</span></label>
                    <select name="gender" id="gender" class="form-select" required>
                        <option value="" disabled {{ $employee->gender == null ? 'selected' : '' }}>Select Options
                        </option>
                        <option value="Male" {{ $employee->gender == 'Male' ? 'selected' : '' }}>Male</option>
                        <option value="Female" {{ $employee->gender == 'Female' ? 'selected' : '' }}>Female</option>
                        <option value="Others" {{ $employee->gender == 'Others' ? 'selected' : '' }}>Others</option>
                    </select>
                </div>

                <div class="col-sm-12 col-md-4 col-xl-4 mb-3 inputs">
                    <label for="marital">Marital Status</label>
                    <select name="marital_status" id="marital_status" class="form-select">
                        <option value="" selected disabled>Select Options</option>
                        <option value="Single" {{ $employee->marital_status == 'Single' ? 'selected' : '' }}>Single
                        </option>
                        <option value="Married" {{ $employee->marital_status == 'Married' ? 'selected' : '' }}>Married
                        </option>
                    </select>
                </div>
                <div class="col-sm-12 col-md-4 col-xl-4 mb-3 inputs">
                    <label for="mail">Email ID <span>*</span></label>
                    <input type="email" class="form-control" name="email" id="mail" placeholder="Enter Email ID"
                        required value="{{ $employee->email }}">
                </div>
                <div class="col-sm-12 col-md-4 col-xl-4 mb-3 inputs">
                    <label for="contact">Contact Number <span>*</span></label>
                    <input type="number" class="form-control" name="contact_no" id="contact" oninput="validate(this)"
                        min="1000000000" max="9999999999" placeholder="Enter Contact Number" required
                        value="{{ $employee->contact_no }}">
                </div>
                <div class="col-sm-12 col-md-4 col-xl-4 inputs mb-3">
                        <label for="contact">Guardian Number <span>*</span></label>
                        <input type="number" class="form-control" name="guardian_no" id="guardian_no" oninput="validate(this)" min="1000000000" max="9999999999"
                            placeholder="Enter Guardian Number" required value="{{ $employee->guardian_no }}">
                    </div>
                <div class="col-sm-12 col-md-4 col-xl-4 mb-3 inputs">
                    <label for="aadhar">Aadhar Number <span>*</span></label>
                    <input type="number" class="form-control" name="aadhar_no" id="aadhar"
                        oninput="validate_aadhar(this)" min="000000000000" max="999999999999"
                        placeholder="Enter Aadhar Number" required value="{{ $employee->aadhar_no }}">
                </div>
                <div class="col-sm-12 col-md-4 col-xl-4 mb-3 inputs">
                    <label for="adrs">Address <span>*</span></label>
                    <textarea rows="1" class="form-control" name="address" id="adrs" placeholder="Enter Address"
                        required>{{ $employee->address }}</textarea>
                </div>
                <div class="col-sm-12 col-md-4 col-xl-4 mb-3 inputs">
                    <label for="district">District <span>*</span></label>
                    <input type="text" class="form-control" name="district" id="district" placeholder="Enter District"
                        required value="{{ $employee->district }}">
                </div>
                <div class="col-sm-12 col-md-4 col-xl-4 mb-3 inputs">
                    <label for="state">State <span>*</span></label>
                    <input type="text" class="form-control" name="state" id="state" placeholder="Enter State" required
                        value="{{ $employee->state }}">
                </div>
                <div class="col-sm-12 col-md-4 col-xl-4 mb-3 inputs">
                    <label for="pincode">Pincode <span>*</span></label>
                    <input type="number" class="form-control" name="pincode" id="pincode" min="000000" max="999999"
                        oninput="validate_pin(this)" placeholder="Enter Pincode" required
                        value="{{ $employee->pincode }}">
                </div>
                <div class="col-sm-12 col-md-4 col-xl-4 mb-3 inputs">
                    <label for="pfimg">Profile Image (Optional)</label>
                    <input type="file" class="form-control" name="profile_image" id="pfimg">

                    @if ($employee->profile_image)
                        <img class="imagePreview" src="{{ asset($employee->profile_image) }}" alt="Image Preview"
                            style="width:100%;height:200px; background-color: #fff; margin-top: 10px;">
                    @endif
                </div>

            </div>
        </div>

        <div class="col-sm-12 col-md-12 col-xl-12 mt-3 d-flex justify-content-center align-items-center">
            <button type="submit" class="formbtn">Save</button>
        </div>
    </form>
</div>

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