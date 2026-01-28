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
                <h6>Manager Performance Form</h6>
            </div>
        </div>
        <div class="sidebodyhead my-3">
            <h4 class="m-0">Operation Executive Performance</h4>
        </div>
        <form action="{{ route('performance.opearation_storeperformance') }}" method="post" id="c_form">
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
                        <label for="storeid">SOP Adherence<span>*</span></label>
                        <div class="star-rating">
                            @for ($i = 5; $i >= 1; $i--)
                                <input type="radio" id="sop_adherence_{{ $i }}" name="sop_adherence" value="{{ $i }}">
                                <label class="star-size" for="sop_adherence_{{ $i }}">&#9733;</label>
                            @endfor
                        </div>
                    </div>

                    <div class="col-sm-12 col-md-6 col-xl-6 inputs mb-3">
                        <label for="storeid">Damage Control<span>*</span></label>
                        <div class="star-rating">
                            @for ($i = 5; $i >= 1; $i--)
                                <input type="radio" id="damage_control_{{ $i }}" name="damage_control" value="{{ $i }}">
                                <label class="star-size" for="damage_control_{{ $i }}">&#9733;</label>
                            @endfor
                        </div>
                    </div>
                    <div class="col-sm-12 col-md-6 col-xl-6 inputs mb-3">
                        <label for="storeid">Remark<span>*</span></label>
                        <textarea name="sop_remark" id="" cols="3" class="form-control"></textarea>
                    </div>
                    <div class="col-sm-12 col-md-6 col-xl-6 inputs mb-3">
                        <label for="storeid">Remark <span>*</span></label>
                        <textarea name="damage_remark" id="" cols="3" class="form-control"></textarea>
                    </div>

                    <div class="row">
                        <div class="col-sm-12 col-md-6 col-xl-6 inputs mb-3">
                            <label for="storeid">Product Quality<span>*</span></label>
                            <div class="star-rating">
                                @for ($i = 5; $i >= 1; $i--)
                                    <input type="radio" id="product_quality_{{ $i }}" name="product_quality" value="{{ $i }}">
                                    <label class="star-size" for="product_quality_{{ $i }}">&#9733;</label>
                                @endfor
                            </div>
                        </div>
                        <div class="col-sm-12 col-md-6 col-xl-6 inputs mb-3">
                            <label for="storeid">Staff training<span>*</span></label>
                            <div class="star-rating">
                                @for ($i = 5; $i >= 1; $i--)
                                    <input type="radio" id="staff_training_{{ $i }}" name="staff_training" value="{{ $i }}">
                                    <label class="star-size" for="staff_training_{{ $i }}">&#9733;</label>
                                @endfor
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-12 col-md-6 col-xl-6 inputs mb-3">
                        <label for="storeid">Remaks <span>*</span></label>
                        <textarea name="product_remakr" id="" cols="3" class="form-control"></textarea>
                    </div>
                    <div class="col-sm-12 col-md-6 col-xl-6 inputs mb-3">
                        <label for="storeid">Remark <span>*</span></label>
                        <textarea name="training_remakr" id="" cols="3" class="form-control"></textarea>
                    </div>
                </div>
                <div class="row">

                    <div class="col-sm-12 col-md-6 col-xl-6 inputs mb-3">
                        <label for="storeid">Daily Photos<span>*</span></label>
                        <div class="star-rating">
                            @for ($i = 5; $i >= 1; $i--)
                                <input type="radio" id="daily_photos_{{ $i }}" name="daily_photos" value="{{ $i }}">
                                <label class="star-size" for="daily_photos_{{ $i }}">&#9733;</label>
                            @endfor
                        </div>
                    </div>
                </div>
                <div class="col-sm-12 col-md-6 col-xl-6 inputs mb-3">
                    <label for="storeid">Remark <span>*</span></label>
                    <textarea name="photos_remark" id="" cols="3" class="form-control"></textarea>
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
