@extends('layouts.app')

@section('content')
    <div class="sidebodydiv mb-3 px-5 py-3">
        <div class="sidebodyback mb-3" onclick="goBack()">
            <div class="backhead">
                <h5><i class="fas fa-arrow-left"></i></h5>
                <h6>Purchase Request Form</h6>
            </div>
        </div>
        <div class="sidebodyhead my-3">
            <h4 class="m-0">Purchase Request Details</h4>
        </div>
        <form action="{{ route('purchase.store_purchase') }}" method="post" enctype="multipart/form-data">
            @csrf
            <div class="container-fluid maindiv">
                <div class="row">
                    <div class="col-sm-12 col-md-4 col-xl-4 inputs mb-3">
                        <label for="storeid">Request Type</label>
                        <select name="request_type" id="" class="form-select" required>
                            <option value="" disabled selected>Select Type</option>
                            <option value="Stock Replenishment">Stock Replenishment</option>
                            <option value="Shoes for Sale">Shoes for Sale</option>
                            <option value="Bulk Customer Orders">Bulk Customer Orders</option>
                            <option value="New Design Procurement">New Design Procurement</option>
                        </select>
                    </div>
                    <div class="col-sm-12 col-md-4 col-xl-4 inputs mb-3">
                        <label for="storeid">Date</label>
                        <input type="date" class="form-control" name="pru_date" id="" value="" autofocus required>
                    </div>

                    <div class="col-sm-12 col-md-4 col-xl-4 inputs mb-3">
                        <label for="storeid">Request To</label>
                        <select name="requst_to" id="" class="form-select" required>
                            <option value="" selected disabled>Select option</option>
                            @foreach ($pur_req as $pr)
                                <option value="{{ $pr->id }}">{{ $pr->name }}</option>
                            @endforeach`
                        </select>
                    </div>
                    <div class="col-sm-12 col-md-4 col-xl-4 inputs mb-3">
                        <label for="storeid">File upload</label>
                        <input type="file" class="form-control" name="pur_file" id="" value="" autofocus>
                    </div>
                    <div class="col-sm-12 col-md-4 col-xl-4 inputs mb-3">
                        <label for="storeid">Description</label>
                        <input type="text" class="form-control" name="pur_des" id="" value="" autofocus required>
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
