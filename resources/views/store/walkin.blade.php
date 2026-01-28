    @extends('layouts.app')

    @section('content')
        <div class="sidebodydiv px-5 py-3">
            <div class="sidebodyhead">
                <h4 class="m-0">Walk-In List</h4>
                @if (auth()->user()->id != 1 && auth()->user()->role_id != 66)
                    <a href="{{ route('store.add_walkin') }}"><button class="listbtn">+ Add Walkin</button></a>
                @endif
            </div>

            <div class="container-fluid listtable mt-4">
                <div class="filter-container row mb-3">
                    <div class="custom-search-container col-sm-12 col-md-8">
                        <select class="headerDropdown form-select filter-option">
                            <option value="All" selected>All</option>
                        </select>
                        <input type="text" id="customSearch" class="form-control filterInput" placeholder=" Search">
                    </div>

                    <div class="select1 col-sm-12 col-md-4 mx-auto"></div>
                </div>

                <div class="table-wrapper">
                    <table class="taskTable table-hover table-striped table">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Date</th>
                                <th>Customer Name</th>
                                <th>Contact</th>
                                <th>Function Date</th>
                                <th>Store</th>
                                <th>Staff</th>
                                <th>Category</th>
                                <th>Sub Category</th>
                                <th width="20%">Remarks</th>
                                <th>Repeat Count</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($walkin as $wl)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ date('d-m-Y', strtotime($wl->created_at)) }}</td>
                                    <td>{{ $wl->name }}</td>
                                    <td>{{ $wl->contact }}</td>
                                    <td>{{ date('d-m-Y', strtotime($wl->f_date)) }}</td>
                                    <td>{{ $wl->store_name }}</td>
                                    <td>{{ $wl->emp_name ?? 'nill' }}</td>
                                    <td>{{ $wl->cat ?? '-' }}</td>
                                    <td>{{ $wl->sub ?? '-' }}</td>
                                    <td>{{ $wl->remark ?? '-' }}</td>
                                    <td>{{ $wl->repeat_count ?? '-' }}</td>
                                    <td>
                                        @if (empty($wl->walk_status) || $wl->walk_status == 'New Walkin')
                                            <button class="listtdbtn mb-1" data-bs-toggle="modal" data-bs-target="#extPopup" data-taskid="{{ $wl->id }}">Update
                                            </button>
                                        @else
                                            {{ $wl->walk_status }}
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

        </div>

        <!-- task extend -->
        <div class="modal fade" id="extPopup" tabindex="-1" aria-labelledby="extPopupLabel" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title fs-5" id="extPopupLabel">Update Status</h4>
                        <button type="button" class="btn-close bg-white" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form method="POST" action="{{ route('store.walkin_status_update') }}">
                        @csrf

                        <input type="hidden" class="form-control" name="walk_id" id="show_walk_id">

                        <div class="modal-body">
                            <div class="row">
                                <div class="col-sm-12 col-md-12 mb-2">
                                    <label for="enddate">Stauts Type</label>
                                    <select class="form-select" id="status_type" name="status">
                                        <option value="" selected disabled>Select status</option>
                                        <option value="Booked">Booked</option>
                                        <option value="Booking & Rentout">Booking & Rentout</option>
                                        <option value="Loss">Loss</option>
                                        <!--<option value="Other">Other</option>-->
                                    </select>
                                </div>
                                <div class="col-sm-12 col-md-12 mb-2">
                                    <label for="category_dropdown">Category</label>
                                    <select name="cat" id="category_dropdown" name="cat" class="form-select">
                                        <option value="" selected disabled>Select category</option>
                                    </select>
                                </div>

                                <div class="col-sm-12 col-md-12 mb-2">
                                    <label for="subcategory_dropdown">Sub Category</label>
                                    <select name="sub_cat" id="subcategory_dropdown" name="sub" class="form-select">
                                        <option value="" selected disabled>Select subcategory</option>
                                    </select>
                                </div>

                                <div class="col-sm-12 col-md-12 mb-2">
                                    <label for="">Remarks</label>
                                    <textarea class="form-control" name="remarks" cols="2"></textarea>
                                </div>
                            </div>
                            <div class="modal-footer d-flex justify-content-center align-items-center pb-1">
                                <button type="submit" class="modalbtn">Save</button>
                            </div>
                    </form>
                </div>
            </div>
        </div>

        <script>
            // For Extend Date Modal
            const extPopup = document.getElementById('extPopup');

            extPopup.addEventListener('show.bs.modal', function(event) {
                const button = event.relatedTarget;
                const taskId = button.getAttribute('data-taskid'); // should be data-taskid
                document.getElementById('show_walk_id').value = taskId;
            });
        </script>
        <script>
            $(document).ready(function() {

                // Initialize DataTable
                var table = $('.taskTable').DataTable({
                    paging: true,
                    searching: true,
                    ordering: true,
                    bDestroy: true,
                    info: false,
                    responsive: true,
                    pageLength: 10,
                    dom: '<"top"f>rt<"bottom"lp><"clear">',
                });

                $('#enddate1, #enddate2').on('change', function() {
                    table.draw();
                });
                // table.column(2).search(value, true, false).draw();

                // search
                $('#customSearch').on('keyup', function() {
                    table.search(this.value).draw();
                });

                // filter   
                $('.headerDropdown').on('change', function() {
                    const value = this.value === "All" ? "" : '^' + this.value + '$';
                    table.column(2).search(value, true, false).draw(); // true = regex
                });
            });
        </script>
        <script>
            $(document).ready(function() {
                let categorySubMap = {};

                $('#status_type').on('change', function() {
                    var selectedType = $(this).val();

                    $.ajax({
                        url: "{{ route('store.walkin_status') }}",
                        method: 'POST',
                        data: {
                            _token: '{{ csrf_token() }}',
                            type: selectedType
                        },
                        success: function(res) {
                            // Save to JS object
                            categorySubMap = res;

                            // Reset dropdowns
                            $('#category_dropdown').empty().append('<option value="" disabled selected>Select category</option>');
                            $('#subcategory_dropdown').empty().append('<option value="" disabled selected>Select subcategory</option>');

                            // Fill category dropdown
                            for (let cat in categorySubMap) {
                                $('#category_dropdown').append(`<option value="${cat}">${cat}</option>`);
                            }
                        },
                        error: function(xhr, status, error) {
                            console.error("AJAX Error:", xhr.responseText);
                            alert('AJAX failed: ' + error);
                        }
                    });
                });

                // When category is selected, populate subcategories
                $('#category_dropdown').on('change', function() {
                    const selectedCat = $(this).val();
                    const subList = categorySubMap[selectedCat] || [];

                    $('#subcategory_dropdown').empty().append('<option value="" disabled selected>Select subcategory</option>');
                    subList.forEach(function(sub) {
                        $('#subcategory_dropdown').append(`<option value="${sub}">${sub}</option>`);
                    });
                });
            });
        </script>
    @endsection
