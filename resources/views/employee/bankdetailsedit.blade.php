@extends('layouts.app')

@section('content')

<div class="sidebodydiv px-5 py-3 mb-3">
    <div class="sidebodyback mb-3" onclick="goBack()">
        <div class="backhead">
            <h5><i class="fas fa-arrow-left"></i></h5>
            <h6>Edit Employee Form</h6>
        </div>
    </div>
    <div class="sidebodyhead my-3">
        <h4 class="m-0">Bank Information</h4>
    </div>
    <form action="{{ route('employee.bankupdate', ['id' => $employee->id]) }}" method="POST">
        @csrf
        <div class="container-fluid maindiv">
            <div class="row">
                <div class="col-sm-12 col-md-4 col-xl-4 mb-3 inputs">
                    <label for="bankname">Bank Name</label>
                    <input type="text" class="form-control" name="bank_name" id="bankname" placeholder="Enter Bank Name"
                        autofocus value="{{ $employee->bank_name }}">
                </div>
                <div class="col-sm-12 col-md-4 col-xl-4 mb-3 inputs">
                    <label for="bankacctholder">Account Holder Name</label>
                    <input type="text" class="form-control" name="bank_holder_name" id="bankacctholder"
                        placeholder="Enter Account Holder Name" value="{{ $employee->bank_holder_name }}">
                </div>
                <div class="col-sm-12 col-md-4 col-xl-4 mb-3 inputs">
                    <label for="bankacctno">Bank Account Number</label>
                    <input type="number" class="form-control" name="ac_no" id="bankacctno" min="0"
                        placeholder="Enter Bank Account Number" value="{{ $employee->ac_no }}">
                </div>
                <div class="col-sm-12 col-md-4 col-xl-4 mb-3 inputs">
                    <label for="ifsc">IFSC Code</label>
                    <input type="text" class="form-control" name="ifcs_code" id="ifsc" placeholder="Enter IFSC Code"
                        value="{{ $employee->ifcs_code }}">
                </div>
                <div class="col-sm-12 col-md-4 col-xl-4 mb-3 inputs">
                    <label for="bankname">Account Type</label>
                    <select class="form-select" name="acount_type" id="accttype">
                        <option value="" selected disabled>Select Options</option>
                        <option value="Savings" {{ $employee->acount_type == 'Savings' ? 'selected' : '' }}>Savings
                        </option>
                        <option value="Current" {{ $employee->acount_type == 'Current' ? 'selected' : '' }}>Current
                        </option>
                    </select>
                </div>
                <div class="col-sm-12 col-md-4 col-xl-4 mb-3 inputs">
                    <label for="bankbranch">Bank Branch</label>
                    <input type="text" class="form-control" name="bank_branch" id="bankbranch"
                        placeholder="Enter Bank Branch" value="{{ $employee->bank_branch }}">
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
                    <input type="number" class="form-control" name="base_salary" id="basic" min="0"
                        placeholder="Enter Basic Salary" value="{{ $employee->base_salary }}">
                </div>
                <div class="col-sm-12 col-md-4 col-xl-4 mb-3 inputs">
                    <label for="hra">House Rent Allowance (HRA)</label>
                    <input type="number" class="form-control" name="house_rent_allowance" id="hra" min="0"
                        placeholder="Enter House Rent Allowance (HRA)" value="{{ $employee->house_rent_allowance }}">
                </div>
                <div class="col-sm-12 col-md-4 col-xl-4 mb-3 inputs">
                    <label for="conveyance">Conveyance</label>
                    <input type="number" class="form-control" name="conveyance" id="conveyance" min="0"
                        placeholder="Enter Conveyance" value="{{ $employee->conveyance }}">
                </div>
                <div class="col-sm-12 col-md-4 col-xl-4 mb-3 inputs">
                    <label for="medical">Medical</label>
                    <input type="number" class="form-control" name="medical" id="medical" min="0"
                        placeholder="Enter Medical" value="{{ $employee->medical }}">
                </div>
                <div class="col-sm-12 col-md-4 col-xl-4 mb-3 inputs">
                    <label for="special">Special</label>
                    <input type="number" class="form-control" name="speical" id="special" min="0"
                        placeholder="Enter Special" value="{{ $employee->speical }}">
                </div>
                <div class="col-sm-12 col-md-4 col-xl-4 mb-3 inputs">
                    <label for="other">Other (Food, Education)</label>
                    <input type="number" class="form-control" name="other" id="other" min="0"
                        placeholder="Enter Other Allowance" value="{{ $employee->other }}">
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
                    <input type="number" class="form-control" name="pro_fund" id="pf" min="0"
                        placeholder="Enter Provident Fund (PF)" value="{{ $employee->pro_fund }}">
                </div>
                <div class="col-sm-12 col-md-4 col-xl-4 mb-3 inputs">
                    <label for="esi">Employment State Insurance (ESI)</label>
                    <input type="number" class="form-control" name="emp_state_insurance" id="esi" min="0"
                        placeholder="Enter Employment State Insurance (ESI)"
                        value="{{ $employee->emp_state_insurance }}">
                </div>
                <div class="col-sm-12 col-md-4 col-xl-4 mb-3 inputs">
                    <label for="ptax">Professional Tax</label>
                    <input type="number" class="form-control" name="profession_tax" id="ptax" min="0"
                        placeholder="Enter Professional Tax" value="{{ $employee->profession_tax }}">
                </div>
                <div class="col-sm-12 col-md-4 col-xl-4 mb-3 inputs">
                    <label for="it">Income Tax (IT)</label>
                    <input type="number" class="form-control" name="income_tax" id="it" min="0"
                        placeholder="Enter Income Tax" value="{{ $employee->income_tax }}">
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
                            <input type="number" class="form-control" name="performance_bonus" id="pbonus"
                                placeholder="Enter Income Tax" value="{{ $employee->performance_bonus }}">

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
                            <input type="number" class="form-control" name="net_salary" id="net" min="0"
                                placeholder="Enter Net Salary" value="{{ $employee->net_salary }}">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-sm-12 col-md-12 col-xl-12 mt-3 d-flex justify-content-center align-items-center">
            <button type="submit" class="formbtn">Save</button>
        </div>
    </form>
</div>

@endsection