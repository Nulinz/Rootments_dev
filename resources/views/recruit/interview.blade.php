<div class="empdetails">
    <div class="sidebodyhead">
        <h4 class="m-0">Interview Details</h4>
    </div>
    <div class="mt-3 listtable">
        <div class="filter-container row mb-3">
            <div class="custom-search-container col-sm-12 col-md-8">
                <select class="form-select filter-option" id="headerDropdown2">
                    <option value="All" selected>All</option>
                </select>
                <input type="text" id="filterInput2" class="form-control" placeholder=" Search">
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
            <table class="table table-hover table-striped" id="table2">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Name</th>
                        <th>Email ID</th>
                        <th>Contact Number</th>
                        <th>Rounds</th>
                        {{-- <th>Last</th> --}}
                        {{-- <th>Technical</th>
                        <th>Manager</th> --}}
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($sc_list as $sc)
                    <tr>
                        <td>{{ $loop->iteration}}</td>
                        <td>{{ $sc->name }}</td>
                        <td>{{ $sc->email }}</td>
                        <td>{{ $sc->contact }}</td>
                        {{-- <td>
                            @foreach ($sc->rounds as $r)
                            <div>
                                {{ $r->round }} - {{ $r->status }}<br>
                            </div>
                            @endforeach
                        </td> --}}
                        <td>{{ $sc->rounds->last()->round ?? '' }}-{{ $sc->rounds->last()->status ?? '' }}</td>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                <a href="{{ route('recruit.candidate_profile', ['id' => $sc->id] ) }}"><i class="fas fa-eye"></i></a>

                            </div>
                        </td>


                    </tr>
                     @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- <!-- Update Interview Modal -->
<div class="modal fade" id="updateInterview" tabindex="-1" aria-labelledby="updateInterviewLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title fs-5" id="updateInterviewLabel">Update Interview</h4>
                <button type="button" class="btn-close bg-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form>
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
</div> --}}

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
        initTable('#table2', '#headerDropdown2', '#filterInput2');
    });
</script>
