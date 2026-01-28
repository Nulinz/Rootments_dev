<div class="empdetails">
    <div class="cards mt-3">

        <div class="profdetails mb-2">
            <div class="maincard row">
                <div class="cardshead">
                    <div class="col-12 cardsh5">
                        <h5 class="mb-0">Profile Details</h5>
                    </div>
                    @if(in_array(auth()->user()->role_id, [1, 2, 3]))
                    <a class="editicon" href="{{ route('employee.edit', ['id' => $users->id]) }}"
                        data-bs-toggle="tooltip" data-bs-title="Edit Employee">
                        <i class="fa-solid fa-pen-to-square"></i>
                    </a>
                    @endif
                </div>
                <div class="row">
                    <div class="col-sm-12 col-md-3 col-xl-3 mb-3">
                        <h6 class="mb-1">Date Of Birth</h6>
                        <h5 class="mb-0">{{ \Carbon\Carbon::parse($users->dob)->format('d-m-Y') }}
                        </h5>
                    </div>
                    <div class="col-sm-12 col-md-3 col-xl-3 mb-3">
                        <h6 class="mb-1">Gender</h6>
                        <h5 class="mb-0">{{ $users->gender }}</h5>
                    </div>
                    <div class="col-sm-12 col-md-3 col-xl-3 mb-3">
                        <h6 class="mb-1">Marital Status</h6>
                        <h5 class="mb-0">{{ $users->marital_status }}</h5>
                    </div>
                    <div class="col-sm-12 col-md-3 col-xl-3 mb-3">
                        <h6 class="mb-1">Aadhar Number</h6>
                        <h5 class="mb-0">{{ $users->aadhar_no }}</h5>
                    </div>
                    <div class="col-sm-12 col-md-3 col-xl-3 mb-3">
                        <h6 class="mb-1">Address</h6>
                        <h5 class="mb-0 descp">{{ $users->address }}</h5>
                    </div>
                    <div class="col-sm-12 col-md-3 col-xl-3 mb-3">
                        <h6 class="mb-1">District</h6>
                        <h5 class="mb-0">{{ $users->district }}</h5>
                    </div>
                    <div class="col-sm-12 col-md-3 col-xl-3 mb-3">
                        <h6 class="mb-1">State</h6>
                        <h5 class="mb-0">{{ $users->state }}</h5>
                    </div>
                    <div class="col-sm-12 col-md-3 col-xl-3 mb-3">
                        <h6 class="mb-1">Pincode</h6>
                        <h5 class="mb-0">{{ $users->pincode }}</h5>
                    </div>
                </div>
            </div>
        </div>

        <div class="jobdetails mb-2">
            <div class="maincard row">
                <div class="cardshead">
                    <div class="col-12 cardsh5">
                        <h5 class="mb-0">Qualification Details</h5>
                    </div>
                    @if(in_array(auth()->user()->role_id, [1, 2, 3]))
                    <a class="editicon" href="{{ route('employee.jobedit', ['id' => $users->id]) }}"
                        data-bs-toggle="tooltip" data-bs-title="Edit Job">
                        <i class="fa-solid fa-pen-to-square"></i>
                    </a>
                    @endif
                </div>
                <div class="row">
                    <div class="col-sm-12 col-md-3 col-xl-3 mb-3">
                        <h6 class="mb-1">Qualification</h6>
                        <h5 class="mb-0">{{ $users->qulification }}</h5>
                    </div>
                    <div class="col-sm-12 col-md-3 col-xl-3 mb-3">
                        <h6 class="mb-1">Job Title</h6>
                        <h5 class="mb-0">{{ $users->job_tittle }}</h5>
                    </div>
                    <div class="col-sm-12 col-md-3 col-xl-3 mb-3">
                        <h6 class="mb-1">Job Type</h6>
                        <h5 class="mb-0">{{ $users->job_type }}</h5>
                    </div>
                    <div class="col-sm-12 col-md-3 col-xl-3 mb-3">
                        <h6 class="mb-1">Experience</h6>
                        <h5 class="mb-0">{{ $users->exprience }}</h5>
                    </div>
                    <div class="col-sm-12 col-md-3 col-xl-3 mb-3">
                        <h6 class="mb-1">Joining Date</h6>
                        <h5>{{ $users->pre_start_date ? date('d-m-Y', strtotime($users->pre_start_date)) : 'NA' }}</h5>
                    </div>
                    <div class="col-sm-12 col-md-3 col-xl-3 mb-3">
                        <h6 class="mb-1">Professionally Skilled In</h6>
                        <h5 class="mb-0">{{ $users->pro_skill }}</h5>
                    </div>
                    <div class="col-sm-12 col-md-3 col-xl-3 mb-3">
                        <h6 class="mb-1">In Time</h6>
                        <h5 class="mb-0">{{ $users->st_time }}</h5>
                    </div>
                    <div class="col-sm-12 col-md-3 col-xl-3 mb-3">
                        <h6 class="mb-1">Out Time</h6>
                        <h5 class="mb-0">{{ $users->end_time }}</h5>
                    </div>
                </div>
            </div>
        </div>

        <div class="bankdetails mb-2">
            <div class="maincard row">
                <div class="cardshead">
                    <div class="col-12 cardsh5">
                        <h5 class="mb-0">Bank Details</h5>
                    </div>
                    @if(in_array(auth()->user()->role_id, [1, 2, 3]))
                    <a class="editicon" href="{{ route('employee.bankedit', ['id' => $users->id]) }}"
                        data-bs-toggle="tooltip" data-bs-title="Edit Bank">
                        <i class="fa-solid fa-pen-to-square"></i>
                    </a>
                    @endif
                </div>
                <div class="row">
                    <div class="col-sm-12 col-md-3 col-xl-3 mb-3">
                        <h6 class="mb-1">Bank Name</h6>
                        <h5 class="mb-0">{{ $users->bank_name }}</h5>
                    </div>
                    <div class="col-sm-12 col-md-3 col-xl-3 mb-3">
                        <h6 class="mb-1">Account Holder Name</h6>
                        <h5 class="mb-0">{{ $users->bank_holder_name }}</h5>
                    </div>
                    <div class="col-sm-12 col-md-3 col-xl-3 mb-3">
                        <h6 class="mb-1">Account Number</h6>
                        <h5 class="mb-0">{{ $users->ac_no }}</h5>
                    </div>
                    <div class="col-sm-12 col-md-3 col-xl-3 mb-3">
                        <h6 class="mb-1">IFSC Code</h6>
                        <h5 class="mb-0">{{ $users->ifcs_code }}</h5>
                    </div>
                    <div class="col-sm-12 col-md-3 col-xl-3 mb-3">
                        <h6 class="mb-1">Account Type</h6>
                        <h5 class="mb-0">{{ $users->acount_type }}</h5>
                    </div>
                    <div class="col-sm-12 col-md-3 col-xl-3 mb-3">
                        <h6 class="mb-1">Bank Branch</h6>
                        <h5 class="mb-0">{{ $users->bank_branch }}</h5>
                    </div>
                    <div class="col-sm-12 col-md-3 col-xl-3 mb-3">
                        <h6 class="mb-1">Basic Salary</h6>
                        <h5 class="mb-0">{{ $users->base_salary }}</h5>
                    </div>
                    <div class="col-sm-12 col-md-3 col-xl-3 mb-3">
                        <h6 class="mb-1">Net Salary</h6>
                        <h5 class="mb-0">{{ $users->net_salary }}</h5>
                    </div>
                </div>
            </div>
        </div>

        <div class="docdetails mb-2">
            <div class="maincard row">
                <div class="cardshead">
                    <div class="col-12 cardsh5">
                        <h5 class="mb-0">Document Details</h5>
                    </div>
                    @if(in_array(auth()->user()->role_id, [1, 2, 3]))
                    <a class="editicon" href="{{ route('employee.jobedit', ['id' => $users->id]) }}"
                        data-bs-toggle="tooltip" data-bs-title="Edit Document">
                        <i class="fa-solid fa-pen-to-square"></i>
                    </a>
                    @endif
                </div>
                <div class="row">
                    <div class="col-sm-12 col-md-4 col-xl-4 mb-3">
                        <h6 class="mb-1">Aadhar Card</h6>
                        @if($users->aadhar_img)
                            <img src="{{ asset($users->aadhar_img) }}" height="150px" alt="Aadhar">
                        @else
                            <!--<img src="{{ asset('assets/images/aadhar.png') }}" height="150px" alt="" class="Aadhar">-->
                            <h5 class="mb-1 text-danger">No File Uploaded</h5>
                        @endif

                    </div>
                    <div class="col-sm-12 col-md-4 col-xl-4 mb-3">
                        <h6 class="mb-1">Agreement</h6>
                        @if($users->agreement)
                            <iframe src="{{ asset($users->agreement) }}" width="90%" height="150px"></iframe>
                        @else
                            <!--<img src="{{ asset('assets/images/pdf.png') }}" height="150px" alt="Agreement" class="Agreement">-->
                            <h5 class="mb-1 text-danger">No File Uploaded</h5>
                        @endif
                    </div>
                    <div class="col-sm-12 col-md-4 col-xl-4 mb-3">
                        <h6 class="mb-1">Termination Remarks</h6>
                        <h5 class="mb-0">{{ $users->ter_remarks  }}.</h5>
                    </div>

                </div>
            </div>
        </div>

    </div>
</div>