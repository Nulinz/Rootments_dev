<div class="empdetails">
    <div class="sidebodyhead">
        <h4 class="m-0">Store Details</h4>
    </div>
    <div class="listtable mt-3">
        <div class="filter-container row mb-3">
            <div class="custom-search-container col-sm-12 col-md-8">
                <select class="form-select filter-option" id="headerDropdown1">
                    <option value="All" selected>All</option>
                </select>
                <input type="text" id="filterInput1" class="form-control" placeholder=" Search">
            </div>

            <div class="select1 col-sm-12 col-md-4 mx-auto">
                <div class="d-flex gap-3">
                    <!--<a href="" id="pdfLink"><img src="{{ asset('assets/images/printer.png') }}" id="print"-->
                    <!--        alt="" height="35px" data-bs-toggle="tooltip" data-bs-title="Print"></a>-->
                    <!--<a href="" id="excelLink"><img src="{{ asset('assets/images/excel.png') }}" id="excel"-->
                    <!--        alt="" height="35px" data-bs-toggle="tooltip" data-bs-title="Excel"></a>-->
                </div>
            </div>
        </div>

        <div class="table-wrapper">
            <table class="table-hover table-striped table" id="table1">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Employee Code</th>
                        <th>Employee Name</th>
                        <th>Role</th>
                        <!--<th>In-time</th>-->
                        <!--<th>Out-time</th>-->
                        @if (!in_array(auth()->user()->role_id, [12, 66]))
                            <th>Action</th>
                        @endif
                    </tr>
                </thead>
                <tbody>
                    @foreach ($employee as $data)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $data->emp_code }}</td>
                            <td>{{ $data->name }}</td>
                            <td>{{ $data->role }}</td>
                            <!--<td>{{ new DateTime($data->login_time, new DateTimeZone('UTC'))->setTimezone(new DateTimeZone('Asia/Kolkata'))->format('h:i A') }}</td>-->
                            <!--<td>{{ new DateTime($data->logout_time, new DateTimeZone('UTC'))->setTimezone(new DateTimeZone('Asia/Kolkata'))->format('h:i A') }}</td>-->
                            @if (!in_array(auth()->user()->role_id, [12, 66]))
                                <td>
                                    <div class="d-flex gap-3">
                                        <a target="__blank" href="{{ route('store.viewemp', ['id' => $data->userId]) }}" data-bs-toggle="tooltip" data-bs-title="View Profile"><i
                                                class="fas fa-eye"></i></a>
                                    </div>
                                </td>
                            @endif
                        </tr>
                    @endforeach
                </tbody>
            </table>
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
        initTable('#table1', '#headerDropdown1', '#filterInput1');
    });
</script>
