<!DOCTYPE html>
<html>

<head>
    <title>Rootments</title>

    <!-- Chrome Theme Changing -->
    <meta name="theme-color" content="#231F20">

    <!-- Favicon -->
    <link rel="icon" href="{{ asset('assets/images/favicon_2.png') }}" sizes="32*32" type="image/png">

    <!-- Bootstrap CDN -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">

    <!-- Font / Icons -->
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" />
    <link href="https://fonts.googleapis.com/css2?family=Jost:wght@500&display=swap" rel="stylesheet">

    <!-- jQuery UI -->
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">

    <!-- Stylesheets -->
    <link rel="stylesheet" href="{{ asset('assets/css/login.css') }}">

    <!-- SwalFire -->
    <link href="https://cdn.jsdelivr.net/npm/@sweetalert2/theme-dark@4/dark.css" rel="stylesheet">

    {{--
    <script src="{{ asset('firebase-messaging-sw.js') }}"></script> --}}

    <script src="https://www.gstatic.com/firebasejs/11.4.0/firebase-app-compat.js"></script>
    <script src="https://www.gstatic.com/firebasejs/11.4.0/firebase-messaging-compat.js"></script>

</head>

<style>
    input::placeholder {
        text-transform: capitalize;
    }

    .swal2-popup.swal2-toast {
        padding: 15px;
        align-items: center;
    }

    .swal2-popup {
        display: grid;
        grid-template-columns: 15% 82%;
        justify-content: space-between;
        align-items: center;
    }

    .swal2-title {
        font-size: 14px !important;
        text-transform: uppercase !important;
    }
</style>

<body>
    <div class="form-structor">
        <div class="signup">
            <div class="logo d-flex justify-content-center align-items-center" id="signup">
                <img src="{{ asset('assets/images/logo_1.png') }}" height="50px" alt="">
            </div>
            <!-- <h2 class="form-title" id="signup"><span>or</span>Sign up</h2> -->
        </div>
        <form action="{{ route('login.submit') }}" method="POST" id="log_form">
            @csrf
            <div class="login slide-up">
                <div class="center">
                    <h2 class="form-title" id="login">Log In</h2>
                    <div class="form-holder row">
                        <div class="col-10 mx-auto mb-2">
                            <input type="text" class="form-control text-uppercase" id="empcode" name="emp_code"
                                placeholder="Employee Code" required autofocus>
                        </div>
                        <div class="col-10 mx-auto mb-2">
                            <div class="inpflex">
                                <input type="password" class="form-control border-0" id="password" name="password"
                                    placeholder="Password" required>
                                <i class="fa-solid fa-eye-slash" id="passHide"
                                    onclick="togglePasswordVisibility('password', 'passShow', 'passHide')"
                                    style="display:none; cursor:pointer;"></i>
                                <i class="fa-solid fa-eye" id="passShow"
                                    onclick="togglePasswordVisibility('password', 'passShow', 'passHide')"
                                    style="cursor:pointer;"></i>
                            </div>
                        </div>
                    </div>
                    <button type="submit" id="sub" class="submit-btn">Login</button></a>
                </div>
            </div>
        </form>
    </div>


    <!-- SwalFire -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.js"></script>

    <!-- jQuery -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>

    <!-- Bootstrap -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>

<script>
    // form two
    $('#log_form').submit(function (e) {
        // e.preventDefault();
        $('#sub').prop('disabled', true).text('Logging...');
    });
</script>

<script>
    function togglePasswordVisibility(inputId, showId, hideId) {
        let $input = $('#' + inputId);
        let $passShow = $('#' + showId);
        let $passHide = $('#' + hideId);

        if ($input.attr('type') === 'password') {
            $input.attr('type', 'text');
            $passShow.hide();
            $passHide.show();
        } else {
            $input.attr('type', 'password');
            $passShow.show();
            $passHide.hide();
        }
    }
</script>

<script>
    console.clear();

    const loginBtn = document.getElementById('login');
    const signupBtn = document.getElementById('signup');

    loginBtn.addEventListener('click', (e) => {
        let parent = e.target.parentNode.parentNode;
        Array.from(e.target.parentNode.parentNode.classList).find((element) => {
            if (element !== "slide-up") {
                parent.classList.add('slide-up')
            } else {
                signupBtn.parentNode.classList.add('slide-up')
                parent.classList.remove('slide-up')
            }
        });
    });

    signupBtn.addEventListener('click', (e) => {
        let parent = e.target.parentNode;
        Array.from(e.target.parentNode.classList).find((element) => {
            if (element !== "slide-up") {
                parent.classList.add('slide-up')
            } else {
                loginBtn.parentNode.parentNode.classList.add('slide-up')
                parent.classList.remove('slide-up')
            }
        });
    });
</script>
<script>
    @if (Session::has('status'))
        const Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 5000,
            timerProgressBar: true,
            didOpen: (toast) => {
                toast.addEventListener('mouseenter', Swal.stopTimer);
                toast.addEventListener('mouseleave', Swal.resumeTimer);
            },
            customClass: {
                title: 'toast-title'
            }
        });

        Toast.fire({
            icon: "{{ Session::get('status') }}",
            title: "{{ Session::get('message') }}",
        });
    @endif
