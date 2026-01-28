<div class="container-fluid px-0 header">
    <div class="container px-0 mt-2 tabbtns">
        @php
            $user = auth()->user();

            //  dd($user->role_id, session('role_id'), Auth::user());


            $asm_count = DB::table('asm_store')->where('store_id', $user->store_id)->where('emp_id', $user->id)->count();

            // Get user role details
            // $role_get = DB::table('roles')
            //     ->join('users', 'users.role_id', '=', 'roles.id')
            //     ->where('users.id', $user->id)
            //     ->select('roles.id as role_id', 'roles.role', 'roles.role_dept')
            //     ->first();
            $r_id = $user->role_id;

            // if ($r_id == 3 || $r_id == 4 || $r_id == 5) {
            //     $route = 'hr.dashboard';
            //     $over = 'HR';
            // } elseif (($r_id == 12) || ($asm_count>0)) {
            //     $route = 'dashboard';
            //     $over = 'Store';
            // } elseif($r_id==11){
            //             $route = 'cluster.dashboard';
            //             $over = 'Cluster';
            // }elseif($r_id==10){
            //             $route = 'area.dashboard';
            //             $over = 'Area';
            // }
            // else {
            //     $route = 'mydash.dashboard';
            // }

            // Define routes and over values in an array
            $route = [
                1 => ['route' => 'gm.dashboard', 'over' => 'GM'],
                2 => ['route' => 'gm.dashboard', 'over' => 'AGM'],
                3 => ['route' => 'hr.dashboard', 'over' => 'HR'],
                4 => ['route' => 'hr.dashboard', 'over' => 'HR'],
                5 => ['route' => 'hr.dashboard', 'over' => 'HR'],
                6 => ['route' => 'operation.dashboard', 'over' => 'Operation'],
                12 => ['route' => 'dashboard', 'over' => 'Store'],
                11 => ['route' => 'cluster.dashboard', 'over' => 'Cluster'],
                10 => ['route' => 'area.dashboard', 'over' => 'Area'],
                7 => ['route' => 'fin.index', 'over' => 'Finance'],
                30 => ['route' => 'maintain.index', 'over' => 'Maintain'],
                37 => ['route' => 'warehouse.index', 'over' => 'Warehouse'],
                41 => ['route' => 'purchase.index', 'over' => 'Purchase'],
            ];

            $cluster_check = DB::table('m_cluster')
                ->where('cl_name', '=', $user->id)
                ->count();

        @endphp

        @if(array_key_exists($r_id, $route))
            <div class="my-2">
                <a href="{{ route($route[$r_id]['route']) }}"><button class="dashtabs">{{$route[$r_id]['over']}}
                        Overview</button></a>
            </div>
        @endif
        @if($r_id == 3 || $r_id == 4 || $r_id == 5)
            <div class="my-2">
                <a href="{{ route('hrkpi.dashboard') }}"><button class="dashtabs ">HR KPI
                        Dashboard</button></a>
            </div>
        @endif
        @if($r_id == 10)
            <div class="my-2">
                <a href="{{ route('area.kpidashboard') }}"><button class="dashtabs ">Area KPI
                        Dashboard</button></a>
            </div>
        @endif
        @if(($r_id == 12))
            <div class="my-2">
                <a href="{{ route('store.dashboard') }}"><button class="dashtabs ">Store
                        Dashboard</button></a>
            </div>
        @endif
        @if($r_id == 11)
            <div class="my-2">
                <a href="{{ route('cluster.strength') }}"><button class="dashtabs">Store Strength</button></a>
            </div>
        @endif

        {{-- checking the store manager as cluster manger --}}

        @if(($r_id == 12) && ($cluster_check > 0))
            <div class="my-2">
                <a href="{{ route('cluster.dashboard') }}"><button class="dashtabs">Cluster Overview</button></a>
            </div>
            <div class="my-2">
                <a href="{{ route('cluster.strength') }}"><button class="dashtabs">Store Strength</button></a>
            </div>
        @endif
        
         @if (in_array($r_id, [12, 13, 14, 15, 16, 53]))
            <div class="my-2">
                <a href="{{ route('employee.emp_dahboard') }}"><button class="dashtabs w-100">Performance Dashboard</button></a>
            </div>
        @endif


        <div class="my-2">
            <a href="{{ route('mydash.dashboard') }}"><button class="dashtabs">Task Dashboard</button></a>
        </div>
    @php

    $attd = DB::table('attendance')->where('user_id', auth()->id())->whereDate('c_on', \Carbon\Carbon::today())->exists();

    $leave = DB::table('leaves')->where('user_id', auth()->id())
    ->whereDate('start_date', '<=', \Carbon\Carbon::today())
    ->whereDate('end_date', '>=', \Carbon\Carbon::today())
    ->where('status', 'Approved')
    ->exists();



    // dd($leave);

    @endphp
        {{-- <div class="my-2">
            <a data-bs-toggle="modal" data-bs-target="#checkinModal"><button class="dashtabs">CheckIn{{ $attd ? 'true' : 'false' }}</button></a>
        </div> --}}
    </div>
</div>

@if((!$attd)&&(!$leave) &&  (!in_array(auth()->user()->role_id,[1, 2])) && !session('impersonator_id'))
    @include('generaldashboard.checkin_popup')

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Assuming you're using Bootstrap modal
            var myModal = new bootstrap.Modal(document.getElementById('checkinModal'));
            myModal.show();
        });
    </script>
@endif

<link rel="stylesheet" href="{{ asset('assets/css/dashboard_main.css') }}">