<div class="empdetails">
    <div class="sidebodyhead">
        <h4 class="m-0">Salary Details</h4>
    </div>
    <div class="mt-3 listtable">
        <div class="filter-container row mb-3">
            <div class="custom-search-container col-sm-12 col-md-8">
                <select class="form-select filter-option" id="headerDropdown1">
                    <option value="All" selected>All</option>
                </select>
                <input type="text" id="filterInput1" class="form-control" placeholder=" Search">
            </div>

            <div class="select1 col-sm-12 col-md-4 mx-auto">
                <div class="d-flex gap-3">
                    <!--<a href="" id="pdfLink"><img src="{{ asset('assets//images/printer.png') }}" id="print" alt=""-->
                    <!--        height="35px" data-bs-toggle="tooltip" data-bs-title="Print"></a>-->
                    <!--<a href="" id="excelLink"><img src="{{ asset('assets/images/excel.png') }}" id="excel" alt=""-->
                    <!--        height="35px" data-bs-toggle="tooltip" data-bs-title="Excel"></a>-->
                </div>
            </div>
        </div>

        <div class="table-wrapper">
            <table class="table table-hover table-striped" id="table1">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Month</th>
                        <th>Salary</th>
                        <th>Incentives</th>
                        <th>LOP</th>
                        <th>Permissions</th>
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody>
                    {{-- <tr>
                        <td>1</td>
                        <td>December</td>
                        <td>15000</td>
                        <td>1000</td>
                        <td>1</td>
                        <td>1</td>
                        <td>16000</td>
                    </tr> --}}
                </tbody>
            </table>
        </div>
    </div>
</div>

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
                "pageLength": 30,
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
        initTable('#table1', '#headerDropdown1', '#filterInput1');
    });
</script>
