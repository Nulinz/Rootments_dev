<script>
    $(document).ready(function () {

        // Initialize DataTable
        var table = $('.example').DataTable({
            paging: true,
            searching: true,
            ordering: true,
            bDestroy: true,
            info: false,
            responsive: true,
            pageLength: 10,
            dom: '<"top"f>rt<"bottom"lp><"clear">',
        });

    });
</script>

<script>
    // Dark Theme Logo and Theme Switching
    const themeSwitch = document.querySelector('#themeSwitcher');
    const lightLogo = document.querySelector('.lightLogo');
    const darkLogo = document.querySelector('.darkLogo');
    const defaultTheme = localStorage.getItem('theme') || 'theme-light';
    setTheme(defaultTheme);
    themeSwitch.checked = defaultTheme === 'theme-dark';
    themeSwitch.addEventListener('change', () => {
        const selectedTheme = themeSwitch.checked ? 'theme-dark' : 'theme-light';
        setTheme(selectedTheme);
    });
    function setTheme(theme) {
        document.documentElement.className = theme;
        localStorage.setItem('theme', theme);

        if (theme === 'theme-dark') {
            lightLogo.style.display = 'none';
            darkLogo.style.display = 'block';
        } else {
            lightLogo.style.display = 'block';
            darkLogo.style.display = 'none';
        }
    }
</script>

<script>
    // Tooltip
    const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]');
    const tooltipList = [...tooltipTriggerList].map(tooltipTriggerEl => new bootstrap.Tooltip(tooltipTriggerEl));
</script>

<script>
    $(document).ready(function () {
        // Select2 Initialization
        $('.select2').select2({
            dropdownParent: $('#completedModal'),
            placeholder: "Select Options",
            allowClear: true,
            width: '100%'
        }).prop('required', true);


        // Drag & Drop Sorting
        if ($('#sortable-list').length) {
            new Sortable(document.getElementById('sortable-list'), {
                animation: 150,
                onEnd: function (evt) {
                    console.log('Item moved:', evt.item);
                }
            });
        }

        // Show Toastr Notifications
        function showToast(type, message) {
            toastr[type](message, 'Notification');
        }

        // SweetAlert Confirmation for Deletion
        $(document).on('click', '.delete-btn', function (e) {
            e.preventDefault();
            let url = $(this).data('url');

            Swal.fire({
                title: 'Are you sure?',
                text: "This action cannot be undone!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: url,
                        type: 'DELETE',
                        success: function (response) {
                            Swal.fire('Deleted!', response.message, 'success');
                        },
                        error: function () {
                            Swal.fire('Error', 'Something went wrong!', 'error');
                        }
                    });
                }
            });
        });

        // Global AJAX Error Handling
        // $(document).ajaxError(function (event, jqxhr, settings, thrownError) {
        //     Swal.fire('Error!', 'An error occurred: ' + thrownError, 'error');
        // });

        // Display SweetAlert Toast if Laravel Session has 'status'
        @if (Session::has('status'))
            Swal.fire({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 5000,
                timerProgressBar: true,
                icon: "{{ Session::get('status') }}",
                title: "{{ Session::get('message') }}"
            });
        @endif
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