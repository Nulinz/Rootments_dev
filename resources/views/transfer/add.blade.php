@extends('layouts.app')

@section('content')
    <div class="sidebodydiv px-5 py-3 mb-3">
        <div class="sidebodyback mb-3" onclick="goBack()">
            <div class="backhead">
                <h5><i class="fas fa-arrow-left"></i></h5>
                <h6>Add Transfer Request Form</h6>
            </div>
        </div>
        <div class="sidebodyhead my-3">
            <h4 class="m-0">Transfer Request Details</h4>
        </div>
        <form action="{{ route('transfer.store') }}" method="post">
            @csrf
            <div class="container-fluid maindiv my-3">
                <div class="row">
                    <div class="col-sm-12 col-md-4 col-xl-4 mb-3 inputs">
                        <label for="empcode">Employee Code <span>*</span></label>
                        <select class="form-select" name="emp_id" id="empcode" autofocus required>
                            <option value="" selected disabled>Select Options</option>

                        </select>
                    </div>
                    <div class="col-sm-12 col-md-4 col-xl-4 mb-3 inputs">
                        <label for="empname">Employee Name <span>*</span></label>
                        <input type="text" class="form-control" name="emp_name" id="empname"
                            placeholder="Enter Employee Name" required>
                    </div>
                    <div class="col-sm-12 col-md-4 col-xl-4 mb-3 inputs">
                        <label for="fromstore">From Store <span>*</span></label>
                        <select class="form-select" name="fromstore_id" id="fromstore" required>
                            <option value="" selected disabled>Select Options</option>

                        </select>
                    </div>
                    <div class="col-sm-12 col-md-4 col-xl-4 mb-3 inputs">
                        <label for="tostore">To Store <span>*</span></label>
                        <select class="form-select" name="tostore_id" id="tostore" required>
                            <option value="" selected disabled>Select Options</option>
                            @foreach ($store as $data)
                                <option value="{{ $data->id }}">{{ $data->store_code }}-{{ $data->store_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-sm-12 col-md-4 col-xl-4 mb-3 inputs">
                        <label for="transferdate">Transfer Request Date <span>*</span></label>
                        <input type="date" class="form-control" pattern="\d{4}-\d{2}-\d{2}" min="1000-01-01"
                            max="9999-12-31" name="transfer_date" id="transferdate" required>
                    </div>
                    <div class="col-sm-12 col-md-4 col-xl-4 mb-3 inputs">
                        <label for="reason">Transfer Description</label>
                        <textarea rows="1" class="form-control" name="transfer_description" id="reason"
                            placeholder="Enter Transfer Description"></textarea>
                    </div>
                </div>
            </div>

            <div class="col-sm-12 col-md-12 col-xl-12 mt-3 d-flex justify-content-center align-items-center">
                <button type="submit" class="formbtn">Request</button>
            </div>
        </form>
    </div>


    <script>
        $(document).ready(function() {
            $.ajax({
                url: '{{ route('get_emp_name') }}',
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}'
                },
                success: function(data) {
                    console.log(data);
                    if (data) {
                        $('#empname').val(data.name);
                        $('#empcode').append(
                            `<option value="${data.id}" selected>${data.emp_code}</option>`);
                        $('#empname').val(data.name);
                        $('#fromstore').append(
                            `<option value="${data.store_id}" selected>${data.store_code} - ${data.store_name}</option>`
                        );

                        $("#tostore option").each(function() {
                            if ($(this).val() == data.store_id) {
                                $(this).prop("disabled", true);
                            }
                        });
                    }
                },
                error: function() {
                    alert('Failed to fetch store details.');
                }
            });

            $('#fromstore').change(function() {
                let selectedFromStore = $(this).val();

                $("#tostore option").each(function() {
                    if ($(this).val() == selectedFromStore) {
                        $(this).prop("disabled", true);
                    } else {
                        $(this).prop("disabled", false);
                    }
                });
            });
        });
    </script>
@endsection
