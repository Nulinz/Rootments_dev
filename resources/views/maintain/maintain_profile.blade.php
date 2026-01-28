@extends('layouts.app')

@section('content')

    <link rel="stylesheet" href="{{ asset('assets/css/profile.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/tasktimeline.css') }}">

    <div class="sidebodydiv px-5 mb-4">
        <div class="sidebodyback my-3" onclick="goBack()">
            <div class="backhead">
                <h5 class="m-0"><i class="fas fa-arrow-left"></i></h5>
                <h6 class="m-0">Maintenance Profile</h6>
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
                                    <h5 class="mb-0">Maintenance Details</h5>
                                </div>
                            </div>
                            <div class="row mt-2">
                                <div class="col-sm-12 col-md-12 col-xl-12 mb-3">
                                    <h6 class="mb-1">Title</h6>
                                    <h5 class="mb-0">{{ $rep[0]->title }}</h5>
                                </div>
                                <div class="col-sm-12 col-md-12 col-xl-12 mb-3">
                                    <h6 class="mb-1">Category</h6>
                                    <h5 class="mb-0">{{ $rep[0]->category }}</h5>
                                </div>
                                <div class="col-sm-12 col-md-12 col-xl-12 mb-3">
                                    <h6 class="mb-1">Sub Category</h6>
                                    <h5 class="mb-0">{{ $rep[0]->subcategory }}.</h5>
                                </div>
                                <div class="col-sm-12 col-md-12 col-xl-12 mb-3">
                                    <h6 class="mb-1">Repair Date</h6>
                                    <h5 class="mb-0">{{ date("d-m-Y",strtotime($rep[0]->req_date)) }}</h5>
                                </div>
                                <div class="col-sm-12 col-md-12 col-xl-12 mb-3">
                                    <h6 class="mb-1">Repair Description</h6>
                                    <h5 class="mb-0">{{ $rep[0]->desp }}.
                                    </h5>
                                </div>
                                <div class="col-sm-12 col-md-12 col-xl-12 mb-3">
                                    <h6 class="mb-1">Created To</h6>
                                    <h5 class="mb-0">{{ $rep[0]->name }}</h5>
                                </div>
                                <div class="col-sm-12 col-md-12 col-xl-12 mb-3">
                                    <h6 class="mb-1">File Attachment</h6>
                                    <h5 class="mb-0">
                                        @if(!is_null($rep[0]->file))
                                        <a href="{{ asset($rep[0]->file) }}" download="{{ basename($rep[0]->file) }}">
                                            <i class="fas fa-download"></i>
                                        </a>
                                        @endif
                                    </h5>
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

                {{-- @dd($rep_task); --}}

                <div class="tab-content" id="tabContentWrapper">
                    <div class="tab-pane fade show active" id="details" role="tabpanel" aria-labelledby="details-tab">
                        @include('maintain.timeline')
                    </div>
                </div>
            </div>
        </div>

    </div>

@endsection
