@extends('layouts.app')

@section('content')
    <div class="sidebodydiv px-5 py-3">
        <div class="sidebodyhead">
            <h4 class="m-0">Recruitment Request List</h4>
            <a href="{{ route('recruitment.add') }}"><button class="listbtn">+ Add Recruit Request</button></a>
        </div>

        <div class="container-fluid mt-4 listtable">
            <div class="filter-container row mb-3">
                <div class="custom-search-container col-sm-12 col-md-8">
                    <select class="headerDropdown form-select filter-option">
                        <option value="All" selected>All</option>
                    </select>
                    <input type="text" id="customSearch" class="form-control filterInput" placeholder=" Search">
                </div>
            </div>

            <div class="table-wrapper">
                <table class="example table table-hover table-striped">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Recruit ID</th>
                            <th>Department</th>
                            <th>Role</th>
                            <th>Vacant Count</th>
                            <th>Recruit Date</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($rec as $rc)


                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>REC{{ $rc->id}}</td>
                            <td>{{ $rc->dept }}</td>
                            <td>{{ $rc->role }}</td>
                            <td>{{ $rc->vacancy }}</td>
                            <td>{{ date("d-m-Y",strtotime($rc->res_date))}}</td>
                            <td>
                                <span class="text-success">{{ $rc->status }}</span>
                            </td>
                        </tr>
                        @endforeach

                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
