@extends('layouts.app')

@section('content')

    <div class="sidebodydiv px-5 py-3 mb-3">
        <div class="sidebodyback mb-3" onclick="goBack()">
            <div class="backhead">
                <h5><i class="fas fa-arrow-left"></i></h5>
                <h6>Edit Job Posting Form</h6>
            </div>
        </div>
        <div class="sidebodyhead my-3">
            <h4 class="m-0">Job Posting Details</h4>
        </div>
        <form action="{{ route('job_post_edit',['id'=>$edit->post_id]) }}" method="post" id="">
            @csrf
            <div class="container-fluid maindiv">
                <div class="row">
                    <div class="col-sm-12 col-md-4 col-xl-4 mb-3 inputs">
                        <label for="recruitid">Recruit ID <span>*</span></label>
                        <input type="text" class="form-control" name="recruitid" id="recruitid"
                            placeholder="Enter Recruit ID"  value="REC{{ $edit->rec_id }}" autofocus >
                    </div>
                    <div class="col-sm-12 col-md-4 col-xl-4 mb-3 inputs">
                        <label for="jobid">Job ID <span>*</span></label>
                        <input type="text" class="form-control" name="jobid" id="jobid"  value="JOB{{ $edit->rec_id }}" placeholder="Enter Job ID"  >
                    </div>
                    <div class="col-sm-12 col-md-4 col-xl-4 mb-3 inputs">
                        <label for="jobtitle">Job Title <span>*</span></label>
                        <input type="text" class="form-control" name="jobtitle" id="jobtitle" value="{{ $edit->job_title }}" placeholder="Enter Job Title"
                            required>
                    </div>
                    <div class="col-sm-12 col-md-4 col-xl-4 mb-3 inputs">
                        <label for="department">Department <span>*</span></label>
                        <input type="text" class="form-control" name="department" id="department" value="{{ $edit->role_dept }}"
                            placeholder="Enter Department"  >
                    </div>
                    <div class="col-sm-12 col-md-4 col-xl-4 mb-3 inputs">
                        <label for="role">Role <span>*</span></label>
                        <input type="text" class="form-control" name="role" id="role" placeholder="Enter Role" value="{{ $edit->role }}" >
                    </div>
                    <div class="col-sm-12 col-md-4 col-xl-4 mb-3 inputs">
                        <label for="resp">Responsibilities <span>*</span></label>
                        <textarea rows="1" class="form-control" name="resp" id="resp" placeholder="Enter Responsibilities"
                            required>{{ $edit->responsibility }}</textarea>
                    </div>
                    <div class="col-sm-12 col-md-4 col-xl-4 mb-3 inputs">
                        <label for="joblocation">Job Location <span>*</span></label>
                        <input type="text" class="form-control" name="joblocation" id="joblocation" value="{{ $edit->loc }}"
                            placeholder="Enter Job Location"  >
                    </div>
                    <div class="col-sm-12 col-md-4 col-xl-4 mb-3 inputs">
                        <label for="jobtype">Job Type <span>*</span></label>
                        <select name="jobtype" id="jobtype" class="form-select" required>
                            <option value="{{ $edit->job_type }}">{{ $edit->job_type     }}</option>
                            <option value="Full-Time">Full-Time</option>
                            <option value="Part-Time">Part-Time</option>
                            <option value="Contract">Contract</option>
                        </select>
                    </div>
                    <div class="col-sm-12 col-md-4 col-xl-4 mb-3 inputs">
                        <label for="jobdesp">Job Description <span>*</span></label>
                        <textarea class="form-control" rows="1" name="jobdesp" id="jobdesp"
                            placeholder="Enter Job Description" required>{{ $edit->job_desc }}</textarea>
                    </div>
                    <div class="col-sm-12 col-md-4 col-xl-4 mb-3 inputs">
                        <label for="expreq">Experience (In Years) <span>*</span></label>
                        <input type="number" class="form-control" name="expreq" id="expreq" min="0" value="{{ $edit->exp }}"
                            placeholder="Enter Experience"  required>
                    </div>
                    <div class="col-sm-12 col-md-4 col-xl-4 mb-3 inputs">
                        <label for="workhours">Working Hours <span>*</span></label>
                        <input type="text" class="form-control" name="workhours" id="workhours" min="0" value="{{ $edit->hrs }}"
                            placeholder="Enter Working Hours" required>
                    </div>
                    <div class="col-sm-12 col-md-4 col-xl-4 mb-3 inputs">
                        <label for="slryrange">Salary Range <span>*</span></label>
                        <input type="number" class="form-control" name="slryrange" id="slryrange" min="0" value="{{ $edit->salary }}"
                            placeholder="Enter Salary Range" required>
                    </div>
                    <div class="col-sm-12 col-md-4 col-xl-4 mb-3 inputs">
                        <label for="benefits">Benefits <span>*</span></label>
                        <textarea class="form-control" rows="1" name="benefits" id="benefits" placeholder="Enter Benefits"
                            required>{{ $edit->benefits }}</textarea>
                    </div>
                    <div class="col-sm-12 col-md-4 col-xl-4 mb-3 inputs">
                        <label for="postdate">Posting Date <span>*</span></label>
                        <input type="date" class="form-control" name="postdate" id="postdate" value="{{ $edit->post_date }}" required>
                    </div>
                    {{-- <div class="col-sm-12 col-md-4 col-xl-4 mb-3 inputs">
                        <label for="appdeadline">Application Deadline</label>
                        <input type="date" class="form-control" name="appdeadline" id="appdeadline">
                    </div>
                    <div class="col-sm-12 col-md-4 col-xl-4 mb-3 inputs">
                        <label for="sts">Status <span>*</span></label>
                        <select name="sts" id="sts" class="form-select" required>
                            <option value="" selected disabled>Select Options</option>
                            <option value="Opened">Opened</option>
                            <option value="Closed">Closed</option>
                        </select>
                    </div> --}}
                </div>
            </div>

            <div class="col-sm-12 col-md-12 col-xl-12 mt-3 d-flex justify-content-center align-items-center">
                <button type="submit" id="submitBtn" class="formbtn">Update</button>
            </div>
        </form>
    </div>

@endsection
