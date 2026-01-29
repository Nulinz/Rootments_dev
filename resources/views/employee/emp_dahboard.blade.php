@extends('layouts.app')
@section('content')
    <div class="sidebodydiv mb-3 px-5 py-3">
        <div class="sidebodyhead">
            <h4 class="m-0">My Dashboard</h4>
        </div>

        @include('generaldashboard.tabs')

        <div class="row mt-4">
            <div class="col-md-3 col-sm-12 col-xl-3 cards mb-2">
                <div class="card">
                    <div class="card-body">
                        <h6 class="card1h6 mb-2">Total Annual/Sick Leave </h6>
                        <p class="mb-0">{{ $annualSickCount }} / 20</p>
                    </div>
                </div>
            </div>

            <div class="col-md-3 col-sm-12 col-xl-3 cards mb-2">
                <div class="card">
                    <div class="card-body">
                        <h6 class="card1h6 mb-2">Total WeekOff </h6>
                        {{-- <p class="mb-0">{{  }} / 4 </p> --}}
                        <p class="mb-0">{{ $weekOffCount }} / 4</p>

                    </div>
                </div>
            </div>

            <div class="col-md-3 col-sm-12 col-xl-3 cards mb-2">
                <div class="card">
                    <div class="card-body">
                        <h6 class="card1h6 mb-2">Task Pending / Completed</h6>
                        {{-- <p class="mb-0">{{  }} / 4 </p> --}}
                        <p class="mb-0">{{ $tasks_state['pending_task'] . '/' . $tasks_state['completed'] }}</p>

                    </div>
                </div>
            </div>
            <div class="col-md-3 col-sm-12 col-xl-3 cards mb-2">
                <div class="card">
                    <div class="card-body">
                        <h6 class="card1h6 mb-2">On Time / Delay</h6>
                        {{-- <p class="mb-0">{{  }} / 4 </p> --}}
                        <p class="mb-0">{{ $tasks_state['on_time'] . '/' . $tasks_state['delayed_task'] }}</p>

                    </div>
                </div>
            </div>
        </div>

        <h6 class="mt-3">DSR Sale</h6>
        <div class="container-fluid listtable">
            <div class="table-wrapper">
                <table class="taskTable table-hover table-striped table">
                    <thead>
                        <tr>
                            {{-- <th>#</th> --}}
                            <th>Shoe Bill</th>
                            <th>Shoe Qty</th>
                            <th>Shoe TGT</th>
                            <th>Shoe ACH</th>
                            <th>Shirt Bill</th>
                            <th>Shirt Qty</th>
                            <th>Shirt TGT</th>
                            <th>Shirt ACH</th>
                            {{-- <th>Created On</th> --}}
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            {{-- <td>{{ $loop->iteration }}</td> --}}
                            <td>{{ number_format($emp_sale_record->shoe_bill_mtd, 0) }}</td>
                            <td>{{ number_format($emp_sale_record->shoe_qty_mtd, 0) }}</td>
                            <td>{{ number_format($emp_sale_record->shoe_tgt, 0) }}</td>
                            <td>{{ number_format($emp_sale_record->shoe_ach, 0) }}</td>
                            <td>{{ number_format($emp_sale_record->shirt_bill_mtd, 0) }}</td>
                            <td>{{ number_format($emp_sale_record->shirt_qty_mtd, 0) }}</td>
                            <td>{{ number_format($emp_sale_record->shirt_tgt, 0) }}</td>
                            <td>{{ number_format($emp_sale_record->shirt_ach, 0) }}</td>
                            {{-- <td>{{ date('d-m-Y', strtotime($emp_sale_record->created_at)) }}</td> --}}
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <h6 class="mt-3">DSR Rental</h6>
        <div class="container-fluid listtable mt-2">
            <div class="table-wrapper">
                <table class="taskTable table-hover table-striped table">
                    <thead>
                        <tr>
                            {{-- <th>#</th> --}}
                            <th>Total Bills</th>
                            <th>Total Quantity</th>
                            <th>Total Value</th>
                            {{-- <th>Total KPI</th>  --}}
                            <th>Taget Value</th>
                            <th>Target Qty</th>
                            <th>Total ACH</th>
                            <th>Total Walkin</th>
                            <th>Total Loss</th>
                            <th>Conversion</th>
                            {{-- <th>Created on</th> --}}
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            {{-- <td>{{ $loop->iteration }}</td> --}}
                            <td>{{ number_format($emp_perf_record->b_mtd, 0) }}</td>
                            <td>{{ number_format($emp_perf_record->q_mtd, 0) }}</td>
                            <td>{{ number_format($emp_perf_record->v_mtd, 0) }}</td>
                            <td>{{ $emp_perf_record->tgt_value ??  0}}</td>
                            <td>{{ $emp_perf_record->tgt_qty ?? 0 }}</td>
                            <td>{{ number_format($emp_perf_record->ach_per, 0) }}</td>
                            <td>{{ number_format($emp_perf_record->w_ftd, 0) }}</td>
                            <td>{{ number_format($emp_perf_record->los_ftd, 0) }}</td>
                            <td>{{ number_format($emp_perf_record->conv, 2) }}</td>
                            {{-- <td>{{ date('d-m-Y', strtotime($emp_perf_record->created_at)) }}</td> --}}
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script src="{{ asset('assets/js/form_script.js') }}"></script>
@endsection
