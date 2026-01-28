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
                <h6 class="m-0">Vendor Profile</h6>
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
                            <div class="row mt-2">
                                <div class="col-sm-12 col-md-12 col-xl-12 mb-3">
                                    <h6 class="mb-1">Vendor Code</h6>
                                    <h5 class="mb-0">{{ $ven_profile->vendor_code }}</h5>
                                </div>
                                <div class="col-sm-12 col-md-12 col-xl-12 mb-3">
                                    <h6 class="mb-1">Vendor Name</h6>
                                    <h5 class="mb-0">{{ $ven_profile->name }}</h5>
                                </div>
                                <div class="col-sm-12 col-md-12 col-xl-12 mb-3">
                                    <h6 class="mb-1">Email ID</h6>
                                    <h5 class="mb-0">{{ $ven_profile->email }}</h5>
                                </div>
                                <div class="col-sm-12 col-md-12 col-xl-12 mb-3">
                                    <h6 class="mb-1">Contact Number</h6>
                                    <h5 class="mb-0">{{ $ven_profile->contact }}</h5>
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
                                        <h5 class="mb-0">Contact and Banking Details</h5>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-sm-12 col-md-3 col-xl-3 mb-3">
                                        <h6 class="mb-1">Opening Balance</h6>
                                        <h5 class="mb-0">{{ $ven_profile->opening_balance }}</h5>
                                    </div>
                                    <div class="col-sm-12 col-md-3 col-xl-3 mb-3">
                                        <h6 class="mb-1">Balance Type</h6>
                                        <h5 class="mb-0">
                                            @if ($ven_profile->balance_type == 'to_pay')
                                                To Pay
                                            @else
                                                To Receive
                                            @endif
                                        </h5>
                                    </div>
                                    <div class="col-sm-12 col-md-3 col-xl-3 mb-3">
                                        <h6 class="mb-1">GSTIN No</h6>
                                        <h5 class="mb-0">{{ $ven_profile->gstin_no }}</h5>
                                    </div>
                                    <div class="col-sm-12 col-md-3 col-xl-3 mb-3">
                                        <h6 class="mb-1">PAN Number</h6>
                                        <h5 class="mb-0">{{ $ven_profile->pan_number }}</h5>
                                    </div>

                                    <div class="col-sm-12 col-md-3 col-xl-3 mb-3">
                                        <h6 class="mb-1">Permanent Address</h6>
                                        <h5 class="mb-0">{{ $ven_profile->permanent_address }}</h5>
                                    </div>
                                    <div class="col-sm-12 col-md-3 col-xl-3 mb-3">
                                        <h6 class="mb-1">Shipping Address</h6>
                                        @if ($ven_profile->permanent_address == $ven_profile->shipping_address)
                                            <h5 class="mb-0">Same Address </h5>
                                        @else
                                            <h5 class="mb-0">{{ $ven_profile->shipping_address }}</h5>
                                        @endif
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
