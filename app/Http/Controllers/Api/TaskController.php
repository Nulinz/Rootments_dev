<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\DB;
use App\Services\FirebaseService;
use Illuminate\Support\Str;
use App\Models\Task;
use App\Models\User;
use App\Models\Role;
use App\Models\Leave;
use App\Models\Resignation;
use App\Models\Transfer;
use App\Models\Notification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use App\Http\Controllers\trait\common;
use App\Models\AutoTask;

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
    public function index(Request $request)
    {


        $assignTo = $request->input('assign_to', Auth::user()->id);

        $statuses = ['To Do', 'In Progress', 'On Hold', 'Completed'];
        $tasks = [];

        foreach ($statuses as $status) {
            $tasks[$status] = DB::table('tasks')
                ->leftJoin('categories', 'tasks.category_id', '=', 'categories.id')
                ->leftJoin('sub_categories', 'tasks.subcategory_id', '=', 'sub_categories.id')
                // ->leftJoin('task_ext', 'tasks.id', '=', 'task_ext.task_id')
                ->leftJoin('roles as assigned_role', 'tasks.assign_to', '=', 'assigned_role.id')
                ->leftJoin('roles as assigned_by_role', 'tasks.assign_by', '=', 'assigned_by_role.id')
                ->leftJoin('users as assigned_by_user', 'tasks.assign_by', '=', 'assigned_by_user.id')
                ->leftJoin('users as assigned_to_user', 'tasks.assign_to', '=', 'assigned_to_user.role_id')
                ->where('tasks.assign_to', $assignTo)
                // ->where('tasks.task_status', $status)
                // Conditionally add status filters
                ->when($status === 'Completed', function ($query) {
                    return $query->whereIn('tasks.task_status', ['Completed', 'Close', 'Assigned'])
                        ->where(function ($query) {
                            $query->where('tasks.task_status', 'Completed') // Keep Completed tasks
                                // Exclude Assigned and Close tasks where end_date + 15 days is greater than the current date
                                ->orWhere(function ($subQuery) {
                                    $subQuery->whereIn('tasks.task_status', ['Assigned', 'Close'])
                                        ->whereRaw('DATE_ADD(tasks.end_date, INTERVAL 15 DAY) >= ?', [Carbon::now()]);
                                });
                        });
                })
                ->when($status !== 'Completed', function ($query) use ($status) {
                    return $query->where('tasks.task_status', $status);
                })
                ->select(
                    'tasks.*',
                    'categories.category',
                    'sub_categories.subcategory',
                    'assigned_role.role as assigned_role',
                    'assigned_by_role.role as task_assigned',
                    'assigned_by_user.name as assigned_by',
                    'assigned_to_user.name as assigned_to',
                    // 'task_ext.task_id',
                    // 'task_ext.status as task_ext_status',
                    // 'task_ext.status as task_close_status'
                )
                ->orderBy('tasks.id', 'DESC')

                // ->get()->map(function ($item) {

                //     $item->task_ext_status =  DB::table('task_ext')->where('task_id', $item->id)->where('category', 'extend')->value('status') ?? null;
                //     $item->task_close_status =  DB::table('task_ext')->where('task_id', $item->id)->where('category', 'close')->value('status') ?? null;

                //     return $item;
                // });

                ->get()->map(function ($item) {

                    $ext_status = DB::table('task_ext')
                        ->where('task_id', $item->id)
                        ->where('category', 'extend')
                        ->value('status');

                    $item->task_ext_status = ($ext_status === 'Closed') ? null : $ext_status;

                    $close_status = DB::table('task_ext')
                        ->where('task_id', $item->id)
                        ->where('category', 'close')
                        ->value('status');

                    $item->task_close_status = ($close_status === 'Closed') ? null : $close_status;

                    return $item;
                });


            foreach ($tasks[$status] as $task) {
                $task->task_fileUrl = $task->task_file ? url($task->task_file) : null;
            }
        }

        return response()->json([
            'data' => $tasks
        ]);
    }


    public function getCategories()
    {
        $categories = DB::table('categories')->select('id', 'category')->where('status', 1)->get();

        return response()->json([
            'data' => $categories,
        ]);
    }

    public function getsubcategories(Request $request)
    {
        $subcategories = DB::table('sub_categories')
            ->where('cat_id', $request->category_id)
            ->select('id', 'subcategory')
            ->where('status', 1)
            ->get();

        return response()->json([
            'data' => $subcategories,
        ]);
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {


        $user = auth()->user();

        if (!$user) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        // Fetch all roles, excluding the logged-in user's role if they belong to a store
        $rolesQuery = Role::select('id', 'role', 'role_dept');

        if (!is_null($user->store_id)) {
            $rolesQuery->where('id', '!=', $user->role_id);
        }

        $roles = $rolesQuery->get();

        // Fetch employees, excluding the logged-in user
        $employeesQuery = User::select('id', 'name', 'role_id', 'store_id')
            ->where('id', '!=', $user->id); // Exclude logged-in user

        if (!is_null($user->store_id)) {
            $employeesQuery->where('store_id', $user->store_id);
        }

        $employees = $employeesQuery->get()->groupBy('role_id');


        return response()->json([
            'data' => [
                'roles' => $roles,   // Filtered roles list
                'employees' => $employees, // Employees grouped by role (excluding logged-in user)
            ],
        ]);
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // $firstTask = null;

        // $assignToString = $request->assign_to;  // This is your string '[1,2,3]'

        // Convert the string to an array using json_decode
        // $assignToArray = is_array($request->assign_to) ? $request->assign_to : [$request->assign_to];


        // $sub_arr = is_array($request->subcategory_id) ? $request->subcategory_id : [$request->subcategory_id];

        $assignToArray = json_decode($request->assign_to, true);

        $sub_arr = json_decode($request->subcategory_id, true);

        //  $assignToArray = is_array($request->assign_to) ? $request->assign_to : [$request->assign_to];

        foreach ($assignToArray as $assignTo) {

            foreach ($sub_arr as $sub_id) {

                $task = new Task();
                $task->task_title = $request->task_title;
                $task->category_id = $request->category_id;
                $task->subcategory_id = $sub_id;
                $task->assign_to = $assignTo;
                $task->task_description = $request->task_description;
                $task->start_date = $request->start_date;
                $task->start_time = $request->start_time;
                $task->end_date = $request->end_date;
                $task->end_time = $request->end_time;
                $task->priority = $request->priority;
                $task->assign_by = $request->assign_by;

                if ($request->hasFile('task_file')) {
                    $file = $request->file('task_file');
                    // $name = date('y') . '-' . Str::upper(Str::random(8)) . '.' . $file->getClientOriginalExtension();
                    $extension = $file->getClientOriginalExtension();
                    $filename = uniqid('task_file_') . '.' . $extension; // Generate a unique filename
                    // $path = $file->storeAs('task_files', $filename, 'public'); // Store in 'storage/app/public/task_files'
                    // $url = Storage::url($path); // Get the public URL of the file


                    $path = 'assets/images/Task/';
                    $file->move($path, $filename);

                    $task->task_file = $path . $filename;
                }


                $task->save();


                // Update f_id for all tasks
                $task->f_id = $task->id;
                $task->save(); // Update task with f_id

                // $task->f_id = $task->id;
                // $task->save();

                // $notifications = [];

                // foreach ($assignToArray as $assignTo) {
                //     try {
                //         $user_assign = User::findOrFail($assignTo);

                //         if ($user_assign->device_token) {

                //             $role_get = DB::table('roles')->where('id', auth()->user()->role_id)->first();

                //             $taskTitle = "New Task Assigned";
                //             $taskBody = "You have been assigned a new task: " . $taskTitle." by ".auth()->user()->name."[".$role_get->role."]";


                //             $response = app(FirebaseService::class)->sendNotification(
                //                 $user_assign->device_token,
                //                 $taskTitle,
                //                 $taskBody
                //             );
                //             Notification::create([
                //                 'user_id' => $assignTo,
                //                 'noty_type' => 'task',
                //                 'type_id' => $task->id,
                //                 'title'=> $taskTitle,
                //                 'body'=> $taskBody,
                //                 'c_by'=>auth()->user()->id
                //             ]);

                //             // $notifications[] = [
                //             //     'user_id' => $assignTo,
                //             //     'device_token' => $user_assign->device_token,
                //             //     'title' => $taskTitle,
                //             //     'body' => $taskBody,
                //             //     'response' => $response
                //             // ];
                //         }
                //     } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
                //         $notifications[] = [
                //             'user_id' => $assignTo,
                //             'error' => 'User not found'
                //         ];
                //     }
                // }
            } // second for each end....

        } // first foreach....

        return response()->json([
            'success' => true,
            'message' => 'Task Added successfully',
            // 'error'=>$notifications
        ]);
    }

    public function completedtaskstore(Request $request)
    {
        $user_id = auth()->user()->id;

        $assignToArray = is_array($request->assign_to) ? $request->assign_to : [$request->assign_to];

        $old_task = Task::find($request->task_id);
        $old_task->task_status = 'Assigned';
        $old_task->save();

        foreach ($assignToArray as $assignTo) {
            $task = new Task();
            $task->f_id = $request->f_id;
            $task->task_title = $request->task_title;
            // $task->task_title = $request->task_title;
            $task->category_id = $request->category_id;
            $task->subcategory_id = $request->subcategory_id;
            $task->assign_to = $assignTo;
            $task->task_description = $request->task_description;
            $task->start_date = $request->start_date;
            $task->start_time = $request->start_time;
            $task->end_date = $request->end_date;
            $task->end_time = $request->end_time;
            $task->priority = $request->priority;

            if ($request->hasFile('task_file')) {
                $file = $request->file('task_file');
                $name = date('y') . '-' . Str::upper(Str::random(8)) . '.' . $file->getClientOriginalExtension();
                $path = 'assets/images/Task/';
                $file->move($path, $name);

                $task->task_file = $path . $name;
            }

            // comments insert
            $comment = DB::table('comments')->insert([
                'task_id' => $request->task_id,
                'comment' => $request->comment,
                'c_by' => $user_id,
                'update_on' => now(),
                'created_on' => now()
            ]);

            $task->assign_by = $user_id;
            $task->save();

            $notifications = [];

            foreach ($assignToArray as $assignTo) {
                try {
                    $user = User::findOrFail($assignTo);

                    if ($user->device_token) {

                        $role_get = DB::table('roles')->where('id', auth()->user()->role_id)->first();

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

                        $notifications[] = [
                            'user_id' => $assignTo,
                            'device_token' => $user->device_token,
                            'title' => $taskTitle,
                            'body' => $taskBody,
                            'response' => $response
                        ];
                    }
                } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
                    $notifications[] = [
                        'user_id' => $assignTo,
                        'error' => 'User not found'
                    ];
                }
            }
        }

        return response()->json([
            'success' => true,
            'message' => 'Task Added successfully'
        ]);
    }

    public function updateTaskStatus(Request $request)
    {


        $request->validate([
            'id' => 'required',
            'status' => 'required|string',
        ]);

        $task = Task::findOrFail($request->id);

        if ($request->status == 'Close') {

            $first  = Task::with(['c_by:id,device_token,name'])->where('f_id', $task->f_id)->orderBy('id', 'asc')->first();

            if ($first) {
                // Update both the current task and the first task with the new status
                DB::table('tasks')
                    ->whereIn('id', [$task->id, $first->id]) // Updating both tasks
                    ->update(['task_status' => $request->status]);

                if (!is_null($first->c_by->device_token)) {

                    $role_get = DB::table('roles')->where('id', auth()->user()->role_id)->first();

                    $taskTitle = "Task Closed ";
                    $taskBody = "Task has been closed: " . $first->task_title . " by " . auth()->user()->name . "[" . $role_get->role . "]";


                    $response = app(FirebaseService::class)->sendNotification(
                        $first->c_by->device_token,
                        $taskTitle,
                        $taskBody
                    );

                    Notification::create([
                        'user_id' => $first->assign_by,
                        'noty_type' => 'task',
                        'type_id' => $task->id,
                        'title' => $taskTitle,
                        'body' => $taskBody,
                        'c_by' => auth()->user()->id
                    ]);
                }
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

    /**
     * Display the specified resource.
     */
    public function leavelist(Request $request)
    {
        $user_id = $request->input('user_id');

        $leave = DB::table('leaves')
            ->leftjoin('users', 'leaves.user_id', '=', 'users.id')
            ->select('leaves.*', 'users.name', 'users.emp_code')
            ->where('leaves.created_by', $user_id)
            ->orderBy('leaves.id', 'desc')
            ->get();

        return response()->json([
            'data' => [
                'leave' => $leave
            ],
        ]);
    }

    public function reginationlist(Request $request)
    {
        $user_id = $request->input('user_id');

        $resgination = DB::table('resignations')
            ->leftjoin('stores', 'stores.id', '=', 'resignations.store_id')
            ->leftjoin('users', 'users.id', '=', 'resignations.emp_id')
            ->select('resignations.*', 'stores.store_name', 'users.emp_code')
            ->where('resignations.created_by', $user_id)
            ->get();

        return response()->json([
            'data' => [
                'resgination' => $resgination
            ],
        ]);
    }

    public function transferlist(Request $request)
    {
        $user_id = $request->input('user_id');

        $transfer = DB::table('transfers')
            ->leftJoin('stores as from_stores', 'from_stores.id', '=', 'transfers.fromstore_id')
            ->leftJoin('stores as to_stores', 'to_stores.id', '=', 'transfers.tostore_id')
            ->leftJoin('users', 'users.id', '=', 'transfers.emp_id')
            ->select(
                'transfers.*',
                'from_stores.store_name as from_store_name',
                'to_stores.store_name as to_store_name',
                'users.emp_code'
            )
            ->where('transfers.created_by', $user_id)
            ->get();

        return response()->json([
            'data' => [
                'transfer' => $transfer
            ],
        ]);
    }



    public function reginationstore(Request $request)
    {

        dd($request->all());

        $user_id = auth()->user();

        //   $role_get = DB::table('roles')
        //     ->leftJoin('users', 'users.role_id', '=', 'roles.id')
        //     ->select('roles.id', 'roles.role', 'roles.role_dept')
        //     ->where('users.id', $user_id->id)
        //     ->first();

        // if ($role_get) {
        // $resgination = new Resignation();
        // $resgination->emp_id =$request->emp_id;
        // $resgination->emp_name =$request->emp_name;
        // $resgination->store_id =$request->store_id;
        // $resgination->res_date =$request->res_date;
        // $resgination->res_reason =$request->res_reason;
        // $resgination->created_by=$user_id->id;
        // $resgination->request_to = $request->request_to;
        // $res_save = $resgination->save();

        // $req_token  = DB::table('users')->where('id',$request->request_to)->first();

        // if (!is_null($req_token->device_token)) {

        //     $role_get = DB::table('roles')->where('id', $user_id->role_id)->first();

        //     $taskTitle = "Resignation Request";

        //     $taskBody = $user_id->name."[".$user_id->role_rel->role."] Requested for Resignation";

        //     $response = app(FirebaseService::class)->sendNotification($req_token->device_token,$taskTitle,$taskBody);

        //     Notification::create([
        //         'user_id' => $req_token->id,
        //         'noty_type' => 'resignation',
        //         'type_id' => $resgination->id,
        //         'title'=> $taskTitle,
        //         'body'=> $taskBody,
        //         'c_by'=>auth()->user()->id
        //     ]);

        //     // dd($response);
        // } // notification end

        // if($user_id->role_id >= 13 && $user_id->role_id <= 19){

        // $store_man = DB::table('users')->where('store_id',$user_id->store_id)->where('role_id',12)->first();
        //         $resgination->request_to = $store_man->id ?? 2;
        //         $req_to = $store_man->id ?? 2;
        //         $req_token  = DB::table('users')->where('id',$store_man->id ?? 2)->first();
        // }else{
        //     $resgination->request_to = $request->request_to;
        //     $req_to = $request->request_to;
        //     $req_token  = DB::table('users')->where('id',$request->request_to)->first();
        // }


        //     if ($req_token->device_token) {
        //          $taskTitle = "Resignation Request";
        //         $taskBody = Auth::user()->name. "Requested for Resignation";

        //         $response = app(FirebaseService::class)->sendNotification($req_token->device_token,$taskTitle,$taskBody);

        //         Notification::create([
        //             'user_id' => $req_to,
        //             'noty_type' => 'resignation',
        //             'type_id' => $resgination->id
        //         ]);
        // } // notification end



        // } else {

        //     return response()->json(['error' => 'User role not found'], 404);
        // }

        return response()->json([
            'status' => $res_save ? 'success' : 'failed',
            'message' => $res_save ? 'Resgination Request Added successfully!' : 'Resgination Request Failed to Add!'
        ]);
    }

    public function transferstore(Request $request)
    {
        $user_id = auth()->user()->id;

        $role_get = DB::table('roles')
            ->leftJoin('users', 'users.role_id', '=', 'roles.id')
            ->select('roles.id', 'roles.role', 'roles.role_dept')
            ->where('users.id', $user_id)
            ->first();

        if ($role_get) {
            $transfer = new Transfer();
            $transfer->emp_id = $request->emp_id;
            $transfer->emp_name = $request->emp_name;
            $transfer->fromstore_id = $request->fromstore_id;
            $transfer->tostore_id = $request->tostore_id;
            $transfer->transfer_date = $request->transfer_date;
            $transfer->transfer_description = $request->transfer_description;
            $transfer->created_by = $user_id;

            if ($role_get->role == 'Store Manager') {
                $transfer->request_to = 3;
            } elseif ($role_get->role == 'Manager') {
                $transfer->request_to = 1;
            } elseif ($role_get->role == 'Managing Director') {
                $transfer->request_to = 3;
            } else {
                $transfer->request_to = 12;
            }

            $transfer->save();
        } else {

            return response()->json(['error' => 'User role not found'], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'Transfer Request Sent successfully'
        ]);
    }

    public function storelist()
    {
        $store = DB::table('stores')->select('id', 'store_name')->where('status', 1)->get();

        return response()->json([
            'data' => $store,
        ]);
    }



    public function notification_list(Request $request)
    {

        $user = $request->user_id;

        $notifications = DB::table('notifications')

            ->where('user_id', $user)
            ->whereIn('noty_type', ['task', 'resignation', 'recruitment', 'Recruitment', 'leave', 'Store Setup', 'Maintenance', 'E_store'])
            // ->whereIn('noty_type',['leave','Store Setup','Maintenance','E_store'])
            ->select('id', 'user_id', 'noty_type', 'type_id', 'created_at', 'title', 'body')
            ->OrderBy('id', 'DESC')
            ->get();



        ////////////////////////////

        // $user = $request->input('user_id');


        // if (!$user) {

        //     return response()->json(['message' => 'Unauthorized'], 401);
        // }


        // $notifications = DB::table('notifications')
        //     ->select('id', 'user_id', 'noty_type', 'type_id', 'created_at')
        //     ->where('user_id', $user)
        //      ->whereIn('noty_type',['leave','task','resignation','recruitment', 'Recruitment','leave','Store Setup','Maintenance','E_store'])
        //     //  ->whereIn('noty_type',['Maintenance'])
        //     ->OrderBy('id','DESC')
        //     ->get();

        //     // dd($notifications);

        // if ($notifications->isEmpty()) {
        //     return response()->json(['message' => 'No notifications found', 'data' => []]);
        // }

        // $data = [];

        // foreach ($notifications as $notification) {

        //     $details = null;

        //     switch ($notification->noty_type) {
        //         case 'task':
        //             $details = DB::table('tasks')
        //                 ->leftJoin('categories', 'tasks.category_id', '=', 'categories.id')
        //                 ->leftJoin('sub_categories', 'tasks.subcategory_id', '=', 'sub_categories.id')
        //                 ->leftJoin('users as assigned_to_user', 'tasks.assign_to', '=', 'assigned_to_user.id')
        //                 ->leftJoin('users as assigned_by_user', 'tasks.assign_by', '=', 'assigned_by_user.id')
        //                 ->leftJoin('roles as assigned_to_role', 'assigned_to_user.role_id', '=', 'assigned_to_role.id')
        //                 ->leftJoin('roles as assigned_by_role', 'assigned_by_user.role_id', '=', 'assigned_by_role.id')
        //                 ->where('tasks.id', $notification->type_id)
        //                  ->where('tasks.assign_to', $notification->user_id)
        //                 ->select(
        //                     'tasks.id',
        //                     'tasks.task_title',
        //                     'categories.category',
        //                     'sub_categories.subcategory',
        //                     'assigned_to_user.name as assigned_to_name',
        //                     'assigned_to_role.role as assigned_to_role',
        //                     'assigned_by_user.name as assigned_by_name',
        //                     'assigned_by_role.role as assigned_by_role'
        //                 )
        //                 ->orderBy('tasks.id','desc')
        //                 ->first();
        //             break;

        //         case 'leave':
        //             $details = DB::table('leaves')
        //             ->where('leaves.id', $notification->type_id)  // Explicitly refer to 'resignations.id'
        //                     ->leftJoin('users', 'users.id', '=', 'leaves.created_by')  // Correct LEFT JOIN
        //                     ->leftJoin('roles', 'roles.id', '=', 'users.role_id')  // Correct LEFT JOIN
        //                     ->select('leaves.id as lev_id', 'leaves.status', 'users.name','leaves.request_type','roles.role','leaves.created_by as cr_by')
        //                     ->orderBy('leaves.id', 'desc')
        //                     ->first();

        //             break;


        //         case 'transfer1':
        //             $details = DB::table('transfers')
        //                 ->where('id', $notification->type_id)
        //                 ->where('emp_id', $user)
        //                 ->select('id as tra_id', 'transfer_description')
        //                 ->orderBy('transfers.id as tra_id','desc')
        //                 ->first();
        //             break;

        //             case 'resignation':
        //                 $details = DB::table('resignations')
        //                     ->where('resignations.id', $notification->type_id)  // Explicitly refer to 'resignations.id'
        //                     ->leftJoin('users', 'users.id', '=', 'resignations.created_by')  // Correct LEFT JOIN
        //                     ->leftJoin('roles', 'roles.id', '=', 'users.role_id')  // Correct LEFT JOIN
        //                     ->select('resignations.id as res_id', 'resignations.status', 'users.name','roles.role','resignations.created_by as cr_by')
        //                     ->orderBy('resignations.id', 'desc')
        //                     ->first();
        //                 break;

        //             case 'Store Setup':
        //                 $details = DB::table('set_up')
        //                     ->where('set_up.id', $notification->type_id)  // Explicitly refer to 'resignations.id'
        //                     ->leftJoin('users', 'users.id', '=', 'set_up.c_by')  // Correct LEFT JOIN
        //                     ->leftJoin('roles', 'roles.id', '=', 'users.role_id')  // Correct LEFT JOIN
        //                     ->select('set_up.id as set_id', 'set_up.status','set_up.st_name', 'users.name','roles.role','set_up.c_by as cr_by')
        //                     ->orderBy('set_up.id', 'desc')
        //                     ->first();
        //                 break;
        //             case 'E_store':
        //                 $details = DB::table('e_setup')
        //                     ->where('e_setup.id', $notification->type_id)  // Explicitly refer to 'resignations.id'
        //                     ->leftJoin('users', 'users.id', '=', 'e_setup.c_by')  // Correct LEFT JOIN
        //                     ->leftJoin('set_up', 'set_up.id', '=', 'e_setup.set_id')  // Correct LEFT JOIN
        //                     ->select('e_setup.id as e_set_id', 'e_setup.status','set_up.st_name','e_setup.sub')
        //                     ->orderBy('e_setup.id', 'desc')
        //                     ->first();
        //                 break;
        //             case 'Maintenance':
        //                 $details = DB::table('maintain_req as main')
        //                     ->where('main.id', $notification->type_id)  // Explicitly refer to 'resignations.id'
        //                     ->leftJoin('users', 'users.id', '=', 'main.c_by')  // Correct LEFT JOIN
        //                     ->leftJoin('roles', 'roles.id', '=', 'users.role_id')  // Correct LEFT JOIN
        //                     ->leftJoin('stores', 'stores.id', '=', 'users.store_id')  // Correct LEFT JOIN
        //                     ->select('main.id as main_id', 'main.status', 'users.name','roles.role','main.c_by as cr_by','stores.store_name','main.req_status','main.status','main.esculate_status')
        //                     ->orderBy('main.id', 'desc')
        //                     ->first();
        //                 break;

        //             case ('recruitment'||'Recruitment'):
        //                 $details = DB::table('recruitments as rc')
        //                     ->where('rc.id', $notification->type_id)
        //                     ->leftJoin('users', 'users.id', '=', 'rc.c_by')  // Correct LEFT JOIN
        //                     ->leftJoin('roles', 'roles.id', '=', 'users.role_id')  // Correct LEFT JOIN
        //                     ->leftJoin('roles as rl',function($join){
        //                             $join->on('rl.id','=','rc.role');
        //                     })
        //                     ->select('rc.id as rec_id', 'rc.description','rc.status','users.name','roles.role','rl.role as ap_role','rc.c_by as cr_by')
        //                     ->orderBy('rc.id','desc')
        //                     ->first();
        //                 break;

        //         default:
        //             Log::warning("Unknown notification type: " . $notification->noty_type);
        //             break;
        //     }

        //     // Convert created_at to IST
        //     $created_at_ist = $notification->created_at
        //         ? Carbon::parse($notification->created_at)->toDateTimeString()
        //         : null;

        //     // Add to response data
        //     $data[] = [
        //         'id' => $notification->id,
        //         'type_id' => $notification->type_id,
        //         'user_id' => $notification->user_id,
        //         'noty_type' => $notification->noty_type,
        //         'created_at' => $created_at_ist,
        //         'details' => $details ?? (object)[], // Ensure empty object instead of null
        //     ];
        // }

        return response()->json(['data' => $notifications]);
    }


    public function tasktimeline(Request $request)
    {
        $taskId = $request->input('id');

        if (!$taskId) {
            return response()->json([
                'message' => 'Task ID is required',
            ], 400);
        }

        $taskCheck = DB::table('tasks')
            ->where('id', $taskId)
            ->select('id', 'f_id')
            ->first();

        if (!$taskCheck) {
            return response()->json([
                'message' => 'Task not found',
            ], 404);
        }

        $queryTaskId = is_null($taskCheck->f_id) ? $taskId : $taskCheck->f_id;

        $tasks = DB::table('tasks')
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
            ->get()
            ->map(function ($task) {

                $rl = DB::table('users')->where('id', $task->assign_by)->first();

                $task->assign_by = (string) ($rl->role_id ?? null);
                return $task;
            });


        return response()->json([

            'data' => $tasks
        ]);
    }

    public function hr_list()
    {
        try {
            // Fetch the HR data from the database
            $hr = DB::table('users')->whereIn('role_id', [3, 4, 5])->where('status', 1)->select('users.id', 'users.name')->get();

            // Return the data in a successful response
            return response()->json([
                'status' => 'Success',
                'data' => $hr
            ]);
        } catch (\Exception $e) {
            // If an error occurs, return the error message in the response
            return response()->json([
                'status' => 'Error',
                'message' => $e->getMessage(),
                'error' => $e->getTraceAsString() // optional, shows the stack trace
            ], 500); // 500 is the status code for internal server error
        }
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
                'auto_tasks.task_description',
                'auto_tasks.created_at',
                'assign_user.name as task_assign',
                'creator_user.name as created_by_name'
            );

        // Apply condition based on role
        if (!in_array($user->role_id, [1, 2])) {
            $taskQuery->where('auto_tasks.assign_to', $user->id);
        }

        $task_cby = $taskQuery->get();


        return response()->json(['task' => $task_cby, 'r_id' => $user->role_id]);
    }


    public function add_auto_task()
    {

        $user = auth()->user();
        $r_id = $user->role_id;

        $roles = DB::table('roles')
            ->join('users', 'roles.id', '=', 'users.role_id')
            ->where('roles.status', 1)
            ->where('users.status', 1)
            ->select('roles.*')
            ->distinct()
            ->get();

        $cat = DB::table('categories')->where('status', 1)->get();

        return response([
            'cat' => $cat,
            'user' => $roles,
        ]);
        // return view('');
    }

    public function store_auto_task(Request $request)
    {
        $request->validate([
            'task_file' => 'nullable|file|max:5120|mimes:pdf,xlsx,xls,csv,jpg,jpeg,png,gif,doc,docx,txt'
        ]);

        $assign_to = json_decode($request->assign_to, true);

        // $assignToArray = is_array($request->assign_to) ? $request->assign_to : [$request->assign_to];
        $assignBy = auth()->user()->id;

        $taskFilePath = null;

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

        $cat_id = json_decode($request->category_id, true);

        $sub_cat = json_decode($request->sub_cat, true);


        try {
            foreach ($assign_to as $roleId) {
                // Get all active users for this role
                $usersInRole = DB::table('users')
                    ->where('role_id', $roleId)
                    ->where('status', 1)
                    ->pluck('id');

                // Log::info($usersInRole);

                foreach ($usersInRole as $assignTo) {
                    foreach ($sub_cat as $sub_id) {
                        $task = new AutoTask();
                        $task->task_title = $request->task_title;
                        $task->category_id = $cat_id;
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
        } catch (\Exception $e) {
            log::info($e->getMessage());
        }

        return response()->json(['status' => 'success', 'message' => 'Auto Task Added successfully!']);
    }
    /**
     * Show the form for editing the specified resource.
     */


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
