@extends('layouts.app')

@section('content')
    <div class="sidebodydiv px-5 py-3">
        <div class="sidebodyhead">
            <h4 class="m-0">Cluster List</h4>
            <a href="{{ route('cluster.new') }}"><button class="listbtn">+ Add Cluster</button></a>
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
                            <th>Cluster Name</th>
                            <th>Cluster Location</th>
                            <th>Contact Number</th>
                            <th>Email ID</th>
                            <th>Stores Count</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($cluster as $index => $cs)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $cs->name }}</td>
                                <td>{{ $cs->location }}</td>
                                <td>{{ $cs->contact_no }}</td>
                                <td>{{ $cs->email }}</td>
                                <td>{{ $cs->cl_count }}</td>
                                <td>
                                    <div class="d-flex gap-3">
                                        <a href="{{ route('cluster.profile', ['id' => $cs->id]) }}" data-bs-toggle="tooltip"
                                            data-bs-title="View Profile"><i class="fas fa-eye"></i></a>
                                        <a href="{{ route('cluster.edit', ['id' => $cs->id]) }}" data-bs-toggle="tooltip"
                                            data-bs-title="Edit Profile"><i class="fa fa-edit"></i></a>
                                            
                                        <a href="{{ route('cluster.delete', ['id' => $cs->id]) }}" onclick="return confirm('Are you sure you want to Delete Cluster')"
                                            data-bs-toggle="tooltip" data-bs-title="Cluster Delete"><i class="fa-solid fa-trash"></i></a>

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
