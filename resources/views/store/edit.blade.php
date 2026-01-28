@extends('layouts.app')

@section('content')
    <div class="sidebodydiv px-5 py-3 mb-3">
        <div class="sidebodyback mb-3" onclick="goBack()">
            <div class="backhead">
                <h5><i class="fas fa-arrow-left"></i></h5>
                <h6>Edit Store Form</h6>
            </div>
        </div>
        <div class="sidebodyhead my-3">
            <h4 class="m-0">Store Details</h4>
        </div>
        <form action="{{ route('store.update', ['id' => $store->id]) }}" method="post">
            @csrf
            <div class="container-fluid maindiv">
                <div class="row">
                    <div class="col-sm-12 col-md-4 col-xl-4 mb-3 inputs">
                        <label for="storeid">Store Code <span>*</span></label>
                        <input type="text" class="form-control" name="store_code" id="storeid"
                            value="{{ $store->store_code }}" autofocus required>
                    </div>

                    <div class="col-sm-12 col-md-4 col-xl-4 mb-3 inputs">
                        <label for="">Brand <span>*</span></label>
                        <select class="form-select" name="brand" id="brand" required >
                            <option>{{ $store->brand }}</option>
                            <option>SUITOR GUY</option>
                            <option>ZORUCCI</option>
                        <select>
                    </div>
                    <div class="col-sm-12 col-md-4 col-xl-4 mb-3 inputs">
                        <label for="storename">Store Name <span>*</span></label>
                        <input type="text" class="form-control" name="store_name" id="storename"
                            placeholder="Enter Store Name" required value="{{ $store->store_name }}">
                    </div>
                    <div class="col-sm-12 col-md-4 col-xl-4 mb-3 inputs">
                        <label for="mail">Email ID <span>*</span></label>
                        <input type="email" class="form-control" name="store_mail" id="mail"
                            placeholder="Enter Email ID" required value="{{ $store->store_mail }}">
                    </div>
                    <div class="col-sm-12 col-md-4 col-xl-4 mb-3 inputs">
                        <label for="contact">Contact Number <span>*</span></label>
                        <input type="number" class="form-control" name="store_contact" id="contact"
                            oninput="validate(this)" min="1000000000" max="9999999999" placeholder="Enter Contact Number" required
                            value="{{ $store->store_contact }}">
                    </div>
                    <div class="col-sm-12 col-md-4 col-xl-4 mb-3 inputs">
                        <label for="altcontact">Alternate Contact Number</label>
                        <input type="number" class="form-control" name="store_alt_contact" id="altcontact"
                            oninput="validate(this)" min="1000000000" max="9999999999"
                            placeholder="Enter Alternate Contact Number" value="{{ $store->store_alt_contact }}">
                    </div>
                    <div class="col-sm-12 col-md-4 col-xl-4 mb-3 inputs">
                        <label for="storesttime">Store Start Time <span>*</span></label>
                        <input type="time" class="form-control" name="store_start_time" id="storesttime" required
                            value="{{ $store->store_start_time }}">
                    </div>
                    <div class="col-sm-12 col-md-4 col-xl-4 mb-3 inputs">
                        <label for="storeendtime">Store End Time <span>*</span></label>
                        <input type="time" class="form-control" name="store_end_time" id="storeendtime" required
                            value="{{ $store->store_end_time }}">
                    </div>
                    <div class="col-sm-12 col-md-4 col-xl-4 mb-3 inputs">
                        <label for="adrs">Address <span>*</span></label>
                        <textarea rows="1" class="form-control" name="store_address" id="adrs" placeholder="Enter Address" required>{{ $store->store_address }}</textarea>
                    </div>
                    <div class="col-sm-12 col-md-4 col-xl-4 mb-3 inputs">
                        <label for="pincode">Pincode <span>*</span></label>
                        <input type="number" class="form-control" name="store_pincode" id="pincode" min="000000"
                            max="999999" oninput="validate_pin(this)" placeholder="Enter Pincode" required
                            value="{{ $store->store_pincode }}">
                    </div>
                    <div class="col-sm-12 col-md-4 col-xl-4 mb-3 inputs">
                        <label for="storeloc">Store Geolocation</label>
                        <input type="text" class="form-control" name="store_geo" id="storeloc"
                            placeholder="Enter Store Location" value="{{ $store->store_geo }}">
                    </div>

                    <div class="col-sm-12 col-md-4 col-xl-4 mb-3 inputs">
                        <label for="storeloc">Attendance Leave(%) Per day</label>
                        <input type="number" class="form-control" name="leave_per" id=""
                            placeholder="Enter Store Location" value="{{ $store->leave_per }}">
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
                        @foreach ($storedata as $item)
                            <tr>
                                <td>
                                    <div>
                                        <select class="form-select role-select" name="role_id[]" required>
                                            <option value="" selected disabled>Select Options</option>
                                            @foreach ($role_data as $data)
                                                <option value="{{ $data->id }}"
                                                    {{ $data->id == $item->role_id ? 'selected' : '' }}>
                                                    {{ $data->role }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </td>
                                <td>
                                    <div>
                                        <input type="number" name="req_count[]" class="form-control" min="0"
                                            placeholder="Enter Required Count" required value="{{ $item->req_count }}">
                                    </div>
                                </td>
                                <td>
                                    <div>
                                        <input type="number" name="emp_count[]" class="form-control" min="0"
                                            placeholder="Enter Employee Count" required value="{{ $item->emp_count }}">
                                    </div>
                                </td>
                                <td>
                                    <div class="d-flex gap-3">
                                        <a><i class="fas fa-circle-plus text-success addRow"></i></a>
                                        <a><i class="fas fa-circle-minus text-danger removeRow"></i></a>
                                    </div>
                                </td>
                            </tr>
                        @endforeach

                    </tbody>
                </table>

            </div>

            <div class="col-sm-12 col-md-12 col-xl-12 mt-3 d-flex justify-content-center align-items-center">
                <a href="">
                    <button type="submit" class="formbtn">Save</button>
                </a>
            </div>
        </form>
    </div>
    <script>
        $(document).ready(function() {

    const newRow = () => {
        return `
            <tr>
                <td>
                    <div>
                        <select class="form-select role-select" name="role_id[]">
                            <option value="" selected disabled>Select Options</option>
                            @foreach ($role_data as $data)
                            <option value="{{ $data->id }}">{{ $data->role }}</option>
                            @endforeach
                        </select>
                    </div>
                </td>
                <td>
                    <div>
                        <input type="number" name="req_count[]" class="form-control" min="0" placeholder="Enter Required Count">
                    </div>
                </td>
                <td>
                    <div>
                        <input type="number" name="emp_count[]" class="form-control" min="0" placeholder="Enter Employee Count">
                    </div>
                </td>
                <td>
                    <div class="d-flex gap-3">
                        <a href="#" class="addRow"><i class="fas fa-circle-plus text-success"></i></a>
                        <a href="#" class="removeRow"><i class="fas fa-circle-minus text-danger"></i></a>
                    </div>
                </td>
            </tr>`;
    };

    // Ensure at least one row is added on load
    if ($(".role-list tr").length === 0) {
        $(".role-list").append(newRow());
    }

    // Add new row only if no empty row exists
    $(document).on("click", ".addRow", function(e) {
        e.preventDefault();

        let isExisting = false;
        $(".role-select").each(function() {
            if ($(this).val() === "") {
                isExisting = true;
            }
        });

        if (!isExisting) {
            $(".role-list").append(newRow());
            updateDisabledOptions();
        }
    });

    // Prevent removing the last row
    $(document).on("click", ".removeRow", function(e) {
        e.preventDefault();
        if ($(".role-list tr").length > 1) {
            $(this).closest("tr").remove();
            updateDisabledOptions();
        }
    });

    // Update disabled options for select elements
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

    // Update disabled options when a role is selected
    $(document).on("change", ".role-select", function() {
        updateDisabledOptions();
    });

});

    </script>
@endsection
