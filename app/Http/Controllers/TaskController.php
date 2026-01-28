<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Models\Task;
use App\Services\FirebaseService;
use App\Models\{AutoTask, User, Role};
use App\Models\Notification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Exception;
use App\Http\Controllers\trait\common;


class TaskController extends Controller
{
    use common;

    protected $firebaseService;

    public function __construct(FirebaseService $firebaseService)
    {
        $this->firebaseService = $firebaseService;
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {

        $user = Auth::user();

        $task_cby = DB::table('tasks')->where('assign_by', $user->id)
            ->leftJoin('users', 'users.id', '=', 'tasks.assign_to')
            ->leftJoin('categories', 'tasks.category_id', '=', 'categories.id')
            ->leftJoin('sub_categories', 'tasks.subcategory_id', '=', 'sub_categories.id')
            ->orderBy('id', 'DESC')
            ->select(
                'tasks.id',
                'tasks.task_title',
                'categories.category',
                'sub_categories.subcategory',
                'tasks.priority',
                'tasks.start_date',
                'tasks.end_date',
                'tasks.task_status',
                'users.name as task_assign'
            )
            ->get();

        //   dd($task_cby->toArray());

        return view('task.list', ['task' => $task_cby, 'r_id' => $user->role_id]);
    }



    public function create(Request $req)
    {


        // $hasCluster = $req->is('task-add/cluster');

        // if ($hasCluster) {
        //     $cluster=1;
        //  }

        $user = auth()->user();
        $role = Role::find($user->role_id);
        $store = DB::table('stores')->where('id', $user->store_id)->first();

        $assignedRolesQuery = Role::join('role_based', 'roles.id', '=', 'role_based.assign_role_id')
            ->join('users', 'role_based.assign_role_id', '=', 'users.role_id')
            ->select('roles.role_dept', 'roles.id', 'roles.role')
            ->groupBy('roles.role_dept', 'roles.id', 'roles.role');

        if (!in_array($user->dept, ['Admin', 'HR'])) {
            if ($store) {
                $assignedRolesQuery->where('users.store_id', $store->id);
            }
        }

        $assignedRoles = $assignedRolesQuery->get();


        $cat = DB::table('categories')->where('status', 1)->get();

        // $user = Auth::user();

        // $employeesQuery = DB::table('users')->where('role_id',$user->id)->where('id', '!=', $user->id);

        // if (!in_array($user->dept, ['Admin', 'HR'])) {
        //     $employeesQuery->where('store_id', $user->store_id);
        // }

        // $employees = $employeesQuery->get();

        // return $employees;

        return view('task.add', [
            'cat' => $cat,
            'assignedRoles' => $assignedRoles,
            'user' => $user,
            'employees' => $employees ?? 0,
            'cluster' => $cluster ?? 0
        ]);
    }


    public function create_task(Request $req)
    {

        // $hasCluster = $req->is('task-add/hr');

        // if ($hasCluster) {
        //     $cluster=1;
        //  }

        $user = auth()->user();
        $r_id = $user->role_id;

        $cluster_check = DB::table('m_cluster as mc')
            ->leftJoin('users', 'users.id', '=', 'mc.cl_name')
            ->where('mc.cl_name', '=', $user->id)
            ->where('users.role_id', 12)
            ->count();

        $arr = $this->role_arr();

        $list = DB::table('users')
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
            $list->leftJoin('stores', 'stores.id', '=', 'users.store_id')
                ->select('users.name', 'roles.role', 'roles.role_dept', 'users.id', 'users.store_id', 'stores.store_name', 'stores.store_code') // Adjust store fields as needed
                ->whereIn('users.role_id', $arr);
            // ->where('users.id', '!=', $user->id);

            $list->orderBy('users.role_id');
        }


        $list = $list->where('users.status', 1)->get();
        // $list = $list->get();

        // dd($list);

        $cat = DB::table('categories')->where('status', 1)->get();


        //  return $list;

        return view('task.add1', [
            'cat' => $cat,
            'user' => $list,

        ]);
    }



