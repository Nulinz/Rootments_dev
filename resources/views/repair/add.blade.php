@extends('layouts.app')

@section('content')
    <div class="sidebodydiv px-5 py-3 mb-3">
        <div class="sidebodyback mb-3" onclick="goBack()">
            <div class="backhead">
                <h5><i class="fas fa-arrow-left"></i></h5>
                <h6>Add Maintenance Request Form</h6>
            </div>
        </div>
        <div class="sidebodyhead my-3">
            <h4 class="m-0">Maintenance Request Details</h4>
        </div>
        <form action="{{ route('repair.store') }}" method="POST" enctype="multipart/form-data" id="c_form">
            @csrf
            <div class="container-fluid maindiv my-3">
                <div class="row">
                    <div class="col-sm-12 col-md-4 col-xl-4 mb-3 inputs">
                        <label for="title">Title <span>*</span></label>
                        <input type="text" class="form-control" name="title" id="title" placeholder="Enter Title" autofocus
                            required>
                    </div>
                    <div class="col-sm-12 col-md-4 col-xl-4 mb-3 inputs">
                        <label for="category">Category <span>*</span></label>
                        <select class="form-select" name="category" id="category" required>
                            <option value="" selected disabled>Select Options</option>
                            @foreach ($cat as $cs)
                            <option value="{{ $cs->id }}">{{ $cs->category}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-sm-12 col-md-4 col-xl-4 mb-3 inputs">
                        <label for="subcategory">Sub Category <span>*</span></label>
                        <select class="form-select" name="subcategory" id="subcategory" required>
                            <option value="" selected disabled>Select Options</option>
                        </select>

                    </div>
                    <div class="col-sm-12 col-md-4 col-xl-4 mb-3 inputs">
                        <label for="repairdate">Repair Request Date <span>*</span></label>
                        <input type="date" class="form-control" pattern="\d{4}-\d{2}-\d{2}" min="1000-01-01"
                            max="9999-12-31" name="repair_date" id="repairdate" required>
                    </div>
                    <div class="col-sm-12 col-md-4 col-xl-4 mb-3 inputs">
                        <label for="reason">Repair Description <span>*</span></label>
                        <textarea rows="1" class="form-control" name="desp" id="reason"
                            placeholder="Enter Repair Description" required></textarea>
                    </div>
                    <div class="col-sm-12 col-md-4 col-xl-4 mb-3 inputs">
                        <label for="file">File Attachment</label>
                        <input type="file" class="form-control" name="repair_file" id="file">
                    </div>
                    <div class="col-sm-12 col-md-4 col-xl-4 mb-3 inputs">
                        <label for="requestto">Request To <span>*</span></label>
                        <select class="form-select" name="request_to" id="request_to" autofocus required>
                            <option value="" selected disabled>Select Options</option>
                            @foreach ($req_to as $req)
                                <option value="{{ $req->id }}">{{ $req->name}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>

            <div class="col-sm-12 col-md-12 col-xl-12 mt-3 d-flex justify-content-center align-items-center">
                <button type="submit" id="sub" class="formbtn">Request</button>
            </div>
        </form>
    </div>

    <script src="{{ asset('assets/js/form_script.js') }}"></script>

    <script>
        $(document).ready(function() {
            $('#category').on('change', function() {
                var categoryId = $(this).val();
                var subcategorySelect = $('#subcategory');

                subcategorySelect.html('<option value="">Select Subcategory</option>');

                if (categoryId) {
                    $.ajax({
                        url: '{{ route('get_sub_cat') }}',
                        type: 'POST',
                        data: {
                            category_id: categoryId,
                            _token: '{{ csrf_token() }}'
                        },
                        success: function(data) {
                            if (data.length > 0) {
                                $.each(data, function(index, subcategory) {
                                    subcategorySelect.append(
                                        $('<option></option>')
                                        .val(subcategory.id)
                                        .text(subcategory.subcategory)
                                    );
                                });
                            } else {
                                subcategorySelect.append(
                                    '<option value="">No Subcategories Available</option>'
                                );
                            }
                        },
                        error: function() {
                            alert('Failed to fetch subcategories. Please try again.');
                        }
                    });
                }
            });
        });
    </script>

@endsection
