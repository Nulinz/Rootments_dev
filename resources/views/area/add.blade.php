@extends('layouts.app')

@section('content')

    <div class="sidebodydiv px-5 py-3 mb-3">
        <div class="sidebodyback mb-3" onclick="goBack()">
            <div class="backhead">
                <h5><i class="fas fa-arrow-left"></i></h5>
                <h6>Add Area Manager Form</h6>
            </div>
        </div>
        <div class="sidebodyhead my-3">
            <h4 class="m-0">Area Manager Details</h4>
        </div>
        <form action="{{ route('area.create')}}" method="POST" id="c_form">
            @csrf
            <div class="container-fluid maindiv">
                <div class="row">
                    <div class="col-sm-12 col-md-4 col-xl-4 mb-3 inputs">
                        <label for="areaname">Area Manager Name <span>*</span></label>
                        <select class="form-select" name="areaname" id="areaname" required autofocus>
                            <option value="" selected disabled>Select Options</option>

                            @foreach ($am as $area)

                                <option value="{{$area->id}}">{{$area->name}}</option>
                            @endforeach
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
            <div class="container-fluid table-wrapper px-0">
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
                        @foreach ($cluster as $cs)
                            <tr>
                                <td>
                                    <div>
                                        <input type="checkbox" name="cl_id[]" value="{{$cs->id}}">
                                    </div>
                                </td>
                                <td>{{$cs->name}}</td>
                                <td>{{$cs->location}}</td>
                                <td>{{$cs->contact_no}}</td>
                                <td>{{$cs->email}}</td>
                                <td>{{$cs->cluster_count}}</td>
                            </tr>
                        @endforeach

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
        $('#areaname').on('change', function () {
            // Trigger an AJAX request when the page is ready
            var area_per = $(this).find('option:selected').val();
            $.ajax({
                url: '{{ route('get_area_per') }}', // Laravel route for the POST request
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}', // CSRF token for security
                    area_per: area_per, // Send the selected store ID
                },

                success: function (response) {
                    console.log(response);
                    $('#mail').val(response.email);
                    $('#contact').val(response.contact_no);
                    $('#adrs').val(response.address + ',' + response.district + ',' + response.state);
                    $('#pincode').val(response.pincode);



                },
                error: function (xhr, status, error) {

                    alert('An error occurred: ' + error);
                }
            });
        });
    </script>

@endsection