<div class="tab-content" id="myTabContent">

    @php
        // dd($stores);
    @endphp
    <!-- Category Tab -->
    <div class="container px-0">
        <form action="{{ route('insert_asm')}}" method="POST">
            @csrf
            <div class="container-fluid maindiv">
                <div class="row">
                    <div class="col-sm-12 col-md-4 col-xl-4 mb-3 px-2 inputs">
                        <label for="title">Store<span>*</span></label>
                       <select name="store" id="store" class="form-select">

                                @foreach ($stores as $id => $store_name)
                                    <option value="{{$id}}">{{$store_name}}</option>
                                @endforeach
                       </select>
                    </div>
                     <div class="col-sm-12 col-md-4 col-xl-4 mb-3 px-2 inputs">
                        <label for="title">Assitant Manager <span>*</span></label>
                        <select name="asm" id="asm" class="form-select">
                                <option disabled>Select Assitant</option>
                        </select>

                    </div>
                    {{-- <div class="col-sm-12 col-md-4 col-xl-4 mb-3 px-2 inputs">
                        <label for="description">Description</label>
                        <textarea rows="1" class="form-control" name="cat_des" id="description" placeholder="Enter Description"></textarea>
                    </div>  --}}
                </div>
            </div>

            <div class="col-sm-12 col-md-12 col-xl-12 mt-3 d-flex justify-content-center align-items-center">
                <button type="submit" class="formbtn">Save</button>
            </div>
        </form>

        <div class="sidebodyhead mt-4">
            <h4 class="m-0">Assigned Details</h4>
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
                        <a href="" id="pdfLink"><img src="./images/printer.png" id="print" alt=""
                                height="35px" data-bs-toggle="tooltip" data-bs-title="Print"></a>
                        <a href="" id="excelLink"><img src="./images/excel.png" id="excel" alt=""
                                height="35px" data-bs-toggle="tooltip" data-bs-title="Excel"></a>
                    </div>
                </div>
            </div>

            <div class="table-wrapper">
                <table class="table table-hover table-striped" id="table1">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Store</th>
                            <th>ASM</th>

                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($asm_assigned as $item)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $item->store_name }}</td>
                                <td>{{ $item->name }}</td>

                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>

<script>
    $('#store').on('change', function () {
        // Trigger an AJAX request when the page is ready
        $('#asm').empty();
        var store_id = $(this).find('option:selected').val();
        $.ajax({
            url: '{{ route('get_assistant') }}', // Laravel route for the POST request
            type: 'POST',
            data: {
                _token: '{{ csrf_token() }}', // CSRF token for security
                store_id: store_id, // Send the selected store ID
            },

            success: function (response) {
                console.log(response);

                $.each(response, function(index, value) {
                    // Create a new option element
                    var option = '<option value="' + value.id + '">' + value.name + '</option>';

                    // Append the new option to the select dropdown
                    $('#asm').append(option);
                });


            },
            error: function (xhr, status, error) {

                alert('An error occurred: ' + error);
            }
        });
    });
</script>
{{--
<meta name="csrf-token" content="{{ csrf_token() }}"> --}}

{{-- <script>
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
        initTable('#table1', '#headerDropdown1', '#filterInput1');
    });
</script>

<script>
    var updateStatusUrl = "{{ route('update.status') }}";

    var csrf = csrf_token();
    console.log(csrf);

    $(document).ready(function() {
        const csrfToken = $('meta[name="csrf-token"]').attr('content');

        // $(".update-status").on("click", function() {
        //     const row = $(this).closest("tr");
        //     const categoryId = row.data("id"); // Get the ID from the tr
        //     const statusCell = row.find(".status-text");

        //     const url = updateStatusUrl.replace(':id', categoryId);

        //     console.log(url);

        //     $.ajax({
        //         url: updateStatusUrl,
        //         type: "POST",
        //         data: {
        //             _token: csrfToken,
        //             id:categoryId
        //         },
        //         success: function(response) {
        //             if (response.status == 1) {
        //                 statusCell.text("Active");
        //                 statusCell.attr("data-status", 1);
        //             } else {
        //                 statusCell.text("Inactive");
        //                 statusCell.attr("data-status", 2);
        //             }

        //             const Toast = Swal.mixin({
        //                 toast: true,
        //                 position: 'top-end',
        //                 showConfirmButton: false,
        //                 timer: 3000,
        //                 timerProgressBar: true,
        //                 didOpen: (toast) => {
        //                     toast.addEventListener('mouseenter', Swal.stopTimer);
        //                     toast.addEventListener('mouseleave', Swal.resumeTimer);
        //                 },
        //                 customClass: {
        //                     title: 'toast-title'
        //                 }
        //             });

        //             if (response.success) {
        //                 Toast.fire({
        //                     icon: 'success',
        //                     title: response.message
        //                 });
        //                 location.reload();
        //             } else {
        //                 Toast.fire({
        //                     icon: 'error',
        //                     title: response.message
        //                 });
        //             }
        //         },
        //         error: function(xhr, status, error) {
        //             Swal.fire({
        //                 icon: 'error',
        //                 title: 'Error!',
        //                 text: 'Something went wrong. Please try again later.'
        //             });
        //         }
        //     });
        // });
    });
</script> --}}

