@extends('layouts.app')

@section('content')
    <div class="sidebodydiv mb-3 px-5 py-3">
        <div class="sidebodyback mb-3" onclick="goBack()">
            <div class="backhead">
                <h5><i class="fas fa-arrow-left"></i></h5>
                <h6>Add Product Form</h6>
            </div>
        </div>
        <div class="sidebodyhead my-3">
            <h4 class="m-0">Product Details</h4>
        </div>
        <form action="{{ route('purchase.store_product') }}" method="post" enctype="multipart/form-data">
            @csrf
            <div class="container-fluid maindiv">
                <div class="row">
                    <div class="col-sm-12 col-md-4 col-xl-4 inputs mb-3">
                        <label for="storeid">Product Code</label>
                        <input type="text" class="form-control" name="product_code" id="" value="" autofocus required>
                    </div>
                    <div class="col-sm-12 col-md-4 col-xl-4 inputs mb-3">
                        <label for="storeid">Product Name</label>
                        <input type="text" class="form-control" name="product_name" id="" value="" autofocus required>
                    </div>
                    <div class="col-sm-12 col-md-4 col-xl-4 inputs mb-3">
                        <label for="storeid">Color</label>
                        <input type="text" class="form-control" name="color" id="" value="" autofocus required>
                    </div>
                    <div class="col-sm-12 col-md-4 col-xl-4 inputs mb-3">
                        <label for="storeid">Measuring Unit</label>
                        <select name="measuring_unit" id="" class="form-select" required>
                            <option value="" selected disabled>Select Option</option>
                            <option value="Meter">Meter</option>
                            <option value="Centimeters">Centimeters</option>
                            <option value="Millimeters">Millimeters</option>
                            <option value="Grams">Grams</option>
                            <option value="Kilograms">Kilograms</option>
                            <option value="Pieces">Pieces</option>
                            <option value="Liter">Liter</option>
                            <option value="Inches">Inches</option>
                        </select>
                    </div>
                    <div class="col-sm-12 col-md-4 col-xl-4 inputs mb-3">
                        <label for="storeid">Selling Price</label>
                        <div class="input-group">
                            <span class="input-group-text">₹</span>
                            <input type="number" class="form-control" id="opening_balance" placeholder="0" name="selling_price" required>

                            <select class="form-select" name="selling_price_type" required>
                                <option value="with_tax">With Tax</option>
                                <option value="without_tax">Without Tax</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-sm-12 col-md-4 col-xl-4 inputs mb-3">
                        <label for="storeid">Selling GST Tax Rate(%)</label>
                        <select name="selling_gst_rate" id="" class="form-select" required>
                            <option value="" disabled selected>Select Option</option>
                            <option value="5%">5%</option>
                            <option value="12%">12%</option>
                            <option value="18%">18%</option>
                            <option value="28%">28%</option>
                        </select>
                    </div>
                    <div class="col-sm-12 col-md-4 col-xl-4 inputs mb-3">
                        <label for="storeid">Opening Stock</label>
                        <div class="input-group">
                            <input type="text" class="form-control" name="opening_stock" required>
                            <span class="input-group-text">Pcs</span>
                        </div>
                    </div>
                    <div class="col-sm-12 col-md-4 col-xl-4 inputs mb-3">
                        <label for="storeid">Purchase Price</label>
                        <div class="input-group">
                            <span class="input-group-text">₹</span>
                            <input type="number" class="form-control" id="opening_balance" placeholder="0" name="purchase_price" required>

                            <select class="form-select" name="purchase_price_type" required>
                                <option value="with_tax">With Tax</option>
                                <option value="without_tax">Without Tax</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-sm-12 col-md-4 col-xl-4 inputs mb-3">
                        <label for="storeid">Purchase GST Tax Rate(%)</label>
                        <select name="purchase_gst_rate" id="" class="form-select" required>
                            <option value="" disabled selected>Select Option</option>
                            <option value="5%">5%</option>
                            <option value="12%">12%</option>
                            <option value="18%">18%</option>
                            <option value="28%">28%</option>
                        </select>
                    </div>
                    <div class="col-sm-12 col-md-4 col-xl-4 inputs mb-3">
                        <label for="storeid">File upload</label>
                        <input type="file" class="form-control" name="product_file" id="" value="" autofocus >
                    </div>

                    <div class="col-sm-12 col-md-4 col-xl-4 inputs mb-3">
                        <label for="storeid">Description</label>
                        <input type="text" class="form-control" name="description" id="" value="" autofocus required>
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
