@extends('layouts.app')

<link rel="stylesheet" href="{{ asset('assets/css/profile.css') }}">

@section('content')
    <div class="sidebodydiv mb-4 px-5">
        <div class="sidebodyback my-3" onclick="goBack()">
            <div class="backhead">
                <h5 class="m-0"><i class="fas fa-arrow-left"></i></h5>
                <h6 class="m-0">Store Details</h6>
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
                                    <h5 class="mb-0">Store Details</h5>
                                </div>
                                @if (in_array(auth()->user()->role_id, [1, 2, 3]))
                                    <a class="editicon" href="{{ route('store.edit', ['id' => $store->id]) }}"
                                        data-bs-toggle="tooltip" data-bs-title="Edit Store">
                                        <i class="fa-solid fa-pen-to-square"></i>
                                    </a>
                                @endif
                            </div>
                            <div class="row mt-2">
                                <div class="col-sm-12 col-md-12 col-xl-12 mb-3">
                                    <h6 class="mb-1">Store Code</h6>
                                    <h5 class="mb-0">{{ $store->store_code }}</h5>
                                </div>
                                <div class="col-sm-12 col-md-12 col-xl-12 mb-3">
                                    <h6 class="mb-1">Store Name</h6>
                                    <h5 class="mb-0">{{ $store->store_name }}</h5>
                                </div>
                                <div class="col-sm-12 col-md-12 col-xl-12 mb-3">
                                    <h6 class="mb-1">Email ID</h6>
                                    <h5 class="mb-0">{{ $store->store_mail }}</h5>
                                </div>
                                <div class="col-sm-12 col-md-12 col-xl-12 mb-3">
                                    <h6 class="mb-1">Contact Number</h6>
                                    <h5 class="mb-0">{{ $store->store_contact }}</h5>
                                </div>
                                <div class="col-sm-12 col-md-12 col-xl-12 mb-3">
                                    <h6 class="mb-1">Alternate Contact Number</h6>
                                    <h5 class="mb-0">{{ $store->store_alt_contact }}</h5>
                                </div>
                                <div class="col-sm-12 col-md-12 col-xl-12 mb-3">
                                    <h6 class="mb-1">Store Start Time</h6>
                                    <h5 class="mb-0">
                                        {{ (new DateTime($store->store_start_time))->format('h:i A') }}

                                    </h5>
                                </div>
                                <div class="col-sm-12 col-md-12 col-xl-12 mb-3">
                                    <h6 class="mb-1">Store End Time</h6>
                                    <h5 class="mb-0">

                                        {{ (new DateTime($store->store_end_time))->format('h:i A') }}

                                    </h5>
                                </div>
                                <div class="col-sm-12 col-md-12 col-xl-12 mb-3">
                                    <h6 class="mb-1">Address</h6>
                                    <h5 class="mb-0">{{ $store->store_address }}</h5>
                                </div>
                                <div class="col-sm-12 col-md-12 col-xl-12 mb-3">
                                    <h6 class="mb-1">Pincode</h6>
                                    <h5 class="mb-0">{{ $store->store_pincode }}</h5>
                                </div>
                                <div class="col-sm-12 col-md-12 col-xl-12 mb-3">
                                    <h6 class="mb-1">Store Geolocation</h6>
                                    <h5 class="mb-0">{{ $store->store_geo }}</h5>
                                </div>
                                <div class="col-sm-12 col-md-12 col-xl-12 mb-3">
                                    <h6 class="mb-1">Attendance Leave(%) Per day</h6>
                                    <h5 class="mb-0">{{ $store->leave_per }}</h5>
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
                            <button class="profiletabs active"
                                data-url="{{ route('store.details', ['id' => $store->id]) }}" id="details-tab"
                                role="tab" data-bs-toggle="tab" type="button" data-bs-target="#details"
                                aria-controls="details" aria-selected="true">Details</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="profiletabs" data-url="{{ route('store.strength', ['id' => $store->id]) }}"
                                id="strength-tab" role="tab" data-bs-toggle="tab" type="button"
                                data-bs-target="#strength" aria-controls="strength" aria-selected="false">Strength</button>
                        </li>
                        {{-- <!-- <li class="nav-item" role="presentation">
                            <button class="profiletabs" data-url="{{ route('store.workupdate') }}" id="workupdate-tab"
                                role="tab" data-bs-toggle="tab" type="button" data-bs-target="#workupdate"
                                aria-controls="workupdate" aria-selected="false">Work Update</button>
                        </li> --> --}}
                    </ul>
                </div>

                <div class="tab-content" id="tabContentWrapper">

                    <p>Loading...</p>

                </div>
            </div>
        </div>

    </div>

    </div>

    <script>
        $(document).ready(function() {
            const loadContent = (url) => {
                $("#tabContentWrapper").html('<p>Loading...</p>');
                $.ajax({
                    url: url,
                    type: 'GET',
                    success: function(data) {
                        $("#tabContentWrapper").html(data);
                    },
                    error: function() {
                        $("#tabContentWrapper").html("<p>Error loading content</p>");
                    }
                });
            };

            $(".profiletabs").on("click", function() {
                $(".profiletabs").removeClass("active");
                $(this).addClass("active");

                const url = $(this).data("url");
                loadContent(url);
            });

            $(".profiletabs.active").trigger("click");
        });
    </script>
@endsection
