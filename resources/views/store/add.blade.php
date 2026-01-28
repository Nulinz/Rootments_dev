@extends('layouts.app')

@section('content')
    <div class="sidebodydiv px-5 py-3 mb-3">
        <div class="sidebodyback mb-3" onclick="goBack()">
            <div class="backhead">
                <h5><i class="fas fa-arrow-left"></i></h5>
                <h6>Add Store Form</h6>
            </div>
        </div>
        <div class="sidebodyhead my-3">
            <h4 class="m-0">Store Details</h4>
        </div>
        <form action="{{ route('store') }}" method="post" id="c_form">
            @csrf
            <div class="container-fluid maindiv">
                <div class="row">
                    <div class="col-sm-12 col-md-4 col-xl-4 mb-3 inputs">
                        <label for="storeid">Store Code <span>*</span></label>
                        <input type="text" class="form-control" name="store_code" id="storeid"
                            value="{{ $store_no }}" autofocus required readonly>
                    </div>
                    <div class="col-sm-12 col-md-4 col-xl-4 mb-3 inputs">
                        <label for="storeid">Brand <span>*</span></label>
                        <select class="form-select" name="brand" id="brand" required >
                            <option>SUITOR GUY</option>
                            <option>ZORUCCI</option>
                        <select>
                    </div>
                    <div class="col-sm-12 col-md-4 col-xl-4 mb-3 inputs">
                        <label for="storename">Store Name <span>*</span></label>
                        <input type="text" class="form-control" name="store_name" id="storename"
                            placeholder="Enter Store Name" required>
                        @error('store_name')
                            <h6 class="errormsg">{{ $message }}</h6>
                        @enderror
                    </div>
                    <div class="col-sm-12 col-md-4 col-xl-4 mb-3 inputs">
                        <label for="mail">Email ID <span>*</span></label>
                        <input type="email" class="form-control" name="store_mail" id="mail"
                            placeholder="Enter Email ID" required>
                        @error('store_mail')
                            <h6 class="errormsg">{{ $message }}</h6>
                        @enderror
                    </div>
                    <div class="col-sm-12 col-md-4 col-xl-4 mb-3 inputs">
                        <label for="contact">Contact Number <span>*</span></label>
                        <input type="number" class="form-control" name="store_contact" id="contact"
                            oninput="validate(this)" min="1000000000" max="9999999999" placeholder="Enter Contact Number" required>
                        @error('store_contact')
                            <h6 class="errormsg">{{ $message }}</h6>
                        @enderror
                    </div>
                    <div class="col-sm-12 col-md-4 col-xl-4 mb-3 inputs">
                        <label for="altcontact">Alternate Contact Number</label>
                        <input type="number" class="form-control" name="store_alt_contact" id="altcontact"
                            oninput="validate(this)" min="1000000000" max="9999999999"
                            placeholder="Enter Alternate Contact Number">
                    </div>
                    <div class="col-sm-12 col-md-4 col-xl-4 mb-3 inputs">
                        <label for="storesttime">Store Start Time <span>*</span></label>
                        <input type="time" class="form-control" name="store_start_time" id="storesttime" required>
                    </div>
                    <div class="col-sm-12 col-md-4 col-xl-4 mb-3 inputs">
                        <label for="storeendtime">Store End Time <span>*</span></label>
                        <input type="time" class="form-control" name="store_end_time" id="storeendtime" required>
                    </div>
                    <div class="col-sm-12 col-md-4 col-xl-4 mb-3 inputs">
                        <label for="adrs">Address <span>*</span></label>
                        <textarea rows="1" class="form-control" name="store_address" id="adrs" placeholder="Enter Address" required></textarea>
                    </div>
                    <div class="col-sm-12 col-md-4 col-xl-4 mb-3 inputs">
                        <label for="pincode">Pincode <span>*</span></label>
                        <input type="number" class="form-control" name="store_pincode" id="pincode" min="000000"
                            max="999999" oninput="validate_pin(this)" placeholder="Enter Pincode" required>
                    </div>
                    <div class="col-sm-12 col-md-4 col-xl-4 mb-3 inputs">
                        <label for="storeloc">Store Geolocation</label>
                        <input type="text" class="form-control" name="store_geo" id="storeloc"
                            placeholder="Enter Store Location">
                    </div>
                    <div class="col-sm-12 col-md-4 col-xl-4 mb-3 inputs">
                        <label for="storeloc">Attendance Leave(%) Per day</label>
                        <input type="number" class="form-control" name="leave_per" id=""
                            placeholder="Enter Percentage (20%)">
                    </div>
                </div>
            </div>

            <div class="sidebodyhead my-3">
                <h4 class="m-0">Store Strength</h4>
            </div>
            <div class="container-fluid maindiv my-3">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Role</th>
                            <th>Required Count</th>
                            <th>Employee Count</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody class="role-list">

                    </tbody>
                </table>

            </div>

            <div class="col-sm-12 col-md-12 col-xl-12 mt-3 d-flex justify-content-center align-items-center">
                <a href="">
                    <button type="submit" id="sub" class="formbtn">Save</button>
                </a>
            </div>
        </form>
    </div>

    <script src="{{ asset('assets/js/form_script.js') }}"></script>
    <script>
       $(document).ready(function() {
    const newRow = () => {
        return `
            <tr>
                <td>
                    <div>
                        <select class="form-select role-select" name="role_id[]" required>
                            <option value="" selected disabled>Select Options</option>
                            @foreach ($role_data as $data)
                                <option value="{{ $data->id }}">{{ $data->role }}</option>
                            @endforeach
                        </select>
                    </div>
                </td>
                <td>
                    <div>
                        <input type="number" name="req_count[]" class="form-control" min="0" placeholder="Enter Required Count" required>
                    </div>
                </td>
                <td>
                    <div>
                        <input type="number" name="emp_count[]" class="form-control" min="0" placeholder="Enter Employee Count" required>
                    </div>
                </td>
                <td>
                    <div class="d-flex gap-3">
                        <a><i class="fas fa-circle-plus text-success addRow"></i></a>
                        <a><i class="fas fa-circle-minus text-danger removeRow"></i></a>
                    </div>
                </td>
            </tr>`;
    };

    $(".role-list").append(newRow()); // Ensure at least one row is added on load

    $(document).on("click", ".addRow", function() {
        $(".role-list").append(newRow());
        updateDisabledOptions();
    });

    $(document).on("click", ".removeRow", function() {
        if ($(".role-list tr").length > 1) {
            $(this).closest("tr").remove();
            updateDisabledOptions();
        }
    });

    $(document).on("change", ".role-select", function() {
        updateDisabledOptions();
    });

    const updateDisabledOptions = () => {
        const selectedValues = $(".role-select").map(function() {
            return $(this).val();
        }).get();

        $(".role-select").each(function() {
            const currentSelect = $(this);
            currentSelect.find("option").each(function() {
                const optionValue = $(this).val();
                if (selectedValues.includes(optionValue) && optionValue !== currentSelect.val()) {
                    $(this).prop("disabled", true);
                } else {
                    $(this).prop("disabled", false);
                }
            });
        });
    };
});

        $(document).ready(function(){

            var store = $('#storeid').val();

            $.ajax({
                url:"{{ route('store.check') }}",
                type:"POST",
                data:{
                    _token: '{{ csrf_token() }}',
                    store:store
                },
                success:function(res){
                    console.log(res);
                    if(res.st_code!=null){
                        $('#storename').val(res.data['st_name']);
                        $('#adrs').val(res.data['st_add']);
                        $('#pincode').val(res.data['st_pin']);
                        $('#storeloc').val(res.data['st_loc']);
                    }
                },
                error:function(error){
                    console.log(error);
                }

            });

        });

    </script>
@endsection
