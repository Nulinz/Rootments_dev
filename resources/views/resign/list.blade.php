@extends('layouts.app')

@section('content')

    <div class="sidebodydiv px-5 py-3">
        <div class="sidebodyhead">
            <h4 class="m-0">Resignation List</h4>
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
                            <th>Resign ID</th>
                            <th>Employee Code</th>
                            <th>Employee Name</th>
                            <th>Department</th>
                            <th>Role</th>
                            <th>Location</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($list as $lt)
                        <tr>
                            <td>{{ $loop->iteration}}</td>
                            <td>RES{{ $lt->res_id }}</td>
                            <td>{{ $lt->emp_code}}</td>
                            <td>{{ $lt->name }}</td>
                            <td>{{ $lt->role_dept }}</td>
                            <td>{{ $lt->role }}</td>
                            <td>{{ $lt->loc }}</td>
                            <td><span class="text-danger">{{ $lt->for_status }} - {{ $lt->status }}</span></td>
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <a href="{{ route('resign.profile',['id'=> $lt->res_id]) }}" data-bs-toggle="tooltip"
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


    <!-- Update Approval Modal -->
    <div class="modal fade" id="updateRecruitApproval" tabindex="-1" aria-labelledby="updateRecruitApprovalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title fs-5" id="updateRecruitApprovalLabel">Update Job Posting</h4>
                    <button type="button" class="btn-close bg-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="updateRecruitForm">
                        <input type="hidden" id="RecruitId" name="id">
                        <div class="col-sm-12 col-md-12 mb-3">
                            <label for="sts" class="col-form-label">Status</label>
                            <select class="form-select" name="status">
                                <option value="" selected disabled>Select Options</option>
                                <option value="Approved">Approved</option>
                                <option value="Rejected">Rejected</option>
                            </select>
                        </div>
                        <div class="d-flex justify-content-center align-items-center mx-auto">
                            <button type="submit" class="modalbtn">Update</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

@endsection
