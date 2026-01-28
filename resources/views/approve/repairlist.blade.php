@extends('layouts.app')

<link rel="stylesheet" href="{{ asset('assets/css/profile.css') }}">

@section('content')
    <div class="sidebodydiv px-5 py-3">
        <div class="sidebodyhead mt-3">
            <h4 class="m-0">Maintenance Approval List</h4>
        </div>

        <div class="container-fluid listtable mt-3">
            <div class="filter-container row mb-3">
                <div class="custom-search-container col-sm-12 col-md-8">
                    <select class="form-select filter-option" id="headerDropdown2">
                        <option value="All" selected>All</option>
                    </select>
                    <input type="text" id="filterInput2" class="form-control" placeholder=" Search">
                </div>

                <div class="select1 col-sm-12 col-md-4 mx-auto">
                    <!--<div class="d-flex gap-3">-->
                    <!--    <a href="" id="pdfLink"><img src="{{ asset('assets/images/printer.png') }}" id="print" alt=""-->
                    <!--            height="28px" data-bs-toggle="tooltip" data-bs-title="Print"></a>-->
                    <!--    <a href="" id="excelLink"><img src="{{ asset('assets/images/excel.png') }}" id="excel" alt=""-->
                    <!--            height="30px" data-bs-toggle="tooltip" data-bs-title="Excel"></a>-->
                    <!--</div>-->
                </div>
            </div>

            <div class="table-wrapper">
                <table class="table-hover table-striped table" id="table2">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Title</th>
                            <th>Category</th>
                            <th>Sub Category</th>
                            <th>Repair Date</th>
                            <th>Repair Description</th>
                            <th>Request By</th>
                            <th>File</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($repair as $rp)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $rp->title }}</td>
                                <td>{{ $rp->category }}</td>
                                <td>{{ $rp->subcategory }}</td>
                                <td>{{ date('d-m-Y', strtotime($rp->req_date)) }}</td>
                                <td>{{ $rp->desp }}</td>
                                <td>{{ $rp->req_by }}</td>
                                <td>
                                    @if (!is_null($rp->file))
                                        <div class="d-flex gap-3">
                                            <a href="{{ asset($rp->file) }}" download="{{ basename($rp->file) }}">
                                                <i class="fas fa-download"></i>
                                            </a>
                                        </div>
                                    @endif
                                </td>
                                <td>
                                    <button class="listtdbtn" data-bs-toggle="modal" data-id="{{ $rp->rep_id }}" data-bs-target="#updateMaintenanceApproval">Update</button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Update Approval Modal -->
        <div class="modal fade" id="updateMaintenanceApproval" tabindex="-1" aria-labelledby="updateMaintenanceApprovalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title fs-5" id="updateMaintenanceApprovalLabel">Update Maintenance Approval</h4>
                        <button type="button" class="btn-close bg-white" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form action="{{ route('approvelrepair.update') }}" method="POST" id="">
                            @csrf
                            <input type="hidden" id="rep_id" name="rep_id">
                            <div class="col-sm-12 col-md-12 mb-3">
                                <label for="sts" class="col-form-label">Status</label>
                                <select class="form-select sts" name="status" id="sts" required>
                                    <option value="" selected disabled>Select Options</option>
                                    @if (auth()->user()->role_id == 30)
                                        <option value="Approved">Approve</option>
                                    @else
                                        <option value="Escalate">Escalate</option>
                                        <option value="Rejected">Rejected</option>
                                    @endif
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
        $('.listtdbtn').on('click', function() {
            var rep_id = $(this).data('id');

            $('#rep_id').val(rep_id);

        });
    </script>

    <script>
        $(document).ready(function() {
            function initTable(tableId, dropdownId, filterInputId) {
                var table = $(tableId).DataTable({
                    "paging": true,
                    "searching": true,
                    "ordering": true,
                    "order": [0, "asc"],
                    "bDestroy": true,
                    "info": false,
                    "responsive": true,
                    "pageLength": 30,
                    "dom": '<"top"f>rt<"bottom"ilp><"clear">',
                });
                $(tableId + ' thead th').each(function(index) {
                    var headerText = $(this).text();
                    if (headerText != "" && headerText.toLowerCase() != "action") {
                        $(dropdownId).append('<option value="' + index + '">' + headerText + '</option>');
                    }
                });
                $(filterInputId).on('keyup', function() {
                    var selectedColumn = $(dropdownId).val();
                    if (selectedColumn !== 'All') {
                        table.column(selectedColumn).search($(this).val()).draw();
                    } else {
                        table.search($(this).val()).draw();
                    }
                });
                $(dropdownId).on('change', function() {
                    $(filterInputId).val('');
                    table.search('').columns().search('').draw();
                });
                $(filterInputId).on('keyup', function() {
                    table.search($(this).val()).draw();
                });
            }
            // Initialize each table
            initTable('#table2', '#headerDropdown2', '#filterInput2');
        });
    </script>
@endsection
