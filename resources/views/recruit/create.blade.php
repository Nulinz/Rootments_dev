@extends('layouts.app')

@section('content')

    <div class="sidebodydiv px-5 py-3 mb-3">
        <div class="sidebodyback mb-3" onclick="goBack()">
            <div class="backhead">
                <h5><i class="fas fa-arrow-left"></i></h5>
                <h6>Add Job Posting Form</h6>
            </div>
        </div>
        <div class="sidebodyhead my-3">
            <h4 class="m-0">Job Posting Details</h4>
        </div>
        <form action="{{ route('job_post_add') }}" method="post" id="c_fomr">
            @csrf
            <div class="container-fluid maindiv">
                <div class="row">
                    <div class="col-sm-12 col-md-4 col-xl-4 mb-3 inputs">
                        <label for="recruitid">Recruit ID <span>*</span></label>
                        <select name="rec_id" id="rec_id" class="form-select" required>
                            <option value="" selected disabled>Select Options</option>
                           @foreach ($rec as $rc)
                               <option value="{{ $rc->id }}">REC{{ $rc->id }}</option>
                           @endforeach
                        </select>
                    </div>
                    {{-- <div class="col-sm-12 col-md-4 col-xl-4 mb-3 inputs">
                        <label for="jobid">Job ID <span>*</span></label>
                        <input type="text" class="form-control" name="jobid" id="jobid" placeholder="Enter Job ID" required>
                    </div> --}}
                    <div class="col-sm-12 col-md-4 col-xl-4 mb-3 inputs">
                        <label for="jobtitle">Job Title <span>*</span></label>
                        <input type="text" class="form-control" name="jobtitle" id="jobtitle" placeholder="Enter Job Title"
                            required>
                    </div>
                    <div class="col-sm-12 col-md-4 col-xl-4 mb-3 inputs">
                        <label for="department">Department <span>*</span></label>
                        <input type="text" class="form-control" name="department" id="department" 
                            placeholder="Enter Department" required>
                    </div>
                    <div class="col-sm-12 col-md-4 col-xl-4 mb-3 inputs">
                        <label for="role">Role <span>*</span></label>
                        <input type="text" class="form-control" name="role" id="role" placeholder="Enter Role"  required>
                    </div>
                    <div class="col-sm-12 col-md-4 col-xl-4 mb-3 inputs">
                        <label for="resp">Responsibilities <span>*</span></label>
                        <textarea rows="1" class="form-control" name="resp" id="resp" placeholder="Enter Responsibilities"
                            required></textarea>
                    </div>
                    <div class="col-sm-12 col-md-4 col-xl-4 mb-3 inputs">
                        <label for="joblocation">Job Location <span>*</span></label>
                        <input type="text" class="form-control" name="joblocation" id="joblocation" 
                            placeholder="Enter Job Location" required>
                    </div>
                    <div class="col-sm-12 col-md-4 col-xl-4 mb-3 inputs">
                        <label for="joblocation">Vacancy<span>*</span></label>
                        <input type="text" class="form-control" name="vacancy" id="vacancy" 
                            placeholder="Enter Job Location" required>
                    </div>
                    <div class="col-sm-12 col-md-4 col-xl-4 mb-3 inputs">
                        <label for="jobtype">Job Type <span>*</span></label>
                        <select name="jobtype" id="jobtype" class="form-select" required>
                            <option value="" selected disabled>Select Options</option>
                            <option value="Full-Time">Full-Time</option>
                            <option value="Part-Time">Part-Time</option>
                            <option value="Contract">Contract</option>
                        </select>
                    </div>
                    <div class="col-sm-12 col-md-4 col-xl-4 mb-3 inputs">
                        <label for="jobdesp">Job Description <span>*</span></label>
                        <textarea class="form-control" rows="1" name="jobdesp" id="jobdesp"
                            placeholder="Enter Job Description" required></textarea>
                    </div>
                    <div class="col-sm-12 col-md-4 col-xl-4 mb-3 inputs">
                        <label for="expreq">Experience (In Years) <span>*</span></label>
                        <input type="number" class="form-control" name="expreq" id="expreq" min="0" 
                            placeholder="Enter Experience" required>
                    </div>
                    <div class="col-sm-12 col-md-4 col-xl-4 mb-3 inputs">
                        <label for="workhours">Working Hours <span>*</span></label>
                        <input type="text" class="form-control" name="workhours" id="workhours" min="0"
                            placeholder="Enter Working Hours" required>
                    </div>
                    <div class="col-sm-12 col-md-4 col-xl-4 mb-3 inputs">
                        <label for="slryrange">Salary Range <span>*</span></label>
                        <input type="number" class="form-control" name="slryrange" id="slryrange" min="0"
                            placeholder="Enter Salary Range" required>
                    </div>
                    <div class="col-sm-12 col-md-4 col-xl-4 mb-3 inputs">
                        <label for="benefits">Benefits <span>*</span></label>
                        <textarea class="form-control" rows="1" name="benefits" id="benefits" placeholder="Enter Benefits"
                            required></textarea>
                    </div>
                    <div class="col-sm-12 col-md-4 col-xl-4 mb-3 inputs">
                        <label for="postdate">Posting Date <span>*</span></label>
                        <input type="date" class="form-control" name="postdate" id="postdate" required>
                    </div>
                    {{-- <div class="col-sm-12 col-md-4 col-xl-4 mb-3 inputs">
                        <label for="appdeadline">Application Deadline</label>
                        <input type="date" class="form-control" name="appdeadline" id="appdeadline">
                    </div> --}}
                    {{-- <div class="col-sm-12 col-md-4 col-xl-4 mb-3 inputs">
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
                <button type="submit" id="sub" class="formbtn">Save</button>
            </div>
        </form>
    </div>

    <script src="{{ asset('assets/js/form_script.js') }}"></script>

    <script>
        $('#rec_id').on('change',function(){
            var rec = $(this).val();
            // alert(rec);
            $.ajax({
                url:'{{ route("recruit.data") }}',
                type:'POST',
                data:{rec:rec,_token:'{{ csrf_token() }}'},
                success:function(res){
                    //  console.log(res);

                    $('#department').val(res[0].dept);
                    $('#role').val(res[0].role);
                    $('#joblocation').val(res[0].loc);

                    $('#expreq').val(res[0].exp);
                    $('#vacancy').val(res[0].vacancy);

                },
                error:function(error){
                    console.log(error)
                }
            });
        });
    </script>


@endsection
