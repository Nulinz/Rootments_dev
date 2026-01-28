@extends('layouts.app')

@section('content')
    <div class="sidebodydiv px-5 py-3">
        <div class="sidebodyhead">
            <h4 class="m-0">Transfer Request List</h4>
            <a href="{{ route('transfer.add') }}"><button class="listbtn">+ Add Transfer Request</button></a>
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
                            <th>From Store</th>
                            <th>To Store</th>
                            <th>Transfer Req Date</th>
                            <th>Transfer Description</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($transfer as $data)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $data->emp_code }}</td>
                                <td>{{ $data->emp_name }}</td>
                                <td>{{ $data->from_store_name }}</td>
                                <td>{{ $data->to_store_name }}</td>
                                <td>{{ $data->transfer_date }}</td>
                                <td>{{ $data->transfer_description }}</td>
                                <td>
                                    @if($data->status == 'Approved')
                                    <span class="text-success">Approved</span>
                                    @elseif($data->status == 'Rejected')
                                    <span class="text-danger">Rejected</span>
                                    @else
                                    <span class="text-warning">Pending</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach

                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
