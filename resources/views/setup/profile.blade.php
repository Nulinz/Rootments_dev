@extends('layouts.app')

@section('content')

    <link rel="stylesheet" href="{{ asset('assets/css/profile.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/tasktimeline.css') }}">

    <div class="sidebodydiv px-5 mb-4">
        <div class="sidebodyback my-3" onclick="goBack()">
            <div class="backhead">
                <h5 class="m-0"><i class="fas fa-arrow-left"></i></h5>
                <h6 class="m-0">Store Setup Profile</h6>
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
                                    <h5 class="mb-0">Setup Details</h5>
                                </div>
                            </div>
                            <div class="row mt-2">
                                <div class="col-sm-12 col-md-12 col-xl-12 mb-3">
                                    <h6 class="mb-1">Store Name</h6>
                                    <h5 class="mb-0">{{ $pro->st_name }}</h5>
                                </div>
                                <div class="col-sm-12 col-md-12 col-xl-12 mb-3">
                                    <h6 class="mb-1">Address</h6>
                                    <h5 class="mb-0">{{ $pro->st_add }}</h5>
                                </div>
                                <div class="col-sm-12 col-md-12 col-xl-12 mb-3">
                                    <h6 class="mb-1">City</h6>
                                    <h5 class="mb-0">{{ $pro->st_city }}</h5>
                                </div>
                                <div class="col-sm-12 col-md-12 col-xl-12 mb-3">
                                    <h6 class="mb-1">State</h6>
                                    <h5 class="mb-0">{{ $pro->st_state }}</h5>
                                </div>
                                <div class="col-sm-12 col-md-12 col-xl-12 mb-3">
                                    <h6 class="mb-1">Pincode</h6>
                                    <h5 class="mb-0">{{ $pro->st_pin }}</h5>
                                </div>
                                <div class="col-sm-12 col-md-12 col-xl-12 mb-3">
                                    <h6 class="mb-1">Geolocation</h6>
                                    <h5 class="mb-0">{{ $pro->st_loc }}</h5>
                                </div>
                                @if(auth()->user()->role_id == 3)
                                    <div class="col-sm-12 col-md-12 col-xl-12 mt-5 mb-3">
                                        <h6 class="mb-1">Status</h6>
                                        @if($pro->status != 'Complete')
                                            <a href="{{ route('liststore.new', ['id' => $pro->id]) }}">
                                                <button class="formbtn">Complete</button>
                                            </a>
                                        @else
                                            <button class="formbtn">{{ $pro->status }}</button>
                                        @endif
                                    </div>
                                @endif

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
                    <div class="tab-pane fade {{ $tab == 'details' ? 'show active' : '' }}" id="details" role="tabpanel"
                        aria-labelledby="details-tab">
                        @if($tab == 'details')
                            @include('setup.timeline', ['set_id' => $pro->id])
                        @endif

                    </div>
                </div>
            </div>
        </div>

    </div>

@endsection