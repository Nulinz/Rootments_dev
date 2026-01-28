@extends('layouts.app')

@section('content')
    <div class="sidebodydiv mb-3 px-5 py-3">
        <div class="sidebodyback mb-3" onclick="goBack()">
            <div class="backhead">
                <h5><i class="fas fa-arrow-left"></i></h5>
                <h6>Add Vendor Form</h6>
            </div>
        </div>
        <div class="sidebodyhead my-3">
            <h4 class="m-0">Vendor Details</h4>
        </div>
        <form action="{{ route('purchase.store_vendor') }}" method="post" id="c_form">
            @csrf
            <div class="container-fluid maindiv">
                <div class="row">
                    <div class="col-sm-12 col-md-4 col-xl-4 inputs mb-3">
                        <label for="storeid">Vendor Code</label>
                        <input type="text" class="form-control" name="vendor_code" id="" value="" autofocus required>
                    </div>
                    <div class="col-sm-12 col-md-4 col-xl-4 inputs mb-3">
                        <label for="storeid">Name </label>
                        <input type="text" class="form-control" name="name" id="" value="" autofocus required>
                    </div>
                    <div class="col-sm-12 col-md-4 col-xl-4 inputs mb-3">
                        <label for="storeid">Mail Id </label>
                        <input type="email" class="form-control" name="email" id="" value="" autofocus required>
                    </div>
                    <div class="col-sm-12 col-md-4 col-xl-4 inputs mb-3">
                        <label for="storeid">Contact</label>
                        <input type="text" class="form-control" name="contact" id="" value="" maxlength="10" minlength="10" autofocus required>
                    </div>
                    <div class="col-sm-12 col-md-4 col-xl-4 inputs mb-3">
                        <label for="opening_balance" class="form-label fw-bold">Opening Balance</label>
                        <div class="input-group">
                            <span class="input-group-text">₹</span>
                            <input type="number" class="form-control" id="opening_balance" placeholder="0" name="opening_balance">

                            <select class="form-select" name="balance_type">
                                <option value="to_pay" selected>To Pay</option>
                                <option value="to_receive">To Receive</option>
                            </select>
                        </div>
                    </div>

                    <div class="col-sm-12 col-md-4 col-xl-4 inputs mb-3">
                        <label for="storeid">GSTIN No</label>
                        <input type="text" class="form-control" name="gstin_no" id="" value="" autofocus required>
                    </div>

                    <div class="col-sm-12 col-md-4 col-xl-4 inputs mb-3">
                        <label for="storeid">PAN Number</label>
                        <input type="text" class="form-control" name="pan_number" id="" value="" autofocus required>
                    </div>
                </div>

                <div class="row">
                    <div class="col-sm-12 col-md-6 col-xl-6 inputs mb-3">
                        <label for="permanent_address">Permanent Address</label>
                        <textarea name="permanent_address" id="permanent_address" cols="4" class="form-control"></textarea>
                    </div>

                    <div class="col-sm-12 col-md-6 col-xl-6 inputs mb-3">
                        <div class="d-flex align-items-center justify-content-between">
                            <label for="shipping_address">Shipping Address</label>
                            <div class="d-flex align-items-center">
                                <input type="checkbox" id="same_address_checkbox">
                                <label for="same_address_checkbox" class="ms-2">Same as Permanent Address</label>
                            </div>
                        </div>
                        <textarea name="shipping_address" id="shipping_address" cols="4" class="form-control"></textarea>
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
    <script>
        document.getElementById('same_address_checkbox').addEventListener('change', function() {
            const permanentAddress = document.getElementById('permanent_address').value;
            const shippingAddress = document.getElementById('shipping_address');

            if (this.checked) {
                shippingAddress.value = permanentAddress;
            } else {
                shippingAddress.value = '';
            }
        });
    </script>
@endsection
