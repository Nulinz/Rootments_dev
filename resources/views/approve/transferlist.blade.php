<meta name="csrf-token" content="{{ csrf_token() }}">

<div class="sidebodyhead mt-3">
    <h4 class="m-0">Transfer Approval List</h4>
</div>

<div class="container-fluid mt-3 listtable">
    <div class="filter-container row mb-3">
        <div class="custom-search-container col-sm-12 col-md-8">
            <select class="form-select filter-option" id="headerDropdown3">
                <option value="All" selected>All</option>
            </select>
            <input type="text" id="filterInput3" class="form-control" placeholder=" Search">
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
        <table class="table table-hover table-striped" id="table3">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Employee Code</th>
                    <th>Employee Name</th>
                    <th>From Store</th>
                    <th>To Store</th>
                    <th>Transfer Date</th>
                    <th>Transfer Description</th>
                    <th>Status</th>
                     @php
                        $user_id = Auth::user()->id;
                    
                        $user = DB::table('users')
                            ->leftJoin('roles', 'users.role_id', '=', 'roles.id')
                            ->where('users.id', $user_id)
                            ->select('users.name', 'users.emp_code', 'roles.role', 'roles.role_dept', 'users.role_id')
                            ->first();
                    @endphp
                    
                    @if(optional($user)->role_id != 3)
                        <th>OverAll Status</th>
                    @endif
                </tr>
            </thead>
            <tbody>
                @foreach ($transfer as $data)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $data->emp_code }}</td>
                        <td>{{ $data->name }}</td>
                        <td>{{ $data->from_store_name }}</td>
                        <td>{{ $data->to_store_name }}</td>
                        <td>{{ $data->transfer_date }}</td>
                        <td>{{ $data->transfer_description }}</td>
                        <td>

                            @php
                                $user = auth()->user();
                            @endphp

                            @if ($user->role_id == 12)
                                @if ($data->request_status == 'Approved')
                                    <span class="text-success">Approved</span>
                                    @if ($data->esculate_to == null)
                                        <button class="esulate_button" data-id="{{ $data->id }}">Escalate</button>
                                    @endif
                                @elseif ($data->request_status == 'Rejected')
                                    <span class="text-danger">Rejected</span>
                                @elseif ($data->request_status == 'Pending')
                                    <button class="listtdbtn" data-id="{{ $data->id }}" data-role='12'
                                        data-user="{{ $data->user_id }}" data-bs-toggle="modal"
                                        data-bs-target="#updateTransferApproval">
                                        Update
                                    </button>
                                @endif
                            @elseif ($user->role_id == 3)
                                @if ($data->esculate_status == 'Approved')
                                    <span class="text-success">Approved</span>
                                @elseif ($data->esculate_status == 'Rejected')
                                    <span class="text-danger">Rejected</span>
                                @else
                                    <button class="listtdbtn" data-id="{{ $data->id }}" data-role='3'
                                        data-user="{{ $data->user_id }}" data-bs-toggle="modal"
                                        data-bs-target="#updateTransferApproval">
                                        Update
                                    </button>
                                @endif
                            @endif
                        </td>
                         @if(optional($user)->role_id != 3)
                         <td>
                             
                               @if($data->request_status == 'Rejected')
                               <span class="text-danger">Rejected</span>
                               @elseif($data->status == 'Rejected')
                                <span class="text-danger">Rejected</span>
                                @else
                               <span class="text-success"> {{$data->status}}</span>
                                
                               @endif
                         
                        </td>
                         @endif
                        
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
<!-- Update Approval Modal -->
<div class="modal fade" id="updateTransferApproval" tabindex="-1" aria-labelledby="updateTransferApprovalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title fs-5" id="updateTransferApprovalLabel">Update Transfer Approval</h4>
                <button type="button" class="btn-close bg-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="updatetransferForm">
                    <input type="hidden" id="TransferId" name="id">
                    <input type="hidden" id="UserId" name="user_id"> <!-- Added User ID -->

                    <div class="col-sm-12 col-md-12 mb-3">
                        <label for="sts" class="col-form-label">Status</label>
                        <select class="form-select sts" name="status" id="sts">
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
        initTable('#table3', '#headerDropdown3', '#filterInput3');
    });
</script>

<script>
    $(document).ready(function() {
        $('.listtdbtn').on('click', function() {
            const id = $(this).data('id');
            const userId = $(this).data('user'); // Get user_id from button
            $('#TransferId').val(id);
            $('#UserId').val(userId); // Set user_id in hidden field
        });

        $('#updatetransferForm').on('submit', function(e) {
            e.preventDefault();
            const formData = $(this).serialize();
            console.log(formData);

            $.ajax({
                url: '{{ route('approveltransfer.update') }}',
                type: 'POST',
                data: formData,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    alert(response.message);
                    location.reload();
                },
                error: function(xhr) {
                    alert('An error occurred. Please try again.');
                    console.error(xhr.responseText);
                }
            });
        });
    });
</script>
<script>
    $(document).ready(function() {
        $(".esulate_button").on("click", function() {
            let id = $(this).data("id");

            $.ajax({
                url: "{{ route('update.transferescalate') }}",
                type: "POST",
                data: {
                    id: id,
                    _token: "{{ csrf_token() }}"
                },
                success: function(response) {
                    alert(response.message);
                    location.reload();
                }
            });
        });
    });
</script>
