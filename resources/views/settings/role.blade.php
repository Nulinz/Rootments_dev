<div class="tab-content" id="myTabContent">

    <!-- Role -->
    <div class="container px-0">
        <form action="{{ route('role.store') }}" method="POST">
            @csrf
            <div class="container-fluid maindiv">
                <div class="row">

                    <div class="col-sm-12 col-md-4 col-xl-4 mb-3 px-2 inputs">
                        <label for="department">Department <span>*</span></label>
                        <select class="form-select" name="role_dept" id="department" required>
                            <option value="" selected disabled>Select Options</option>
                            <option value="Admin">Admin</option>
                            <option value="HR">HR</option>
                            <option value="Operation">Operation</option>
                            <option value="Finance">Finance</option>
                            <option value="IT">IT</option>
                            <option value="Sales/Marketing">Sales/Marketing</option>
                            <option value="Area">Area</option>
                            <option value="Cluster">Cluster</option>
                            <option value="Store">Store</option>
                            <option value="Warehouse">Warehouse</option>
                            <option value="Maintenance">Maintenance</option>
                            <!--<option value="Repair">Repair</option>-->
                            <!--<option value="Warehouse">Warehouse</option>-->
                            <!--<option value="Employee">Employee</option>-->
                        </select>
                    </div>
                    <div class="col-sm-12 col-md-4 col-xl-4 mb-3 px-2 inputs">
                        <label for="roletitle">Role Desgination <span>*</span></label>
                        <input type="text" class="form-control" name="role" id="roletitle"
                            placeholder="Enter Role Desgination" required>
                    </div>
                    <div class="col-sm-12 col-md-4 col-xl-4 mb-3 px-2 inputs">
                        <label for="roledescription">Description</label>
                        <textarea rows="1" class="form-control" name="role_des" id="roledescription"
                            placeholder="Enter Description"></textarea>
                    </div>
                </div>
            </div>

            <div class="col-sm-12 col-md-12 col-xl-12 mt-3 d-flex justify-content-center align-items-center">
                <button type="submit" class="formbtn">Save</button>
            </div>
        </form>

        <div class="sidebodyhead mt-4">
            <h4 class="m-0">Role Details</h4>
        </div>
        <div class="mt-3 listtable">
            <div class="filter-container row mb-3">
                <div class="custom-search-container col-sm-12 col-md-8">
                    <select class="form-select filter-option" id="headerDropdown3">
                        <option value="All" selected>All</option>
                    </select>
                    <input type="text" id="filterInput3" class="form-control" placeholder=" Search">
                </div>

                <div class="select1 col-sm-12 col-md-4 mx-auto">
                    <div class="d-flex gap-3">
                        <a href="" id="pdfLink"><img src="{{ asset('assets/images/printer.png') }}" id="print" alt=""
                                height="35px" data-bs-toggle="tooltip" data-bs-title="Print"></a>
                        <a href="" id="excelLink"><img src="{{ asset('assets/images/excel.png') }}" id="excel" alt=""
                                height="35px" data-bs-toggle="tooltip" data-bs-title="Excel"></a>
                    </div>
                </div>
            </div>

            <div class="table-wrapper">
                <table class="table table-hover table-striped" id="table3">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Department</th>
                            <th>Role</th>
                            <th>Description</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($role as $item)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $item->role_dept }}</td>
                                <td>{{ $item->role }}</td>
                                <td>{{ $item->role_des }}</td>
                                <td>
                                    @if ($item->status == 1)
                                        Active
                                    @elseif ($item->status == 2)
                                        Inactive
                                    @endif
                                </td>
                                <td>
                                    <div class="d-flex gap-3">
                                        @if ($item->status == 1)
                                            <a href="javascript:void(0)" class="update-status" data-id="{{ $item->id }}">
                                                <i class="fas fa-circle-xmark text-danger"></i>
                                            </a>
                                        @elseif ($item->status == 2)
                                            <a href="javascript:void(0)" class="update-status" data-id="{{ $item->id }}">
                                                <i class="fas fa-check-circle text-success"></i>

                                            </a>
                                        @endif
                                    </div>
                                </td>

                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>

<meta name="csrf-token" content="{{ csrf_token() }}">

<script>
    $(document).ready(function () {
        function initTable(tableId, dropdownId, filterInputId) {
            var table = $(tableId).DataTable({
                "paging": true,
                "searching": true,
                "ordering": true,
                "order": [0, "asc"],
                "bDestroy": true,
                "info": false,
                "responsive": true,
                "dom": '<"top"f>rt<"bottom"ilp><"clear">',
            });
            $(tableId + ' thead th').each(function (index) {
                var headerText = $(this).text();
                if (headerText != "" && headerText.toLowerCase() != "action") {
                    $(dropdownId).append('<option value="' + index + '">' + headerText + '</option>');
                }
            });
            $(filterInputId).on('keyup', function () {
                var selectedColumn = $(dropdownId).val();
                if (selectedColumn !== 'All') {
                    table.column(selectedColumn).search($(this).val()).draw();
                } else {
                    table.search($(this).val()).draw();
                }
            });
            $(dropdownId).on('change', function () {
                $(filterInputId).val('');
                table.search('').columns().search('').draw();
            });
            $(filterInputId).on('keyup', function () {
                table.search($(this).val()).draw();
            });
        }
        // Initialize each table
        initTable('#table3', '#headerDropdown3', '#filterInput3');
    });
</script>

<script>
    var updateStatusUrl = "{{ route('roleupdate.status', ':id') }}";

    $(document).ready(function () {
        const csrfToken = $('meta[name="csrf-token"]').attr('content');

        $(".update-status").on("click", function () {
            const categoryId = $(this).data("id");
            const statusCell = $(this).closest("tr").find(".status-text");

            const url = updateStatusUrl.replace(':id', categoryId);

            $.ajax({
                url: url,
                type: "POST",
                data: {
                    _token: csrfToken
                },
                success: function (response) {
                    if (response.status == 1) {
                        statusCell.text("Active");
                        statusCell.attr("data-status", 1);
                    } else {
                        statusCell.text("Inactive");
                        statusCell.attr("data-status", 2);
                    }

                    const Toast = Swal.mixin({
                        toast: true,
                        position: 'top-end',
                        showConfirmButton: false,
                        timer: 3000,
                        timerProgressBar: true,
                        didOpen: (toast) => {
                            toast.addEventListener('mouseenter', Swal
                                .stopTimer);
                            toast.addEventListener('mouseleave', Swal
                                .resumeTimer);
                        },
                        customClass: {
                            title: 'toast-title'
                        }
                    });

                    if (response.success) {
                        Toast.fire({
                            icon: 'success',
                            title: response.message
                        });
                        location.reload();
                    } else {
                        Toast.fire({
                            icon: 'error',
                            title: response.message
                        });
                    }
                },
                error: function (xhr, status, error) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error!',
                        text: 'Something went wrong. Please try again later.'
                    });
                }
            });
        });
    });
</script>