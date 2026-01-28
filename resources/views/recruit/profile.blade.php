@extends('layouts.app')

@section('content')

    <link rel="stylesheet" href="{{ asset('assets/css/profile.css') }}">

    <div class="sidebodydiv px-5 mb-4">
        <div class="sidebodyback my-3" onclick="goBack()">
            <div class="backhead">
                <h5 class="m-0"><i class="fas fa-arrow-left"></i></h5>
                <h6 class="m-0">Job Posting Profile</h6>
            </div>
        </div>

        <div class="mainbdy">
            <!-- Left Content -->
            <div class="contentleft">
                <div class="cards mt-2">

                    <div class="basicdetails mb-2">
                        <div class="maincard row">
                            <div class="cardshead">
                                <div class="col-12 cardsh5">
                                    <h5 class="mb-0">Job Posting Details</h5>
                                </div>
                            </div>
                            <div class="row mt-2">
                                <div class="col-sm-12 col-md-6 col-xl-6 mb-3">
                                    <h6 class="mb-1">Total Applied</h6>
                                    <h3 class="mb-0">{{$total_appiled }}</h3>
                                </div>
                                 <div class="col-sm-12 col-md-6 col-xl-6 mb-3">
                                    <h6 class="mb-1">Total Resume</h6>
                                    <h3 class="mb-0">{{$total_resume }}</h3>
                                </div>
                                <div class="col-sm-12 col-md-12 col-xl-12 mb-3">
                                    <h6 class="mb-1">Recruit ID</h6>
                                    <h5 class="mb-0">REC{{ $list->rec_id }}</h5>
                                </div>
                                <div class="col-sm-12 col-md-12 col-xl-12 mb-3">
                                    <h6 class="mb-1">Job ID</h6>
                                    <h5 class="mb-0">JOB{{ $list->id }}</h5>
                                </div>
                                <div class="col-sm-12 col-md-12 col-xl-12 mb-3">
                                    <h6 class="mb-1">Job Title</h6>
                                    <h5 class="mb-0">{{ $list->job_title }}</h5>
                                </div>
                                <div class="col-sm-12 col-md-12 col-xl-12 mb-3">
                                    <h6 class="mb-1">Department</h6>
                                    <h5 class="mb-0">{{ $list->role_dept }}</h5>
                                </div>
                                <div class="col-sm-12 col-md-12 col-xl-12 mb-3">
                                    <h6 class="mb-1">Role</h6>
                                    <h5 class="mb-0">{{ $list->role }}</h5>
                                </div>
                                <div class="col-sm-12 col-md-12 col-xl-12 mb-3">
                                    <h6 class="mb-1">Responsibilties</h6>
                                    <h5 class="mb-0">{{ $list->responsibility }}</h5>
                                </div>
                                <div class="col-sm-12 col-md-12 col-xl-12 mb-3">
                                    <h6 class="mb-1">Location</h6>
                                    <h5 class="mb-0">{{ $list->loc }}</h5>
                                </div>
                                <div class="col-sm-12 col-md-12 col-xl-12 mb-3">
                                    <h6 class="mb-1">Job Type</h6>
                                    <h5 class="mb-0">{{ $list->job_type }}</h5>
                                </div>
                                <div class="col-sm-12 col-md-12 col-xl-12 mb-3">
                                    <h6 class="mb-1">Job Description</h6>
                                    <h5 class="mb-0">{{ $list->job_desc }}</h5>
                                </div>
                                <div class="col-sm-12 col-md-12 col-xl-12 mb-3">
                                    <h6 class="mb-1">Experience</h6>
                                    <h5 class="mb-0">{{ $list->exp }} years</h5>
                                </div>
                                <div class="col-sm-12 col-md-12 col-xl-12 mb-3">
                                    <h6 class="mb-1">Working Hours</h6>
                                    <h5 class="mb-0">{{ $list->hrs }} hrs</h5>
                                </div>
                                <div class="col-sm-12 col-md-12 col-xl-12 mb-3">
                                    <h6 class="mb-1">Salary Range</h6>
                                    <h5 class="mb-0">{{ $list->salary }}</h5>
                                </div>
                                <div class="col-sm-12 col-md-12 col-xl-12 mb-3">
                                    <h6 class="mb-1">Benefits</h6>
                                    <h5 class="mb-0">{{ $list->benefits }}</h5>
                                </div>
                                <div class="col-sm-12 col-md-12 col-xl-12 mb-3">
                                    <h6 class="mb-1">Posting Date</h6>
                                    <h5 class="mb-0">{{ date("d-m-Y", strtotime($list->created_at)) }}</h5>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>

            <!-- Right Content -->
            <div class="contentright ps-2">
                <div class="proftabs">
                    <ul class="nav nav-tabs d-flex justify-content-start align-items-center gap-md-3 gap-xl-3 border-0"
                        id="myTab" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="profiletabs active" id="details-tab" role="tab" data-bs-toggle="tab"
                                type="button" data-bs-target="#details" aria-controls="details" aria-selected="true">Applied
                                / Screening</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="profiletabs" id="interview-tab" role="tab" data-bs-toggle="tab" type="button"
                                data-bs-target="#interview" aria-controls="interview"
                                aria-selected="false">Interview</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="profiletabs" id="shortlist-tab" role="tab" data-bs-toggle="tab" type="button"
                                data-bs-target="#shortlist" aria-controls="shortlist"
                                aria-selected="false">Shortlisted</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="profiletabs" id="holdlist-tab" role="tab" data-bs-toggle="tab" type="button"
                                data-bs-target="#holdlist" aria-controls="holdlist"
                                aria-selected="false">Hold</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="profiletabs" id="rejectlist-tab" role="tab" data-bs-toggle="tab" type="button"
                                data-bs-target="#rejectlist" aria-controls="rejectlist"
                                aria-selected="false">Rejected</button>
                        </li>
                    </ul>
                </div>

                <div class="tab-content" id="tabContentWrapper">
                    <div class="tab-pane fade show active" id="details" role="tabpanel" aria-labelledby="details-tab">
                        @include('recruit.applied')
                    </div>
                    <div class="tab-pane fade" id="interview" role="tabpanel" aria-labelledby="interview-tab">
                        @include('recruit.interview')
                    </div>
                    <div class="tab-pane fade" id="shortlist" role="tabpanel" aria-labelledby="shortlist-tab">
                        @include('recruit.shortlist')
                    </div>
                    <div class="tab-pane fade" id="holdlist" role="tabpanel" aria-labelledby="holdlist-tab">
                        @include('recruit.holdlist')
                    </div>
                    <div class="tab-pane fade" id="rejectlist" role="tabpanel" aria-labelledby="rejectlist-tab">
                        @include('recruit.rejectlist')
                    </div>
                </div>
            </div>
        </div>

    </div>

@endsection
