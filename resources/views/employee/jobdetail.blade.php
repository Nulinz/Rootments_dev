@extends('layouts.app')

@section('content')

    <style>
        .hidden {
            display: none;
        }
    </style>

    <div class="sidebodydiv px-5 py-3 mb-3">
        <div class="sidebodyback mb-3" onclick="goBack()">
            <div class="backhead">
                <h5><i class="fas fa-arrow-left"></i></h5>
                <h6>Add Employee Form</h6>
            </div>
        </div>
        <div class="sidebodyhead my-3">
            <h4 class="m-0">Job Details</h4>
        </div>
        <form action="{{ route('employee.jobstore', ['id' => $id]) }}" method="POST" id="c_form">
            @csrf
            <div class="container-fluid maindiv">
                <div class="row">
                    <div class="col-sm-12 col-md-4 col-xl-4 mb-3 inputs">
                        <label for="qualification">Qualification <span>*</span></label>
                        <input type="text" class="form-control" name="qulification" id="qualification"
                            placeholder="Enter Qualification" autofocus required>
                    </div>
                    <div class="col-sm-12 col-md-4 col-xl-4 mb-3 inputs">
                        <label for="jobtitle">Job Title <span>*</span></label>
                        <input type="text" class="form-control" name="job_tittle" id="jobtitle"
                            placeholder="Enter Job Title" required>
                    </div>
                    <div class="col-sm-12 col-md-4 col-xl-4 mb-3 inputs">
                        <label for="jobtype">Job Type <span>*</span></label>
                        <select name="job_type" id="jobtype" class="form-select" required>
                            <option value="" selected disabled>Select Options</option>
                            <option value="Internship">Internship</option>
                            <option value="Full-Time">Full-Time</option>
                            <option value="Part-Time">Part-Time</option>
                        </select>
                    </div>
                    <div class="col-sm-12 col-md-4 col-xl-4 mb-3 inputs">
                        <label for="experience">Experience</label>
                        <select name="exprience" id="experience" class="form-select">
                            <option value="" selected disabled>Select Options</option>
                            <option value="Fresher">Fresher</option>
                            <option value="0-1 Year">0-1 Year</option>
                            <option value="1+ Year">1+ Year</option>
                            <option value="2+ Years">2+ Years</option>
                            <option value="3+ Years">3+ Years</option>
                            <option value="4+ Years">4+ Years</option>
                            <option value="5+ Years">5+ Years</option>
                        </select>
                    </div>
                    <div class="col-sm-12 col-md-4 col-xl-4 mb-3 inputs">
                        <label for="prefdate">Joining Date</label>
                        <input type="date" class="form-control" pattern="\d{4}-\d{2}-\d{2}" min="1000-01-01"
                            max="9999-12-31" name="pre_start_date" id="prefdate">
                    </div>
                    <div class="col-sm-12 col-md-4 col-xl-4 mb-3 inputs">
                        <label for="skilledin">Professionally Skilled In</label>
                        <input type="text" class="form-control" name="pro_skill" id="skilledin"
                            placeholder="Enter Professionally Skilled In">
                    </div>
                    <div class="col-sm-12 col-md-4 col-xl-4 mb-3 inputs">
                        <label for="intime">In Time</label>
                        <input type="time" class="form-control" name="intime" id="intime" value="{{ date("H:i") }}" required>
                    </div>
                    <div class="col-sm-12 col-md-4 col-xl-4 mb-3 inputs">
                        <label for="outtime">Out Time</label>
                        <input type="time" class="form-control" name="outtime" id="outtime" value="{{ date("H:i") }}" required>
                    </div>
                </div>
            </div>

            <div class="sidebodyhead my-3">
                <h4 class="m-0">Document Details</h4>
            </div>
            <div class="container-fluid maindiv">
                <div class="row">
                    <div class="col-sm-12 col-md-4 col-xl-4 mb-3 inputs">
                        <label for="aadhar">Aadhar Card</label>
                        <input type="file" class="form-control" name="aadhar_img" id="aadhar">
                    </div>
                    <div class="col-sm-12 col-md-4 col-xl-4 mb-3 inputs">
                        <label for="agreement">Agreement</label>
                        <input type="file" class="form-control" name="agreement" id="agreement" accept=".pdf, .doc, .docx">
                    </div>
                </div>
            </div>

            <div class="sidebodyhead my-3">
                <h4 class="m-0">Assign Details</h4>
            </div>
            <div class="container-fluid maindiv">
                <div class="row">
                    <div class="col-sm-12 col-md-4 col-xl-4 mb-3 inputs">
                        <label for="department">Department <span>*</span></label>
                        <select name="dept" id="department" class="form-select" required>
                            <option value="" selected disabled>Select Options</option>
                            @foreach ($dept as $item)
                                <option value="{{ $item->role_dept }}">{{ $item->role_dept }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-sm-12 col-md-4 col-xl-4 mb-3 inputs">
                        <label for="role">Assign Role <span>*</span></label>
                        <select name="role_id" id="role" class="form-select" required>
                            <option value="" selected disabled>Select Options</option>
                        </select>
                    </div>
                    <div class="col-sm-12 col-md-4 col-xl-4 mb-3 inputs" id="storeDiv" class="hidden">
                        <label for="store">Assign Store <span>*</span></label>
                        <select name="store_id" id="store" class="form-select">
                            <option value="" selected disabled>Select Options</option>
                            @foreach ($store as $item)
                                <option value="{{ $item->id }}">{{ $item->store_name }}</option>
                            @endforeach
                        </select>
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
        $(document).ready(function () {
            $('#department').on('change', function () {
                var deptId = $(this).val();
                var deptSelect = $('#role');

                deptSelect.html('<option value="">Select Role</option>');

                if (deptId) {
                    $.ajax({
                        url: '{{ route('get_role') }}',
                        type: 'POST',
                        data: {
                            dept_id: deptId,
                            _token: '{{ csrf_token() }}'
                        },
                        success: function (data) {
                            if (data.length > 0) {
                                data.forEach(function (role) {
                                    deptSelect.append(
                                        $('<option></option>')
                                            .val(role.id)
                                            .text(role.role)
                                    );
                                });
                            } else {
                                deptSelect.append(
                                    '<option value="">No Roles Available</option>');
                            }
                        },
                        error: function (xhr, status, error) {
                            console.error('Error:', error);
                            alert('Failed to fetch roles. Please try again later.');
                        }
                    });
                }
            });
        });
    </script>

    <script>
        document.getElementById("department").addEventListener("change", function () {
            let selectedDept = this.value;
            let storeDiv = document.getElementById("storeDiv");
            let hiddenDepartments = ["Admin", "Finance", "HR", "IT", "Operation", "Sales/Marketing", "Area", "Cluster"];
            if (hiddenDepartments.includes(selectedDept)) {
                storeDiv.classList.add("hidden");
            } else {
                storeDiv.classList.remove("hidden");
            }
        });
    </script>

@endsection