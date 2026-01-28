<!-- CheckIn Modal -->
<div class="modal fade" id="checkinModal" tabindex="-1" aria-labelledby="checkinModalLabel" aria-hidden="true"
    data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form>
                <div class="modal-body">
                    <div class="modal-img mb-3">
                        <img src="{{ asset('assets/images/Check_In_2.png') }}"
                            class="d-flex mx-auto align-items-center justify-content-center">
                    </div>
                    <div class="mb-3">
                        <h2 class="text-center mb-2">Check In</h2>
                        <h6 class="text-center">Quick, Secure, and Smart Attendance tracking for everyday.</h6>
                    </div>
                </div>
                <div class="modal-footer d-flex justify-content-center align-items-center">
                    <button type="button" class="modalbtn" onclick="getLocation()">Check In</button>
                    {{-- <a href="{{ route('leave.add',['type'=>'sick']) }}"><button type="button" class="modalbtn">Leave Request</button></a> --}}
                    <a href="{{ route('leave.add') }}"><button type="button" class="modalbtn">Leave Request</button></a>
                </div>
            </form>
        </div>
    </div>
</div>