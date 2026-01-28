<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\Task;
use App\Models\Role;
use App\Models\Store;
use App\Models\User;
// use App\Models\{User, Role, Store};
use Carbon\Carbon;
use App\Http\Controllers\trait\common;

class HrDashBoardController extends Controller
{
    use common;
    /**
     * Display a listing of the resource.
     */
    public function index()
    {


        $user = Auth::user();

        $managerRoles = DB::table('roles')
            ->whereIn('id', [7, 10, 11, 12, 30, 37, 41])
            ->pluck('id')
            ->toArray();

        $overview = DB::table('users')
            ->leftJoin('attendance', function ($join) {
                $join->on('users.id', '=', 'attendance.user_id')
                    ->whereDate('attendance.c_on', Carbon::today());
            })
            ->select(
                'users.id as user_id',
                'users.name',
                'users.profile_image',
                'attendance.in_time',
                'attendance.user_id',
                'attendance.attend_status',
                'attendance.out_time',
                'attendance.status',
                'attendance.in_location'
            )
            ->whereIn('users.role_id', $managerRoles)
            ->where('users.id', '!=', $user->id)
            ->where('users.status', '=', 1)
            ->get();

              $hr_emp = $this->attd_index(['HR', 'IT']);

        //   dd($hr_emp);


        // employyes list from hr department



        // $hr_emp = DB::table('roles')->where('role_dept','HR')
        // ->leftJoin('users as us','us.role_id','=','roles.id')
        // ->leftJoin('attendance',function($query){
        //     $query->on('us.id','=','attendance.user_id')
        //     ->whereDate('attendance.c_on', Carbon::today());
        // })
        // ->where('us.id', '!=', $user->id)
        // ->select( 'us.id as user_id',
        // 'us.name',
        // 'us.profile_image',
        // 'attendance.in_time',
        // 'attendance.user_id',
        // 'attendance.attend_status',
        // 'attendance.out_time',
        // 'attendance.status',
        // 'attendance.in_location')
        // ->get();

        //   dd($hr_emp);

        $roleData = DB::table('users')
            ->join('roles', 'users.role_id', '=', 'roles.id')
            ->select('roles.role', DB::raw('COUNT(users.id) as count'))
            ->groupBy('roles.role')
            ->orderByDesc('count')
            ->get();

        $roleNames = [];
        $userCounts = [];

        foreach ($roleData as $data) {
            $roleNames[] = $data->role;
            $userCounts[] = (int) $data->count;
        }

        $role_get = DB::table('roles')
            ->join('users', 'users.role_id', '=', 'roles.id')
            ->select('roles.id as role_id', 'roles.role', 'roles.role_dept')
            ->where('users.id', $user->id)
            ->first();

        // $pendingLeaves = DB::table('leaves')
        // ->leftJoin('users', 'leaves.user_id', '=', 'users.id')
        // ->leftJoin('roles', 'users.role_id', '=', 'roles.id')
        // ->select(
        //     DB::raw("'Leave' as request_type"),
        //     'leaves.id',
        //     'users.name',
        //     'users.profile_image',
        //     'users.emp_code',
        //     'users.store_id',
        //     DB::raw('NULL as store_name'),
        //     'leaves.request_to',
        //     'leaves.status',
        //     'leaves.start_date',
        //     'leaves.end_date',
        //     'leaves.reason'
        // )
        // ->where('leaves.esculate_to', $role_get->role_id)
        // ->where('leaves.status', 'Pending');

        $pendingLeaves = DB::table('leaves')
            ->leftJoin('users', 'users.id', '=', 'leaves.user_id')
            ->leftJoin('roles', 'roles.id', '=', 'users.role_id')
            ->leftJoin('stores', 'stores.id', '=', 'users.store_id')
            ->where(function ($query) {
                $query->where('leaves.request_status', 'Pending')
                    ->orWhere('leaves.request_status', 'Escalate');
            })
            ->where('leaves.esculate_status', 'Pending')
            ->where('users.status', 1)
            ->where(function ($query) use ($user) {
                $query->where('leaves.request_to', $user->id)
                    ->orWhere('leaves.esculate_to', $user->id);
            })
            ->select('leaves.id', 'users.name', 'users.emp_code', 'users.profile_image', 'roles.role', 'roles.role_dept', 'leaves.request_status', 'leaves.request_type', 'stores.store_name', 'leaves.start_date', 'leaves.end_date')
            ->distinct()
            ->orderByDesc('leaves.start_date')
            ->get();


        $currentDate = Carbon::now()->format('Y-m-d');  // Get current date in Y-m-d format

        $absent = DB::table('leaves')
            ->whereDate('start_date', '<=', $currentDate) // Check if the current date is after or equal to the start_date
            ->whereDate('end_date', '>=', $currentDate)
            ->where('leaves.status', 'Approved')
            ->join('users as us', 'us.id', '=', 'leaves.user_id')
            ->where('us.status', 1)
            ->leftJoin('roles', 'roles.id', '=', 'us.role_id')
            ->select('us.name', 'roles.role', 'roles.role_dept', 'us.profile_image') // Check if the current date is before or equal to the end_date
            ->get();

        $today = Carbon::today()->toDateString(); // Current date



        $today = Carbon::today()->toDateString(); // Ensure date format is correct

        // Step 1: Get users without attendance for today
        $usersWithoutAttendance = User::whereDoesntHave('attendance', function ($query) use ($today) {
            $query->whereDate('c_on', $today);
        })->with('store_rel:id,store_name,store_code') // Eager load the store relationship
            ->get();

        $statusGroups = $usersWithoutAttendance->groupBy('store_id');

        //  $statusGroups = $usersWithoutAttendance->groupBy(function ($user) {
        //     return $user->store_rel->id ?? 'No Store'; // Group by store_id, fallback if null
        // });

        //    dd($statusGroups->first());
        $roles = DB::table('roles')->pluck('role', 'id');

        $knownStores = $statusGroups->filter(function ($group) {
            return ($group->first()->store_rel) !== null;
        })->map(function ($users) use ($roles) {
            $store = $users->first()->store_rel;

            return [
                'store_name' => $store->store_name ?? 'Unknown Store',
                'store_code' => $store->store_code ?? 'N/A',
                'users' => $users
                    ->filter(fn($user) => $user->status === 1) // ✅ Only active users
                    ->map(function ($user) use ($roles) {
                        return [
                            'user_name' => $user->name,
                            'role' => $roles[$user->role_id] ?? 'Unknown',
                        ];
                    })
                    ->values()
            ];
        })->values(); // Re-index the array

        // $roles = DB::table('roles')->pluck('role', 'id');

        $unknownStores = $statusGroups
            ->filter(fn($group) => $group->first()->store_rel === null)
            ->flatMap(
                fn($users) =>
                $users->filter(fn($user) => $user->status === 1) // ✅ Only active users
                    ->map(fn($user) => [
                        'user_name' => $user->name,
                        'dept' => $user->dept,
                        'role' => $roles[$user->role_id] ?? 'Unknown'
                    ])
            )
            ->values();


        $group_unknown = $unknownStores->groupBy('dept');
        //   dd($group_unknown);

        // $allData = $knownStores->merge($group_unknown);

        $store_per = DB::table('stores')
            ->leftJoin('users as us', 'us.store_id', '=', 'stores.id')
            ->leftJoin('attendance as att', 'att.user_id', '=', 'us.id')
            ->where('us.status', '=', 1)
            ->select(
                'stores.store_code',
                'stores.store_name',
                DB::raw('count(distinct us.id) as members_count'),
                DB::raw('count(case when date(att.c_on) = "' . date("Y-m-d") . '" then 1 end) as present_today_count') // Count of present users today
            )->groupBy('stores.id') // Group by store ID (no need to group by user_id)
            ->get();

        // show bday or joining

        $today = date('Y-m-d');
        $currMonth = date('m');
        $currDay = date('d');

        $bday = DB::table('users')
            ->where('status', 1)
            ->where(function ($q) use ($currMonth, $currDay) {
                $q->whereMonth('dob', $currMonth)->whereDay('dob', $currDay);
            })
            ->orWhere(function ($q) use ($currMonth, $currDay) {
                $q->whereMonth('pre_start_date', $currMonth)->whereDay('pre_start_date', $currDay);
            })
            ->get()
            ->map(function ($user) use ($currMonth, $currDay) {
                $user->type = (date('m-d', strtotime($user->dob)) === "$currMonth-$currDay")
                    ? 'Birthday'
                    : 'Joining';
                return $user;
            });


        // $task_ext = DB::table('task_ext')
        //     ->leftJoin('tasks as tk', 'task_ext.task_id', '=', 'tk.id')
        //     ->leftJoin('users as us', '')
        //     ->select(
        //         'task_ext.request_for',
        //         'task_ext.extend_date',
        //         'task_ext.c_remarks',
        //         'tk.task_title'
        //     )
        //     ->get();


        $task_ext = DB::table('task_ext')->where('request_for', Auth::id())
            ->leftJoin('tasks as tk', 'task_ext.task_id', '=', 'tk.id')
            ->leftJoin('users as us', 'task_ext.c_by', '=', 'us.id')

            ->select(
                'task_ext.id',
                'task_ext.extend_date',
                'task_ext.c_remarks',
                'task_ext.created_at',
                'task_ext.category',
                'task_ext.status',
                'tk.task_title',
                'task_ext.attach',
                'us.name'
            )
            ->whereIn('task_ext.status', ['Pending', 'Close Request'])
            ->orderBy('task_ext.created_at', 'desc')
            ->get();


        $tast_ext = DB::table('task_ext')
            ->select('task_id', 'status')
            ->orderBy('id', 'desc')
            ->get()
            ->unique('task_id')
            ->keyBy('task_id');
            
            
              $currentMonthStart = Carbon::now()->startOfMonth()->toDateString();
        $currentMonthEnd = Carbon::now()->endOfMonth()->toDateString();

            
             $user_leave = User::with([
            'store_rel:id,store_name,store_code',
            'role_rel:id,role',
        ])->where('status', 1)

            ->select('id', 'name', 'emp_code', 'store_id', 'dept', 'role_id')
            ->withCount([
                'leaves as Annual_Leave_Days' => function ($query) use ($currentMonthStart, $currentMonthEnd) {
                    $query->select(DB::raw("COALESCE(SUM(DATEDIFF(
                LEAST(end_date, '{$currentMonthEnd}'),
                GREATEST(start_date, '{$currentMonthStart}')
            ) + 1), 0)"))
                        ->where('status', 'Approved')
                        ->where('request_type', 'Annual Leave')
                        ->where(function ($q) use ($currentMonthStart, $currentMonthEnd) {
                            $q->whereBetween('start_date', [$currentMonthStart, $currentMonthEnd])
                                ->orWhereBetween('end_date', [$currentMonthStart, $currentMonthEnd]);
                        });
                },
                'leaves as Week_Off_Days' => function ($query) use ($currentMonthStart, $currentMonthEnd) {
                    $query->select(DB::raw("COALESCE(SUM(DATEDIFF(
                LEAST(end_date, '{$currentMonthEnd}'),
                GREATEST(start_date, '{$currentMonthStart}')
            ) + 1), 0)"))
                        ->where('status', 'Approved')
                        ->where('request_type', 'Week Off')
                        ->where(function ($q) use ($currentMonthStart, $currentMonthEnd) {
                            $q->whereBetween('start_date', [$currentMonthStart, $currentMonthEnd])
                                ->orWhereBetween('end_date', [$currentMonthStart, $currentMonthEnd]);
                        });
                },
            ])
            ->get();

        // ✅ Group Store Dept users by store name safely
        $storeDept = $user_leave
            ->where('dept', 'Store')
            ->groupBy('store_id')
            ->values();

        // dd($storeDept->toArray());

        $storeDept = $storeDept->map(function ($group) {
            $storeName = $group->first()->store_rel->store_name ?? 'Unknown Store';
            $storeCode = $group->first()->store_rel->store_code ?? 'N/A';
            $role_name = $group->first()->role_rel->role ?? 'Unknown Role';

            return $group->map(function ($user) use ($storeName, $storeCode, $role_name) {
                return [
                    'store_name' => $storeCode . '-' . $storeName,
                    'role_name' => $role_name,
                    'user_name' => $user->name,
                    'emp_code' => $user->emp_code,
                    'Annual_Leave_Days' => $user->Annual_Leave_Days,
                    'Week_Off_Days' => $user->Week_Off_Days,
                ];
            });
        });
        $otherDept = $user_leave->where('dept', '!=', 'Store')->groupBy('dept')->values(); // All others

        $otherDept = $otherDept->map(function ($group, $dept) {
            $role_name = $group->first()->role_rel->role ?? 'Unknown Role';

            return $group->map(function ($user) use ($role_name) {
                return [
                    'store_name' => $user->dept,
                    'role_name' => $role_name,
                    'user_name' => $user->name,
                    'emp_code' => $user->emp_code,
                    'Annual_Leave_Days' => $user->Annual_Leave_Days,
                    'Week_Off_Days' => $user->Week_Off_Days,
                ];
            });
        });

        $user_leave_list = $storeDept->concat($otherDept);


        return view('hr.overview', ['overview' => $overview, 'roleNames' => $roleNames, 'user_leave_list' => $user_leave_list, 'userCounts' => $userCounts, 'pendingRequests' => $pendingLeaves, 'hr_emp' => $hr_emp, 'absent' => $absent, 'known' => $knownStores, 'unknown' => $group_unknown, 'store_per' => $store_per, 'bday' => $bday, 'task_ext' => $task_ext,  'tast_ext' => $tast_ext]);
    }

    public function mydashboard()
    {

        $authId = Auth::user()->id;

        $user = Auth::user();
        $role = Role::find($user->role_id);

        $managerRoleIds = DB::table('roles')
            ->whereIn('role', ['Manager', 'Store Manager'])
            ->pluck('id')
            ->toArray();

        $employeesQuery = DB::table('users')
            ->select('id', 'name')
            ->whereNotNull('role_id')
            ->where('id', '!=', $user->id)
            ->whereIn('role_id', $managerRoleIds);

        $employees = $employeesQuery->get();



        $tasks_todo = DB::table('tasks')
            ->leftJoin('categories', 'tasks.category_id', '=', 'categories.id')
            ->leftJoin('sub_categories', 'tasks.subcategory_id', '=', 'sub_categories.id')
            ->leftJoin('roles as assigned_role', 'tasks.assign_to', '=', 'assigned_role.id')
            ->leftJoin('roles as assigned_by_role', 'tasks.assign_by', '=', 'assigned_by_role.id')
            ->leftJoin('users as assigned_by_user', 'tasks.assign_by', '=', 'assigned_by_user.id')
            ->where('tasks.assign_to', $authId)
            ->where('tasks.task_status', 'To Do')
            ->select(
                'tasks.*',
                'categories.category',
                'sub_categories.subcategory',
                'assigned_role.role as assigned_role',
                'assigned_by_role.role as task_assigned',
                'assigned_by_user.name as assigned_by'
            )
            ->orderBy('tasks.id', 'DESC')
            ->get();

        $tasks_todo_count = DB::table('tasks')
            ->where('assign_to', $authId)
            ->where('task_status', 'To Do')
            ->count();

        $tasks_inprogress = DB::table('tasks')
            ->leftJoin('categories', 'tasks.category_id', '=', 'categories.id')
            ->leftJoin('sub_categories', 'tasks.subcategory_id', '=', 'sub_categories.id')
            ->leftJoin('roles as assigned_role', 'tasks.assign_to', '=', 'assigned_role.id')
            ->leftJoin('roles as assigned_by_role', 'tasks.assign_by', '=', 'assigned_by_role.id')
            ->leftJoin('users as assigned_by_user', 'tasks.assign_by', '=', 'assigned_by_user.id')
            ->where('tasks.assign_to', $authId)
            ->where('tasks.task_status', 'In Progress')
            ->select(
                'tasks.*',
                'categories.category',
                'sub_categories.subcategory',
                'assigned_role.role as assigned_role',
                'assigned_by_role.role as task_assigned',
                'assigned_by_user.name as assigned_by'
            )
            ->orderBy('tasks.id', 'DESC')
            ->get();

        $tasks_inprogress_count = DB::table('tasks')
            ->where('assign_to', $authId)
            ->where('task_status', 'In Progress')
            ->count();

        $tasks_onhold = DB::table('tasks')
            ->leftJoin('categories', 'tasks.category_id', '=', 'categories.id')
            ->leftJoin('sub_categories', 'tasks.subcategory_id', '=', 'sub_categories.id')
            ->leftJoin('roles as assigned_role', 'tasks.assign_to', '=', 'assigned_role.id')
            ->leftJoin('roles as assigned_by_role', 'tasks.assign_by', '=', 'assigned_by_role.id')
            ->leftJoin('users as assigned_by_user', 'tasks.assign_by', '=', 'assigned_by_user.id')
            ->where('tasks.assign_to', $authId)
            ->where('tasks.task_status', 'On Hold')
            ->select(
                'tasks.*',
                'categories.category',
                'sub_categories.subcategory',
                'assigned_role.role as assigned_role',
                'assigned_by_role.role as task_assigned',
                'assigned_by_user.name as assigned_by'
            )
            ->orderBy('tasks.id', 'DESC')
            ->get();

        $tasks_onhold_count = DB::table('tasks')
            ->where('assign_to', $authId)
            ->where('task_status', 'On Hold')
            ->count();

        $tasks_complete = DB::table('tasks')
            ->leftJoin('categories', 'tasks.category_id', '=', 'categories.id')
            ->leftJoin('sub_categories', 'tasks.subcategory_id', '=', 'sub_categories.id')
            ->leftJoin('roles as assigned_role', 'tasks.assign_to', '=', 'assigned_role.id')
            ->leftJoin('roles as assigned_by_role', 'tasks.assign_by', '=', 'assigned_by_role.id')
            ->leftJoin('users as assigned_by_user', 'tasks.assign_by', '=', 'assigned_by_user.id')
            ->where('tasks.assign_to', $authId)
            ->where('tasks.task_status', 'Completed')
            ->select(
                'tasks.*',
                'categories.category',
                'sub_categories.subcategory',
                'assigned_role.role as assigned_role',
                'assigned_by_role.role as task_assigned',
                'assigned_by_user.name as assigned_by'
            )
            ->orderBy('tasks.id', 'DESC')
            ->get();


        $tasks_complete_count = DB::table('tasks')
            ->where('assign_to', $authId)
            ->where('task_status', 'Completed')
            ->count();

        // select task extension


        return view('generaldashboard.mydashboard', ['tasks_todo' => $tasks_todo, 'tasks_todo_count' => $tasks_todo_count, 'tasks_inprogress' => $tasks_inprogress, 'tasks_inprogress_count' => $tasks_inprogress_count, 'tasks_onhold' => $tasks_onhold, 'tasks_onhold_count' => $tasks_onhold_count, 'tasks_complete' => $tasks_complete, 'tasks_complete_count' => $tasks_complete_count, 'employees' => $employees, 'role' => $role]);
    }

    public function kpidashboard()
    {
        return view('hr.kpidashboard');
    }
}
