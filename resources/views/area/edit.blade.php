@extends('layouts.app')

@section('content')

    <div class="sidebodydiv px-5 py-3 mb-3">
        <div class="sidebodyback mb-3" onclick="goBack()">
            <div class="backhead">
                <h5><i class="fas fa-arrow-left"></i></h5>
                <h6>Edit Area Manager Form</h6>
            </div>
        </div>
        <div class="sidebodyhead my-3">
            <h4 class="m-0">Area Manager Details</h4>
        </div>
        <form action="" method="POST">
            <div class="container-fluid maindiv">
                <div class="row">
                    <div class="col-sm-12 col-md-4 col-xl-4 mb-3 inputs">
                        <label for="areaname">Area Manager Name <span>*</span></label>
                        <select class="form-select" name="areaname" id="areaname" required autofocus>
                            <option value="" selected disabled>Select Options</option>
                            <option value=""></option>
                        </select>
                    </div>
                    <div class="col-sm-12 col-md-4 col-xl-4 mb-3 inputs">
                        <label for="mail">Email ID <span>*</span></label>
                        <input type="email" class="form-control" name="mail" id="mail" placeholder="Enter Email ID" required
                            readonly>
                    </div>
                    <div class="col-sm-12 col-md-4 col-xl-4 mb-3 inputs">
                        <label for="contact">Contact Number <span>*</span></label>
                        <input type="number" class="form-control" name="contact" id="contact" oninput="validate(this)"
                            min="1000000000" max="9999999999" placeholder="Enter Contact Number" required readonly>
                    </div>
                    <div class="col-sm-12 col-md-4 col-xl-4 mb-3 inputs">
                        <label for="altcontact">Alternate Contact Number</label>
                        <input type="number" class="form-control" name="altcontact" id="altcontact" oninput="validate(this)"
                            min="1000000000" max="9999999999" placeholder="Enter Alternate Contact Number">
                    </div>
                    <div class="col-sm-12 col-md-4 col-xl-4 mb-3 inputs">
                        <label for="adrs">Address <span>*</span></label>
                        <textarea rows="1" class="form-control" name="adrs" id="adrs" placeholder="Enter Address" required
                            readonly></textarea>
                    </div>
                    <div class="col-sm-12 col-md-4 col-xl-4 mb-3 inputs">
                        <label for="pincode">Pincode <span>*</span></label>
                        <input type="number" class="form-control" name="pincode" id="pincode" min="000000" max="999999"
                            oninput="validate_pin(this)" placeholder="Enter Pincode" required readonly>
                    </div>
                    <div class="col-sm-12 col-md-4 col-xl-4 mb-3 inputs">
                        <label for="arealoc">Area Manager Location</label>
                        <input type="text" class="form-control" name="arealoc" id="arealoc"
                            placeholder="Enter Area Manager Location">
                    </div>
                </div>
            </div>

            <div class="sidebodyhead my-3">
                <h4 class="m-0">Cluster Manager List</h4>
            </div>
            <div class="container-fluid px-0">
                <table id="dataTable" class="table">
                    <thead>
                        <tr>
                            <th>Select</th>
                            <th>Cluster Manager Name</th>
                            <th>Cluster Location</th>
                            <th>Cluster Contact Number</th>
                            <th>Cluster Email ID</th>
                            <th>Cluster Stores Count</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>
                                <div>
                                    <input type="checkbox">
                                </div>
                            </td>
                            <td>Sabari</td>
                            <td>Edappally</td>
                            <td>9876543210</td>
                            <td>sabari@gmail.com</td>
                            <td>9</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div class="col-sm-12 col-md-12 col-xl-12 mt-3 d-flex justify-content-center align-items-center">
                <a href="">
                    <button type="submit" class="formbtn">Update</button>
                </a>
            </div>
        </form>
    </div>

@endsection