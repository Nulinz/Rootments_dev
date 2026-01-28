@extends('layouts.app')

@section('content')

    <link rel="stylesheet" href="{{ asset('assets/css/profile.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/tasktimeline.css') }}">

    <div class="sidebodydiv px-5 mb-4">
        <div class="sidebodyback my-3" onclick="goBack()">
            <div class="backhead">
                <h5 class="m-0"><i class="fas fa-arrow-left"></i></h5>
                <h6 class="m-0">Resignation Profile</h6>
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
                                    <h5 class="mb-0">Resignation Details</h5>
                                </div>
                            </div>
                            <div class="row mt-2">
                                <div class="col-sm-12 col-md-12 col-xl-12 mb-3">
                                    <h6 class="mb-1">Resign ID</h6>
                                    <h5 class="mb-0">RES{{ $pro->res_id }}</h5>
                                </div>
                                <div class="col-sm-12 col-md-12 col-xl-12 mb-3">
                                    <h6 class="mb-1">Employee Code</h6>
                                    <h5 class="mb-0">{{ $pro->emp_code }}</h5>
                                </div>
                                <div class="col-sm-12 col-md-12 col-xl-12 mb-3">
                                    <h6 class="mb-1">Employee Name</h6>
                                    <h5 class="mb-0">{{ $pro->name}}</h5>
                                </div>
                                <div class="col-sm-12 col-md-12 col-xl-12 mb-3">
                                    <h6 class="mb-1">Department</h6>
                                    <h5 class="mb-0">{{ $pro->role_dept}}</h5>
                                </div>
                                <div class="col-sm-12 col-md-12 col-xl-12 mb-3">
                                    <h6 class="mb-1">Role</h6>
                                    <h5 class="mb-0">{{ $pro->role}}</h5>
                                </div>
                                <div class="col-sm-12 col-md-12 col-xl-12 mb-3">
                                    <h6 class="mb-1">Location</h6>
                                    <h5 class="mb-0">{{ $pro->loc}}</h5>
                                </div>
                                <div class="col-sm-12 col-md-12 col-xl-12 mb-3">
                                    <h6 class="mb-1">Reason For Leaving</h6>
                                    <h5 class="mb-0">{{ $pro->res_reason}}.</h5>
                                </div>
                                <div class="col-sm-12 col-md-12 col-xl-12 mb-3">
                                    <h6 class="mb-1">Resign Req Date</h6>
                                    <h5 class="mb-0">{{ date("d-m-Y",strtotime($pro->res_date))}}</h5>
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
                                type="button" data-bs-target="#details" aria-controls="details"
                                aria-selected="true">Details</button>
                        </li>
                    </ul>
                </div>

                <div class="tab-content" id="tabContentWrapper">
                    <div class="tab-pane fade show active" id="details" role="tabpanel" aria-labelledby="details-tab">
                        @include('resign.timeline')
                    </div>
                </div>
            </div>
        </div>

    </div>

@endsection
