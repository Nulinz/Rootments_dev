@extends('layouts.app')

@section('content')

    <div class="sidebodydiv px-5 py-3">
        <div class="sidebodyhead">
            <h4 class="m-0">Area Manager List</h4>
            <a href="{{ route('area.add') }}"><button class="listbtn">+ Add Area</button></a>
        </div>

        <div class="container-fluid mt-4 listtable">
            <div class="filter-container row mb-3">
                <div class="custom-search-container col-sm-12 col-md-8">
                    <select class="headerDropdown form-select filter-option">
                        <option value="All" selected>All</option>
                    </select>
                    <input type="text" id="customSearch" class="form-control filterInput" placeholder=" Search">
                </div>

                <div class="select1 col-sm-12 col-md-4 mx-auto">
                    <div class="d-flex gap-3">
                        <!--<a href="" id="pdfLink"><img src="{{ asset('assets/images/printer.png') }}" id="print" alt=""-->
                        <!--        height="28px" data-bs-toggle="tooltip" data-bs-title="Print"></a>-->
                        <!--<a href="" id="excelLink"><img src="{{ asset('assets/images/excel.png') }}" id="excel" alt=""-->
                        <!--        height="30px" data-bs-toggle="tooltip" data-bs-title="Excel"></a>-->
                    </div>
                </div>
            </div>

            <div class="table-wrapper">
                <table class="example table table-hover table-striped">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Area Manager Name</th>
                            <th>Area Manager Location</th>
                            <th>Contact Number</th>
                            <th>Email ID</th>
                            <th>Clusters Count</th>
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
                            <td>{{$ls->cluster_count}}</td>
                            <td>
                                <div class="d-flex gap-3">
                                   <a href="{{ route('area.profile', ['id' => $ls->id]) }}" data-bs-toggle="tooltip" data-bs-title="View Profile"><i class="fas fa-eye"></i></a>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

@endsection
