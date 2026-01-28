<div class="empdetails">
    <div class="sidebodyhead">
        <h4 class="mb-2">Applied Details</h4>

        <!--<h6 class="m-0" style="font-size: 12px"><a target="__blank" class="copy-link" data-url="{{ route('post_application',['id'=>$list->id]) }}"-->
        <!--        href="{{ route('post_application',['id'=>$list->id]) }}">Post Form Link</a>-->
        <!--</h6>-->
        <h6 class="m-0" style="font-size: 12px">
          <a href="javascript:void(0)" class="copy-link btn btn-sm bg-dark px-4 text-white" style="border-radius: 20px;"
                data-url="{{ route('post_application', ['id' => $list->id]) }}">
                <i class="fa-solid fa-copy"></i> Form Link
            </a>
            </h6>
    </div>
    <div class="mt-3 listtable">
        <div class="filter-container row mb-3">
            <div class="custom-search-container col-sm-12 col-md-8">
                <select class="form-select filter-option" id="headerDropdown1">
                    <option value="All" selected>All</option>
                </select>
                <input type="text" id="filterInput1" class="form-control" placeholder=" Search">
            </div>
        </div>

        <div class="table-wrapper">
            <table class="table table-hover table-striped" id="table1">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Name</th>
                        <th>Email ID</th>
                        <th>Contact Number</th>
                        <th>Experience</th>
                        <th>Skills</th>
                        <th>Location</th>
                        <th>Documents</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($ap_list as $ap)
                    <tr>
                        <td>{{ $loop->iteration}}</td>
                        <td>{{ $ap->name }}</td>
                        <td>{{ $ap->email }}</td>
                        <td>{{ $ap->contact }}</td>
                        <td>{{ $ap->work_exp }}</td>
                        <td>{{ $ap->skill }}</td>
                        <td>{{ $ap->job_location ?? '-'  }}</td>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                @if(!is_null($ap->certify))
                                <a data-bs-toggle="tooltip" href="{{ asset($ap->certify) }}" data-bs-title="Certification"><i
                                        class="fas fa-download" download="{{ basename($ap->certify) }}"></i></a>
                                |
                                @endif
                                @if(!is_null($ap->resume))
                                <a data-bs-toggle="tooltip" href="{{ asset($ap->resume) }}" data-bs-title="Resume"><i
                                        class="fas fa-download" download="{{ basename($ap->resume) }}"></i></a>
                                @endif
                            </div>
                        </td>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                {{-- <a href="{{ route('recruit.candidate_profile', ['id' => $ap->id] ) }}"><i class="fas fa-eye"></i></a> --}}
                                @if($ap->status=='applied')
                                <button class="listtdbtn" data-bs-toggle="modal" data-id="{{ $ap->id }}" data-bs-target="#updateApplied">Update</button>
                                @else
                                   {{ $ap->status}}
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

<!-- Update Applied Modal -->
<div class="modal fade" id="updateApplied" tabindex="-1" aria-labelledby="updateAppliedLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title fs-5" id="updateAppliedLabel">Update Process</h4>
                <button type="button" class="btn-close bg-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="" method="POST" id="updateApplyForm">

                    <input hidden type="text" name="ap_id" id="ap_id">
                    <div class="col-sm-12 col-md-12 mb-3">
                        <label for="sts" class="col-form-label">Status</label>
                        <select class="form-select" name="status">
                            <option value="" selected disabled>Select Options</option>
                            <option value="Screening">Screening</option>
                            {{-- <option value="Interview">Interview</option>
                            <option value="Shortlisted">Shortlisted</option> --}}
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

<script>
    $(document).ready(function () {
        // Set RecruitId value on button click
        $('.listtdbtn').on('click', function () {
            const id = $(this).data('id');
            $('#ap_id').val(id);
        });



        // Handle form submission
        $('#updateApplyForm').on('submit', function (e) {
            e.preventDefault();
            const formData = $(this).serialize();
            // console.log(formData);

            $.ajax({
                url: '{{ route("update_screen") }}',
                type: 'POST',
                data: formData,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function (response) {
                    alert(response.message);
                    location.reload(); // Refresh the page after successful update
                },
                error: function (error) {
                    alert('An error occurred. Please try again.');
                    console.error(error.responseText); // Display detailed error
                }
            });
        });
     });
</script>

<script>
    $(document).ready(function() {
        $('.copy-link').on('click', function(e) {
            e.preventDefault(); // Prevent default anchor behavior
            var link = $(this).data('url'); // Get the link from data attribute

            navigator.clipboard.writeText(link)
                .then(() => alert('Link copied to clipboard!'))
                .catch(err => console.error('Error copying link:', err));
        });
    });
    </script>
