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
                <h6>Edit Employee Form</h6>
            </div>
        </div>
        <div class="sidebodyhead my-3">
            <h4 class="m-0">Job Details</h4>
        </div>
        <form action="{{ route('employee.jobupdate', ['id' => $employee->id]) }}" method="POST"
            enctype="multipart/form-data">
            @csrf
            <div class="container-fluid maindiv">
                <div class="row">
                    <div class="col-sm-12 col-md-4 col-xl-4 mb-3 inputs">
                        <label for="qualification">Qualification <span>*</span></label>
                        <input type="text" class="form-control" name="qulification" id="qualification"
                            placeholder="Enter Qualification" autofocus required value="{{ $employee->qulification }}">
                    </div>
                    <div class="col-sm-12 col-md-4 col-xl-4 mb-3 inputs">
                        <label for="jobtitle">Job Title <span>*</span></label>
                        <input type="text" class="form-control" name="job_tittle" id="jobtitle"
                            placeholder="Enter Job Title" required value="{{ $employee->job_tittle }}">
                    </div>
                    <div class="col-sm-12 col-md-4 col-xl-4 mb-3 inputs">
                        <label for="jobtype">Job Type <span>*</span></label>
                        <select name="job_type" id="jobtype" class="form-select" required>
                            <option value="" selected disabled>Select Options</option>
                            <option value="Internship" {{ $employee->job_type == 'Internship' ? 'selected' : '' }}>
                                Internship</option>
                            <option value="Full-Time" {{ $employee->job_type == 'Full-Time' ? 'selected' : '' }}>Full-Time
                            </option>
                             <option value="Part-Time" {{ $employee->job_type == 'Part-Time' ? 'selected' : '' }}>Part-Time
                            </option>
                        </select>
                    </div>
                    <div class="col-sm-12 col-md-4 col-xl-4 mb-3 inputs">
                        <label for="experience">Experience</label>
                        <select name="exprience" id="experience" class="form-select">
                            <option value="" selected disabled>Select Options</option>
                            <option value="Fresher" {{ $employee->exprience == 'Fresher' ? 'selected' : '' }}>Fresher
                            </option>
                            <option value="0-1 Year" {{ $employee->exprience == '0-1 Year' ? 'selected' : '' }}>0-1 Year
                            </option>
                            <option value="1+ Year" {{ $employee->exprience == '1+ Year' ? 'selected' : '' }}>1+ Year
                            </option>
                            <option value="2+ Years" {{ $employee->exprience == '2+ Years' ? 'selected' : '' }}>2+ Years
                            </option>
                            <option value="3+ Years" {{ $employee->exprience == '3+ Years' ? 'selected' : '' }}>3+ Years
                            </option>
                            <option value="4+ Years" {{ $employee->exprience == '4+ Years' ? 'selected' : '' }}>4+ Years
                            </option>
                            <option value="5+ Years" {{ $employee->exprience == '5+ Years' ? 'selected' : '' }}>5+ Years
                            </option>
                        </select>
                    </div>
                    <div class="col-sm-12 col-md-4 col-xl-4 mb-3 inputs">
                        <label for="prefdate">Joining Date</label>
                        <input type="date" class="form-control" pattern="\d{4}-\d{2}-\d{2}" min="1000-01-01"
                            max="9999-12-31" name="pre_start_date" id="prefdate" value="{{ $employee->pre_start_date }}">
                    </div>
                    <div class="col-sm-12 col-md-4 col-xl-4 mb-3 inputs">
                        <label for="skilledin">Professionally Skilled In</label>
                        <input type="text" class="form-control" name="pro_skill" id="skilledin"
                            placeholder="Enter Professionally Skilled In" value="{{ $employee->pro_skill }}">
                    </div>
                    <div class="col-sm-12 col-md-4 col-xl-4 mb-3 inputs">
                        <label for="intime">In Time</label>
                        <input type="time" class="form-control" name="intime" value="{{ $employee->st_time }}" id="intime" required>
                    </div>
                    <div class="col-sm-12 col-md-4 col-xl-4 mb-3 inputs">
                        <label for="outtime">Out Time</label>
                        <input type="time" class="form-control" name="outtime" value="{{ $employee->end_time }}" id="outtime" required>
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
                        <img class="imagePreview aadharPreview" src="{{ asset($employee->aadhar_img) }}"
                            alt="Aadhar Image Preview"
                            style="height:185px;background-color:#fff;margin-top:10px;{{ $employee->aadhar_img ? 'display:block;' : 'display:none;' }}">
                    </div>

                    <div class="col-sm-12 col-md-4 col-xl-4 mb-3 inputs">
                        <label for="agreement">Agreement</label>
                        <input type="file" class="form-control" name="agreement" id="agreement" accept=".pdf, .doc, .docx">
                        <iframe src="{{ asset($employee->agreement) }}" style="width:100%; height:200px; background-color:#fff; margin-top:10px;
                                {{ $employee->agreement ? 'display:block;' : 'display:none;' }}"></iframe>
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
                                <option value="{{ $item->role_dept }}" {{ $item->role_dept == $employee->dept ? 'selected' : '' }}>
                                    {{ $item->role_dept }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-sm-12 col-md-4 col-xl-4 mb-3 inputs">
                        <label for="role">Assign Role <span>*</span></label>
                        <select name="role_id" id="role" class="form-select" required>
                            <option value="" selected disabled>Select Options</option>
                            @foreach ($assgin as $item)
                                <option value="{{ $item->id }}" {{ $item->id == $employee->role_id ? 'selected' : '' }}>
                                    {{ $item->role }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-sm-12 col-md-4 col-xl-4 mb-3 inputs" id="storeDiv" class="hidden">
                        <label for="stroe">Assign Store <span>*</span></label>
                        <select name="store_id" id="store" class="form-select">
                            <option value="" selected>Select Options</option>
                            @foreach ($store as $item)
                                <option value="{{ $item->id }}" {{ $item->id == $employee->store_id ? 'selected' : '' }}>
                                    {{ $item->store_name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>

            <div class="col-sm-12 col-md-12 col-xl-12 mt-3 d-flex justify-content-center align-items-center">
                <button type="submit" class="formbtn">Save</button>
            </div>
        </form>
    </div>

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
        function handleFilePreview(inputId, previewClass) {
            document.getElementById(inputId).addEventListener("change", function (event) {
                const file = event.target.files[0];

                if (file) {
                    const reader = new FileReader();

                    reader.onload = function (e) {
                        const imagePreview = document.querySelector(`.${previewClass}`);
                        imagePreview.src = e.target.result;
                        imagePreview.style.display = "block";
                    };

                    reader.readAsDataURL(file);
                }
            });
        }

        // Attach event listeners for both input fields
        handleFilePreview("aadhar", "aadharPreview");
        handleFilePreview("agreement", "agreementPreview");
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
        document.getElementById("department").dispatchEvent(new Event("change"));
    </script>

@endsection