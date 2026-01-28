@extends('layouts.app')
@section('content')
    <style>
        .star {
            font-size: 3rem;
        }

        .star-rating {
            direction: rtl;
            /* display: flex; */
            font-size: 1.5rem;
            text-align: end;
            /* padding: 10px; */
            /* margin:0px 10px; */
        }

        .star-rating input[type="radio"] {
            display: none;
        }

        .star-rating label {
            /* color: #9c9797 !important; */
            cursor: pointer;
            transition: color 0.2s;
            margin: 0px 5px;
        }

        .star-rating input[type="radio"]:checked~label,
        .star-rating label:hover,
        .star-rating label:hover~label {
            color: #ffc107;
            /* yellow stars */
        }

        .star-size {
            font-size: 2.2rem !important;
        }
    </style>
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
        <form action="{{ route('performance.hr_storeperformance') }}" method="post">
            @csrf
            <div class="container-fluid maindiv">
                <div class="row">
                    <div class="col-sm-12 col-md-4 col-xl-4 inputs mb-3">
                        <label for="storeid">Manager Name<span>*</span></label>
                        <select name="manager" id="" class="form-select">
                            <option value="" selected disabled>-- Selected --</option>
                            @foreach ($managers as $item)
                                <option value="{{ $item->id }}">{{ $item->name }}</option>
                            @endforeach
                        </select>
                    </div>

                </div>
                <div class="row">

                    <div class="col-sm-12 col-md-6 col-xl-6 inputs mb-3">
                        <label for="storeid">Staff Attrition<span>*</span></label>
                        <div class="star-rating">
                            @for ($i = 5; $i >= 1; $i--)
                                <input type="radio" id="staff_attrition_{{ $i }}" name="staff_attrition" value="{{ $i }}" required>
                                <label class="star-size" for="staff_attrition_{{ $i }}">&#9733;</label>
                            @endfor
                        </div>
                    </div>

                    <div class="col-sm-12 col-md-6 col-xl-6 inputs mb-3">
                        <label for="storeid">Hiring<span>*</span></label>
                        <div class="star-rating">
                            @for ($i = 5; $i >= 1; $i--)
                                <input type="radio" id="hiring_{{ $i }}" name="hiring" value="{{ $i }}" required>
                                <label class="star-size" for="hiring_{{ $i }}">&#9733;</label>
                            @endfor
                        </div>
                    </div>
                    <div class="col-sm-12 col-md-6 col-xl-6 inputs mb-3">
                        <label for="storeid">Remark <span>*</span></label>
                        <textarea name="staff_remark" id="" cols="3" class="form-control" required></textarea>
                    </div>
                    <div class="col-sm-12 col-md-6 col-xl-6 inputs mb-3">
                        <label for="storeid">Remark <span>*</span></label>
                        <textarea name="hiring_remark" id="" cols="3" class="form-control" required></textarea>
                    </div>

                    <div class="row">
                        <div class="col-sm-12 col-md-4 col-xl-4 inputs mb-3">
                            <label for="storeid">Task completion <span>*</span></label>
                            <div class="star-rating">
                                @for ($i = 5; $i >= 1; $i--)
                                    <input type="radio" id="task_completion_{{ $i }}" name="task_completion" value="{{ $i }}" required>
                                    <label class="star-size" for="task_completion_{{ $i }}">&#9733;</label>
                                @endfor
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-12 col-md-6 col-xl-6 inputs mb-3">
                        <label for="storeid">Remark <span>*</span></label>
                        <textarea name="task_remark" id="" cols="3" class="form-control" required></textarea>
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
