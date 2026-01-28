@extends('layouts.app')

@section('content')
    <style>
        .star {
            color: #9c9797 !important;
        }
    </style>
    <div class="sidebodydiv px-5 py-3">
        <div class="sidebodyhead">
            <h4 class="m-0">Store Audit List</h4>

            {{-- {{ $role->role_id }} --}}
            @if (!in_array(auth()->user()->role_id, [12, 66]))
                <a href="{{ route('store.add_audit') }}"><button class="listbtn">+ Add Audit</button></a>
            @endif
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
                        <!--<a href="" id="print" data-bs-toggle="tooltip" data-bs-title="Print"><img src="{{ asset('assets/images/printer.png') }}" id="print"-->
                        <!--        alt="" height="28px"></a>-->
                        <!--<a href="" id="excel" data-bs-toggle="tooltip" data-bs-title="Excel"><img src="{{ asset('assets/images/excel.png') }}" id="excel"-->
                        <!--        alt="" height="30px"></a>-->
                    </div>
                </div>
            </div>

            <div class="table-wrapper">
                <table class="example table-hover table-striped table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Store Name</th>
                            <th>Overall Rating</th>
                            <th>Rated By</th>
                            <th>Created on</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($store_audit as $data)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $data->store_name }}</td>
                                <td>{{ $data->average_rating }}</td>
                                <td>
                                    {{ $data->rated_by }}
                                </td>
                                <td>
                                    {{ date('d-m-Y', strtotime($data->created_on)) }}
                                </td>
                                <td>
                                    <div class="d-flex gap-3">
                                        <a href="{{ route('store.audit_view', ['id' => $data->audit_id]) }}" data-bs-toggle="tooltip" data-bs-title="View Profile"><i
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
