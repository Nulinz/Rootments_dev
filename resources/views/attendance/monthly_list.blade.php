@extends('layouts.app')

@section('content')

    <div class="sidebodydiv px-5 py-3">
        <div class="sidebodyhead">
            <h4 class="m-0">Monthly Attendance List</h4>
        </div>

        @if(request()->isMethod('get'))
        <form action="{{ route('attendance.monthly_list')}}" method="post" id="">
            @csrf
            <div class="container-fluid maindiv my-3">
                <div class="row">

                    <div class="col-sm-12 col-md-4 col-xl-4 mb-3 inputs">
                        <label for="stores">Departments <span>*</span></label>
                        <select class="form-select" name="dept" id="dept" autofocus required>
                            @foreach ($dept as $item)
                               <option value="{{ $item->role_dept }}">{{ $item->role_dept }}</option>
                           @endforeach
                        </select>
                    </div>
                    <div class="col-sm-12 col-md-4 col-xl-4 mb-3 inputs" id="store_div" style="display:none">
                        <label for="stores">Stores <span>*</span></label>
                        <select class="form-select" name="stores" id="stores" >
                            <option value="" selected disabled>Select Options</option>
                            @foreach ($stores as $store)
                                <option value="{{$store->id}}" {{ old('stores') == $store->id ? 'selected' : '' }}>
                                    {{$store->store_name}}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-sm-12 col-md-4 col-xl-4 mb-3 inputs">
                        <label for="month">Month <span>*</span></label>
                        <input type="month" class="form-control" name="month" id="month">
                    </div>
                </div>
            </div>

            <div class="col-sm-12 col-md-12 col-xl-12 mt-3 w-50 d-flex justify-content-center align-items-center mx-auto">
                <button type="submit" class="formbtn">Save</button>
            </div>
        </form>
        @endif

        <div class="container-fluid mt-4 listtable">
            <div class="filter-container row mb-3">
                <div class="custom-search-container col-sm-12 col-md-8">
                    <select class="headerDropdown form-select filter-option">
                        <option value="All" selected>All</option>
                    </select>
                    <input type="text" id="customSearch" class="form-control filterInput" placeholder=" Search">
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
                <table class="example table table-hover table-striped">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Employee Code</th>
                            <th>Employee Name</th>
                            <th>Role</th>
                            <th>Present</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($lists as $list)
                        <tr>
                            <td>{{$loop->iteration}}</td>
                            <td>{{$list->emp_code}}</td>
                            <td>{{$list->name}}</td>
                            <td>{{$list->role}}</td>
                            <td>{{$list->attd_count}}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script>
        $('#dept').on('change',function(){
            var dept = $(this).find('option:selected').val();
            if(dept==='Store'){
                $('#store_div').show();
            }else{
                $('#store_div').hide();
            }
        });
    </script>

@endsection
