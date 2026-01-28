@extends('layouts.app')

<link rel="stylesheet" href="{{ asset('assets/css/profile.css') }}">
<link rel="stylesheet" href="{{ asset('assets/css/tasktimeline.css') }}">

@section('content')
    <div class="sidebodydiv px-5 mb-4">
        <div class="sidebodyback my-3" onclick="goBack()">
            <div class="backhead">
                <h5 class="m-0"><i class="fas fa-arrow-left"></i></h5>
                <h6 class="m-0">Candidate Profile</h6>
            </div>
        </div>

        <div class="mainbdy">
            <!-- Left Content -->
            <div class="contentleft">
                <div class="cards mt-2">

                    <div class="basicrounds mb-2">
                        <div class="maincard row">
                            <div class="cardshead">
                                <div class="col-12 cardsh5">
                                    <h5 class="mb-0">Candidate Details</h5>
                                </div>
                            </div>
                            <div class="row mt-2">
                                <div class="col-sm-12 col-md-12 col-xl-12 mb-3">
                                    <h6 class="mb-1">Applied Role</h6>
                                    <h5 class="mb-0">{{ $pro->rl}}</h5>
                                </div>
                                <div class="col-sm-12 col-md-12 col-xl-12 mb-3">
                                    <h6 class="mb-1">Job Location</h6>
                                    <h5 class="mb-0">{{ $pro->loc}}</h5>
                                </div>
                                <div class="col-sm-12 col-md-12 col-xl-12 mb-3">
                                    <h6 class="mb-1">Code</h6>
                                    <h5 class="mb-0">JOB{{ $pro->job_id}}</h5>
                                </div>
                                <div class="col-sm-12 col-md-12 col-xl-12 mb-3">
                                    <h6 class="mb-1">Full Name</h6>
                                    <h5 class="mb-0">{{ $pro->name }}</h5>
                                </div>
                                <div class="col-sm-12 col-md-12 col-xl-12 mb-3">
                                    <h6 class="mb-1">Date Of Birth</h6>
                                    <h5 class="mb-0">{{ date("d-m-Y", strtotime($pro->dob))}}</h5>
                                </div>
                                <div class="col-sm-12 col-md-12 col-xl-12 mb-3">
                                    <h6 class="mb-1">Email ID</h6>
                                    <h5 class="mb-0">{{ $pro->email }}</h5>
                                </div>
                                <div class="col-sm-12 col-md-12 col-xl-12 mb-3">
                                    <h6 class="mb-1">Contact Number</h6>
                                    <h5 class="mb-0">{{ $pro->contact }}</h5>
                                </div>
                                <div class="col-sm-12 col-md-12 col-xl-12 mb-3">
                                    <h6 class="mb-1">Address</h6>
                                    <h5 class="mb-0">{{ $pro->add }}</h5>
                                </div>
                                <div class="col-sm-12 col-md-12 col-xl-12 mb-3">
                                    <h6 class="mb-1">Educational Background</h6>
                                    <h5 class="mb-0">{{ $pro->edu }}</h5>
                                </div>
                                <div class="col-sm-12 col-md-12 col-xl-12 mb-3">
                                    <h6 class="mb-1">Work Experience</h6>
                                    <h5 class="mb-0">{{ $pro->work_exp}}</h5>
                                </div>
                                <div class="col-sm-12 col-md-12 col-xl-12 mb-3">
                                    <h6 class="mb-1">Skills</h6>
                                    <h5 class="mb-0">{{ $pro->skill }}</h5>
                                </div>
                                <div class="col-sm-12 col-md-12 col-xl-12 mb-3">
                                    <h6 class="mb-1">Notice Period</h6>
                                    <h5 class="mb-0">{{ $pro->notice }}</h5>
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
                            <button class="profiletabs active" id="rounds-tab" role="tab" data-bs-toggle="tab" type="button"
                                data-bs-target="#rounds" aria-controls="rounds" aria-selected="true">Rounds</button>
                        </li>
                    </ul>
                </div>

                <div class="tab-content" id="tabContentWrapper">
                    <div class="tab-pane fade show active" id="rounds" role="tabpanel" aria-labelledby="rounds-tab">
                        @include('recruit.candidate_rounds')
                    </div>
                </div>
            </div>
        </div>

    </div>

@endsection
