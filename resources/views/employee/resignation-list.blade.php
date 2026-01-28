@extends('layouts.app')

@section('content')
    <div class="sidebodydiv px-5 py-3">
        <div class="sidebodyhead">
            <h4 class="m-0">Resignation List</h4>
        </div>

        <div class="container-fluid listtable mt-4">
            <div class="filter-container row mb-3">
                <div class="custom-search-container col-sm-12 col-md-8">
                    <select class="headerDropdown form-select filter-option">
                        <option value="All" selected>All</option>
                    </select>
                    <input type="text" id="customSearch" class="form-control filterInput" placeholder=" Search">
                </div>

                <div class="select1 col-sm-12 col-md-4 mx-auto">
                    <div class="d-flex gap-3">
                        <!--<a href="" id="print" data-bs-toggle="tooltip" data-bs-title="Print"><img src="{{ asset('assets/images/printer.png') }}"-->
                        <!--    id="print" alt="" height="28px"></a>-->
                        <!--<a href="" id="excel" data-bs-toggle="tooltip" data-bs-title="Excel"><img src="{{ asset('assets/images/excel.png') }}" -->
                        <!--    id="excel" alt="" height="30px"></a>-->
                    </div>
                </div>
            </div>

            <div class="table-wrapper">
                <table class="example table-hover table-striped table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Employee Code</th>
                            <th>Full Name</th>
                            <th>Store</th>
                            <th>Role</th>
                            <th>Contact Number</th>
                            <th>Joining Date</th>
                            <th>Resgin Date</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($res_list as $item)
                            <tr>
                                 <td>{{ $loop->iteration }}</td>
                                <td>{{ $item->emp_code }}</td>
                                <td>{{ $item->emp_name }}</td>
                                <td>{{ $item->store_name ?? '-' }}</td>
                                <td>{{ $item->role }}</td>
                                <td>{{ $item->contact_no }}</td>
                                <td>{{ date('d-m-Y', strtotime($item->pre_start_date)) }}</td>
                                <td>
                                    @if (!empty($item->end_date))
                                        {{ date('d-m-Y', strtotime($item->end_date)) }}
                                    @else
                                        -
                                    @endif
                                </td>
                                <td>
                                    <div class="d-flex gap-3">
                                        <a href="{{ route('employee.view', ['id' => $item->user_id ?? 0]) }}" data-bs-toggle="tooltip" data-bs-title="View Profile"><i
                                                class="fas fa-eye"></i></a>
                                    </div>
                                </td>
                            </tr>
                        @endforeach

                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
