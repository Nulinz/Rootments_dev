@php
    use Carbon\Carbon;
    use Illuminate\Support\Facades\Auth;
    use Illuminate\Support\Facades\DB;
    $auth_id = Auth::id();
    $user = DB::table('attendance')
        ->leftJoin('users', 'attendance.user_id', '=', 'users.id')
        ->where('users.id', $auth_id)
        ->whereDate('c_on', date('Y-m-d'))
        ->select('attendance.in_time', 'attendance.c_on')
        ->first();
    // Define your preferred Asian timezone
    $timezone = 'Asia/Kolkata';
    if ($user) {
        $in_time = !empty($user->in_time)
            ? Carbon::parse($user->in_time)->setTimezone($timezone)->format('h:i a')
            : 'N/A';

        $c_on = !empty($user->c_on)
            ? Carbon::parse($user->c_on)->setTimezone($timezone)->format('d-m-Y')
            : 'N/A';
    } else {
        $in_time = 'No Data';
        $c_on = 'No Data';
    }
@endphp

<div class="user" data-bs-toggle="tooltip" data-bs-title="{{$in_time . ' - ' . $c_on }}">
    @php
        $auth_id = Auth::user()->id;

        $role = DB::table('users')
            ->leftJoin('roles', 'users.role_id', '=', 'roles.id')
            ->leftJoin('stores', 'users.store_id', '=', 'stores.id')
            ->where('users.id', $auth_id)
            ->select(
                'roles.role',
                'roles.role_dept',
                'stores.store_name',
                'stores.id as store_id',
                'users.profile_image',
            )
            ->first();
    @endphp
    <img src="{{ asset($role->profile_image ?? 'assets/images/avatar.png') }}" width="45px" height="45px"
        class="rounded-5" alt="Profile Image">

    <h6 class="px-3 py-1 m-0">
        <span class="username">{{ Auth::user()->name }}</span>
        <br>
        <span class="userrole"> {{ $role->role_dept ?? 'No role assigned' }} -
            {{ $role->role ?? 'No role assigned' }}</span><br>
        <span class="userrole">{{ $role->store_name ?? 'No Store assigned' }}</span>
    </h6>
</div>