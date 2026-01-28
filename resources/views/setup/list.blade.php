@extends('layouts.app')

@section('content')

    <div class="sidebodydiv px-5 py-3">
        <div class="sidebodyhead">
            <h4 class="m-0">Store Setup List</h4>
            <a href="{{ route('setup.add') }}"><button class="listbtn">+ Add Store Setup</button></a>
        </div>

        <div class="container-fluid mt-4 listtable">
            <div class="filter-container row mb-3">
                <div class="custom-search-container col-sm-12 col-md-8">
                    <select class="headerDropdown form-select filter-option">
                        <option value="All" selected>All</option>
                    </select>
                    <input type="text" id="customSearch" class="form-control filterInput" placeholder=" Search">
                </div>

                <div class="select1 col-sm-12 col-md-4 mx-auto"></div>
            </div>

            <div class="table-wrapper">
                <table class="example table table-hover table-striped">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Store Name</th>
                            <th>Address</th>
                            <th>City</th>
                            <th>State</th>
                            <th>Pincode</th>
                            <th>Geolocation</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($list as $li)

                        @php
                            $st = $li->status == 'Active' ? 'Pending' : $li->status;
                        @endphp

                        <tr>
                            <td>{{ $loop->iteration}}</td>
                            <td>{{ $li->st_name }}</td>
                            <td>{{ $li->st_add }}</td>
                            <td>{{ $li->st_city }}</td>
                            <td>{{ $li->st_state }}</td>
                            <td>{{ $li->st_pin }}</td>
                            <td>{{ $li->st_loc }}</td>
                            <td>{{ $st }}</td>
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <a href="{{ route('setup.profile',['id'=>$li->id,'tab'=>'details']) }}" data-bs-toggle="tooltip"
                                        data-bs-title="View Profile">
                                        <i class="fa-solid fa-eye"></i>
                                    </a>
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
