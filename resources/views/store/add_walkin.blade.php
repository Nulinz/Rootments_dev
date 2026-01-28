@extends('layouts.app')

@section('content')
    <div class="sidebodydiv mb-3 px-5 py-3">
        <div class="sidebodyback mb-3" onclick="goBack()">
            <div class="backhead">
                <h5><i class="fas fa-arrow-left"></i></h5>
                <h6>Add Walkin Form</h6>
            </div>
        </div>
        <div class="sidebodyhead my-3">
            <h4 class="m-0">Walkin Details</h4>
        </div>
        <form action="" method="POST" id="c_form">
            @csrf

            <div class="container-fluid maindiv">
                <div class="row">
                    <div class="col-sm-12 col-md-4 col-xl-4 inputs mb-3">
                        <label for="storename">Customer mobile number <span>*</span></label>
                        <input type="text" class="form-control" name="contact" id="cus_mobile"
                            placeholder="Enter Mobile Number" maxlength="10" minlength="10"
                            oninput="this.value = this.value.replace(/[^0-9]/g, '')" required>

                        <!-- Info div -->
                        <small id="mobile_status" class="text-danger fw-bold d-block mt-1"></small>
                    </div>

                    <div class="col-sm-12 col-md-4 col-xl-4 inputs mb-3">
                        <label for="address">Customer Name<span>*</span></label>
                        <input type="text" class="form-control" name="cus_name" id="" placeholder="" required>
                    </div>

                    <!-- Walk-in Status field -->
                    <div class="col-sm-12 col-md-4 col-xl-4 inputs d-none mb-3" id="walkin_status_wrapper">
                        <label for="walk_status">Walk-in Status</label>
                        <select name="walk_status" class="form-select" id="walk_status">
                            <option value="" selected disabled>Select status</option>
                            <!-- Default options (shown when not loss) -->
                            <option value="Rentout" class="default-options">Rentout</option>
                            <option value="Return" class="default-options">Return</option>
                            <option value="Trial" class="default-options">Trial</option>
                            <option value="Reissue" class="default-options">Reissue</option>
                            <option value="Revisit Booking" class="default-options">Revisit Booking</option>
                            <option value="New Walkin" class="default-options">New Walkin</option>
                            
                            <!-- Loss-specific options (shown only when previous status is loss) -->
                            <option value="New Booking" class="loss-options" style="display: none;">New Booking</option>
                            <option value="Revisit Booking" class="loss-options" style="display: none;">Revisit Booking</option>
                            <option value="Revisit Loss" class="loss-options" style="display: none;">Revisit Loss</option>
                            <option value="New Walkin" class="loss-options" style="display: none;">New Walkin</option>

                        </select>
                    </div>

                    <!-- Category field (shown only when NewBooking is selected) -->
                    <div class="col-sm-12 col-md-4 col-xl-4 inputs d-none mb-3" id="category_wrapper">
                        <label for="category">Category <span>*</span></label>
                        <select name="category" class="form-select" id="category">
                            <option value="" selected disabled>Select Category</option>
                        </select>
                    </div>

                    <!-- Subcategory field (shown only when category is selected) -->
                    <div class="col-sm-12 col-md-4 col-xl-4 inputs d-none mb-3" id="subcategory_wrapper">
                        <label for="subcategory">Subcategory <span>*</span></label>
                        <select name="subcategory" class="form-select" id="subcategory">
                            <option value="" selected disabled>Select Subcategory</option>
                        </select>
                    </div>

                    <!-- Remarks field -->
                    <div class="col-sm-12 col-md-4 col-xl-4 inputs d-none mb-3" id="remarks_wrapper">
                        <label for="remarks">Remarks</label>
                        <input type="text" name="remarks" class="form-control" placeholder="Enter any remarks">
                    </div>

                    <div class="col-sm-12 col-md-4 col-xl-4 inputs mb-3">
                        <label for="city">Function Date<span>*</span></label>
                        <input type="date" class="form-control" name="fun_date" id="" placeholder="" required>
                    </div>
                    <div class="col-sm-12 col-md-4 col-xl-4 inputs mb-3">
                        <label for="state">Creating as <span>*</span></label>
                        <select name="c_for" id="" class="form-select">
                            <option value="" disabled selected>Select</option>
                            @foreach ($creating as $c_for)
                                <option value="{{ $c_for->id }}">{{ $c_for->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>

            <div class="col-sm-12 col-md-12 col-xl-12 d-flex justify-content-center align-items-center mt-3">
                <button type="submit" class="formbtn">Save</button>
            </div>
        </form>
    </div>

    <script src="{{ asset('assets/js/form_script.js') }}"></script>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script>
        $(document).ready(function() {
            // Store the customer's loss status globally
            let customerIsLoss = false;

            // Prevent Enter key from submitting the form
            $('#c_form input').on('keypress', function(e) {
                if (e.which === 13) {
                    e.preventDefault();
                    return false;
                }
            });

            // On typing 10-digit mobile number, trigger AJAX
            $('#cus_mobile').on('keyup change', function() {
                let mobile = $(this).val();

                if (mobile.length === 10) {
                    $.ajax({
                        url: "{{ route('store.walkin_check') }}",
                        type: "POST",
                        data: {
                            _token: "{{ csrf_token() }}",
                            contact: mobile
                        },
                        success: function(res) {
                            let statusDiv = $('#mobile_status');
                            
                            // Store the customer's loss status
                            customerIsLoss = res.is_loss;

                            if (res.exists === 1) {
                                // Show status depending on today's repeat or not
                                if (res.repeat_count > 0) {
                                    statusDiv.html("Existing customer. Repeat today: " + res
                                        .repeat_count);
                                } else if (res.is_loss) {
                                    statusDiv.html("Existing customer. You may proceed.");
                                } else {
                                    statusDiv.html("Existing customer. You may proceed.");
                                }

                                statusDiv.removeClass('text-success').addClass('text-danger');
                                $('#walkin_status_wrapper, #remarks_wrapper').removeClass('d-none');

                                // Toggle dropdown options based on loss status
                                toggleWalkStatusOptions(res.is_loss);

                            } else {
                                statusDiv.html("New customer. You may proceed.");
                                statusDiv.removeClass('text-danger').addClass('text-success');
                                $('#walkin_status_wrapper, #remarks_wrapper').addClass('d-none');
                                customerIsLoss = false;
                            }

                            $('input[name="cus_name"]').val(res.name || '');
                        },
                        error: function() {
                            $('#mobile_status').html("Something went wrong.").addClass(
                                'text-danger');
                            $('#walkin_status_wrapper, #remarks_wrapper').addClass('d-none');
                        }
                    });
                } else {
                    $('#mobile_status').html('').removeClass('text-danger text-success');
                    $('#walkin_status_wrapper, #remarks_wrapper').addClass('d-none');
                    // Hide category and subcategory when mobile is cleared
                    $('#category_wrapper, #subcategory_wrapper').addClass('d-none');
                    customerIsLoss = false;
                }
            });

            // Function to toggle walk status options - FIXED VERSION
            function toggleWalkStatusOptions(isLoss) {
                if (isLoss) {
                    // Hide default options, show loss-specific options
                    $('.default-options').hide();
                    $('.loss-options').show();
                } else {
                    // Show default options, hide loss-specific options
                    $('.default-options').show();
                    $('.loss-options').hide();
                }
                // Reset the select value
                $('#walk_status').val('');
            }

            // Handle walk-in status change - IMPROVED VERSION
            $('#walk_status').on('change', function() {
                let selectedStatus = $(this).val();
                
                if (selectedStatus === 'NewBooking') {
                    // Show category dropdown and load categories
                    $('#category_wrapper').removeClass('d-none');
                    loadCategories();
                } else {
                    // Hide category and subcategory dropdowns for all other options
                    $('#category_wrapper, #subcategory_wrapper').addClass('d-none');
                    // Reset dropdowns
                    $('#category').val('');
                    $('#subcategory').val('');
                }
                
                // Note: We don't need to call toggleWalkStatusOptions here anymore
                // because the options are already properly set based on customerIsLoss
            });

            // Handle category change
            $('#category').on('change', function() {
                let categoryId = $(this).val();
                
                if (categoryId) {
                    $('#subcategory_wrapper').removeClass('d-none');
                    loadSubcategories(categoryId);
                } else {
                    $('#subcategory_wrapper').addClass('d-none');
                    $('#subcategory').val('');
                }
            });

            // Function to load categories
            function loadCategories() {
                $.ajax({
                    url: "{{ route('store.get_categories') }}",
                    type: "GET",
                    data: {
                        _token: "{{ csrf_token() }}"
                    },
                    success: function(res) {
                        let categorySelect = $('#category');
                        categorySelect.empty();
                        categorySelect.append('<option value="" selected disabled>Select Category</option>');
                        
                        if (res.categories && res.categories.length > 0) {
                            $.each(res.categories, function(index, category) {
                                categorySelect.append('<option value="' + category.id + '">' + category.cat + '</option>');
                            });
                        }
                    },
                    error: function() {
                        console.log('Error loading categories');
                    }
                });
            }

            // Function to load subcategories
            function loadSubcategories(categoryId) {
                $.ajax({
                    url: "{{ route('store.get_subcategories') }}",
                    type: "GET",
                    data: {
                        _token: "{{ csrf_token() }}",
                        category_id: categoryId
                    },
                    success: function(res) {
                        let subcategorySelect = $('#subcategory');
                        subcategorySelect.empty();
                        subcategorySelect.append('<option value="" selected disabled>Select Subcategory</option>');
                        
                        if (res.subcategories && res.subcategories.length > 0) {
                            $.each(res.subcategories, function(index, subcategory) {
                                subcategorySelect.append('<option value="' + subcategory.id + '">' + subcategory.sub + '</option>');
                            });
                        }
                    },
                    error: function() {
                        console.log('Error loading subcategories');
                    }
                });
            }
        });
    </script>
@endsection