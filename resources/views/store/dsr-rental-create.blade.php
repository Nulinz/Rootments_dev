@extends ('layouts.app')

@section('content')
    <div class="sidebodydiv px-5 py-3">
        <div class="sidebodyhead">
            <h4 class="m-0">Add DSR-Rental</h4>
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
                <form action="{{ route('dsr.rental.store') }}" id="c_form" method="post" enctype="multipart/form-data">
                    @csrf
                    <div class="row">
                        <div class="col-sm-12 col-md-4 col-xl-4 inputs mb-3">
                            <label for="date">Date</label>
                            <input type="date" class="form-control" name="date" id="date" value="{{ date('Y-m-d') }}">
                        </div>
                    </div>
                    <table class="table-hover table-striped table">
                        <thead>
                            <tr>
                                <th rowspan="3" style="vertical-align: middle; text-align: start; border-right: 1px solid #888;">Employee Name</th>
                                <th style="border-right: 1px solid #888;">Bill</th>
                                <th style="border-right: 1px solid #888;">Qty</th>
                                <th style="border-right: 1px solid #888;">Value</th>
                                {{-- <th>KPI</th> --}}
                            </tr>
                            <tr>
                                <th style="border-right: 1px solid #888;">Ftd</th>
                                <th style="border-right: 1px solid #888;">Ftd</th>
                                <th style="border-right: 1px solid #888;">Ftd</th>
                                {{-- <th>Ftd</th> --}}
                            </tr>
                        </thead>
                        <tbody>
                            @php

                            @endphp
                            @foreach ($sto_emp as $emp)
                                <tr>
                                    <td>
                                        <input type="text" class="form-control" value="{{ $emp->name }}" readonly>
                                        <input type="hidden" class="form-control" name="emp_id[]" value="{{ $emp->id }}" readonly>
                                    </td>
                                    <td><input type="number" class="form-control" name="bill_ftd[]" id="bill_ftd" required></td>
                                    <td><input type="number" class="form-control" name="qty_ftd[]" id="qty_ftd" required></td>
                                    <td><input type="number" class="form-control" name="value_ftd[]" id="value_ftd" required></td>
                                    {{-- <td><input type="number" class="form-control" name="kpi_ftd[]" id="kpi_ftd" required></td> --}}
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <div class="col-sm-12 col-md-12 col-xl-12 d-flex justify-content-center align-items-center mt-3">
                        @if ($count == 0)
                            <button type="submit" class="formbtn" id="sub">Save</button>
                        @endif
                    </div>
                </form>

                {{-- <form action="{{ route('store.imp_perf_workupdate') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="form-group">
                        <label for="file">Upload Performance Data</label>
                        <p class="text-danger">Please upload a **.csv** or **.txt** file only.</p>
                        <input type="file" name="file" id="file" class="form-control" required>
                    </div>
                    <button type="submit" class="btn btn-primary mt-3">Import Data</button>
                </form> --}}
            </div>
        </div>
    </div>

    <script src="{{ asset('assets/js/form_script.js') }}"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const form = document.getElementById("c_form");
            const submitBtn = document.getElementById("sub");

            form.addEventListener("submit", function() {
                // Disable the button immediately after submit
                submitBtn.disabled = true;
                submitBtn.innerText = "Saving..."; // Optional: change button text for feedback
            });
        });
    </script>
@endsection
