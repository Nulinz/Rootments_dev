@extends('layouts.app')

@section('content')

    <div class="sidebodydiv px-5 py-3">
        <div class="sidebodyhead">
            <h4 class="m-0">Job Posting List</h4>
            <a href="{{ route('recruit.add') }}"><button class="listbtn">+ Add Job Posting</button></a>
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
                            <th>Recruit ID</th>
                            <th>Job ID</th>
                            <th>Job Title</th>
                            <th>Department</th>
                            <th>Role</th>
                            <th>Experience</th>
                            <th>Location</th>
                            <th>Salary Range</th>
                            <th>Status</th>

                        </tr>
                    </thead>
                    <tbody>
                        @foreach($list as $lt)
                        <tr>
                            <td>{{ $loop->iteration}}</td>
                            <td>REC{{ $lt->rec_id}}</td>
                            <td>JOB{{ $lt->id}}</td>
                            <td>{{ $lt->job_title}}</td>
                            <td>{{ $lt->dept}}</td>
                            <td>{{ $lt->roll}}</td>
                            <td>{{ $lt->exp}}</td>
                            <td>{{ $lt->loc}}</td>
                            <td>{{ $lt->salary}}</td>
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    @if($lt->jp_status=='Pending')

                                    <button class="listtdbtn"  data-id="{{ $lt->id }}" data-bs-toggle="modal"
                                        data-bs-target="#updateRecruitApproval">
                                        Update
                                    </button>

                                    @else
                                    <a href="{{ route('recruit.edit',['id'=>$lt->id]) }}" data-bs-toggle="tooltip" data-bs-title="Edit">
                                        <i class="fa-solid fa-pen-to-square"></i>
                                    </a>
                                    @endif
                                    @if($lt->jp_status=='Approved' || $lt->jp_status=='Pending')
                                    <a href="{{ route('recruit.profile',['id'=>$lt->id]) }}" data-bs-toggle="tooltip"
                                    data-bs-title="View Profile">
                                    <i class="fa-solid fa-eye"></i>
                                    @endif
                                </a>
                                @php
                            //    echo $en = enc($lt->id);  // Encrypt the ID
                            //     $decryptedValue = dec($en); // Decrypt it back
                            @endphp
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
                    <form action="{{ route('job_post_up') }}" id="updateRecruitForm" method="POST">
                        <input type="hidden" id="job_id" name="job_id">
                        @csrf
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

    <script>
        $('.listtdbtn').on('click',function(){

            let job = $(this).data('id');

            $('#job_id').val(job);

            // console.log(job);

        });
    </script>

@endsection
