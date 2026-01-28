    @extends('layouts.app')

    <link rel="stylesheet" href="{{ asset('assets/css/profile.css') }}">

    @section('content')
        <div class="sidebodydiv px-5 py-3">
                <div class="sidebodyhead">
                    <h4 class="m-0">Retirement Request List</h4>
                </div>

                <div class="container-fluid listtable mt-3">
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
                                    <th>Action</th>
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
                                        <td>
                                            <button class="listtdbtn" data-bs-toggle="modal" data-id="{{ $ret->id }}" data-bs-target="#updateApproval">Update</button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="modal fade" id="updateApproval" tabindex="-1" aria-labelledby="updateApprovalLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title fs-5" id="updateApprovalLabel">Update Purchase Order Approval</h4>
                            <button type="button" class="btn-close bg-white" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form action="{{ route('approve.retirement_approve') }}" method="POST" id="">
                                @csrf
                                <input type="hidden" id="ret_id" name="ret_id">
                                <div class="col-sm-12 col-md-12 mb-3">
                                    <label for="sts" class="col-form-label">Status</label>
                                    <select class="form-select sts" name="ret_status" id="sts" required>
                                        <option value="" selected disabled>Select Options</option>
                                        <option value="Approved">Approve</option>
                                        <option value="Rejected">Rejected</option>
                                    </select>
                                </div>
                                <!-- Move the button inside the form -->
                                <div class="d-flex justify-content-center align-items-center mx-auto">
                                    <button type="submit" class="modalbtn btn btn-primary">Update</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

        </div>
        <script>
            $(document).on('click', '.listtdbtn', function() {
                var ret_id = $(this).data('id');
                $('#ret_id').val(ret_id);
            });
        </script>
    @endsection
