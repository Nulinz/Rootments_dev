<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Http\Controllers\trait\common;

class maintain_cnt extends Controller
{
    use common;
    /**
     * Display a listing of the resource.
     */
    public function index()
    {

        $user = Auth::user();

        $main_emp = $this->attd_index('Maintenance');

        $men = DB::table('roles')->where('role_dept', 'Maintenance')
            ->leftJoin('users as us', 'us.role_id', '=', 'roles.id')
            ->pluck('us.id')->toArray();

        // $men = [];

        //  dd($men);

        // foreach($role_mem as $role_men1){

        //          $men[] = $role_men1->id;

        //         // dd($role_men1->id);
        // }




        if (!empty($men)) {

            $totaltask = DB::table('tasks')
                ->whereIn('tasks.assign_to', $men)
                ->selectRaw("
                    SUM(CASE WHEN task_status = 'To Do' THEN 1 ELSE 0 END) AS todo,
                    SUM(CASE WHEN task_status = 'In Progress' THEN 1 ELSE 0 END) AS in_progress,
                    SUM(CASE WHEN task_status = 'On Hold' THEN 1 ELSE 0 END) AS on_hold,
                    SUM(CASE WHEN task_status = 'Completed' THEN 1 ELSE 0 END) AS completed
                ")
                ->first();

            $task = [
                'todo' => $totaltask->todo ?? 0,
                'in_progress' => $totaltask->in_progress ?? 0,
                'on_hold' => $totaltask->on_hold ?? 0,
                'completed' => $totaltask->completed ?? 0,
            ];
        } else {
            $task = [
                'todo' => 0,
                'in_progress' => 0,
                'on_hold' => 0,
                'completed' => 0,
            ];
        }

        //  dd($task);

        if (!empty($men)) {
            $teampertask = DB::table('tasks')
                ->join('users', 'tasks.assign_to', '=', 'users.id')
                ->whereIn('tasks.assign_to', $men)
                ->selectRaw("
                    users.name,
                    COUNT(*) AS total_tasks
                ")
                ->groupBy('users.name')
                ->get();
        } else {
            $teampertask = collect();
        }

        $staffNames = $teampertask->pluck('name')->toArray();
        $taskCounts = $teampertask->pluck('total_tasks')->toArray();

        //   dd($taskCounts);


        // category and subcategory charts.....

        $categoryTask = DB::table('tasks')
            ->join('categories', 'tasks.category_id', '=', 'categories.id')
            ->whereIn('tasks.assign_to', $men)
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
            ->whereIn('tasks.assign_to', $men)
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


        // dd($categoryNames);

        if (!empty($men)) {
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
                ->whereIn('users.id', $men)
                ->where('leaves.request_to',  $user->id)
                ->where('leaves.request_status', 'Pending')
                ->get();
        } else {
            $pendingLeaves = collect();
        }

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


        return view('maintain.maintain_index', ['main_emp' => $main_emp, 'task' => $task, 'staffNames' => $staffNames, 'taskCounts' => $taskCounts, 'categoryNames' => $categoryNames, 'categorytaskCounts' => $categorytaskCounts, 'subcategoryNames' => $subcategoryNames, 'subcategorytaskCounts' => $subcategorytaskCounts, 'pendingLeaves' => $pendingLeaves, 'task_ext' => $task_ext,  'tast_ext' => $tast_ext]);
    }

    public function list()
    {
        $rep = DB::table('maintain_req')
            ->leftJoin('users', 'users.id', '=', 'maintain_req.c_by')
            ->leftJoin('stores', 'stores.id', '=', 'users.store_id') // 👈 join store
            ->leftJoin('categories', 'categories.id', '=', 'maintain_req.cat')
            ->leftJoin('tasks', 'maintain_req.task_id', '=', 'tasks.id')
            ->leftJoin('sub_categories', 'sub_categories.id', '=', 'maintain_req.sub')
            ->select(
                'maintain_req.*',
                'users.name',
                'categories.category',
                'sub_categories.subcategory',
                'maintain_req.status as m_status',
                'maintain_req.id as m_id',
                'tasks.task_status as t_status',
                'stores.store_name',   // 👈 store name
                'stores.store_code'    // optional
            )
            ->get();

        return view('maintain.maintain_list', ['rep' => $rep]);
    }

    public function profile(Request $req)
    {
        $rep = DB::table('maintain_req')->where('maintain_req.id', $req->id)
            ->leftJoin('users', 'users.id', '=', 'maintain_req.c_by')
            ->leftJoin('categories', 'categories.id', '=', 'maintain_req.cat')
            ->leftJoin('sub_categories', 'sub_categories.id', '=', 'maintain_req.sub')
            ->select('maintain_req.*', 'users.name', 'categories.category', 'sub_categories.subcategory', 'maintain_req.status as m_status')
            ->get();
        //   dd($rep);


        $rep_task = DB::table('tasks')
            ->where('f_id', $rep[0]->task_id)
            ->leftJoin('categories', 'categories.id', '=', 'tasks.category_id')
            ->leftJoin('sub_categories', 'sub_categories.id', '=', 'tasks.subcategory_id')
            ->join('users as us', 'tasks.assign_to', '=', 'us.id') // Join for assign_to
            ->join('roles as u_role', 'us.role_id', '=', 'u_role.id') // Corrected join with roles for assign_to
            ->join('users as c_user', 'tasks.assign_by', '=', 'c_user.id') // Join for assign_by
            ->join('roles as c_role', 'c_user.role_id', '=', 'c_role.id') // Corrected join with roles for assign_by
            ->select(
                'tasks.*',
                'us.name as assign_name', // Name of user assigned to the task
                'c_user.name as c_name',  // Name of user who assigned the task
                'u_role.role as user_role', // Role of the user assigned to the task
                'c_role.role as cr_role', // Role of the user who assigned the task
                'categories.category',
                'sub_categories.subcategory'
            )
            ->get();



        //   dd($rep_task);

        return view('maintain.maintain_profile', ['rep' => $rep, 'rep_task' => $rep_task]);
    }

    public function task(Request $req)
    {
        $cat =  DB::table('categories')->whereIn('id', [17, 18, 19, 20, 21, 22, 23, 24, 25, 26])->get();

        $arr = $this->role_arr();

        $status =  DB::table('maintain_req')->where('maintain_req.id', $req->id)
            ->leftJoin('users', 'users.id', '=', 'maintain_req.c_by')
            ->select('users.role_id as u_role')
            ->first();

        $arr[] = intval($status->u_role);

        //  dd($arr);

        // $url = $req->url();

        // dd($url);

        $emp =  DB::table('users')->whereIn('role_id', $arr)
            ->where('users.status', 1)
            ->leftJoin('roles', 'roles.id', '=', 'users.role_id')
            ->select('users.name', 'users.emp_code', 'roles.role', 'roles.role_dept', 'users.id')->get();

        return view('maintain.maintain_task', ['cat' => $cat, 'emp' => $emp, 'm_id' => $req->id]);
    }

    public function show(string $id)
    {
        //
    }

    public function edit(string $id)
    {
        //
    }

    public function update(Request $request, string $id)
    {
        //
    }

    public function destroy(string $id)
    {
        //
    }
}
