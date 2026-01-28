@extends('layouts.app')

@section('content')

    <link rel="stylesheet" href="{{ asset('assets/css/profile.css') }}">

    <div class="sidebodydiv px-5 mb-4">
        <div class="sidebodyback my-3" onclick="goBack()">
            <div class="backhead">
                <h5 class="m-0"><i class="fas fa-arrow-left"></i></h5>
                <h6 class="m-0">Area Manager Details</h6>
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
                                    <h5 class="mb-0">Area Manager Details</h5>
                                </div>
                                <!--<a class="editicon" href="{{ route('area.edit') }}" data-bs-toggle="tooltip"-->
                                <!--    data-bs-title="Edit Cluster">-->
                                <!--    <i class="fa-solid fa-pen-to-square"></i>-->
                                <!--</a>-->
                            </div>
                            <div
                                class="col-sm-12 col-md-12 col-xl-12 mb-3 d-flex justify-content-center align-items-center">
                                <img src="{{ asset($area->profile_image ?? 'assets/images/avatar.png') }}" width="125px" height="125px" alt=""
                                    class="profileimg">
                            </div>
                            <div class="row mt-2">
                                <div class="col-sm-12 col-md-12 col-xl-12 mb-3">
                                    <h6 class="mb-1">Area Manager Name</h6>
                                    <h5 class="mb-0">{{ $area->name }}</h5>
                                </div>
                                <div class="col-sm-12 col-md-12 col-xl-12 mb-3">
                                    <h6 class="mb-1">Email ID</h6>
                                    <h5 class="mb-0">{{ $area->email }}</h5>
                                </div>
                                <div class="col-sm-12 col-md-12 col-xl-12 mb-3">
                                    <h6 class="mb-1">Contact Number</h6>
                                    <h5 class="mb-0">{{ $area->contact_no }}</h5>
                                </div>
                                <div class="col-sm-12 col-md-12 col-xl-12 mb-3">
                                    <h6 class="mb-1">Alternate Contact Number</h6>
                                    <h5 class="mb-0">{{ $area->name }}</h5>
                                </div>
                                <div class="col-sm-12 col-md-12 col-xl-12 mb-3">
                                    <h6 class="mb-1">Address</h6>
                                    <h5 class="mb-0">{{ $area->address }}</h5>
                                </div>
                                <div class="col-sm-12 col-md-12 col-xl-12 mb-3">
                                    <h6 class="mb-1">Pincode</h6>
                                    <h5 class="mb-0">{{ $area->pincode }}</h5>
                                </div>
                                <div class="col-sm-12 col-md-12 col-xl-12 mb-3">
                                    <h6 class="mb-1">Area Manager Geolocation</h6>
                                    <h5 class="mb-0">{{ $area->location }}</h5>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>

            <!-- Right Content -->
            <div class="contentright">
                <div class="proftabs">
                    <ul class="nav nav-tabs d-flex justify-content-start align-items-center gap-md-3 gap-xl-3" id="myTab"
                        role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="profiletabs active" id="store-tab" role="tab" data-bs-toggle="tab" type="button"
                                data-bs-target="#store" aria-controls="store" aria-selected="true">Cluster
                                List</button>
                        </li>
                    </ul>
                </div>

                <div class="tab-content" id="myTabContent">
                    <div class="tab-pane fade show active" id="store" role="tabpanel" aria-labelledby="store-tab">
                        <div class="empdetails">
                            <div class="sidebodyhead">
                                <h4 class="m-0">Cluster List Details</h4>
                            </div>
                            <div class="mt-3 listtable">
                                <div class="filter-container row mb-3">
                                    <div class="custom-search-container col-sm-12 col-md-8">
                                        <select class="form-select filter-option" id="headerDropdown1">
                                            <option value="All" selected>All</option>
                                        </select>
                                        <input type="text" id="filterInput1" class="form-control" placeholder=" Search">
                                    </div>

                                    <div class="select1 col-sm-12 col-md-4 mx-auto">
                                        <div class="d-flex gap-3">
                                            <a href="" id="pdfLink"><img src="{{ asset('assets//images/printer.png') }}"
                                                    id="print" alt="" height="35px" data-bs-toggle="tooltip"
                                                    data-bs-title="Print"></a>
                                            <a href="" id="excelLink"><img src="{{ asset('assets/images/excel.png') }}"
                                                    id="excel" alt="" height="35px" data-bs-toggle="tooltip"
                                                    data-bs-title="Excel"></a>
                                        </div>
                                    </div>
                                </div>

                                <div class="table-wrapper">
                                    <table class="table table-hover table-striped" id="table1">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Cluster Name</th>
                                                <th>Location</th>
                                                <th>Contact Number</th>
                                                <th>EmailID</th>
                                                <th>Stores Count</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($list as $ls)
                                            <tr>
                                                <td>{{$loop->iteration}}</td>
                                                <td>{{$ls->name}}</td>
                                                <td>{{$ls->location}}</td>
                                                <td>{{$ls->contact_no}}</td>
                                                <td>{{$ls->email}}</td>
                                                <td>{{$ls->cluster_store_count}}</td>
                                                <td>
                                                    <div class="d-flex gap-3">
                                                        <a href="{{ route('cluster.profile', ['id' => $ls->id]) }}" data-bs-toggle="tooltip"
                                                            data-bs-title="View Profile"><i class="fas fa-eye"></i></a>
                                                    </div>
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function () {
            function initTable(tableId, dropdownId, filterInputId) {
                var table = $(tableId).DataTable({
                    "paging": true,
                    "searching": true,
                    "ordering": true,
                    "order": [0, "asc"],
                    "bDestroy": true,
                    "info": false,
                    "responsive": true,
                    "pageLength": 30,
                    "dom": '<"top"f>rt<"bottom"ilp><"clear">',
                });
                $(tableId + ' thead th').each(function (index) {
                    var headerText = $(this).text();
                    if (headerText != "" && headerText.toLowerCase() != "action") {
                        $(dropdownId).append('<option value="' + index + '">' + headerText + '</option>');
                    }
                });
                $(filterInputId).on('keyup', function () {
                    var selectedColumn = $(dropdownId).val();
                    if (selectedColumn !== 'All') {
                        table.column(selectedColumn).search($(this).val()).draw();
                    } else {
                        table.search($(this).val()).draw();
                    }
                });
                $(dropdownId).on('change', function () {
                    $(filterInputId).val('');
                    table.search('').columns().search('').draw();
                });
                $(filterInputId).on('keyup', function () {
                    table.search($(this).val()).draw();
                });
            }
            // Initialize each table
            initTable('#table1', '#headerDropdown1', '#filterInput1');
        });
    </script>

@endsection
