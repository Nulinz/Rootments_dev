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
                <h6 class="m-0">Product Profile</h6>
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
                                    <h6 class="mb-1">Product Code</h6>
                                    <h5 class="mb-0">{{ $pro_profile->product_code }}</h5>
                                </div>
                                <div class="col-sm-12 col-md-12 col-xl-12 mb-3">
                                    <h6 class="mb-1">Vendor Code</h6>
                                    <h5 class="mb-0">{{ $pro_profile->product_name }}</h5>
                                </div>
                                <div class="col-sm-12 col-md-12 col-xl-12 mb-3">
                                    <h6 class="mb-1">Color</h6>
                                    <h5 class="mb-0">{{ $pro_profile->color }}</h5>
                                </div>
                                <div class="col-sm-12 col-md-12 col-xl-12 mb-3">
                                    <h6 class="mb-1">Measuring Unit</h6>
                                    <h5 class="mb-0">{{ $pro_profile->measuring_unit }}</h5>
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
                                        <h5 class="mb-0">Price and Tax Details</h5>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-sm-12 col-md-3 col-xl-3 mb-3">
                                        <h6 class="mb-1">Selling Price</h6>
                                        <h5 class="mb-0">{{ $pro_profile->selling_price }}</h5>
                                    </div>
                                    <div class="col-sm-12 col-md-3 col-xl-3 mb-3">
                                        <h6 class="mb-1">Selling Price Type</h6>
                                        <h5 class="mb-0">{{ $pro_profile->selling_price_type }}</h5>
                                    </div>
                                    <div class="col-sm-12 col-md-3 col-xl-3 mb-3">
                                        <h6 class="mb-1">Selling GST</h6>
                                        <h5 class="mb-0">{{ $pro_profile->selling_gst_rate }}</h5>
                                    </div>
                                    <div class="col-sm-12 col-md-3 col-xl-3 mb-3">
                                        <h6 class="mb-1">Opening Stock</h6>
                                        <h5 class="mb-0">{{ $pro_profile->opening_stock }}</h5>
                                    </div>

                                    <div class="col-sm-12 col-md-3 col-xl-3 mb-3">
                                        <h6 class="mb-1">Purchase Price</h6>
                                        <h5 class="mb-0">{{ $pro_profile->purchase_price }}</h5>
                                    </div>
                                    <div class="col-sm-12 col-md-3 col-xl-3 mb-3">
                                        <h6 class="mb-1">Purchase Price Type</h6>
                                        <h5 class="mb-0">{{ $pro_profile->purchase_price_type }}</h5>
                                    </div>
                                    <div class="col-sm-12 col-md-3 col-xl-3 mb-3">
                                        <h6 class="mb-1">Purchase GST</h6>
                                        <h5 class="mb-0">{{ $pro_profile->purchase_gst_rate }}</h5>
                                    </div>
                                    <div class="col-sm-12 col-md-3 col-xl-3 mb-3">
                                        <h6 class="mb-1">Attachment</h6>
                                        @if ($pro_profile->file_path)
                                            <h5 class="mb-0"><a href="{{ asset('assets/images/Task/' . $pro_profile->file_path) }}" download>{{ $pro_profile->file_path }}</a></h5>
                                        @else
                                            No File
                                        @endif
                                    </div>
                                    <div class="col-sm-12 col-md-3 col-xl-3 mb-3">
                                        <h6 class="mb-1">Description</h6>
                                        <h5 class="mb-0">{{ $pro_profile->description }}</h5>
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
