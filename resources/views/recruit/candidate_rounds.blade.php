<div class="sidebodyhead mb-3">
    <h4 class="m-0">Rounds</h4>
    <a data-bs-toggle="modal" data-bs-target="#updateModal"><button class="listbtn">+ Add Interview</button></a>
</div>

<div class="container maindiv" style="height: 500px" id="timelinecards">
    <div class="timeline">

        @foreach ($round_list as $rl)


        <div class="entry completed">
            <div class="title">
                <h3>{{ $rl->round}}</h3>
                <h6 class="text-success">{{ $rl->status }}</h6>
            </div>
            <div class="entrybody">
                <div class="taskname">
                    <div class="tasknameleft mb-1">
                        <h6 class="mb-0">{{ $rl->name}}</h6>
                    </div>
                </div>
                <div class="taskdescp mb-2">
                    <h6 class="mb-0">{{ $rl->review }}.</h6>
                </div>
                <div class="taskdate">
                    <h6 class="m-0 startdate"><i class="fa-regular fa-calendar"></i>&nbsp;
                        {{ date("d-m-Y",strtotime($rl->created_at)) }}</h6>
                    </h6>
                </div>
            </div>


            {{-- <div class="entrybody">
                <div class="taskname">
                    <div class="tasknameleft mb-1">
                        <h6 class="mb-0">HR - Abhima</h6>
                    </div>
                </div>
                <div class="taskdescp mb-2">
                    <h6 class="mb-0">Discuss staff responsibilities during the upcoming
                        holiday rush.</h6>
                </div>
                <div class="taskdate">
                    <h6 class="m-0 startdate"><i class="fa-regular fa-calendar"></i>&nbsp;
                        12/12/2024</h6>
                    </h6>
                </div>
            </div> --}}
        </div>
        @endforeach

        {{-- <div class="entry pending">
            <div class="title">
                <h3>Technical</h3>
                <h6 class="text-success">Completed</h6>
            </div>
            <div class="entrybody">
                <div class="taskname">
                    <div class="tasknameleft mb-1">
                        <h6 class="mb-0">Assistant General Manager - Rivas</h6>
                    </div>
                </div>
                <div class="taskdescp mb-2">
                    <h6 class="mb-0">Discuss staff responsibilities during the upcoming
                        holiday rush.</h6>
                </div>
                <div class="taskdate">
                    <h6 class="m-0 startdate"><i class="fa-regular fa-calendar"></i>&nbsp;
                        12/12/2024</h6>
                    </h6>
                </div>
            </div>
        </div>

        <div class="entry">
            <div class="title">
                <h3>Manager</h3>
                <h6 class="text-danger">Pending</h6>
            </div>
            <div class="entrybody">
                <div class="taskname">
                    <div class="tasknameleft mb-1">
                        <h6 class="mb-0">Update Now</h6>
                    </div>
                </div>
            </div>
        </div> --}}

    </div>
</div>

<!-- Update Modal -->
<div class="modal fade" id="updateModal" tabindex="-1" aria-labelledby="updateModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title fs-5" id="updateModalLabel">Update Rounds</h4>
                <button type="button" class="btn-close bg-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('add_round') }}" method="POST" id="c_form">
                    @csrf
                    <input hidden type="text" name="app_id" value="{{ $pro->jobid }}">
                    <div class="col-sm-12 col-md-12 mb-3">
                        <label for="round">Round <span>*</span></label>
                        <select name="round" id="round" class="form-select" required>
                            <option value="" selected disabled>Select Options</option>
                            <option value="HR">HR</option>
                            <option value="Technical">Technical</option>
                            <option value="Manager">Manager</option>
                            <option value="GM or AGM">GM or AGM</option>
                        </select>
                    </div>
                    <div class="col-sm-12 col-md-12 mb-3">
                        <label for="status">Status <span>*</span></label>
                        <select name="pop_status" id="pop_status" class="form-select" required>
                            <option value="" selected disabled>Select Options</option>
                            <option value="Completed">Completed (All Rounds Finished)</option>
                            <option value="Accepted">Accepted (Next Round)</option>
                            <option value="Rejected">Rejected (No Next Round)</option>
                            <option value="Hold">Hold</option>
                        </select>
                    </div>
                    <div class="col-sm-12 col-md-12 mb-3" style="display:none;" id="assignto_div">
                        <label for="assignto">Assign To <span>*</span></label>
                        <select name="assignto" id="assignto" class="form-select "  >
                            <option value="" selected disabled>Select Options</option>
                            @foreach ($assign_to as $at)
                                <option value="{{$at->id}}">{{ $at->name}}</option>

                            @endforeach
                        </select>
                    </div>
                    <div class="col-sm-12 col-md-12 mb-3">
                        <label for="review">Review <span>*</span></label>
                        <textarea rows="3" class="form-control" name="review" id="review" placeholder="Enter Review"
                            required></textarea>
                    </div>
                    <div class="d-flex justify-content-center align-items-center mx-auto">
                        <button type="submit" id="sub" class="modalbtn">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<script src="{{ asset('assets/js/form_script.js') }}"></script>

<script>
    $('#pop_status').on('change',function(){
        var status = $(this).find('option:selected').val();
      if(status==='Accepted'){
            $('#assignto_div').show();
      }else{
        $('#assignto_div').hide();
      }
    });
</script>
