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
                <h6>Add Employee Performance</h6>
            </div>
        </div>
        <div class="sidebodyhead my-3">
            <h4 class="m-0">Employee Performance</h4>
        </div>
        <form action="{{ route('performance.employee_storeperformance') }}" method="post">
            @csrf
            <div class="container-fluid maindiv">
                <div class="row">
                    <div class="col-sm-12 col-md-4 col-xl-4 inputs mb-3">
                        <label for="storeid">Staff Name<span>*</span></label>
                        <input type="text" class="form-control" id="" value="{{ $emp->name }}" readonly>
                    </div>

                </div>
                <div class="row">

                    <div class="col-sm-12 col-md-3 col-xl-3 inputs mb-3">
                        <label for="">Customer Feedback<span>*</span></label>
                        <div class="star-rating">
                            @for ($i = 5; $i >= 1; $i--)
                                <input type="radio" id="Customer Feedback_{{ $i }}" name="customer_feedback" value="{{ $i }}" required>
                                <label class="star-size" for="Customer Feedback_{{ $i }}">&#9733;</label>
                            @endfor
                        </div>
                    </div>

                    <div class="col-sm-12 col-md-3 col-xl-3 inputs mb-3">
                        <label for="">Team Work<span>*</span></label>
                        <div class="star-rating">
                            @for ($i = 5; $i >= 1; $i--)
                                <input type="radio" id="Team Work_{{ $i }}" name="team_work" value="{{ $i }}" required>
                                <label class="star-size" for="Team Work_{{ $i }}">&#9733;</label>
                            @endfor
                        </div>
                    </div>
                    <div class="col-sm-12 col-md-3 col-xl-3 inputs mb-3">
                        <label for="">Google Review<span>*</span></label>
                        <div class="star-rating">
                            @for ($i = 5; $i >= 1; $i--)
                                <input type="radio" id="Google Review_{{ $i }}" name="google_review" value="{{ $i }}" required>
                                <label class="star-size" for="Google Review_{{ $i }}">&#9733;</label>
                            @endfor
                        </div>
                    </div>
                    <div class="col-sm-12 col-md-3 col-xl-3 inputs mb-3">
                        <label for="storeid">SOP Adherence<span>*</span></label>
                        <div class="star-rating">
                            @for ($i = 5; $i >= 1; $i--)
                                <input type="radio" id="SOP Adherence_{{ $i }}" name="sop_adherence" value="{{ $i }}" required>
                                <label class="star-size" for="SOP Adherence_{{ $i }}">&#9733;</label>
                            @endfor
                        </div>
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
