@extends('layouts.app')

@section('content')

    <div class="sidebodydiv px-5 py-3">
        <div class="sidebodyhead">
            <h4 class="m-0">Maintenance List</h4>
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
                            <th>Store</th>
                            <th>Title</th>
                            <th>Category</th>
                            <th>Sub Category</th>
                            <th>Repair Date</th>
                            <th>Created</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($rep as $rp)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $rp->store_name }}</td>
                            <td>{{ $rp->title}}</td>
                            <td>{{ $rp->category}}</td>
                            <td>{{ $rp->subcategory}}</td>
                            <td>{{ date("d-m-Y",strtotime($rp->req_date))}}</td>
                            <td>{{ $rp->name }}</td>
                             <td>{{ $rp->t_status ?? 'Not Started' }}</td>
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <a href="{{ route('maintain.profile',['id'=> $rp->m_id ]) }}" data-bs-toggle="tooltip" data-bs-title="View Profile">
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
