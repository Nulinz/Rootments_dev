@extends ('layouts.app')

@section('content')
    <div class="sidebodydiv px-5 py-3">
        <div class="sidebodyhead">
            <h4 class="m-0">Add DSR-Sales</h4>
        </div>

        {{-- <div class="container-fluid maindiv my-3 bg-white">
            <div class="row">
                <div class="col-sm-12 col-md-4 col-xl-4 inputs mb-3">
                    <label for="date">Date</label>
                    <input type="date" class="form-control" name="date" id="date" value="{{ date('Y-m-d') }}">
                </div>
            </div>
        </div> --}}

        <div class="container-fluid listtable mt-4">
            <div class="table-wrapper">
                <form action="{{ route('dsr.sales.store') }}" method="post" id="c_form" enctype="multipart/form-data">
                    @csrf
                    <div class="row">
                        <div class="col-sm-12 col-md-4 col-xl-4 inputs mb-3">
                            <label for="date">Date</label>
                            <input type="date" class="form-control" name="" id="date" value="{{ date('Y-m-d') }}" readonly>
                        </div>
                    </div>
                    <table class="table-hover table-striped table">
                        <thead>
                            <tr>
                                <th rowspan="3" style="vertical-align: middle; text-align: start; border-right: 1px solid #888;">Employee Name</th>
                                <th colspan="2" style="border-right: 1px solid #888;">Shoe</th>
                                <th colspan="2">Shirt</th>
                            </tr>
                            <tr>
                                <th style="border-right: 1px solid #888;">Bill</th>
                                <th style="border-right: 1px solid #888;">Qty</th>
                                <th style="border-right: 1px solid #888;">Bill</th>
                                <th>Qty</th>
                            </tr>
                            <tr>
                                <th style="border-right: 1px solid #888;">Ftd</th>
                                <th style="border-right: 1px solid #888;">Ftd</th>
                                <th style="border-right: 1px solid #888;">Ftd</th>
                                <th>Ftd</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($sto_emp as $emp)
                                <tr>
                                    <td>
                                        <input type="text" class="form-control" value="{{ $emp->name }}" readonly>
                                        <input type="hidden" class="form-control" name="emp_id[]" value="{{ $emp->id }}" readonly>
                                    </td>
                                    <td><input type="number" class="form-control" name="shoe_bill[]" id="shoe_bill" required></td>
                                    <td><input type="number" class="form-control" name="shoe_qty[]" id="shoe_qty" required></td>
                                    <td><input type="number" class="form-control" name="shirt_bill[]" id="shirt_bill" required></td>
                                    <td><input type="number" class="form-control" name="shirt_qty[]" id="shirt_qty" required></td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <div class="col-sm-12 col-md-12 col-xl-12 d-flex justify-content-center align-items-center mt-3">
                        <button type="submit" class="formbtn" id="sub">Save</button>
                    </div>
                </form>
            </div>

            {{-- <form action="{{ route('store.imp_workupdate') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="file" name="file" required>
                <button type="submit" class="btn btn-primary">Upload</button>
            </form> --}}
        </div>
    </div>

    <script src="{{ asset('assets/js/form_script.js') }}"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            // First form (DSR-Sales)
            const form1 = document.getElementById("c_form");
            const submitBtn1 = document.getElementById("sub");

            form1.addEventListener("submit", function() {
                submitBtn1.disabled = true;
                submitBtn1.innerText = "Saving..."; // feedback
            });
        });
    </script>
@endsection
