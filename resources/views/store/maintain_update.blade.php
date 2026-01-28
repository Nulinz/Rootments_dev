@extends('layouts.app')
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
@section('content')
    <div class="sidebodydiv mb-3 px-5 py-3">
        <div class="sidebodyback mb-3" onclick="goBack()">
            <div class="backhead">
                <h5><i class="fas fa-arrow-left"></i></h5>
                <h6>Maintenance Form</h6>
            </div>
        </div>
        <div class="sidebodyhead my-3">
            <h4 class="m-0">Maintenance Update</h4>
        </div>
        <form action="{{ route('store.maintain_update_store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <input type="hidden" name="store_id" value="{{ auth()->user()->store_id }}">
            <input type="hidden" name="c_by" value="{{ auth()->user()->id }}">
            <input type="hidden" name='task_id' value="{{ $id }}">

            <div class="container-fluid maindiv">
                <div class="row">
                    <div class="col-sm-12 col-md-4 col-xl-4 inputs mb-3">
                        <label for="storename">Staff Arrival <span>*</span></label>
                        <input type="time" class="form-control" name="staff_arr" id="" required>
                    </div>

                    <div class="col-sm-12 col-md-4 col-xl-4 inputs mb-3">
                        <label for="address">Work Completion<span>*</span></label>
                        <input type="text" class="form-control" name="work_comp" required>
                    </div>

                    <div class="col-sm-12 col-md-4 col-xl-4 inputs mb-3" id="walkin_status_wrapper">
                        <label for="walk_status">File</label>
                        <input type="file" class="form-control" name="mnt_file[]" multiple>
                    </div>

                    <div class="col-sm-12 col-md-4 col-xl-4 inputs mb-3">
                        <label for="storename">End Time <span>*</span></label>
                        <input type="time" class="form-control" name="end_time" id="" required>
                    </div>

                    <div class="col-sm-12 col-md-8 col-xl-8 inputs">
                        <label for="storename">Rating <span>*</span></label>
                        <div class="star-rating">
                            @for ($i = 5; $i >= 1; $i--)
                                <input type="radio" id="signage_board_condition_{{ $i }}" name="mnt_update" value="{{ $i }}">
                                <label class="star-size" for="signage_board_condition_{{ $i }}">&#9733;</label>
                            @endfor
                        </div>
                    </div>

                    <div class="col-sm-12 col-md-4 col-xl-4 mb-3" id="remarks_wrapper">
                        <label for="remarks">Comments</label>
                        {{-- <textarea rows="1" name="comments" class="form-control p-2" placeholder="Enter comments">
                        </textarea> --}}
                        <textarea rows="1" name="comments" class="form-control p-2" placeholder="Enter comments"></textarea>
                    </div>

                </div>
            </div>

            <div class="col-sm-12 col-md-12 col-xl-12 d-flex justify-content-center align-items-center mt-3">
                <button type="submit" class="formbtn">Save</button>
            </div>
        </form>
    </div>

    {{-- <script src="{{ asset('assets/js/form_script.js') }}"></script> --}}

    {{-- <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> --}}
@endsection
