<aside>
    <div class="flex-shrink-0 sidebar">
        <div class="nav col-md-11">
            <a href="./index.php" class="w-100">
                <img src="{{ asset('assets/images/logo.png') }}" alt="" height="50px" class="mx-auto lightLogo">
            </a>
            <a href="./index.php" class="w-100">
                <img src="{{ asset('assets/images/logo_1.png') }}" alt="" height="50px" class="mx-auto darkLogo"
                    style="display: none;">
            </a>
        </div>

        <ul class="list-unstyled ps-0" style="margin-top: 30px;">
            @include ('layouts.menu')
        </ul>

    </div>
</aside>

<!-- Responsive Sidebar -->
<div class="offcanvas offcanvas-start" tabindex="-1" id="offcanvasExample" aria-labelledby="offcanvasExampleLabel">
    <div class="offcanvas-header">
        <!-- user.blade.php -->
        @include('layouts.user')
        <button type="button" class="btn-close bg-white" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body">
        <div class="flex-shrink-0 sidebar">
            <ul class="list-unstyled mt-1 ps-0">
                @include ('layouts.menu')
            </ul>

            <ul class="list-unstyled lgt">
                <li class="mb-1">
                    <a href="{{ route('settings') }}">
                        <button class="asidebtn mx-auto">
                            <div class="btnname">
                                <i class="fas fa-gear"></i> &nbsp;Settings
                            </div>
                        </button>
                    </a>
                </li>
                <li class="mb-1">
                    <a href="">
                        <button class="asidebtn mx-auto">
                            <div class="btnname">
                                <i class="fas fa-bell"></i> &nbsp;Notifications
                            </div>
                        </button>
                    </a>
                </li>
            </ul>
        </div>
    </div>
</div>

<script>
   function getLocation() {
        const storeCoordinates =
            "{{ auth()->user()->store_rel->store_cordinates ?? 'no' }}"; // Example: "37.7749,-122.4194"
        const [targetLat, targetLng] = storeCoordinates.split(',').map(Number);

        console.log("Target Coordinates:", targetLat, targetLng);

        $('.attd').hide();
        $('#checkinModal').hide();
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(
                function(position) {
                    // Get latitude and longitude
                    var latitude = position.coords.latitude;
                    var longitude = position.coords.longitude;
                    const accuracy = position.coords.accuracy; // meters



                    // latitude1 = parseFloat(position.coords.latitude.toFixed(6));
                    // longitude1 = parseFloat(position.coords.longitude.toFixed(6));

                    console.log("Current Coordinates:", latitude, longitude, "Accuracy:", accuracy);
                    var store_emp = "{{ auth()->user()->store_id }}";

                    if (store_emp !== null && store_emp !== '') {
                        // alert('You are not assigned to any store. Please contact admin.');
                        if (storeCoordinates === 'no') {
                            alert('Store coordinates not set. Please contact admin.');
                            location.reload();
                            return;
                        }
                        // Calculate distance in meters
                        var distance = getDistanceFromLatLonInM(latitude, longitude, targetLat, targetLng);

                        console.log("Distance: " + distance + " meters");

                        if (distance <= 1000) {
                            console.log("✅ Within 1000 meters");
                        } else {
                            console.log("❌ Outside 1000 meters");
                            alert('You are outside the allowed range for attendance marking.');
                            location.reload();

                        }

                        return;
                    }


                    // return;



                    // console.log(latitude,longitude);

                    $.ajax({
                        url: "{{ route('get.coordinates') }}", // Make sure this matches your route
                        type: 'POST',
                        dataType: 'json', // Expecting a JSON response
                        data: {
                            latitude: latitude,
                            longitude: longitude,
                            _token: '{{ csrf_token() }}' // CSRF token for security
                        },
                        success: function(data) {
                            if (data.status == 'success') {
                                // Store the status and message in the session via JavaScript
                                @if (Session::has('status') && Session::has('message'))

                                    const Toast = Swal.mixin({
                                        toast: true,
                                        position: 'top-end',
                                        showConfirmButton: false,
                                        timer: 3000,
                                        timerProgressBar: true,
                                        didOpen: (toast) => {
                                            toast.addEventListener('mouseenter', Swal
                                                .stopTimer);
                                            toast.addEventListener('mouseleave', Swal
                                                .resumeTimer);
                                        },
                                        customClass: {
                                            title: 'toast-title'
                                        }
                                    });

                                    Toast.fire({
                                        icon: "{{ Session::get('status') }}",
                                        title: "{{ Session::get('message') }}",
                                    })
                                @endif
                            }
                            window.location.reload();
                        },
                        error: function(xhr, status, error) {
                            // Handle errors here
                            // window.location.reload();
                            //  console.error('Error:', error);
                            // alert('Something went wrong.');
                        }
                    });
                },
                function(error) { // ✅ error callback (must be a function)
                    console.error("Geolocation error:", error);
                    alert("Unable to fetch location. Please allow location access.");
                }, {
                    enableHighAccuracy: true,
                    timeout: 15000,
                    maximumAge: 0
                }
            );
        }
    }

    // Function to calculate distance between two coordinates
    function getDistanceFromLatLonInM(lat1, lon1, lat2, lon2) {
        const R = 6371e3; // radius of Earth in meters
        const toRad = (x) => (x * Math.PI) / 180;

        const dLat = toRad(lat2 - lat1);
        const dLon = toRad(lon2 - lon1);

        const a =
            Math.sin(dLat / 2) * Math.sin(dLat / 2) +
            Math.cos(toRad(lat1)) * Math.cos(toRad(lat2)) *
            Math.sin(dLon / 2) * Math.sin(dLon / 2);

        const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));

        return R * c; // Distance in meters
    }
    // getLocation();
</script>