    public function getSubcategories(Request $request)
    {
        $subcategories = DB::table('sub_categories')
            ->where('cat_id', $request->category_id)
            ->where('status', 1)
            ->get();

        return response()->json($subcategories);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'task_file' => 'nullable|file|max:5120|mimes:pdf,xlsx,xls,csv,jpg,jpeg,png,gif,doc,docx,txt'
        ]);

        $assignToArray = is_array($request->assign_to) ? $request->assign_to : [$request->assign_to];
        $assignBy = auth()->user()->id;
        $taskFilePath = null;

        // Handle file upload
        if ($request->hasFile('task_file')) {
            $file = $request->file('task_file');
            $fileName = date('y') . '-' . Str::upper(Str::random(8)) . '.' . $file->getClientOriginalExtension();
            $filePath = 'assets/images/Task/';

            if (!file_exists($filePath)) {
                mkdir($filePath, 0777, true);
            }

            $file->move($filePath, $fileName);
            $taskFilePath = $filePath . $fileName;
        }

        $notifications = [];

        foreach ($assignToArray as $assignTo) {

            foreach ($request->sub_cat as $sub_id) {
                $task = new Task();
                $task->task_title = $request->task_title;
                $task->category_id = $request->category_id;
                $task->subcategory_id = $sub_id;
                $task->assign_to = $assignTo;
                $task->task_description = $request->task_description;
                $task->additional_info = $request->additional_info;
                $task->start_date = $request->start_date;
                $task->start_time = $request->start_time;
                $task->end_date = $request->end_date;
                $task->end_time = $request->end_time;
                $task->priority = $request->priority;
                $task->task_file = $taskFilePath;
                $task->assign_by = $assignBy;

                $task->save();

                $task->f_id = $task->id;
                $task->save();

                try {
                    $user = User::find($assignTo);

                    $role_get = DB::table('roles')->where('id', auth()->user()->role_id)->first();

                    if ($user && $user->device_token) {

                        $taskTitle = "New Task Assigned";
                        $taskBody = "You have been assigned a new task: " . $taskTitle . " by " . auth()->user()->name . "[" . $role_get->role . "]";

                        $response = app(FirebaseService::class)->sendNotification(
                            $user->device_token,
                            $taskTitle,
                            $taskBody
                        );

                        Notification::create([
                            'user_id' => $assignTo,
                            'noty_type' => 'task',
                            'type_id' => $task->id,
                            'title' => $taskTitle,
                            'body' => $taskBody,
                            'c_by' => auth()->user()->id
                        ]);

                        // $notifications[] = [
                        //     'user_id' => $assignTo,
                        //     'device_token' => $user->device_token,
                        //     'title' => $taskTitle,
                        //     'body' => $taskBody,
                        //     'response' => $response
                        // ];
                    }
                } catch (\Exception $e) {
                    // Log::error('Notification Send Error: ' . $e->getMessage());
                    // $notifications[] = [
                    //     'user_id' => $assignTo,
                    //     'error' => $e->getMessage()
                    // ];
                }
            } // second foreach end....
        }

        // maintain request task.......
        if ($request->maintain == 'maintain') {

            $lat = DB::table('tasks')->orderBy('id', 'DESC')->first();
            $up = DB::table('maintain_req')->where('id', $request->m_id)
                ->update([
                    'task_id' => $lat->id
                ]);
        }

        return redirect()->route('task.index')->with([
            'status' => 'success',
            'message' => 'Task Added successfully!'
        ]);
    }

    public function completedtaskstore(Request $request)
    {

        $request->validate([
            'task_file' => 'nullable|file|max:5120|mimes:pdf,xlsx,xls,csv,jpg,jpeg,png,gif,doc,docx,txt'
        ]);

        //updating the old task

        $user_id = auth()->user()->id;
        $assignTo = $request->assign_to;

        $task = new Task();
        $task->f_id = $request->f_id;
        $task->task_title = $request->task_title;
        $task->assign_to = $assignTo;
        $task->category_id = $request->category_id;
        $task->subcategory_id = $request->subcategory_id;
        $task->task_description = $request->task_description;
        $task->start_date = $request->start_date;
        $task->start_time = $request->start_time;
        $task->end_date = $request->end_date;
        $task->end_time = $request->end_time;
        $task->priority = $request->priority;
        $task->assign_by = $user_id;


        // Handle file upload
        if ($request->hasFile('task_file')) {
            $file = $request->file('task_file');
            $fileName = date('y') . '-' . Str::upper(Str::random(8)) . '.' . $file->getClientOriginalExtension();
            $filePath = 'assets/images/Task/';

            if (!file_exists($filePath)) {
                mkdir($filePath, 0777, true);
            }

            $file->move($filePath, $fileName);
            $task->task_file = $filePath . $fileName;
        }

        $task->save();

        // comments insert
        $comment = DB::table('comments')->insert([
            'task_id' => $request->task_id,
            'comment' => $request->comment,
            'c_by' => $user_id,
            'update_on' => now(),
            'created_on' => now()
        ]);

        $old_task = Task::find($request->task_id);
        $old_task->task_status = 'Assigned';
        $old_task->save();

        try {


            $user = User::find($assignTo);
            if ($user && $user->device_token) {

                $role_get = DB::table('roles')->where('id', auth()->user()->role_id)->first();

                $taskTitle = "New Task Assigned";
                $taskBody = "You have been assigned a new task: " . $taskTitle . " by " . auth()->user()->name . "[" . $role_get->role . "]";


                $response = app(FirebaseService::class)->sendNotification(
                    $user->device_token,
                    $taskTitle,
                    $taskBody
                );

                // Store notification
                $notification = Notification::create([
                    'user_id' => $assignTo,
                    'noty_type' => 'task',
                    'type_id' => $task->id,
                    'title' => $taskTitle,
                    'body' => $taskBody,
                    'c_by' => auth()->user()->id
                ]);
            }
        } catch (\Exception $e) {
            Log::error('Error: ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Task could not be saved.',
                'error' => $e->getMessage()
            ], 500);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Task added successfully!'
        ]);
    }




    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $taskCheck = DB::table('tasks')
            ->where('id', $id)
            ->select('id', 'f_id')
            ->first();

        $queryTaskId = is_null($taskCheck->f_id) ? $id : $taskCheck->f_id;

        $task = DB::table('tasks')
            ->leftJoin('categories', 'tasks.category_id', '=', 'categories.id')
            ->leftJoin('sub_categories', 'tasks.subcategory_id', '=', 'sub_categories.id')
            ->leftJoin('users as assigned_to_user', 'tasks.assign_to', '=', 'assigned_to_user.id')
            ->leftJoin('users as assigned_by_user', 'tasks.assign_by', '=', 'assigned_by_user.id')
            ->leftJoin('comments as cs', 'cs.task_id', '=', 'tasks.id')
            ->leftJoin('roles as assigned_to_role', 'assigned_to_user.role_id', '=', 'assigned_to_role.id')
            ->leftJoin('roles as assigned_by_role', 'assigned_by_user.role_id', '=', 'assigned_by_role.id')
            ->where(function ($query) use ($queryTaskId) {
                $query->where('tasks.id', $queryTaskId)
                    ->orWhere('tasks.f_id', $queryTaskId);
            })
            ->select(
                'tasks.*',
                'cs.comment as comment',
                'categories.category',
                'sub_categories.subcategory',
                'assigned_to_user.name as assigned_to_name',
                'assigned_to_role.role as assigned_to_role',
                'assigned_by_user.name as assigned_by_name',
                'assigned_by_role.role as assigned_by_role'
            )
            ->orderBy('tasks.id', 'asc')
            ->get();


        // $tk = Task::findOrFail($id);

        // $task1 = Task::findOrFail($tk->f_id);

        // $comments = DB::table('comments')
        //     ->join('tasks', 'comments.task_id', '=', 'tasks.id')
        //     ->select('comments.comment', 'tasks.f_id')
        //     ->where('tasks.f_id', $tk->f_id)
        //     ->distinct()
        //     ->get();

        // 'comments' => $comments

        return view('task.profile', ['task' => $task]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        return view('task.edit');
    }

    public function completed_list(Request $req)
    {
        $user = Auth::user();

        $task_cby = DB::table('tasks')->where('assign_by', $user->id)
            ->whereIn('task_status', ['Close', 'Assigned'])
            // ->whereRaw('DATE_ADD(end_date, INTERVAL 15 DAY) >= ?', [now()])
            ->leftJoin('categories', 'tasks.category_id', '=', 'categories.id')
            ->leftJoin('sub_categories', 'tasks.subcategory_id', '=', 'sub_categories.id')
            ->orderBy('id', 'DESC')
            ->select(
                'tasks.id',
                'tasks.task_title',
                'categories.category',
                'sub_categories.subcategory',
                'tasks.priority',
                'tasks.start_date',
                'tasks.end_date',
            )
            ->get();

        //   dd($task_cby);

        return view('task.completed_list', ['task' => $task_cby]);
    }


    public function updateTaskStatus(Request $request)
    {


        $request->validate([
            'id' => 'required|integer|exists:tasks,id',
            'status' => 'required|string',
        ]);

        $task = Task::findOrFail($request->id);

        // if ($request->status == 'Close') {

        //     $first = DB::table('tasks')->where('f_id', $task->f_id)->orderBy('id', 'asc')->first();

        //     if ($first) {
        //         // Update both the current task and the first task with the new status
        //         DB::table('tasks')
        //             ->whereIn('id', [$task->id, $first->id]) // Updating both tasks
        //             ->update(['task_status' => $request->status]);
        //     }
        // } else {
        //     $task->update(['task_status' => $request->status]);
        // }
        
         if ($request->status == 'Close') {

            $first = DB::table('tasks')->where('f_id', $task->f_id)->orderBy('id', 'asc')->first();

            if ($first) {
                // Update both the current task and the first task with the new status
                DB::table('tasks')
                    ->whereIn('id', [$task->id, $first->id]) // Updating both tasks
                    ->update(['task_status' => $request->status]);
            }
        } elseif ($request->status == 'Completed') {
            // dd($request->all());
            $task->update(['task_status' => $request->status, 'task_completed' => now()]);
        } else {
            // Update only the current task with the new status
            dd($request->all());
            $task->update(['task_status' => $request->status]);
        }



        return response()->json([
            'success' => true,
            'message' => 'Task status updated successfully'
        ]);
    }

    public function not_completed_list(Request $request)
    {


        $task_cby = DB::table('tasks')->where('assign_by', auth()->user()->id)
            ->whereNotIn('task_status', ['Close', 'Assigned'])
            ->whereRaw('DATE(end_date) < ?', [now()])
            ->leftJoin('categories', 'tasks.category_id', '=', 'categories.id')
            ->leftJoin('sub_categories', 'tasks.subcategory_id', '=', 'sub_categories.id')
            ->orderBy('id', 'DESC')
            ->select(
                'tasks.id',
                'tasks.task_title',
                'categories.category',
                'sub_categories.subcategory',
                'tasks.priority',
                'tasks.start_date',
                'tasks.end_date',
            )
            ->get();

        return view('task.not_completed_list', ['task' => $task_cby]);
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

    //  request extend or close 
    public function task_ext(Request $req)
    {

        $user = Auth::user();
        $task = Task::findOrFail($req->task_id);

        $task1 = Task::findOrFail($task->f_id);

        if ($req->category == 'close') {

            // Handle file upload
            if ($req->hasFile('close_attach')) {
                $image = $req->file('close_attach');

                if ($image->isValid()) {
                    $filename = time() . '_' . $image->getClientOriginalName();
                    $image->move('assets/images/Task', $filename);
                } else {
                    return back()->with('error', 'Uploaded file is invalid.');
                }
            } else {
                return back()->with('error', 'Please upload a file to close the task.');
            }

            // Insert close request
            DB::table('task_ext')->insert([
                'task_id'     => $req->task_id,
                'request_for' => $task1->assign_by ?? null,
                'attach'      => $filename,
                'status'      => 'Close Request',
                'c_remarks'   => $req->remarks,
                'category' => $req->category,
                'c_by'        => $user->id,
                'created_at'  => now(),
                'updated_at'  => now(),
            ]);
        } else {
            // Insert extend request
            DB::table('task_ext')->insert([
                'task_id'     => $req->task_id,
                'request_for' => $task1->assign_by ?? null,
                'extend_date' => $req->extend_date,
                'status'      => 'Pending',
                'c_remarks'   => $req->remarks,
                'category' => $req->category,
                'c_by'        => $user->id,
                'created_at'  => now(),
                'updated_at'  => now(),
            ]);
        }


        return redirect()->back()->with('success', 'Task end date updated successfully.');
    }

    // extend task update
    public function task_ext_update(Request $req)
    {

        $taskExtRow = DB::table('task_ext')->where('id', $req->id)->first();

        $task_ext = DB::table('task_ext')->where('id', $req->id)->update([
            'extend_date' => $req->extend_date,
            'a_remarks' => $req->remarks,
            'status' => 'Completed',
            'updated_at' => now(),
        ]);

        $task = Task::findOrFail($taskExtRow->task_id);

        // $task1 = Task::findOrFail($task->f_id);

        $task_ext_update = DB::table('tasks')->where('id', $task->id)->update([
            'end_date' => $req->extend_date,
            'updated_at' => now(),
        ]);

        // Notification
        if ($task_ext_update) {
            $user = User::findOrFail($taskExtRow->c_by);

            if ($user->device_token) {

                $role_get = DB::table('roles')->where('id', auth()->user()->role_id)->first();

                $taskTitle = "Task Extend Approved";
                $taskBody = "Your task has been extended succesfully: " . $task->task_title . " by " . auth()->user()->name . "[" . $role_get->role . "]";


                $response = app(FirebaseService::class)->sendNotification(
                    $user->device_token,
                    $taskTitle,
                    $taskBody
                );

                Notification::create([
                    'user_id' => $taskExtRow->c_by,
                    'noty_type' => 'task',
                    'type_id' => $task->id,
                    'title' => $taskTitle,
                    'body' => $taskBody,
                    'c_by' => auth()->user()->id
                ]);
            }
        }

        return redirect()->back()->with('success', 'Task end date updated successfully.');
    }
    // close task
    public function del_task(Request $req)
    {
        $taskExtRow = DB::table('task_ext')->where('id', $req->id)->first();

        $task_close =  DB::table('task_ext')->where('id', $req->id)->update([
            'a_remarks' => $req->remarks,
            'status' => 'Closed',
            'updated_at' => now(),
        ]);

        $task = Task::findOrFail($taskExtRow->task_id);

        DB::table('tasks')->where('id', $task->id)->update([
            'task_status' => 'Close',
            'updated_at' => now(),
        ]);

        // Notification
        if ($task_close) {
            $user = User::findOrFail($taskExtRow->c_by);

            if ($user->device_token) {

                $role_get = DB::table('roles')->where('id', auth()->user()->role_id)->first();

                $taskTitle = "Task Close Approved";
                $taskBody = "Your task has been closed succesfully: " . $task->task_title . " by " . auth()->user()->name . "[" . $role_get->role . "]";


                $response = app(FirebaseService::class)->sendNotification(
                    $user->device_token,
                    $taskTitle,
                    $taskBody
                );

                Notification::create([
                    'user_id' => $taskExtRow->c_by,
                    'noty_type' => 'task',
                    'type_id' => $task->id,
                    'title' => $taskTitle,
                    'body' => $taskBody,
                    'c_by' => auth()->user()->id
                ]);
            }
        }


        return redirect()->back()->with('success', 'Task closed updated successfully.');
    }
    
        public function auto_task_list()
    {
        $user = Auth::user();

        $user = auth()->user(); // or however you get $user

        $taskQuery = DB::table('auto_tasks')
            ->leftJoin('users as assign_user', 'assign_user.id', '=', 'auto_tasks.assign_to')
            ->leftJoin('users as creator_user', 'creator_user.id', '=', 'auto_tasks.created_by')
            ->leftJoin('categories', 'auto_tasks.category_id', '=', 'categories.id')
            ->leftJoin('sub_categories', 'auto_tasks.subcategory_id', '=', 'sub_categories.id')
            ->orderBy('auto_tasks.id', 'DESC')
            ->select(
                'auto_tasks.id',
                'auto_tasks.task_title',
                'categories.category',
                'sub_categories.subcategory',
                'auto_tasks.priority',
                'auto_tasks.start_date',
                'auto_tasks.end_date',
                'auto_tasks.created_at',
                'auto_tasks.task_description',
                'assign_user.name as task_assign',
                'creator_user.name as created_by_name'
            );

        // Apply condition based on role
        if (!in_array($user->role_id, [1, 2])) {
            $taskQuery->where('auto_tasks.assign_to', $user->id);
        }

        $task_cby = $taskQuery->get();


        return view('task.autotask_list', ['task' => $task_cby, 'r_id' => $user->role_id]);
    }

    // public function add_auto_task()
    // {

    //     $user = auth()->user();
    //     $r_id = $user->role_id;

    //     $cluster_check = DB::table('m_cluster as mc')
    //         ->leftJoin('users', 'users.id', '=', 'mc.cl_name')
    //         ->where('mc.cl_name', '=', $user->id)
    //         ->where('users.role_id', 12)
    //         ->count();

    //     $arr = $this->role_arr();

    //     $list = DB::table('users')
    //         ->leftJoin('roles', 'roles.id', '=', 'users.role_id')
    //         ->select('users.name', 'roles.role', 'roles.role_dept', 'users.id', 'users.store_id');


    //     if (($r_id >= 12 && $r_id <= 19) or ($r_id == 50)) {

    //         if ($cluster_check == 0) {
    //             $list->where('users.store_id', $user->store_id);
    //             // ->where('users.id', '!=', $user->id);
    //         } else {

    //             $list->leftJoin('stores', 'stores.id', '=', 'users.store_id')
    //                 ->where(function ($query) use ($user) {
    //                     // Include all users with role_id = 12
    //                     $query->where('users.role_id', 12)
    //                         // Include all users from the current user's store
    //                         ->orWhere('users.store_id', $user->store_id);
    //                 })
    //                 // ->where('users.id', '!=', $user->id)
    //                 ->orderBy('users.role_id');
    //         }
    //     } else {
    //         $list->leftJoin('stores', 'stores.id', '=', 'users.store_id')
    //             ->select('users.name', 'roles.role', 'roles.role_dept', 'users.id', 'users.store_id', 'stores.store_name', 'stores.store_code') // Adjust store fields as needed
    //             ->whereIn('users.role_id', $arr);
    //         // ->where('users.id', '!=', $user->id);

    //         $list->orderBy('users.role_id');
    //     }


    //     $list = $list->where('users.status', 1)->get();
    //     // $list = $list->get();

    //     // dd($list);

    //     $cat = DB::table('categories')->where('status', 1)->get();


    //     return view('task.add_autotask', [
    //         'cat' => $cat,
    //         'user' => $list,

    //     ]);
    //     // return view('');
    // }
    
      public function add_auto_task()
    {

        $user = auth()->user();
        $r_id = $user->role_id;

        // $roles = DB::table('roles')->where('status', 1)->get();
        $roles = DB::table('roles')
            ->join('users', 'roles.id', '=', 'users.role_id')
            ->where('roles.status', 1)
            ->where('users.status', 1)
            ->select('roles.*')
            ->distinct()
            ->get();

        $cat = DB::table('categories')->where('status', 1)->get();

        return view('task.add_autotask', [
            'cat' => $cat,
            'roles' => $roles,
        ]);
    }
    
    public function store_auto_task(Request $request)
    {
    $request->validate([
            'task_file' => 'nullable|file|max:5120|mimes:pdf,xlsx,xls,csv,jpg,jpeg,png,gif,doc,docx,txt'
        ]);

        $assignToArray = is_array($request->assign_to) ? $request->assign_to : [$request->assign_to];
        $assignBy = auth()->user()->id;
        $taskFilePath = null;

        // Handle file upload
        if ($request->hasFile('task_file')) {
            $file = $request->file('task_file');
            $fileName = date('y') . '-' . Str::upper(Str::random(8)) . '.' . $file->getClientOriginalExtension();
            $filePath = 'assets/images/Task/';

            if (!file_exists($filePath)) {
                mkdir($filePath, 0777, true);
            }

            $file->move($filePath, $fileName);
            $taskFilePath = $filePath . $fileName;
        }

        foreach ($assignToArray as $roleId) {
            // Get all active users for this role
            $usersInRole = DB::table('users')
                ->where('role_id', $roleId)
                ->where('status', 1)
                ->pluck('id');

            foreach ($usersInRole as $assignTo) {
                foreach ($request->sub_cat as $sub_id) {
                    $task = new AutoTask();
                    $task->task_title = $request->task_title;
                    $task->category_id = $request->category_id;
                    $task->subcategory_id = $sub_id;
                    $task->assign_to = $assignTo; // user id
                    $task->task_description = $request->task_description;
                    $task->start_date = $request->start_date;
                    // $task->end_date = $request->end_date;
                    $task->priority = $request->priority;
                    $task->task_file = $taskFilePath;
                    $task->created_by = $assignBy;
                    $task->save();
                }
            }
        }

        return redirect()->route('auto_task_list')->with([
            'status' => 'success',
            'message' => 'Auto Task Added successfully!'
        ]);
    }
}