</script>


<script>
        // if ('serviceWorker' in navigator) {
        //     navigator.serviceWorker.register('/firebase-messaging-sw.js')
        //         .then((registration) => {
        //             console.log('Service Worker registered with scope:', registration.scope);

        //             // Get firebaseConfig from backend.
        //             fetch('/api/firebase-config')
        //                 .then(response => response.json())
        //                 .then(firebaseConfig => {

        //                     console.log(firebaseConfig);
        //                     // Rename keys to match Firebase SDK expectations
        //                     const config = {
        //                         apiKey: firebaseConfig.apiKey,
        //                         authDomain: firebaseConfig.authDomain,
        //                         projectId: firebaseConfig.projectId,
        //                         storageBucket: firebaseConfig.storageBucket,
        //                         messagingSenderId: firebaseConfig.messagingSenderId,
        //                         appId: firebaseConfig.appId,
        //                     };

        //                     // console.log(config);


        //                     firebase.initializeApp(config);

        //                     const messaging = firebase.messaging();

        //                     console.log(messaging);
        //                     // messaging.useServiceWorker(registration);

        //                     messaging.getToken().then((token) => {
        //                         console.log('FCM Token:', token);
        //                         sendTokenToServer(token);
        //                         // ... send token to server ...
        //                     }).catch((error) => {
        //                         console.error('Error retrieving FCM token:', error);
        //                     });

        //                     messaging.onMessage((payload) => {
        //                         console.log('Message received. ', payload);
        //                         const notificationTitle = payload.notification.title;
        //                         const notificationOptions = {
        //                             body: payload.notification.body,
        //                         };
        //                         new Notification(notificationTitle, notificationOptions);
        //                     });
        //                 })
        //                 .catch(error => {
        //                     console.error('Error fetching Firebase config:', error);
        //                 });

        //         }).catch((error) => {
        //             console.error('Service Worker registration failed:', error);
        //         });
        // }

        // function sendTokenToServer(token) {
        //     $.ajax({
        //         url: '{{ route('send_not') }}',
        //         type: 'POST',
        //         data: {
        //             _token: '{{ csrf_token() }}',
        //             not_token: token,
        //         },
        //         success: function (response) {
        //         },
        //         error: function (xhr, status, error) {
        //             alert('An error occurred: ' + error);
        //         }
        //     });
        // }

</script>



{{--
<script src="./public/firebase-messaging-sw.js"></script> --}}
{{--
<script src="https://www.gstatic.com/firebasejs/11.4.0/firebase-app-compat.js"></script>
<script src="https://www.gstatic.com/firebasejs/11.4.0/firebase-messaging-compat.js"></script>

<body>
    <script>
            const firebaseConfig = {
            apiKey: "AIzaSyDVdH_PbNRhl2YGMPronbaklLPbZCPyT4w",
            authDomain: "rootments-app.firebaseapp.com",
            projectId: "rootments-app",
            storageBucket: "rootments-app.firebasestorage.app",
            messagingSenderId: "406832035732",
            appId: "1:406832035732:web:0564580a464ee6f336c181"
        };
        firebase.initializeApp(firebaseConfig);
        const messaging = firebase.messaging();

        // console.log(firebaseConfig);

        messaging.getToken({ vapidKey: 'BODDBuf7vDtJyFsKeBLA4gNKtCF_U8AxM4F3-OHP-80fZtMGQWvWLHvLdR5VxKvq0PFQ7SFdNTBgPSwTY3NRlFE' }).then((currentToken) => {
            if (currentToken) {
                // Send the token to your server and update the UI if necessary
                // console.log('FCM registration token:', currentToken);
                //send token to server.
                sendTokenToServer(currentToken);
            } else {
                // Show permission request UI
                console.log('No registration token available. Request permission to generate one.');
                requestPermission();
            }
        }).catch((err) => {
            console.log('An error occurred while retrieving token. ', err);
        });

        // function requestPermission() {
        //     console.log('Requesting permission...');
        //     Notification.requestPermission().then((permission) => {
        //         if (permission === 'granted') {
        //             console.log('Notification permission granted.');
        //             messaging.getToken({ vapidKey: 'YOUR_VAPID_KEY' }).then((currentToken) => {
        //                 if (currentToken) {
        //                     console.log('FCM registration token:', currentToken);
        //                      sendTokenToServer(currentToken);
        //                 }
        //             });
        //         } else {
        //             console.log('Unable to get permission to notify.');
        //         }
        //     });
        // }

        function sendTokenToServer(currentToken) {
            $.ajax({
                url: '{{ route('send_not') }}', // Laravel route for the POST request
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}', // CSRF token for security
                    not_token: currentToken, // Send the selected store ID
                },

                success: function (response) {
                    // console.log(response);
                },
                error: function (xhr, status, error) {

                    alert('An error occurred: ' + error);
                }
            });

        }

        // navigator.serviceWorker.register('/firebase-messaging-sw.js')
        //     .then((registration) => {
        //         messaging.useServiceWorker(registration);
        //         console.log('Service worker registered.', registration);
        //     }); --}}
    </script>
</body>

</html>