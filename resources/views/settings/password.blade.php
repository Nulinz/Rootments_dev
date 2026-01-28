<div class="tab-content" id="myTabContent">

    <!-- Password -->
    <div class="container px-0">
        <form action="{{ route('change_password.update') }}" method="post" id="c_form">
            @csrf
            <div class="container-fluid maindiv">
                <div class="row">
                    <div class="col-sm-12 col-md-4 col-xl-4 mb-3 px-2 inputs">
                        <label for="name">Emp Code</label>
                        <input type="text" class="form-control" name="emp_code" id="name"
                            value="{{ Auth::user()->emp_code }}" readonly>
                    </div>
                    <div class="col-sm-12 col-md-4 col-xl-4 mb-3 px-2 inputs">
                        <label for="name">Name</label>
                        <input type="text" class="form-control" id="name" readonly
                            value="{{ Auth::user()->name }}">
                    </div>
                    <div class="col-sm-12 col-md-4 col-xl-4 mb-3 px-2 inputs">
                        <label for="email">Email ID</label>
                        <input type="email" class="form-control" id="email" readonly
                            value="{{ Auth::user()->email }}">
                    </div>
                    <div class="col-sm-12 col-md-4 col-xl-4 mb-3 px-2 inputs">
                        <label for="contact">Contact Number</label>
                        <input type="number" class="form-control" id="contact" oninput="validate(this)"
                            min="1000000000" max="9999999999" readonly value="{{ Auth::user()->contact_no }}">
                    </div>
                    <div class="col-sm-12 col-md-4 col-xl-4 mb-3 px-2 inputs">
                        <label for="password1">New Password</label>
                        <div class="d-flex justify-content-between align-items-center inpflex">
                            <input type="password" id="password1" class="form-control border-0 old_password"
                                placeholder="Enter New Password" required>
                            <i class="fa-solid fa-eye-slash" id="passHide_1"
                                onclick="togglePasswordVisibility('password1', 'passShow_1', 'passHide_1')"
                                style="display:none; cursor:pointer;"></i>
                            <i class="fa-solid fa-eye" id="passShow_1"
                                onclick="togglePasswordVisibility('password1', 'passShow_1', 'passHide_1')"
                                style="cursor:pointer;"></i>
                        </div>
                    </div>
                    <div class="col-sm-12 col-md-4 col-xl-4 mb-3 px-2 inputs">
                        <label for="password2">Confirm New Password</label>
                        <div class="d-flex justify-content-between align-items-center inpflex">
                            <input type="password" name="password" id="password2" class="form-control border-0"
                                placeholder="Enter Confirm New Password" required onchange="pass_same()">
                            <i class="fa-solid fa-eye-slash" id="passHide_2"
                                onclick="togglePasswordVisibility('password2', 'passShow_2', 'passHide_2')"
                                style="display:none; cursor:pointer;"></i>
                            <i class="fa-solid fa-eye" id="passShow_2"
                                onclick="togglePasswordVisibility('password2', 'passShow_2', 'passHide_2')"
                                style="cursor:pointer;"></i>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-sm-12 col-md-12 col-xl-12 mt-3 d-flex justify-content-center align-items-center">
                <button type="submit" id="sub" class="formbtn">Save</button>
            </div>
        </form>
    </div>

</div>

<script src="{{ asset('assets/js/form_script.js') }}"></script>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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
    function pass_same() {
        const password1 = document.getElementById('password1').value;
        const password2 = document.getElementById('password2').value;

        if (password1 !== password2) {
            const Toast = Swal.mixin({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3000,
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
                icon: 'error',
                title: 'Passwords do not match. Please try again.'
            });

            document.getElementById('password2').value = '';
        }
    }
</script>
