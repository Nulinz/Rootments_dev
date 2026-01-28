@extends('layouts.app')

@section('content')
    @php
        // dd($store);
    @endphp

    <div class="sidebodydiv mb-3 px-5 py-3">
        <div class="sidebodyback mb-3" onclick="goBack()">
            <div class="backhead">
                <h5><i class="fas fa-arrow-left"></i> Salary Hold Form</h5>
                <h6></h6>
            </div>
        </div>
        <div class="sidebodyhead my-3">
            <h4 class="m-0">Salary Hold Form</h4>
        </div>
        <form action="{{ route('payroll.store_salaryhold') }}" method="POST">
            @csrf
            <div class="container-fluid maindiv">
                <div class="row">
                    {{-- <div class="col-sm-12 col-md-4 col-xl-4 inputs mb-3">
                        <label for="">Employee Code</label>
                        <input type="text" class="form-control" name="emp_code" id="emp_code" value="{{ auth()->user()->emp_code }}" readonly>
                    </div>
                    <div class="col-sm-12 col-md-4 col-xl-4 inputs mb-3">
                        <label for="">Employee Name</label>
                        <input type="text" class="form-control" name="emp_name" id="emp_name" value="{{ auth()->user()->name }}" readonly>
                    </div> --}}
                    <div class="col-sm-12 col-md-4 col-xl-4 inputs mb-3">
                        <label for="">Employee Code</label>
                        <input type="text" class="form-control" name="emp_code" id="emp_code" required style="text-transform: uppercase">
                    </div>
                    <div class="col-sm-12 col-md-4 col-xl-4 inputs mb-3">
                        <label for="">Employee Name</label>
                        <input type="text" class="form-control" name="emp_name" id="emp_name" readonly>
                    </div>
                    <div class="col-sm-12 col-md-4 col-xl-4 inputs mb-3">
                        <label for="">Hold Type <span>*</span></label>
                        <select class="form-select" name="req_type" required autofocus>
                            <option value="" selected disabled>Select Options</option>
                            <option value="Installment cutting for loan">Installment cutting for loan</option>
                            <option value="Starting salary hold">Starting salary hold</option>
                            <option value="Resignation">Resignation</option>
                            <option value="Termination">Termination</option>
                        </select>
                    </div>
                    <div class="col-sm-12 col-md-4 col-xl-4 inputs mb-3">
                        <label for="contact">Reason <span>*</span></label>
                        <input type="text" class="form-control" name="reason" id="reason" required>
                    </div>
                    <div class="col-sm-12 col-md-4 col-xl-4 inputs mb-3">
                        <label for="">Hold start month <span>*</span></label>
                        <input type="date" class="form-control" name="start_hold_date" id="hold_date" required>
                    </div>
                    <div class="col-sm-12 col-md-4 col-xl-4 inputs mb-3">
                        <label for="">Hold end month <span>*</span></label>
                        <input type="date" class="form-control" name="end_hold_date" id="hold_date" required>
                    </div>
                    <div class="col-sm-12 col-md-4 col-xl-4 inputs mb-3">
                        <label for="contact">Notes </label>
                        <input type="text" class="form-control" name="hold_note" id="hold">
                    </div>
                </div>
            </div>

            <div class="col-sm-12 col-md-12 col-xl-12 d-flex justify-content-center align-items-center mt-3">
                <a href="">
                    <button type="submit" class="formbtn">Save</button>
                </a>
            </div>
        </form>
    </div>
    <script src="{{ asset('assets/js/form_script.js') }}"></script>
    <script>
        document.getElementById('emp_code').addEventListener('input', function() {
            let empCode = this.value.trim();

            if (empCode.length > 0) {
                fetch(`/get-employee-name/${empCode}`)
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            document.getElementById('emp_name').value = data.name;
                        } else {
                            document.getElementById('emp_name').value = '';
                        }
                    })
                    .catch(error => {
                        console.error('Error fetching name:', error);
                        document.getElementById('emp_name').value = '';
                    });
            } else {
                document.getElementById('emp_name').value = '';
            }
        });
    </script>
@endsection
