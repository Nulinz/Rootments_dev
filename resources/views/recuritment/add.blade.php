@extends('layouts.app')

@section('content')
    <div class="sidebodydiv px-5 py-3 mb-3">
    <div class="sidebodyback mb-3" onclick="goBack()">
            <div class="backhead">
                <h5><i class="fas fa-arrow-left"></i></h5>
                <h6>Add Recruit Request Form</h6>
            </div>
        </div>
        <div class="sidebodyhead my-3">
            <h4 class="m-0">Recruit Request Details</h4>
        </div>
        <form action="{{ route('recruitment.store') }}" method="post" id="c_form">
            @csrf
            <div class="container-fluid maindiv my-3">
                <div class="row">
                    <div class="col-sm-12 col-md-4 col-xl-4 mb-3 inputs">
                        <label for="department">Department <span>*</span></label>
                        <select class="form-select " name="department" id="department" autofocus required>
                             @foreach ($dept as $item)
                                <option value="{{ $item->role_dept }}">{{ $item->role_dept }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-sm-12 col-md-4 col-xl-4 mb-3 inputs">
                        <label for="role">Role <span>*</span></label>
                        <select class="form-select " name="role" id="role" required>
                            <option value="" selected disabled>Select Options</option>
                        </select>
                    </div>
                    <div class="col-sm-12 col-md-4 col-xl-4 mb-3 inputs">
                        <label for="location">Location <span>*</span></label>
                        <input class="form-control " name="location" id="location" placeholder="Enter Location">
                    </div>
                    <div class="col-sm-12 col-md-4 col-xl-4 mb-3 inputs">
                        <label for="repairdate">Recruit Date <span>*</span></label>
                        <input type="date" class="form-control" pattern="\d{4}-\d{2}-\d{2}" min="1000-01-01"
                            max="9999-12-31" name="res_date" id="repairdate" required>
                    </div>
                    <div class="col-sm-12 col-md-4 col-xl-4 mb-3 inputs">
                        <label for="vacancy">Vacancy <span>*</span></label>
                        <input type="number" class="form-control" name="vacancy" id="vacancy" placeholder="Enter Vacancy"
                            required>
                    </div>
                    <div class="col-sm-12 col-md-4 col-xl-4 mb-3 inputs">
                        <label for="experience">Experience (In Years) <span>*</span></label>
                        <input type="text" class="form-control" name="experience" id="experience"
                            placeholder="Enter Experience" required>
                    </div>
                    <div class="col-sm-12 col-md-4 col-xl-4 mb-3 inputs">
                        <label for="recruitdescp">Recruit Description</label>
                        <textarea rows="1" class="form-control" name="recruitdescp" id="recruitdescp"
                            placeholder="Enter Recruit Description"></textarea>
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
        $(document).ready(function () {
            $('#department').change(function () {
                var department = $(this).val();
                $('.store-section').hide().find('input, select').prop('required', false);
                if (department === 'Store') {
                    $('.store-section').show().find('input, select').prop('required', true);
                }
            });
        });
    </script>
    <script>
          $(document).ready(function() {
            function loadRoles(dept) {
                if (!dept) return;

                $.ajax({
                    url: "{{ route('recruitment.role') }}",
                    type: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        dept: dept
                    },
                    success: function(res) {
                        $('#role').empty();
                        $.each(res, function(index, value) {
                            $('#role').append('<option value="' + value.id + '">' + value.role + '</option>');
                        });
                    },
                    error: function(error) {
                        console.log(error);
                    }
                });
            }

            // Trigger on change
            $('#department').on('change', function() {
                loadRoles($(this).val());
            });

            // Trigger automatically if a value is already selected (like “Store”)
            let initialDept = $('#department').val();
            if (initialDept) {
                loadRoles(initialDept);
            }
        });
    </script>

@endsection
