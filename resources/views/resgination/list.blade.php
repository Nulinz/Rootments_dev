@extends('layouts.app')

@section('content')
    <div class="sidebodydiv px-5 py-3">
        <div class="sidebodyhead">
            <h4 class="m-0">Resign Request List</h4>
            <a href="{{ route('resignation.add') }}"><button class="listbtn">+ Add Resign Request</button></a>
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
                    <!--<div class="d-flex gap-3">-->
                    <!--    <a href="" id="pdfLink"><img src="{{ asset('assets/images/printer.png') }}" id="print"-->
                    <!--            alt="" height="28px" data-bs-toggle="tooltip" data-bs-title="Print"></a>-->
                    <!--    <a href="" id="excelLink"><img src="{{ asset('assets/images/excel.png') }}" id="excel"-->
                    <!--            alt="" height="30px" data-bs-toggle="tooltip" data-bs-title="Excel"></a>-->
                    <!--</div>-->
                </div>
            </div>

            <div class="table-wrapper">
                <table class="example table table-hover table-striped">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Employee Code</th>
                            <th>Employee Name</th>
                            <th>Loc</th>
                            {{-- <th>Store Name</th> --}}
                            <th>Resign Req Date</th>
                            <th>Reason</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($resgination as $data)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $data->emp_code }}</td>
                                <td>{{ $data->emp_name }}</td>
                                <td>{{ $data->loc }}</td>
                                {{-- <td>{{ $data->store_name }}</td> --}}
                                <td>{{ date("d-m-Y",strtotime($data->res_date)) }}</td>
                                <td>{{ $data->res_reason }}</td>
                                <td>{{ $data->status }}</td>

                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
