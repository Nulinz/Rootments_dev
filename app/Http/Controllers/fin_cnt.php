<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Http\Controllers\trait\common;

class fin_cnt extends Controller
{
    use common;
    /**
     * Display a listing of the resource.
     */
    public function index()
    {

        $user = Auth::user();

        $hr_emp = $this->attd_index('Finance');

        $role_mem = DB::table('roles')->where('role_dept', 'Finance')
            ->leftJoin('users as us', 'us.role_id', '=', 'roles.id')
            ->select('us.id')->get();

        $men = [];

        foreach ($role_mem as $role_men1) {

            $men[] = $role_men1->id;

            // dd($role_men1->id);
        }




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

        // dd($task);

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


        //    dd($categoryTask);

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

        // dd($pendingLeaves);

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



        return view('finance.finance_index', [
            'hr_emp' => $hr_emp,
            'task' => $task,
            'staffNames' => $staffNames,
            'taskCounts' => $taskCounts,
            'categoryNames' => $categoryNames,
            'categorytaskCounts' => $categorytaskCounts,
            'subcategoryNames' => $subcategoryNames,
            'subcategorytaskCounts' => $subcategorytaskCounts,
            'pendingLeaves' => $pendingLeaves,
            'task_ext' => $task_ext,
            'tast_ext' => $tast_ext
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
}
