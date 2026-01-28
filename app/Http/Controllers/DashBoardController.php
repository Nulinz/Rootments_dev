<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\Task;
use App\Models\{User, Role};
use Carbon\Carbon;
use App\Http\Controllers\trait\common;

class DashBoardController extends Controller
{
    use common;
    /**
     * Display a listing of the resource.
     */
    public function index(Request $req)
    {
        $user = Auth::user();

        $store = DB::table('stores')->where('id', $user->store_id)->first();

        $storeMembers = DB::table('users')->where('store_id', $user->store_id)->pluck('id')->toArray();


        $role_get = DB::table('roles')
            ->join('users', 'users.role_id', '=', 'roles.id')
            ->select('roles.id as role_id', 'roles.role', 'roles.role_dept')
            ->where('users.id', $user->id)
            ->first();

        $storeManagerRole = DB::table('roles')
            ->where('role', 'Store Manager')
            ->value('id');

        $overview = DB::table('users')
            ->leftJoin('attendance', function ($join) {
                $join->on('users.id', '=', 'attendance.user_id')
                    ->whereDate('attendance.c_on', Carbon::today());
            })
            ->select('users.id as user_id', 'users.name', 'users.profile_image', 'attendance.in_time', 'attendance.user_id', 'attendance.attend_status', 'attendance.out_time', 'attendance.status', 'attendance.in_location')
            ->where('users.store_id', $store->id)
            ->where('users.role_id', '!=', $storeManagerRole)
            ->where('users.status', 1)

            ->get();


        if (!empty($storeMembers)) {
            $pendingLeaves = DB::table('leaves')
                ->leftJoin('users', 'leaves.user_id', '=', 'users.id')
                ->leftJoin('roles', 'users.role_id', '=', 'roles.id')
                ->select(
                    'leaves.*',
                    'users.name',
                    'users.profile_image',
                    'users.emp_code',
                    'users.store_id',
                    'leaves.request_to'
                )
                ->whereIn('users.id', $storeMembers)
                ->where('leaves.request_to',  $user->id)
                ->where('leaves.request_status', 'Pending')
                ->get();
        } else {
            $pendingLeaves = collect();
        }

        // if (!empty($storeMembers)) {
        //     $teampertask = DB::table('tasks')
        //         ->join('users', 'tasks.assign_to', '=', 'users.id')
        //         ->whereIn('tasks.assign_to', $storeMembers)
        //         ->selectRaw("
        //                 users.name,
        //                 COUNT(*) AS total_tasks
        //             ")
        //         ->groupBy('users.name')
        //         ->get();
        // } else {
        //     $teampertask = collect();
        // }

        // $staffNames = $teampertask->pluck('name')->toArray();
        // $taskCounts = $teampertask->pluck('total_tasks')->toArray();
        
          $currentMonth = now()->month;

        if (!empty($storeMembers)) {
            $walkin = DB::table('walkin')
                ->join('users', 'walkin.c_by', '=', 'users.id')
                ->whereMonth('walkin.created_at', $currentMonth)
                ->whereIn('walkin.c_by', $storeMembers)
                ->selectRaw("
                             users.name,
                             COUNT(*) AS total_walkin
                         ")
                ->groupBy('users.name')
                ->get();
        } else {
            $walkin = collect();
        }
        $staffNames = $walkin->pluck('name')->toArray();
        $taskCounts = $walkin->pluck('total_walkin')->toArray();

        if (!empty($storeMembers)) {
            $tolatask = DB::table('tasks')
                ->whereIn('tasks.assign_to', $storeMembers)
                ->selectRaw("
                        SUM(CASE WHEN task_status = 'To Do' THEN 1 ELSE 0 END) AS todo,
                        SUM(CASE WHEN task_status = 'In Progress' THEN 1 ELSE 0 END) AS in_progress,
                        SUM(CASE WHEN task_status = 'On Hold' THEN 1 ELSE 0 END) AS on_hold,
                        SUM(CASE WHEN task_status = 'Completed' THEN 1 ELSE 0 END) AS completed
                    ")
                ->first();

            $tolatask = [
                'todo' => $tolatask->todo ?? 0,
                'in_progress' => $tolatask->in_progress ?? 0,
                'on_hold' => $tolatask->on_hold ?? 0,
                'completed' => $tolatask->completed ?? 0,
            ];
        } else {
            $tolatask = [
                'todo' => 0,
                'in_progress' => 0,
                'on_hold' => 0,
                'completed' => 0,
            ];
        }

        $categoryTask = DB::table('tasks')
            ->join('categories', 'tasks.category_id', '=', 'categories.id')
            ->whereIn('tasks.assign_to', $storeMembers)
            ->where('tasks.task_status', 'Completed')
            ->select(
                'categories.category',
                DB::raw('COUNT(*) as total_tasks')
            )
            ->groupBy('categories.category')
            ->get();

        $categoryNames = $categoryTask->pluck('category')->toArray();
        $categorytaskCounts = $categoryTask->pluck('total_tasks')->toArray();

        $subcategoryTask = DB::table('tasks')
            ->join('sub_categories', 'tasks.subcategory_id', '=', 'sub_categories.id')
            ->join('categories', 'tasks.category_id', '=', 'categories.id')
            ->whereIn('tasks.assign_to', $storeMembers)
            ->where('tasks.task_status', 'Completed')
            ->select(
                'categories.category',
                'sub_categories.subcategory',
                DB::raw('COUNT(*) as subtotal_tasks')
            )
            ->groupBy('categories.category', 'sub_categories.subcategory')
            ->get();
        $subcategoryNames = $subcategoryTask->pluck('subcategory')->toArray();
        $subcategorytaskCounts = $subcategoryTask->pluck('subtotal_tasks')->toArray();


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
            
            $today = now()->subDay()->format('Y-m-d');

        $store_id = $user->store_id;


        $dsr_values = DB::table('emp_perf_workupdate as dsr')
            ->leftJoin('users', 'users.id', '=', 'dsr.emp_id')
            ->where('dsr.store_id', $store_id)
            ->whereDate('dsr.created_at', $today)
            ->select(
                'dsr.abs',
                'dsr.abv',
                'dsr.conversion',
                'users.name'
            )
            ->groupBy('users.name')
            ->get();

        // $abs_dt = $dsr_values->pluck('abs')->toArray();
         $abs_dt = $dsr_values->pluck('abs')
            ->map(function ($val) {
                return number_format((float)$val, 2, '.', '');
            })
            ->toArray();
        // $abv_dt = $dsr_values->pluck('abv')->toArray();
        $abv_dt = $dsr_values->pluck('abv')->map(fn($v) => (int) $v)->toArray();
       $conv_dt = $dsr_values->pluck('conversion')
    ->map(function($val){
        return number_format((float)$val, 2, '.', '');
    })
    ->toArray();

        $dsr_name = $dsr_values->pluck('name')->toArray();


        return view('store.overview', ['overview' => $overview, 'pendingLeaves' => $pendingLeaves, 'tolatask' => $tolatask, 'staffNames' => $staffNames, 'taskCounts' => $taskCounts, 'categoryNames' => $categoryNames, 'categorytaskCounts' => $categorytaskCounts, 'subcategoryNames' => $subcategoryNames, 'subcategorytaskCounts' => $subcategorytaskCounts, 'task_ext' => $task_ext,  'tast_ext' => $tast_ext, 'abs_dt' => $abs_dt, 'dsr_name' => $dsr_name, 'abv_dt' => $abv_dt, 'conv_dt' => $conv_dt]);
    }

    public function attendanceApprove(Request $request)
    {
        $user_id = $request->input('user_id');

        $updated = DB::table('attendance')
            ->where('user_id', $user_id)
            ->update(['status' => 'approved']);

        if ($updated) {
            return response()->json(['success' => true, 'message' => 'Attendance approved!']);
        }

        return response()->json(['success' => false, 'message' => 'User not found or already approved!']);
    }

    public function generalstoreindex()
    {


        $user = auth()->user();

        $store = DB::table('stores')->where('id', $user->store_id)->first();

        if ($store) {
            $users = DB::table('users')
                ->leftJoin('stores', 'users.store_id', '=', 'stores.id')
                ->leftJoin('roles', 'users.role_id', '=', 'roles.id')
                ->select('users.id as user_id', 'users.name', 'users.emp_code', 'roles.role', 'users.profile_image')
                ->where('users.store_id', $store->id)
                ->where('users.status', 1)
                ->where('users.id', '!=', $user->id)
                ->get();
        } else {
            $users = DB::table('users')
                ->leftJoin('stores', 'users.store_id', '=', 'stores.id')
                ->leftJoin('roles', 'users.role_id', '=', 'roles.id')
                ->select('users.id as user_id', 'users.name', 'users.emp_code', 'roles.role', 'users.profile_image')
                ->where('users.status', 1)
                ->where('users.id', '!=', $user->id)
                ->get();
        }



        return view('store.storedashboard', ['users' => $users]);
    }

    public function useragainststask(Request $request)
    {



        $user_id = $request->input('user_id');

        $tasks_todo = DB::table('tasks')
            ->leftJoin('categories', 'tasks.category_id', '=', 'categories.id')
            ->leftJoin('sub_categories', 'tasks.subcategory_id', '=', 'sub_categories.id')
            ->leftJoin('roles as assigned_role', 'tasks.assign_to', '=', 'assigned_role.id')
            ->leftJoin('roles as assigned_by_role', 'tasks.assign_by', '=', 'assigned_by_role.id')
            ->leftJoin('users as assigned_by_user', 'tasks.assign_by', '=', 'assigned_by_user.id')
            ->where('tasks.assign_to', $user_id)
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
            ->where('assign_to', $user_id)
            ->where('task_status', 'To Do')
            ->count();

        $tasks_inprogress = DB::table('tasks')
            ->leftJoin('categories', 'tasks.category_id', '=', 'categories.id')
            ->leftJoin('sub_categories', 'tasks.subcategory_id', '=', 'sub_categories.id')
            ->leftJoin('roles as assigned_role', 'tasks.assign_to', '=', 'assigned_role.id')
            ->leftJoin('roles as assigned_by_role', 'tasks.assign_by', '=', 'assigned_by_role.id')
            ->leftJoin('users as assigned_by_user', 'tasks.assign_by', '=', 'assigned_by_user.id')
            ->where('tasks.assign_to', $user_id)
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
            ->where('assign_to', $user_id)
            ->where('task_status', 'In Progress')
            ->count();

        $tasks_onhold = DB::table('tasks')
            ->leftJoin('categories', 'tasks.category_id', '=', 'categories.id')
            ->leftJoin('sub_categories', 'tasks.subcategory_id', '=', 'sub_categories.id')
            ->leftJoin('roles as assigned_role', 'tasks.assign_to', '=', 'assigned_role.id')
            ->leftJoin('roles as assigned_by_role', 'tasks.assign_by', '=', 'assigned_by_role.id')
            ->leftJoin('users as assigned_by_user', 'tasks.assign_by', '=', 'assigned_by_user.id')
            ->where('tasks.assign_to', $user_id)
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
            ->where('assign_to', $user_id)
            ->where('task_status', 'On Hold')
            ->count();

        $tasks_complete = DB::table('tasks')
            ->leftJoin('categories', 'tasks.category_id', '=', 'categories.id')
            ->leftJoin('sub_categories', 'tasks.subcategory_id', '=', 'sub_categories.id')
            ->leftJoin('roles as assigned_role', 'tasks.assign_to', '=', 'assigned_role.id')
            ->leftJoin('roles as assigned_by_role', 'tasks.assign_by', '=', 'assigned_by_role.id')
            ->leftJoin('users as assigned_by_user', 'tasks.assign_by', '=', 'assigned_by_user.id')
            ->where('tasks.assign_to', $user_id)
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
            ->where('assign_to', $user_id)
            ->where('task_status', 'Completed')
            ->count();

        return response()->json([
            'tasks_todo' => $tasks_todo,
            'tasks_todo_count' => $tasks_todo_count,
            'tasks_inprogress' => $tasks_inprogress,
            'tasks_inprogress_count' => $tasks_inprogress_count,
            'tasks_onhold' => $tasks_onhold,
            'tasks_onhold_count' => $tasks_onhold_count,
            'tasks_complete' => $tasks_complete,
            'tasks_complete_count' => $tasks_complete_count,
        ]);
    }


    public function mydashboardindex()
    {
        $authId = Auth::user()->id;

        $user = Auth::user();

        $role = Role::find($user->role_id);

        $store = DB::table('stores')->where('id', $user->store_id)->first();

        // $employeesQuery = DB::table('users')
        //     ->select('id', 'name')
        //     ->whereNotNull('role_id');

        // if (!in_array($user->dept, ['Admin', 'HR']) && $store) {
        //     $employeesQuery->where('store_id', $store->id);
        // }

        // $employeesQuery->where('id', '!=', $user->id);

        // $employees = $employeesQuery->get();




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
            ->whereIn('tasks.task_status', ['Completed', 'Close', 'Assigned'])
            ->where(function ($query) {
                $query->where('tasks.task_status', 'Completed') // Keep Completed tasks
                    // Exclude Assigned and Close tasks with end_date + 15 days > current date
                    ->orWhere(function ($subQuery) {
                        $subQuery->whereIn('tasks.task_status', ['Assigned', 'Close'])
                            ->whereRaw('DATE_ADD(tasks.end_date, INTERVAL 15 DAY) >= ?', [Carbon::now()]);
                    });
            })

            // ->whereIn('tasks.fid', function($query) use ($authId) {
            //     // Subquery: Get the list of f_id values based on tasks assigned to authId
            //     $query->select('tasks.fid')
            //         ->from('tasks')
            //         ->where('tasks.assign_to', $authId);

            // })
            // ->selectRaw('CASE WHEN tasks.assign_by = '.$authId.' THEN 1 ELSE 0 END as cr_by')
            ->select(
                'tasks.*',
                'categories.category',
                'sub_categories.subcategory',
                'assigned_role.role as assigned_role',
                'assigned_by_role.role as task_assigned',
                'assigned_by_user.name as assigned_by',
                'tasks.end_date',


            )


            ->orderBy('tasks.id', 'DESC')
            ->get();




        //  Step 1: Extract f_id values from the tasks_complete array
        // $f_id = [];

        // $f_id = []; // Initialize an array to store f_id values

        // foreach ($tasks_complete as $tc) {
        //     // Query to fetch the task with the same f_id, ordered by the smallest id
        //     $tasks_with_assigned_check = DB::table('tasks')
        //         ->where('tasks.f_id', $tc->f_id) // Use the f_id from the current task
        //         ->orderBy('tasks.id', 'asc') // Order by id in ascending order to get the task with the smallest id
        //         ->select(
        //             'tasks.id',
        //             'tasks.f_id',
        //             DB::raw('CASE WHEN tasks.assign_by = '.$authId.' THEN 1 ELSE 0 END as cr_by') // Check if assign_by matches authId
        //         )
        //         ->first(); // Get only the first (smallest id) task

        //     // If the query returns a task, process it
        //     if ($tasks_with_assigned_check) {
        //         // Add the f_id of the task to the array (or perform other processing)
        //         $f_id[] = $tasks_with_assigned_check->cr_by;

        //         // If you need to access other values, you can do so here
        //         // Example: echo $tasks_with_assigned_check->cr_by; // For debugging
        //     }
        // }


        // dd($f_id);




        //  dd($tasks_with_assigned_check);



        $tasks_complete_count = DB::table('tasks')
            ->where('assign_to', $authId)
            ->whereIn('task_status', ['Completed', 'Close', 'Assigned'])
            // Apply the same condition as in the main query
            ->where(function ($query) {
                $query->where('tasks.task_status', 'Completed') // Keep Completed tasks
                    // Only include Assigned and Close tasks where end_date + 15 days <= current date
                    ->orWhere(function ($subQuery) {
                        $subQuery->whereIn('tasks.task_status', ['Assigned', 'Close'])
                            ->whereRaw('DATE_ADD(tasks.end_date, INTERVAL 15 DAY) >= ?', [Carbon::now()]);
                    });
            })
            ->count();  // Get the count of tasks

        // assign to employees list

        // dd($tasks_complete_count);


        // $user = auth()->user();
        $r_id = $user->role_id;

        $cluster_check = DB::table('m_cluster as mc')
            ->leftJoin('users', 'users.id', '=', 'mc.cl_name')
            ->where('mc.cl_name', '=', $user->id)
            ->where('users.role_id', 12)
            ->count();



        $list =  DB::table('users')
            ->leftJoin('roles', 'roles.id', '=', 'users.role_id')
            ->select('users.name', 'roles.role', 'roles.role_dept', 'users.id', 'users.store_id');


        if (($r_id >= 12 && $r_id <= 19) or ($r_id == 50)) {

            if ($cluster_check == 0) {
                $list->where('users.store_id', $user->store_id);
                // ->where('users.id', '!=', $user->id);
            } else {

                $list->leftJoin('stores', 'stores.id', '=', 'users.store_id')
                    ->where(function ($query) use ($user) {
                        // Include all users with role_id = 12
                        $query->where('users.role_id', 12)
                            // Include all users from the current user's store
                            ->orWhere('users.store_id', $user->store_id);
                    })
                    // ->where('users.id', '!=', $user->id)
                    ->orderBy('users.role_id');
            }
        } else {

            // dd(auth()->user()->role_id);


            $arr = $this->role_arr();



            $list->leftJoin('stores', 'stores.id', '=', 'users.store_id')
                // ->where('users.id', '!=', $user->id)
                ->select('users.name', 'roles.role', 'roles.role_dept', 'users.id', 'users.store_id', 'stores.store_name', 'stores.store_code') // Adjust store fields as needed
                ->whereIn('users.role_id', $arr);

            $list->orderBy('users.role_id');

            // dd($list);

        }

        $list = $list->where('users.status', 1)->get();

        // dd($list);

        $tast_ext = DB::table('task_ext')
            ->select('task_id', 'status')
            ->orderBy('id', 'desc')
            ->get()
            ->unique('task_id')
            ->keyBy('task_id');

        return view('generaldashboard.mydashboard', ['tasks_todo' => $tasks_todo, 'tasks_todo_count' => $tasks_todo_count, 'tasks_inprogress' => $tasks_inprogress, 'tasks_inprogress_count' => $tasks_inprogress_count, 'tasks_onhold' => $tasks_onhold, 'tasks_onhold_count' => $tasks_onhold_count, 'tasks_complete' => $tasks_complete, 'tasks_complete_count' => $tasks_complete_count, 'employees' => $list, 'role' => $role, 'tast_ext' => $tast_ext]);
    }



    public function updateTaskStatus(Request $request)
    {
        $taskId = $request->id;
        $newStatus = $request->status;

        $authId = Auth::user()->id;

        $task = Task::find($taskId);

        if ($request->status == 'Close') {

            $first  = DB::table('tasks')->where('f_id', $task->f_id)->orderBy('id', 'asc')->first();

            if ($first) {
                // Update both the current task and the first task with the new status
                DB::table('tasks')
                    ->whereIn('id', [$taskId, $first->id]) // Updating both tasks
                    ->update(['task_status' => $request->status]);
            }
        } else {

            if ($task) {
                $task->task_status = $newStatus;
                $task->save();
            }
        }

        $tasks_todo_count = DB::table('tasks')
            ->where('assign_to', $authId)
            ->where('task_status', 'To Do')
            ->count();

        $tasks_inprogress_count = DB::table('tasks')
            ->where('assign_to', $authId)
            ->where('task_status', 'In Progress')
            ->count();

        $tasks_onhold_count = DB::table('tasks')
            ->where('assign_to', $authId)
            ->where('task_status', 'On Hold')
            ->count();

        $tasks_complete_count = DB::table('tasks')
            ->where('assign_to', $authId)
            ->where('task_status', 'Completed')
            ->count();

        return response()->json([
            'success' => true,
            'message' => 'Task status updated',
            'taskCounts' => [
                'todo' => $tasks_todo_count,
                'inprogress' => $tasks_inprogress_count,
                'onhold' => $tasks_onhold_count,
                'complete' => $tasks_complete_count
            ]
        ]);


        return response()->json(['success' => false, 'message' => 'Task not found']);
    }


    public function attd_row()
    {

        $user_check = Auth::user()->id;

        $attd = DB::table('attendance')->where('user_id', $user_check)->whereDate('c_on', date('Y-m-d'))->count();

        if ($attd == 0) {
            $val = 'attd_in';
        } else {
            $attd_ch = DB::table('attendance')->where('user_id', $user_check)->whereDate('c_on', date('Y-m-d'))->orderBy('id', 'desc')->first();
            if (is_null($attd_ch->out_location)) {
                $val = 'attd_out';
            } else {
                $val = 'attd_mark';
            }
        }

        // dd($val);

        return with([
            'attd_data' => [$attd_ch->in_time ?? null, $attd_ch->out_time ?? null, $val],
        ]);
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

     public function walkinStatus(Request $req)
    {
        $user = Auth::user();

        $user_ids = $user->role_id == 12
            ? DB::table('users')->where('store_id', $user->store_id)->pluck('id')->toArray()
            : [$user->id];

        // Fetch walk-in data
        $walkins = DB::table('walkin')
            ->whereBetween(DB::raw('DATE(created_at)'), [$req->fromdate, $req->todate])
            ->whereNotNull('walk_status')
            ->whereIn('c_by', $user_ids)
            ->select('contact', 'walk_status', DB::raw('DATE(created_at) as walk_date'))
            ->get();

        // Group by contact + date
        $grouped = $walkins->groupBy(fn($item) => "{$item->contact}_{$item->walk_date}");

        $statusCounts = [];

        foreach ($grouped as $group) {
            $hasRevisit = $group->contains('walk_status', 'Revisit Booking');

            foreach ($group as $item) {
                // Skip 'Loss' if 'Revisit Booking' also exists
                if ($hasRevisit && $item->walk_status === 'Loss') continue;

                $status = $item->walk_status;
                $statusCounts[$status] = ($statusCounts[$status] ?? 0) + 1;
            }
        }

        return response()->json([
            'statuses' => array_keys($statusCounts),
            'counts' => array_values($statusCounts),
        ]);
    }
}
