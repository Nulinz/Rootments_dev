@extends('layouts.app')

@section('content')

    <div class="sidebodydiv px-5 py-3 mb-3">
        <div class="sidebodyback mb-3" onclick="goBack()">
            <div class="backhead">
                <h5><i class="fas fa-arrow-left"></i></h5>
                <h6>Add Task Form</h6>
            </div>
        </div>
        <div class="sidebodyhead my-3">
            <h4 class="m-0">Task Details</h4>
        </div>
        <form action="{{ route('task.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <input type="hidden" class="form-control" name="maintain" value="maintain">
            <input type="hidden" class="form-control" name="m_id" value="{{ $m_id }}">
            <div class="container-fluid maindiv my-3">
                <div class="row">
                    <div class="col-sm-12 col-md-4 col-xl-4 mb-3 inputs">
                        <label for="tasktitle">Task Title <span>*</span></label>
                        <input type="text" class="form-control" name="task_title" id="tasktitle"
                            placeholder="Enter Task Title" autofocus required>
                    </div>
                    <div class="col-sm-12 col-md-4 col-xl-4 mb-3 inputs">
                        <label for="category">Category <span>*</span></label>
                        <select name="category_id" id="category" class="form-select" required>
                            <option value="" selected disabled>Select Options</option>
                            @foreach ($cat as $ct)
                                <option value="{{ $ct->id }}">{{ $ct->category}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-sm-12 col-md-4 col-xl-4 mb-3 inputs">
                        <label for="subcategory">Sub Category <span>*</span></label>
                        <select name="sub_cat[]" id="subcategory" class="form-select" required>
                            <option value="" selected disabled>Select Options</option>

                        </select>
                    </div>

                    <div class="col-sm-12 col-md-4 col-xl-4 mb-3 inputs">
                        <label for="assignto">Assign To <span>*</span></label>
                        <div class="col-sm-12 col-md-12 col-xl-12">
                            <div class="col-sm-12 col-md-12 col-xl-12">
                                <div class="dropdown-center tble-dpd">
                                    <button class="w-100 text-start form-select checkdrp" type="button"
                                        data-bs-toggle="dropdown" id="assignto" aria-expanded="false">
                                        Select Options
                                    </button>
                                    <ul class="dropdown-menu w-100 px-1" id="assignDropdown">
                                        <li>
                                            <div class="d-flex align-items-center w-100 mt-1 mb-2">
                                                <input type="text" class="form-control" id="assignSearch"
                                                    data-dropdown="assignDropdown" placeholder="Search">
                                            </div>
                                        </li>
                                        @foreach ($emp as $em)
                                            <li class="d-flex justify-content-start gap-1 align-items-center">
                                                <input type="checkbox" class="me-2 employee-checkbox role"
                                                    id="emp-{{ $em->id }}" name="assign_to[]" value="{{ $em->id }}">
                                                <label for="emp-{{ $em->id }}">{{ $em->name }} - {{ $em->role }}</label>
                                            </li>
                                        @endforeach
                                    </ul>

                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-sm-12 col-md-4 col-xl-4 mb-3 inputs">
                        <label for="description">Task Description <span>*</span></label>
                        <textarea rows="1" class="form-control" name="task_description" id="description"
                            placeholder="Enter Task Description" required></textarea>
                    </div>
                    <div class="col-sm-12 col-md-4 col-xl-4 mb-3 inputs">
                        <label for="additionalinfo">Additional Information</label>
                        <textarea rows="1" class="form-control" name="additional_info" id="additionalinfo"
                            placeholder="Enter Additional Information"></textarea>
                    </div>
                    <div class="col-sm-12 col-md-4 col-xl-4 mb-3 inputs">
                        <label for="startdate">Start Date <span>*</span></label>
                        <input type="date" class="form-control" pattern="\d{4}-\d{2}-\d{2}" min="1000-01-01"
                            max="9999-12-31" name="start_date" id="start_date" value="{{ date("Y-m-d") }}" required>
                    </div>
                    <div class="col-sm-12 col-md-4 col-xl-4 mb-3 inputs">
                        <label for="time">Start Time <span>*</span></label>
                        <input type="time" class="form-control" name="start_time" value="{{ date("h:i") }}" id="time" required>
                    </div>
                    <div class="col-sm-12 col-md-4 col-xl-4 mb-3 inputs">
                        <label for="enddate">End Date <span>*</span></label>
                        <input type="date" class="form-control" pattern="\d{4}-\d{2}-\d{2}" min="1000-01-01"
                            max="9999-12-31" name="end_date" id="enddatedate" required>
                    </div>
                    <div class="col-sm-12 col-md-4 col-xl-4 mb-3 inputs">
                        <label for="time">End Time <span>*</span></label>
                        <input type="time" class="form-control" name="end_time" id="time" required>
                    </div>
                    <div class="col-sm-12 col-md-4 col-xl-4 mb-3 inputs">
                        <label for="priority">Priority <span>*</span></label>
                        <select class="form-select" name="priority" id="priority" required>
                            <option value="" selected disabled>Select Options</option>
                            <option value="High">High</option>
                            <option value="Medium">Medium</option>
                            <option value="Low">Low</option>
                        </select>
                    </div>
                    <div class="col-sm-12 col-md-4 col-xl-4 mb-3 inputs">
                        <label for="file">File</label>
                        <input type="file" class="form-control" name="task_file" id="file_img">
                    </div>
                </div>
            </div>

            <div class="col-sm-12 col-md-12 col-xl-12 mt-3 d-flex justify-content-center align-items-center">
                <button type="submit" class="formbtn">Save</button>
            </div>
        </form>
    </div>

    <script>
        $(document).ready(function () {
            $('#category').on('change', function () {
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
                        success: function (data) {
                            if (data.length > 0) {
                                $.each(data, function (index, subcategory) {
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
                        error: function () {
                            alert('Failed to fetch subcategories. Please try again.');
                        }
                    });
                }
            });
        });
    </script>

    <script>
        document.getElementById("assignSearch").addEventListener("keyup", function () {
            let filter = this.value.toLowerCase();
            let dropdownId = this.getAttribute("data-dropdown");
            let items = document.querySelectorAll(`#${dropdownId} li:not(:first-child)`);

            items.forEach(item => {
                item.classList.toggle("d-none", !item.textContent.toLowerCase().includes(filter));
            });
        });
    </script>

@endsection