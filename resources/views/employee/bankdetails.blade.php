@extends('layouts.app')

@section('content')

<div class="sidebodydiv px-5 py-3 mb-3">
    <div class="sidebodyback mb-3" onclick="goBack()">
        <div class="backhead">
            <h5><i class="fas fa-arrow-left"></i></h5>
            <h6>Add Employee Form</h6>
        </div>
    </div>
    <div class="sidebodyhead my-3">
        <h4 class="m-0">Bank Information</h4>
    </div>
    <form action="{{ route('employee.bankstore', ['id' => $id]) }}" method="POST" id="c_form">
        @csrf
        <div class="container-fluid maindiv">
            <div class="row">
                <div class="col-sm-12 col-md-4 col-xl-4 mb-3 inputs">
                    <label for="bankname">Bank Name</label>
                    <input type="text" class="form-control" name="bank_name" id="bankname" placeholder="Enter Bank Name"
                        autofocus>
                </div>
                <div class="col-sm-12 col-md-4 col-xl-4 mb-3 inputs">
                    <label for="bankacctholder">Account Holder Name</label>
                    <input type="text" class="form-control" name="bank_holder_name" id="bankacctholder"
                        placeholder="Enter Account Holder Name">
                </div>
                <div class="col-sm-12 col-md-4 col-xl-4 mb-3 inputs">
                    <label for="bankacctno">Account Number</label>
                    <input type="number" class="form-control" name="ac_no" id="bankacctno" min="0"
                        placeholder="Enter Account Number">
                </div>
                <div class="col-sm-12 col-md-4 col-xl-4 mb-3 inputs">
                    <label for="ifsc">IFSC Code</label>
                    <input type="text" class="form-control" name="ifcs_code" id="ifsc" placeholder="Enter IFSC Code"
                       >
                </div>
                <div class="col-sm-12 col-md-4 col-xl-4 mb-3 inputs">
                    <label for="bankname">Account Type</label>
                    <select class="form-select" name="acount_type" id="accttype">
                        <option value="" selected disabled>Select Options</option>
                        <option value="Savings">Savings</option>
                        <option value="Current">Current</option>
                    </select>
                </div>
                <div class="col-sm-12 col-md-4 col-xl-4 mb-3 inputs">
                    <label for="bankbranch">Bank Branch</label>
                    <input type="text" class="form-control" name="bank_branch" id="bankbranch"
                        placeholder="Enter Bank Branch">
                </div>
            </div>
        </div>

        <div class="sidebodyhead my-3">
            <h4 class="m-0">Salary & Allowances</h4>
        </div>
        <div class="container-fluid maindiv">
            <div class="row">
                <div class="col-sm-12 col-md-4 col-xl-4 mb-3 inputs">
                    <label for="basic">Basic Salary</label>
                    <input type="number" class="form-control" name="base_salary" id="basic" min="0" value="0"
                        placeholder="Enter Basic Salary">
                </div>
                <div class="col-sm-12 col-md-4 col-xl-4 mb-3 inputs">
                    <label for="hra">House Rent Allowance (HRA)</label>
                    <input type="number" class="form-control" name="house_rent_allowance" id="hra" min="0" value="0"
                        placeholder="Enter House Rent Allowance (HRA)">
                </div>
                <div class="col-sm-12 col-md-4 col-xl-4 mb-3 inputs">
                    <label for="conveyance">Conveyance</label>
                    <input type="number" class="form-control" name="conveyance" id="conveyance" min="0" value="0"
                        placeholder="Enter Conveyance">
                </div>
                <div class="col-sm-12 col-md-4 col-xl-4 mb-3 inputs">
                    <label for="medical">Medical</label>
                    <input type="number" class="form-control" name="medical" id="medical" min="0" value="0"
                        placeholder="Enter Medical">
                </div>
                <div class="col-sm-12 col-md-4 col-xl-4 mb-3 inputs">
                    <label for="special">Special</label>
                    <input type="number" class="form-control" name="speical" id="special" min="0" value="0"
                        placeholder="Enter Special">
                </div>
                <div class="col-sm-12 col-md-4 col-xl-4 mb-3 inputs">
                    <label for="other">Other (Food, Education)</label>
                    <input type="number" class="form-control" name="other" id="other" min="0" value="0"
                        placeholder="Enter Other Allowance">
                </div>
            </div>
        </div>

        <div class="sidebodyhead my-3">
            <h4 class="m-0">Deductions</h4>
        </div>
        <div class="container-fluid maindiv">
            <div class="row">
                <div class="col-sm-12 col-md-4 col-xl-4 mb-3 inputs">
                    <label for="pf">Provident Fund (PF)</label>
                    <input type="number" class="form-control" name="pro_fund" id="pf" min="0" value="0"
                        placeholder="Enter Provident Fund (PF)">
                </div>
                <div class="col-sm-12 col-md-4 col-xl-4 mb-3 inputs">
                    <label for="esi">Employment State Insurance (ESI)</label>
                    <input type="number" class="form-control" name="emp_state_insurance" id="esi" min="0" value="0"
                        placeholder="Enter Employment State Insurance (ESI)">
                </div>
                <div class="col-sm-12 col-md-4 col-xl-4 mb-3 inputs">
                    <label for="ptax">Professional Tax</label>
                    <input type="number" class="form-control" name="profession_tax" id="ptax" min="0" value="0"
                        placeholder="Enter Professional Tax">
                </div>
                <div class="col-sm-12 col-md-4 col-xl-4 mb-3 inputs">
                    <label for="it">Income Tax (IT)</label>
                    <input type="number" class="form-control" name="income_tax" id="it" min="0" value="0"
                        placeholder="Enter Income Tax">
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-sm-12 col-md-4 col-xl-4">
                <div class="sidebodyhead my-3">
                    <h4 class="m-0">Bonus & Incentives</h4>
                </div>
                <div class="container-fluid maindiv">
                    <div class="row">
                        <div class="col-sm-12 col-md-12 col-xl-12 mb-3 inputs">
                            <label for="pbonus">Performance Bonus</label>
                            <input type="number" class="form-control" name="performance_bonus" id="pbonus" min="0" value="0"
                                placeholder="Enter Performance Bonus">
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-sm-12 col-md-4 col-xl-4">
                <div class="sidebodyhead my-3">
                    <h4 class="m-0">Net Salary</h4>
                </div>
                <div class="container-fluid maindiv">
                    <div class="row">
                        <div class="col-sm-12 col-md-12 col-xl-12 mb-3 inputs">
                            <label for="net">Net Salary</label>
                            <input type="number" class="form-control" name="net_salary" id="net" min="0" value="0"
                                placeholder="Enter Net Salary">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-sm-12 col-md-12 col-xl-12 mt-3 d-flex justify-content-center align-items-center">
            <button type="submit" id="sub" class="formbtn">Save</button>
        </div>
    </form>
</div>

<script src="{{ asset('assets/js/form_script.js') }}"></script>

@endsection
