<div class="empdetails">
    <div class="sidebodyhead">
        <h4 class="m-0">Shortlisted Details</h4>
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
                    <!--<a href="" id="pdfLink"><img src="{{ asset('assets/images/printer.png') }}" id="print" alt=""-->
                    <!--        height="35px" data-bs-toggle="tooltip" data-bs-title="Print"></a>-->
                    <!--<a href="" id="excelLink"><img src="{{ asset('assets/images/excel.png') }}" id="excel" alt=""-->
                    <!--        height="35px" data-bs-toggle="tooltip" data-bs-title="Excel"></a>-->
                </div>
            </div>
        </div>

        <div class="table-wrapper">
            <table class="table table-hover table-striped" id="table3">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Name</th>
                        <th>Email ID</th>
                        <th>Contact Number</th>
                        <th>Experience</th>
                        <th>Skills</th>
                        <th>Education</th>
                    </tr>
                </thead>
                <tbody>
                    @if(count($short) > 0)
                    @foreach($short as $st)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $st->name }}</td>
                        <td>{{ $st->email }}</td>
                        <td>{{ $st->contact }}</td>
                        <td>{{ $st->work_exp }}</td>
                        <td>{{ $st->skill }}</td>
                        <td>{{ $st->edu }}</td>
                    </tr>
                    @endforeach
                @endif
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Update Applied Modal -->
<div class="modal fade" id="updateShortlist" tabindex="-1" aria-labelledby="updateShortlistLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title fs-5" id="updateShortlistLabel">Update Process</h4>
                <button type="button" class="btn-close bg-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form>
                    <input type="hidden" id="RecruitId" name="id">
                    <div class="col-sm-12 col-md-12 mb-3">
                        <label for="sts" class="col-form-label">Status</label>
                        <select class="form-select" name="status">
                            <option value="" selected disabled>Select Options</option>
                            <option value="Screening">Screening</option>
                            <option value="Interview">Interview</option>
                            <option value="Shortlisted">Shortlisted</option>
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
                "pageLength": 10,
                "dom": '<"top"f>rt<"bottom"ilp><"clear">',
            });

            // console.log(table); // Debugging the DataTable object


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
