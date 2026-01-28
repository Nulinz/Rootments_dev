@extends('layouts.app')

@section('content')
    <div class="sidebodydiv px-5 py-3">
        <div class="sidebodyhead">
            <h4 class="m-0">Retirement request List</h4>
            @if (!in_array(auth()->user()->role_id, [1, 2]))
                <a href="{{ route('retirement.add_retire') }}"><button class="listbtn">+ Add Request</button></a>
            @endif
        </div>

        <div class="container-fluid listtable mt-4">
            <div class="filter-container row mb-3">
                <div class="custom-search-container col-sm-12 col-md-8">
                    <select class="headerDropdown form-select filter-option">
                        <option value="All" selected>All</option>
                    </select>
                    <input type="text" id="customSearch" class="form-control filterInput" placeholder=" Search">
                </div>

                <div class="select1 col-sm-12 col-md-4 mx-auto">
                    <div class="d-flex gap-3">
                        <!--<a href="" id="pdfLink"><img src="{{ asset('assets/images/printer.png') }}" id="print"-->
                        <!--        alt="" height="28px" data-bs-toggle="tooltip" data-bs-title="Print"></a>-->
                        <!--<a href="" id="excelLink"><img src="{{ asset('assets/images/excel.png') }}" id="excel"-->
                        <!--        alt="" height="30px" data-bs-toggle="tooltip" data-bs-title="Excel"></a>-->
                    </div>
                </div>
            </div>

            <div class="table-wrapper">
                <table class="example table-hover table-striped table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Employee Code</th>
                            <th>Employee Name</th>
                            <th>Type</th>
                            <th>Request Date</th>
                            <th>Status</th>
                            <th>Created on</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($ret_list as $ret)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $ret->emp_code }}</td>
                                <td>{{ $ret->emp_name }}</td>
                                <td>{{ $ret->req_type }}</td>
                                <td>{{ date('d-m-Y', strtotime($ret->req_date)) }}</td>
                                <td>{{ $ret->status }}</td>
                                <td>{{ date('d-m-Y', strtotime($ret->created_at)) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
