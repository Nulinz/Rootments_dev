@extends('layouts.app')

@section('content')
    <div class="sidebodydiv mb-3 px-5 py-3">
        <div class="sidebodyback mb-3" onclick="goBack()">
            <div class="backhead">
                <h5><i class="fas fa-arrow-left"></i></h5>
                <h6>Add WorkUpdate</h6>
            </div>
        </div>
        <div class="sidebodyhead my-3">
            <h4 class="m-0">Workupdate</h4>
        </div>
        <form action="{{ route('hr_storework') }}" method="post">
            @csrf
            <div class="container-fluid maindiv">
                <div class="row">
                    <div class="col-sm-12 col-md-4 col-xl-4 inputs mb-3">
                        <label for="storeid">Attrition Rate <span>*</span></label>
                        <input type="text" class="form-control" name="att_ratio" id="" value="{{ $attr }}" autofocus required readonly>
                    </div>

                    <div class="col-sm-12 col-md-4 col-xl-4 inputs mb-3">
                        <label for="storeid">Retention Rate <span>*</span></label>
                        <input type="text" class="form-control" name="ret_ratio" id="" value="{{ $ret }}" autofocus required readonly>
                    </div>
                    <div class="col-sm-12 col-md-4 col-xl-4 inputs mb-3">
                        <label for="storeid">Hiring completion <span>*</span></label>
                        <input type="text" class="form-control" name="hire_completion" autofocus required>
                    </div>
                    <div class="col-sm-12 col-md-4 col-xl-4 inputs mb-3">
                        <label for="storeid">Task completion <span>*</span></label>
                        <input type="text" class="form-control" name="task_comp" id="" value="{{ $t_c }}" autofocus required>
                    </div>
                    <div class="col-sm-12 col-md-4 col-xl-4 inputs mb-3">
                        <label for="storeid">Spendings <span>*</span></label>
                        <input type="text" class="form-control" name="spending" id="" value="" autofocus required>
                    </div>

                </div>
            </div>

            <div class="col-sm-12 col-md-12 col-xl-12 d-flex justify-content-center align-items-center mt-3">
                <a href="">
                    <button type="submit" id="sub" class="formbtn">Save</button>
                </a>
            </div>
        </form>
    </div>

    <script src="{{ asset('assets/js/form_script.js') }}"></script>
@endsection
