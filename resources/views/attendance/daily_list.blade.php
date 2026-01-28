@extends('layouts.app')

@section('content')

    <div class="sidebodydiv px-5 py-3">
        <div class="sidebodyhead">
            <h4 class="m-0">Daily Attendance List</h4>
        </div>

        @if (request()->isMethod('get'))
            <form action="{{ route('attendance.list') }}" method="POST" id="attendanceForm">
                @csrf
                <div class="container-fluid maindiv my-3">
                    <div class="row">
                        <div class="col-sm-12 col-md-4 col-xl-4 inputs mb-3">
                            <label for="">Dept<span>*</span></label>
                            <select class="form-select" name="dept" id="dept" autofocus required>
                                @foreach ($dept as $item)
                                    <option value="{{ $item->role_dept }}">{{ $item->role_dept }}</option>
                                @endforeach
                                {{-- <option value="" selected disabled>Select Options</option>
                            @foreach ($stores as $store)
                                <option value="{{$store->id}}" {{ old('stores') == $store->id ? 'selected' : '' }}>
                                    {{$store->store_name}}
                                </option>
                            @endforeach --}}
                            </select>
                        </div>
                        <div class="col-sm-12 col-md-4 col-xl-4 inputs mb-3" id="store_div" style="display:none">
                            <label for="stores">Stores <span>*</span></label>
                            <select class="form-select" name="stores" id="stores">
                                <option value="" selected disabled>Select Options</option>
                                @foreach ($stores as $store)
                                    <option value="{{ $store->id }}" {{ old('stores') == $store->id ? 'selected' : '' }}>
                                        {{ $store->store_name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-sm-12 col-md-4 col-xl-4 inputs mb-3">
                            <label for="date">Date <span>*</span></label>
                            <input type="date" class="form-control" name="date" id="date" required
                                value="{{ date('Y-m-d') }}">
                        </div>
                    </div>
                </div>

                <div
                    class="col-sm-12 col-md-12 col-xl-12 w-50 d-flex justify-content-center align-items-center mx-auto mt-3">
                    <button type="submit" class="formbtn">Save</button>
                </div>
            </form>
        @endif

        <!-- Table for displaying attendance data -->
        <div class="container-fluid listtable mt-4">
            <div class="filter-container row mb-3">
                <div class="custom-search-container col-sm-12 col-md-8">
                    <select class="headerDropdown form-select filter-option">
                        <option value="All" selected>All</option>
                    </select>
                    <input type="text" id="customSearch" class="form-control filterInput" placeholder="Search">
                </div>

                <div class="select1 col-sm-12 col-md-4 mx-auto">
                    <div class="d-flex gap-3">
                        <!--<a href="" id="pdfLink"><img src="{{ asset('assets/images/printer.png') }}" id="print" alt=""-->
                        <!--        height="28px" data-bs-toggle="tooltip" data-bs-title="Print"></a>-->
                        <!--<a href="" id="excelLink"><img src="{{ asset('assets/images/excel.png') }}" id="excel" alt=""-->
                        <!--        height="30px" data-bs-toggle="tooltip" data-bs-title="Excel"></a>-->
                    </div>
                </div>
            </div>

            <div class="table-wrapper">
                <table class="example table-hover table-striped table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Employee Code</th>
                            <th>Employee Name</th>
                            <th>Role</th>
                            <th>In-Time</th>
                            <th>Out-Time</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($lists as $list)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $list->emp_code }}</td>
                                <td>{{ $list->name }}</td>
                                <td>{{ $list->role }}</td>
                                <td>{{ $list->in_time }}</td>
                                <td>{{ $list->out_time }}</td>
                                <td>{{ $list->status }}</td>
                                <td>

                                    <form action="{{ route('attendance.delete', $list->attd_id) }}" method="POST"
                                        style="display: inline;">
                                        @csrf
                                        <button type="submit"
                                            onclick="return confirm('Are you sure you want to delete this item {{ $list->name }} -- {{ $list->emp_code }}?')"
                                            style="border: none; background: none; color: red;">
                                            <i class="fa-solid fa-trash"></i>
                                        </button>
                                    </form>
                                
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script>
        $('#dept').on('change', function() {
            var dept = $(this).find('option:selected').val();
            if (dept === 'Store') {
                $('#store_div').show();
            } else {
                $('#store_div').hide();
            }
        });
    </script>
@endsection
