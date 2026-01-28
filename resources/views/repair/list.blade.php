@extends('layouts.app')

@section('content')

    <div class="sidebodydiv px-5 py-3">
        <div class="sidebodyhead">
            <h4 class="m-0">Maintenance Request List</h4>
            <a href="{{ route('repair.add') }}"><button class="listbtn">+ Add Maintenance Request</button></a>
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
                            <th>Title</th>
                            <th>Category</th>
                            <th>Sub Category</th>
                            <th>Repair Date</th>
                            <th>Repair Description</th>
                            <th>Requested To</th>
                            {{-- <th>File</th> --}}
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($rep as $rp)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $rp->title}}</td>
                            <td>{{ $rp->category}}</td>
                            <td>{{ $rp->subcategory}}</td>
                            <td>{{ date("d-m-Y",strtotime($rp->req_date))}}</td>
                            <td>{{ $rp->desp}}</td>
                            <td>{{ $rp->name }}</td>
                            {{-- <td>
                                <div class="d-flex gap-3">
                                    <a href="" download>
                                        <i class="fas fa-download"></i>
                                    </a>
                                </div>
                            </td> --}}
                            <td>
                               @if ($rp->m_status == 'Approved')
                                        <a href="{{ route('maintain.update', ['id' => $rp->id]) }}"
                                            class="btn btn-sm bg-dark text-white">update</a>
                                    @else
                                        <span>{{ $rp->m_status }}</span>
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
