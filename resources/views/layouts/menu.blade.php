@php
    $user = auth()->user();
    // Get user role details
    $role_get = DB::table('roles')
        ->join('users', 'users.role_id', '=', 'roles.id')
        ->where('users.id', $user->id)
        ->select('roles.id as role_id', 'roles.role', 'roles.role_dept')
        ->first();
    $r_id = $user->role_id;
    $route = [
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

@endphp
<div id="menuContainer">
    <li class="mb-1" id="menu-dashboard">
        <a href="{{ route($route[$r_id]['route'] ?? 'mydash.dashboard') }}">
            <button class="asidebtn collapsed {{ Request::routeIs('dashboard.*') ? 'active' : '' }} mx-auto" data-bs-toggle="collapse" data-bs-target="#collapse1"
                aria-expanded="false">
                <div class="btnname">
                    <i class="bx bxs-dashboard"></i> &nbsp;Dashboard
                </div>
                <div class="righticon d-flex ms-auto">
                    <i class="fa-solid fa-angle-right"></i>
                </div>
            </button>
        </a>
    </li>
    @if (hasAccess($r_id, 'store_audit'))
        <li class="mb-1" id="menu-store">
            <button class="asidebtn collapsed {{ Request::routeIs('store.*') && !Request::routeIs('store.walkin*') ? 'active' : '' }} mx-auto" data-bs-toggle="collapse"
                data-bs-target="#collapse3" aria-expanded="false">
                <div class="btnname">
                    <i class="fa-solid fa-shop"></i> &nbsp;Store
                </div>
                <div class="righticon d-flex ms-auto">
                    <i class="fa-solid fa-angle-right toggle-icon"></i>
                </div>
            </button>
            <div class="collapse" id="collapse3">
                <ul class="btn-toggle-nav list-unstyled pb-3 pe-0 ps-5 text-start">
                    @if (hasAccess($r_id, 'store'))
                        <li><a href="{{ route('store.index') }}" class="d-inline-flex text-decoration-none mt-3 rounded">Store
                                List</a>
                        </li>
                    @endif
                    <li><a href="{{ route('store.audit') }}" class="d-inline-flex text-decoration-none rounded">Store
                            Audit</a>
                    </li>
                    @if (hasAccess($r_id, 'store_target'))
                        <li><a href="{{ route('store.target') }}" class="d-inline-flex text-decoration-none rounded">Store
                                Target</a>
                        </li>
                    @endif
                </ul>
            </div>
        </li>
    @endif
    @if (hasAccess($r_id, 'store_setup'))
        <li class="mb-1">
            <button class="asidebtn collapsed {{ Request::routeIs('setup.*') ? 'active' : '' }} mx-auto" data-bs-toggle="collapse" data-bs-target="#collapse13"
                aria-expanded="false">
                <div class="btnname">
                    <i class="fa-solid fa-shop-lock"></i> &nbsp;Store Setup
                </div>
                <div class="righticon d-flex ms-auto">
                    <i class="fa-solid fa-angle-right toggle-icon"></i>
                </div>
            </button>
            <div class="collapse" id="collapse13">
                <ul class="btn-toggle-nav list-unstyled pb-3 pe-0 ps-5 text-start">
                    <li><a href="{{ route('setup.list') }}" class="d-inline-flex text-decoration-none mt-3 rounded">Setup
                            List</a>
                    </li>
                </ul>
            </div>
        </li>
    @endif
    @if (hasAccess($r_id, 'employee'))
        <li class="mb-1" id="menu-employee">
            <button class="asidebtn collapsed {{ Request::routeIs('employee.*') ? 'active' : '' }} mx-auto" data-bs-toggle="collapse" data-bs-target="#collapse2"
                aria-expanded="false">
                <div class="btnname">
                    <i class="fa-solid fa-user"></i> &nbsp;Employee
                </div>
                <div class="righticon d-flex ms-auto">
                    <i class="fa-solid fa-angle-right toggle-icon"></i>
                </div>
            </button>
            <div class="collapse" id="collapse2">
                <ul class="btn-toggle-nav list-unstyled pb-3 pe-0 ps-5 text-start">

                    <li><a href="{{ route('employee.index') }}" class="d-inline-flex text-decoration-none mt-3 rounded">Employee List</a>
                    </li>
                    @if (in_array($r_id, [1, 2, 3]))
                        <li><a href="{{ route('employee.term_list') }}" class="d-inline-flex text-decoration-none rounded">Termination List</a>
                        </li>
                        <li><a href="{{ route('employee.resignation') }}" class="d-inline-flex text-decoration-none rounded">Completed Resignation List</a></li>
                    @endif
                    @if ($r_id == 12 || $r_id == 3)
                        <li><a href="{{ route('employee.leave_report') }}" class="d-inline-flex text-decoration-none rounded">Leave Report</a></li>

                        @if ($r_id == 12)
                            <li><a href="{{ route('attendance.ind_report') }}" class="d-inline-flex text-decoration-none rounded">Attendance Report</a></li>

                            <li><a href="{{ route('employee.emp_target') }}" class="d-inline-flex text-decoration-none rounded">DSR-Sale Target</a></li>

                            <li><a href="{{ route('employee.emp_performance_target') }}" class="d-inline-flex text-decoration-none rounded">DSR-Rental Target</a></li>
                        @endif
                    @endif
                </ul>
            </div>
        </li>
    @endif
    @if (hasAccess($r_id, 'walk_in'))
        <li class="mb-1" id="menu-walkin">
            <button class="asidebtn collapsed {{ Request::routeIs('store.walkin*') ? 'active' : '' }} mx-auto" data-bs-toggle="collapse" data-bs-target="#collapse17"
                aria-expanded="false">
                <div class="btnname">
                    <i class="fa-solid fa-comments"></i> &nbsp;Walk-In
                </div>
                <div class="righticon d-flex ms-auto">
                    <i class="fa-solid fa-angle-right"></i>
                </div>
            </button>
            <div class="collapse mt-3" id="collapse17">
                <ul class="btn-toggle-nav list-unstyled pb-3 pe-0 ps-5 text-start">

                    @if (!in_array($r_id, [1, 2, 6, 10, 11, 27, 66]))
                        <li>
                            <a href="{{ route('store.walkin') }}" class="d-inline-flex text-decoration-none rounded">
                                Walkin List
                            </a>
                        </li>
                    @endif
                    <li><a href="{{ route('store.walkinlist') }}" class="d-inline-flex text-decoration-none rounded">Walkin Report</a></li>
                    <!--@if (hasAccess($r_id, 'all_task'))
-->
                    <!--
@endif-->
                </ul>
            </div>
        </li>
    @endif
    @if (hasAccess($r_id, 'cluster'))
        <li class="mb-1">
            <button class="asidebtn collapsed {{ Request::routeIs('cluster.*') ? 'active' : '' }} mx-auto" data-bs-toggle="collapse" data-bs-target="#collapse10"
                aria-expanded="false">
                <div class="btnname">
                    <i class="fas fa-users-gear"></i> &nbsp;Cluster
                </div>
                <div class="righticon d-flex ms-auto">
                    <i class="fa-solid fa-angle-right toggle-icon"></i>
                </div>
            </button>
            <div class="collapse" id="collapse10">
                <ul class="btn-toggle-nav list-unstyled pb-3 pe-0 ps-5 text-start">
                    <li><a href="{{ route('cluster.index') }}" class="d-inline-flex text-decoration-none mt-3 rounded">Cluster
                            List</a>
                    </li>
                </ul>
            </div>
        </li>
    @endif
    @if (hasAccess($r_id, 'area'))
        <li class="mb-1">
            <button class="asidebtn collapsed {{ Request::routeIs('area.*') ? 'active' : '' }} mx-auto" data-bs-toggle="collapse" data-bs-target="#collapse11"
                aria-expanded="false">
                <div class="btnname">
                    <i class="fa-solid fa-chart-area"></i> &nbsp;Area
                </div>
                <div class="righticon d-flex ms-auto">
                    <i class="fa-solid fa-angle-right toggle-icon"></i>
                </div>
            </button>
            <div class="collapse" id="collapse11">
                <ul class="btn-toggle-nav list-unstyled pb-3 pe-0 ps-5 text-start">
                    <li><a href="{{ route('area.list') }}" class="d-inline-flex text-decoration-none mt-3 rounded">Area
                            List</a>
                    </li>
                </ul>
            </div>
        </li>
    @endif
    @if (hasAccess($r_id, 'task'))
        <li class="mb-1" id="menu-task">
            <button class="asidebtn collapsed {{ Request::routeIs('task.*') ? 'active' : '' }} mx-auto" data-bs-toggle="collapse" data-bs-target="#collapse4"
                aria-expanded="false">
                <div class="btnname">
                    <i class="fa-solid fa-list-check"></i> &nbsp;Task
                </div>
                <div class="righticon d-flex ms-auto">
                    <i class="fa-solid fa-angle-right toggle-icon"></i>
                </div>
            </button>
            <div class="collapse mt-3" id="collapse4">
                <ul class="btn-toggle-nav list-unstyled pb-3 pe-0 ps-5 text-start">
                    @if (hasAccess($r_id, 'all_task'))
                        <li><a href="{{ route('task.index') }}" class="d-inline-flex text-decoration-none rounded">Task
                                List</a>
                        </li>
                    @endif
                    @if (in_array($r_id, [1, 2]))
                        <li><a href="{{ route('auto_task_list') }}" class="d-inline-flex text-decoration-none rounded">Auto Task
                                List</a>
                    @endif
                    <li><a href="{{ route('task.completed-task') }}" class="d-inline-flex text-decoration-none rounded">Completed Task
                            List</a>
                    </li>
                    <li><a href="{{ route('task.not-completed-task') }}" class="d-inline-flex text-decoration-none rounded">Not
                            Completed Task
                            List</a>
                    </li>

                </ul>
            </div>
        </li>
    @endif
    @if (hasAccess($r_id, 'payroll'))
        <li class="mb-1">
            <button class="asidebtn collapsed {{ Request::routeIs('recruit.*') ? 'active' : '' }} mx-auto" data-bs-toggle="collapse" data-bs-target="#collapse8"
                aria-expanded="false">
                <div class="btnname">
                    <i class="fa-solid fa-user-plus"></i> &nbsp;Recruitment
                </div>
                <div class="righticon d-flex ms-auto">
                    <i class="fa-solid fa-angle-right toggle-icon"></i>
                </div>
            </button>
            <div class="collapse" id="collapse8">
                <ul class="btn-toggle-nav list-unstyled pb-3 pe-0 ps-5 text-start">
                    <li><a href="{{ route('recruit.list') }}" class="d-inline-flex text-decoration-none mt-3 rounded">Job
                            Posting</a>
                    </li>
                </ul>
            </div>
        </li>
    @endif
    @if (hasAccess($r_id, 'payroll'))
        <li class="mb-1">
            <button class="asidebtn collapsed {{ Request::routeIs('resign.*') ? 'active' : '' }} mx-auto" data-bs-toggle="collapse" data-bs-target="#collapse12"
                aria-expanded="false">
                <div class="btnname">
                    <i class="fa-solid fa-user-xmark"></i> &nbsp;Resignation
                </div>
                <div class="righticon d-flex ms-auto">
                    <i class="fa-solid fa-angle-right toggle-icon"></i>
                </div>
            </button>
            <div class="collapse" id="collapse12">
                <ul class="btn-toggle-nav list-unstyled pb-3 pe-0 ps-5 text-start">
                    <li><a href="{{ route('resign.list') }}" class="d-inline-flex text-decoration-none mt-3 rounded">Resignation
                            List</a>
                    </li>
                </ul>
            </div>
        </li>
    @endif
    @if (hasAccess($r_id, 'purchase'))
        <li class="mb-1">
            <button class="asidebtn collapsed {{ Request::routeIs('purchase.*') ? 'active' : '' }} mx-auto" data-bs-toggle="collapse" data-bs-target="#collapse19"
                aria-expanded="false">
                <div class="btnname">
                    <i class="fa-solid fa-cart-shopping"></i> &nbsp;Purchase
                </div>
                <div class="righticon d-flex ms-auto">
                    <i class="fa-solid fa-angle-right toggle-icon"></i>
                </div>
            </button>
            <div class="collapse" id="collapse19">
                <ul class="btn-toggle-nav list-unstyled pb-3 pe-0 ps-5 text-start">
                    <li><a href="{{ route('purchase.vendor_list') }}" class="d-inline-flex text-decoration-none mt-3 rounded">Vendor
                            List</a>
                    </li>
                    <li><a href="{{ route('purchase.product_list') }}" class="d-inline-flex text-decoration-none rounded">Product
                            List</a>
                    </li>
                    <li><a href="{{ route('purchase.purchase_order_list') }}" class="d-inline-flex text-decoration-none rounded">Purchase
                            Order</a>
                    </li>
                </ul>
            </div>
        </li>
    @endif
    @if (hasAccess($r_id, 'maintain_req'))
        <li class="mb-1" id="menu-maintenance">
            <button class="asidebtn collapsed {{ Request::routeIs('maintain.*') ? 'active' : '' }} mx-auto" data-bs-toggle="collapse" data-bs-target="#collapse14"
                aria-expanded="false">
                <div class="btnname">
                    <i class="fa-solid fa-screwdriver-wrench"></i> &nbsp;Maintenance
                </div>
                <div class="righticon d-flex ms-auto">
                    <i class="fa-solid fa-angle-right toggle-icon"></i>
                </div>
            </button>
            <div class="collapse" id="collapse14">
                <ul class="btn-toggle-nav list-unstyled pb-3 pe-0 ps-5 text-start">
                    @if ($r_id != 12)
                        <li><a href="{{ route('maintain.list') }}" class="d-inline-flex text-decoration-none mt-3 rounded">Maintenance List</a>
                        </li>

                        @if (hasAccess($r_id, 'maintanance_update'))
                            <li><a href="{{ route('maintain.update_list') }}" class="d-inline-flex text-decoration-none mt-3 rounded">Maintenance Update</a>
                            </li>
                        @endif
                    @endif
                    @if (hasAccess($r_id, 'st_manager'))
                        <li><a href="{{ route('repair.index') }}" class="d-inline-flex text-decoration-none rounded">Maintenance
                                Request</a>
                        </li>
                    @endif
                </ul>
            </div>
        </li>
    @endif
    @if (hasAccess($r_id, 'payroll'))
        <li class="mb-1">
            <button class="asidebtn collapsed {{ Request::routeIs('payroll.*') ? 'active' : '' }} mx-auto" data-bs-toggle="collapse" data-bs-target="#collapse9"
                aria-expanded="false">
                <div class="btnname">
                    <i class="fa-solid fa-credit-card"></i> &nbsp;Payroll
                </div>
                <div class="righticon d-flex ms-auto">
                    <i class="fa-solid fa-angle-right toggle-icon"></i>
                </div>
            </button>
            <div class="collapse" id="collapse9">
                <ul class="btn-toggle-nav list-unstyled pb-3 pe-0 ps-5 text-start">
                    <li><a href="{{ route('payroll.index') }}" class="d-inline-flex text-decoration-none mt-3 rounded">Salary
                            Generation
                            List</a>
                    </li>
                    <li><a href="{{ route('payroll.payroll_list') }}" class="d-inline-flex text-decoration-none rounded">View
                            Salary
                            List</a>
                    </li>
                    <li><a href="{{ route('payroll.salaryhold_list') }}" class="d-inline-flex text-decoration-none rounded">
                            Salary Hold
                            List</a>
                    </li>
                </ul>
            </div>
        </li>
    @endif
    @if (hasAccess($r_id, 'attendance'))
        <li class="mb-1">
            <button class="asidebtn collapsed {{ Request::routeIs('attendance.*') ? 'active' : '' }} mx-auto" data-bs-toggle="collapse" data-bs-target="#collapse5"
                aria-expanded="false">
                <div class="btnname">
                    <i class="fa-solid fa-clipboard-user"></i> &nbsp;Attendance
                </div>
                <div class="righticon d-flex ms-auto">
                    <i class="fa-solid fa-angle-right toggle-icon"></i>
                </div>
            </button>
            <div class="collapse" id="collapse5">
                <ul class="btn-toggle-nav list-unstyled pb-3 pe-0 ps-5 text-start">
                    <li><a href="{{ route('attendance.daily') }}" class="d-inline-flex text-decoration-none mt-3 rounded">Daily</a>
                    </li>
                    <li><a href="{{ route('attendance.monthly') }}" class="d-inline-flex text-decoration-none rounded">Monthly</a>
                    </li>
                    <li><a href="{{ route('attendance.individual') }}" class="d-inline-flex text-decoration-none rounded">Individual</a>
                    </li>
                    <li><a href="{{ route('attendance.overtime') }}" class="d-inline-flex text-decoration-none rounded">Overtime
                            / Late</a>
                    </li>
                    <li><a href="{{ route('attendance.ot.report') }}" class="d-inline-flex text-decoration-none rounded">OT
                            / Late Report</a>
                    </li>
                </ul>
            </div>
        </li>
    @endif
    @if (hasAccess($r_id, 'request'))
        <li class="mb-1" id="menu-request">
            <button
                class="asidebtn collapsed {{ Request::routeIs('leave.*', 'repair.*', 'transfer.*', 'resignation.*', 'recruitment.*', 'p_request_list*') ? 'active' : '' }} mx-auto"
                data-bs-toggle="collapse" data-bs-target="#collapse6" aria-expanded="false">
                <div class="btnname">
                    <i class="fa-solid fa-comment-dots"></i> &nbsp;Request
                </div>
                <div class="righticon d-flex ms-auto">
                    <i class="fa-solid fa-angle-right toggle-icon"></i>
                </div>
            </button>
            <div class="collapse" id="collapse6">
                <ul class="btn-toggle-nav list-unstyled pb-3 pe-0 ps-5 text-start">
                    @if (!in_array($r_id, [1, 2]))
                        <li><a href="{{ route('leave.index') }}" class="d-inline-flex text-decoration-none mt-3 rounded">Leave
                                Request</a>
                        </li>
                        {{-- <li><a href="{{ route('transfer.index') }}" class="d-inline-flex text-decoration-none rounded">Transfer
                        Request</a>
                </li> --}}
                        <li><a href="{{ route('resignation.index') }}" class="d-inline-flex text-decoration-none rounded">Resignation
                                Request</a>
                        </li>
                        <li><a href="{{ route('retirement.retire_list') }}" class="d-inline-flex text-decoration-none rounded">Retirement Request</a>
                        </li>
                    @endif
                    @if (hasAccess($r_id, 'all_manager'))
                        <li><a href="{{ route('recruitment.index') }}" class="d-inline-flex text-decoration-none rounded">Recruitment
                                Request</a>
                        </li>
                    @endif
                    @if (hasAccess($r_id, 'pur_req'))
                        <li><a href="{{ route('p_request_list') }}" class="d-inline-flex text-decoration-none rounded">Purchase
                                Request</a>
                        </li>
                    @endif
                </ul>
            </div>
        </li>
    @endif
    @if (hasAccess($r_id, 'approval'))
        <li class="mb-1" id="menu-approval">
            {{-- <a href="{{ route('approve.index') }}"> --}}
            <button class="asidebtn collapsed {{ Request::routeIs('approve.*') ? 'active' : '' }} mx-auto" data-bs-toggle="collapse" data-bs-target="#collapse7"
                aria-expanded="false">
                <div class="btnname">
                    <i class="fa-solid fa-clipboard-check"></i> &nbsp;Approval List
                </div>

                <div class="righticon d-flex ms-auto">
                    <i class="fa-solid fa-angle-right"></i>
                </div>
            </button>

            <div class="collapse" id="collapse7">
                <ul class="btn-toggle-nav list-unstyled p2-3 pe-0 ps-5 text-start">
                    <li><a href="{{ route('approveleave.index') }}" class="d-inline-flex text-decoration-none mt-3 rounded">Leave</a>
                    </li>
                    <li><a href="{{ route('approveresgin.index') }}" class="d-inline-flex text-decoration-none rounded">Resign</a>
                    </li>
                    @if (in_array(auth()->user()->role_id, [1, 2, 3, 4, 5, 11]))
                        <li><a href="{{ route('approverecruit.index') }}" class="d-inline-flex text-decoration-none rounded">Recruit</a>
                        </li>
                    @endif
                    @if (hasAccess(auth()->user()->role_id, 'maintain_req'))
                        <li><a href="{{ route('approverepair.index') }}" class="d-inline-flex text-decoration-none rounded">Maintanance</a>
                        </li>
                    @endif
                    @if (hasAccess(auth()->user()->role_id, 'pur_req'))
                        <li><a href="{{ route('approvepurchase_order') }}" class="d-inline-flex text-decoration-none rounded">Purchase Order</a>
                        </li>
                    @endif
                    @if (hasAccess(auth()->user()->role_id, 'ret_req'))
                        <li><a href="{{ route('approve.retirement_list') }}" class="d-inline-flex text-decoration-none rounded">Retirement</a>
                        </li>
                    @endif
                </ul>
            </div>
            {{-- </a> --}}
        </li>
    @endif
    @if (hasAccess($r_id, 'DSR'))
        <li class="mb-1" id="menu-dsr">
            <button class="asidebtn collapsed {{ Request::routeIs('dsr.*') ? 'active' : '' }} mx-auto" data-bs-toggle="collapse" data-bs-target="#collapse15"
                aria-expanded="false">
                <div class="btnname">
                    <i class="fa-solid fa-pencil"></i> &nbsp;DSR
                </div>
                <div class="righticon d-flex ms-auto">
                    <i class="fa-solid fa-angle-right toggle-icon"></i>
                </div>
            </button>
            <div class="collapse" id="collapse15">
                <ul class="btn-toggle-nav list-unstyled pb-3 pe-0 ps-5 text-start">

                    @if ($r_id == 12)
                        <li><a href="{{ route('dsr.sale.list') }}" class="d-inline-flex text-decoration-none mt-2 rounded">DSR-Sale</a>
                        </li>
                    @endif
                    @if ($r_id == 12)
                        <li><a href="{{ route('dsr.rental.list') }}" class="d-inline-flex text-decoration-none rounded">DSR-Rental</a>
                        </li>
                    @endif
                    @if (in_array($r_id, [1, 2, 6, 10, 11, 12, 66]))
                        <li><a href="{{ route('dsr.sale.report') }}" class="d-inline-flex text-decoration-none rounded">DSR-Sale
                                Report</a>
                        </li>
                    @endif
                    @if (in_array($r_id, [1, 2, 6, 10, 11, 12, 66]))
                        <li><a href="{{ route('dsr.rental.report') }}" class="d-inline-flex text-decoration-none rounded">DSR-Rental Report</a>
                        </li>
                    @endif
                </ul>
            </div>
        </li>
    @endif
    @if (hasAccess($r_id, 'work_update'))
        <li class="mb-1">
            <button class="asidebtn collapsed {{ Request::routeIs('workupdate.*') ? 'active' : '' }} mx-auto" data-bs-toggle="collapse" data-bs-target="#collapse20"
                aria-expanded="false">
                <div class="btnname">
                    <i class="fa-solid fa-clipboard"></i> &nbsp; Workupdate
                </div>
                <div class="righticon d-flex ms-auto">
                    <i class="fa-solid fa-angle-right toggle-icon"></i>
                </div>
            </button>
            <div class="collapse" id="collapse20">
                <ul class="btn-toggle-nav list-unstyled pb-3 pe-0 ps-5 text-start">
                    @if (in_array($r_id, [1, 2, 3, 4, 5]))
                        <li><a href="{{ route('hr_workupdate-list') }}" class="d-inline-flex text-decoration-none rounded">Hr Workupate List</a>
                        </li>
                    @endif
                </ul>
            </div>
        </li>
    @endif

    @if (hasAccess($r_id, 'performance'))
        <li class="mb-1" id="menu-performance">
            <button class="asidebtn collapsed {{ Request::routeIs('performance.*') ? 'active' : '' }} mx-auto" data-bs-toggle="collapse" data-bs-target="#collapse18"
                aria-expanded="false">
                <div class="btnname">
                    <i class="fa-solid fa-chart-simple"></i> &nbsp;Performance
                </div>
                <div class="righticon d-flex ms-auto">
                    <i class="fa-solid fa-angle-right toggle-icon"></i>
                </div>
            </button>
            <div class="collapse" id="collapse18">
                <ul class="btn-toggle-nav list-unstyled pb-3 pe-0 ps-5 text-start">
                    @if (in_array($r_id, [1, 2, 3]))
                        <li><a href="{{ route('performance.hr_performance') }}" class="d-inline-flex text-decoration-none mt-3 rounded">Hr Performance</a>
                        </li>
                    @endif
                    @if (in_array($r_id, [12, 13, 14, 15, 16, 17, 19, 50, 53]))
                        <li><a href="{{ route('performance.employee_performance') }}" class="d-inline-flex text-decoration-none mt-3 rounded">Employee Self Rating</a>
                        </li>
                    @endif
                </ul>
            </div>
        </li>
    @endif
    <li class="mb-1" id="menu-attendance_check">
        @php
            $user_check = Auth::user()->id;
            $attd = DB::table('attendance')->where('user_id', $user_check)->whereDate('c_on', date('Y-m-d'))->select(DB::raw('count(*) as count'), 'out_add')->first();
            use Carbon\Carbon;
            $c_time = Carbon::now(); // Get the current time using Carbon
            $start_time = Carbon::parse(!is_null(Auth::user()->store_id) ? Auth::user()->store_rel->store_start_time : Auth::user()->st_time);
            $start_time_minus_5 = $start_time->copy()->subMinutes(10);
        @endphp

        @if ($attd->count == 0 && $c_time >= $start_time_minus_5)
            <a onclick="getLocation()">
                <button class="asidebtn collapsed attd mx-auto" aria-expanded="false">
                    <div class="btnname">
                        <i class="fa-solid fa-right-to-bracket" style="color: green;"></i> &nbsp;CheckIn
                    </div>
                </button>
            </a>
        @else
            @if (is_null($attd->out_add))
                @if ($role_get->role_dept != 'Maintenance')
                    <a onclick="getLocation()">
                        <button class="asidebtn collapsed attd mx-auto" aria-expanded="false">
                            <div class="btnname">
                                <i class="fa-solid fa-right-to-bracket" style="color: red;"></i> &nbsp;CheckOut
                            </div>
                        </button>
                    </a>
                @endif
            @endif
        @endif
    </li>
    <li class="mb-0" id="menu-logout">
        <a href="{{ route('logout') }}">
            <button class="asidebtn collapsed mx-auto" aria-expanded="false">
                <div class="btnname">
                    <i class="fa-solid fa-right-from-bracket" style="color: red;"></i> &nbsp;Logout
                </div>
            </button>
        </a>
    </li>
</div>
<script>
    $(document).ready(function() {
        const $menuContainer = $('#menuContainer'); // ✅ define first
        // $menuContainer.empty();

        const roleId = "{{ auth()->user()->role_id }}";

        // Only rearrange if role id == 12
        if (parseInt(roleId) === 12) {
            const order = [
                'menu-dashboard', 'menu-walkin', 'menu-dsr', 'menu-task',
                'menu-approval', 'menu-request', 'menu-maintenance', 'menu-performance', 'menu-store', 'menu-employee',
                'menu-attendance_check', 'menu-logout'
            ];

            const orderedItems = [];

            // Collect items in the given order
            order.forEach(id => {
                const $item = $('#' + id);
                if ($item.length) orderedItems.push($item);
            });

            // console.log(orderedItems);

            // Clear existing items and re-insert in the correct order
            $menuContainer.empty().append(orderedItems);
        }
    });
</script>
