@extends('layouts.app')

<link rel="stylesheet" href="{{ asset('assets/css/profile.css') }}">
@section('content')
    <style>
        .star {
            font-size: 3rem;
        }

        .star-rating {
            direction: rtl;
            /* display: flex; */
            font-size: 1.5rem;
            text-align: end;
            /* padding: 10px; */
            /* margin:0px 10px; */
        }

        .star-rating input[type="radio"] {
            display: none;
        }

        .star-rating label {
            /* color: #9c9797 !important; */
            cursor: pointer;
            transition: color 0.2s;
            margin: 0px 0px;
        }

        .star-rating input[type="radio"]:checked~label,
        .star-rating label:hover,
        .star-rating label:hover~label {
            color: #ffc107;
            /* yellow stars */
        }

        .star-size {
            font-size: 1.5rem !important;
        }
    </style>
    <div class="sidebodydiv mb-4 px-5">
        <div class="sidebodyback my-3" onclick="goBack()">
            <div class="backhead">
                <h5 class="m-0"><i class="fas fa-arrow-left"></i></h5>
                <h6 class="m-0">Employee Profile</h6>
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
                                    <h5 class="mb-0">Basic Details</h5>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-sm-12 col-md-12 col-xl-12 mb-3">
                                    <h6 class="mb-1">Employee Code</h6>
                                    <h5 class="mb-0">{{ $performance->emp_code ?? 'N/A' }}</h5>
                                </div>
                                <div class="col-sm-12 col-md-12 col-xl-12 mb-3">
                                    <h6 class="mb-1">Full Name</h6>
                                    <h5 class="mb-0">{{ $performance->emp_name ?? 'N/A' }}</h5>
                                </div>
                                <div class="col-sm-12 col-md-12 col-xl-12 mb-3">
                                    <h6 class="mb-1">Email ID</h6>
                                    <h5 class="mb-0">{{ $performance->email ?? 'N/A' }}</h5>
                                </div>
                                <div class="col-sm-12 col-md-12 col-xl-12 mb-3">
                                    <h6 class="mb-1">Contact Number</h6>
                                    <h5 class="mb-0">{{ $performance->contact_no ?? 'N/A' }}</h5>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Content -->
            <div class="contentright">
                <div class="empdetails">
                    <div class="cards mt-2">

                        <div class="profdetails mb-2">
                            <div class="maincard row">
                                <div class="cardshead">
                                    <div class="col-12 cardsh5">
                                        <h5 class="mb-0">Performance Details</h5>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-12 col-md-3 col-xl-3 mb-3">
                                        <h6 class="mb-1">SOP Adherence</h6>
                                        <h5 class="mb-0">{{ $performance->sop_adherence }}/5</h5>
                                    </div>
                                    <div class="col-sm-12 col-md-3 col-xl-3 mb-3">
                                        <h6 class="mb-1">Remark</h6>
                                        <h5 class="mb-0">{{ $performance->sop_remark }}</h5>
                                    </div>
                                    <div class="col-sm-12 col-md-3 col-xl-3 mb-3">
                                        <h6 class="mb-1">Damage Control</h6>
                                        <h5 class="mb-0">{{ $performance->damage_control }}/5</h5>
                                    </div>
                                    <div class="col-sm-12 col-md-3 col-xl-3 mb-3">
                                        <h6 class="mb-1">Remark</h6>
                                        <h5 class="mb-0">{{ $performance->damage_remark }}</h5>
                                    </div>
                                    <div class="col-sm-12 col-md-3 col-xl-3 mb-3">
                                        <h6 class="mb-1">Product Quality</h6>
                                        <h5 class="mb-0">{{ $performance->product_quality }}/5</h5>
                                    </div>
                                    <div class="col-sm-12 col-md-3 col-xl-3 mb-3">
                                        <h6 class="mb-1">Remark</h6>
                                        <h5 class="mb-0">{{ $performance->product_remakr }}</h5>
                                    </div>
                                    <div class="col-sm-12 col-md-3 col-xl-3 mb-3">
                                        <h6 class="mb-1">Staff training</h6>
                                        <h5 class="mb-0">{{ $performance->staff_training }}/5</h5>
                                    </div>
                                    <div class="col-sm-12 col-md-3 col-xl-3 mb-3">
                                        <h6 class="mb-1">Remark</h6>
                                        <h5 class="mb-0">{{ $performance->training_remakr }}</h5>
                                    </div>
                                    <div class="col-sm-12 col-md-3 col-xl-3 mb-3">
                                        <h6 class="mb-1">Daily Photos<h6>
                                                <h5 class="mb-0">{{ $performance->daily_photos }}/5</h5>
                                    </div>
                                    <div class="col-sm-12 col-md-3 col-xl-3 mb-3">
                                        <h6 class="mb-1">Remark</h6>
                                        <h5 class="mb-0">{{ $performance->photos_remark }}</h5>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
@endsection
