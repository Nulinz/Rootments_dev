@extends('layouts.app')

@section('content')
    @php
        // dd($store);
    @endphp

    <div class="sidebodydiv mb-3 px-5 py-3">
        <div class="sidebodyback mb-3" onclick="goBack()">
            <div class="backhead">
                <h5><i class="fas fa-arrow-left"></i></h5>
                <h6>Edit Cluster Form</h6>
            </div>
        </div>
        <div class="sidebodyhead my-3">
            <h4 class="m-0">Cluster Details</h4>
        </div>
        <form action="{{ route('cluster.edit_update') }}" method="POST" id="c_form">
            @csrf
            <input type="hidden" name="edit_id" value="{{ $check->id }}">
            <div class="container-fluid maindiv">
                <div class="row">
                    <div class="col-sm-12 col-md-4 col-xl-4 inputs mb-3">
                        <label for="clustername">Cluster Name <span>*</span></label>
                        <select class="form-select" name="clustername" id="clustername" required autofocus>
                            <option value="" selected disabled>Select Options</option>

                            @foreach ($cluster as $index => $value)
                                <option value="{{ $value->id }}" {{ $check->cl_name == $value->id ? 'selected' : '' }}>
                                    {{ $value->name }}
                                </option>
                            @endforeach

                            {{-- <option value=""></option> --}}
                        </select>
                    </div>
                    <div class="col-sm-12 col-md-4 col-xl-4 inputs mb-3">
                        <label for="mail">Email ID <span>*</span></label>
                        <input type="email" class="form-control" name="mail" id="mail"
                            value="{{ $check->user_data->email }}" placeholder="Enter Email ID" required readonly>
                    </div>
                    <div class="col-sm-12 col-md-4 col-xl-4 inputs mb-3">
                        <label for="contact">Contact Number <span>*</span></label>
                        <input type="number" class="form-control" name="contact" id="contact" oninput="validate(this)"
                            min="1000000000" max="9999999999" value="{{ $check->user_data->contact_no }}"
                            placeholder="Enter Contact Number" required readonly>
                    </div>
                    <div class="col-sm-12 col-md-4 col-xl-4 inputs mb-3">
                        <label for="altcontact">Alternate Contact Number</label>
                        <input type="number" class="form-control" name="altcontact" id="altcontact"
                            oninput="validate(this)" min="1000000000" max="9999999999" value="{{ $check->alter_con }}"
                            placeholder="Enter Alternate Contact Number">
                    </div>
                    <div class="col-sm-12 col-md-4 col-xl-4 inputs mb-3">
                        <label for="adrs">Address <span>*</span></label>
                        <textarea rows="1" class="form-control" name="adrs" id="adrs" value="{{ $check->user_data->address }}"
                            placeholder="Enter Address" required readonly></textarea>
                    </div>
                    <div class="col-sm-12 col-md-4 col-xl-4 inputs mb-3">
                        <label for="pincode">Pincode <span>*</span></label>
                        <input type="number" class="form-control" name="pincode" id="pincode" min="000000"
                            max="999999" oninput="validate_pin(this)" value="{{ $check->user_data->pincode }}"
                            placeholder="Enter Pincode" required readonly>
                    </div>
                    <div class="col-sm-12 col-md-4 col-xl-4 inputs mb-3">
                        <label for="storeloc">Cluster Location</label>
                        <input type="text" class="form-control" name="storeloc" id="storeloc"
                            value="{{ $check->location }}" placeholder="Enter Cluster Location">
                    </div>
                </div>
            </div>

            <div class="sidebodyhead my-3">
                <h4 class="m-0">Stores List</h4>
            </div>
            <div class="container-fluid table-wrapper px-0">
                <table id="dataTable" class="table">
                    <thead>
                        <tr>
                            <th>Select</th>
                            <th>Store Code</th>
                            <th>Store Name</th>
                            <th>Store Manager</th>
                            <th>Location</th>
                            <th>Contact Number</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($stores as $st)
                            <tr>
                                <td>
                                    <div>
                                        <input type="checkbox" name="store[]" value="{{ $st->id }}"
                                            {{ $st->is_matched ? 'checked disabled' : '' }}>
                                    </div>
                                </td>
                                <td>{{ $st->store_code }}</td>
                                <td>{{ $st->store_name }}</td>
                                <td>{{ $st->user_name ?? 'No Manager' }}</td>
                                <td>{{ $st->store_geo }}</td>
                                <td>{{ $st->store_contact }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
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
        $('#clustername').on('change', function() {
            // Trigger an AJAX request when the page is ready
            var cluster_per = $(this).find('option:selected').val();
            $.ajax({
                url: '{{ route('get_cluster_per') }}', // Laravel route for the POST request
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}', // CSRF token for security
                    cluster_per: cluster_per, // Send the selected store ID
                },

                success: function(response) {
                    console.log(response);
                    $('#mail').val(response.email);
                    $('#contact').val(response.contact_no);
                    $('#adrs').val(response.address + ',' + response.district + ',' + response.state);
                    $('#pincode').val(response.pincode);

                    // $.each(response.store, function (index, value) {
                    //     // Create a new table row
                    //     var row = '<tr>' +
                    //         '<td><div><input type="checkbox" name="store[]" value="' + value
                    //             .store_ref_id + '"></div></td>' +
                    //         '<td>' + value.store_name + '</td>' +
                    //         '<td>' + value.store_code + '</td>' +
                    //         '<td>' + value.user_name + '</td>' +
                    //         '<td>' + value.store_geo + '</td>' +
                    //         '<td>' + value.store_contact + '</td>' +
                    //         '</tr>';

                    //     // Append the new row to the tbody
                    //     $('tbody').append(row);
                    // });

                },
                error: function(xhr, status, error) {

                    alert('An error occurred: ' + error);
                }
            });
        });
    </script>
@endsection
