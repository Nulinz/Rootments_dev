<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Leave;
use App\Models\Notification;
use Illuminate\Support\Facades\Auth;
use App\Services\FirebaseService;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

use Google\Client;
use Google\Service\FirebaseCloudMessaging;
use Google\Service\FirebaseCloudMessaging\Message;
use Google\Service\FirebaseCloudMessaging\Notification as FcmNotification;
use Google\Service\FirebaseCloudMessaging\SendMessageRequest;
use App\Http\Controllers\trait\common;


class LeaveController extends Controller
{
    use common;
    /**
     * Display a listing of the resource.
     */
    public function index()
      {
        $user_id = Auth::user()->id;

        $leave = DB::table('leaves')
            ->leftjoin('users', 'leaves.user_id', '=', 'users.id')
            ->select('leaves.*', 'users.name', 'users.emp_code')
            ->where('leaves.created_by', $user_id)
            ->orderByDesc('created_at')
            ->get();

        // dd($leave);
        return view('leave.list', ['leave' => $leave]);
    }
    /**
     * Store a newly created resource in storage.
     */

    
        public function create($type = null)
    {

        return view('leave.add', ['type' => $type]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        
        // dd($request);
        
         $user = auth()->user();

        $role_get = DB::table('roles')
            ->leftJoin('users', 'users.role_id', '=', 'roles.id')
            ->select('roles.id', 'roles.role', 'roles.role_dept')
            ->where('users.id', $user->id)
            ->first();


        $r_dept = $user->dept;
        $r_id = $user->role_id;

        // Initialize the $arr variable
        $arr = [];

        // Simplify the switch statement
         switch ($r_dept) {
            case 'HR':
            case 'Operation':
            case 'Area':
            case 'Cluster':
            case 'IT':
                $arr = [3];
                break;
            case 'Finance':
                $arr = ($r_id == 7) ? [3] : [7];
                break;
            case 'Sales/Marketing':
                $arr = [3, 4, 5];
                break;
            case 'Store':
                $arr = ($r_id == 12) ? [11] : [12];
                break;
            case 'Maintenance':
            case 'Warehouse':
                $arr = [3];
                break;
            case 'Purchase':
                $arr = ($r_id == 41) ? [3] : [41];
                break;
        }

        // Query the users table based on the roles
          if (in_array(11, $arr)) {
            // If role is cluster, find cluster head for user's store
            $clusterHead = DB::table('cluster_store as cs')
                ->join('m_cluster as mc', 'mc.id', '=', 'cs.cluster_id')
                ->join('users as u', 'u.id', '=', 'mc.cl_name')
                ->where('cs.store_id', $user->store_id)
                ->where('cs.status', 1)
                ->select('u.id as approver_id')
                ->first();

            if (!$clusterHead) {
                return response()->json([
                    'success' => false,
                    'message' => 'No cluster head assigned for this store.',
                ]);
            }

            $req_to = $clusterHead->approver_id;
        } else {

            $list = DB::table('users')
                ->whereIn('role_id', $arr)
                ->where('status', 1)
                ->when($arr == [12], function ($query) use ($user) {
                    return $query->where('store_id', $user->store_id);
                })
                ->select('id')
                ->first();

            if (! $list) {
                return response()->json([
                    'success' => false,
                    'message' => 'No approver found for this department/role.',
                ]);
            }

            $req_to = $list->id;
        }

        // dd($req_to);

        $leave = new Leave();
        
         if ($request->request_type != 'Permission') {
            $leave->start_date = $request->start_date;
            $leave->end_date = $request->end_date;
        } else {
            $leave->start_date = $request->start_date;
            $leave->end_date = $request->start_date;
        }

       if ($request->request_type == 'Sick leave' && $request->hasFile('medical')) {
            $image = $request->file('medical');

            if ($image->isValid()) {
                $filename = time() . '_' . $image->getClientOriginalName();
                $image->move('assets/images/Medical', $filename);
                // $leave->medical = $filename;
            } else {
                return back()->with('error', 'Uploaded file is invalid.');
            }
        } else {
            // return back()->with('error', 'Please upload a file to close the task.');
        }
        
    
       $leave->request_type = $request->request_type;
        $leave->reason = $request->reason;
        $leave->start_time = $request->start_time;
        $leave->end_time = $request->end_time;
        $leave->user_id = $user->id;
        $leave->created_by = $user->id;
        $leave->request_to = $req_to;
        $leave->medical_cer = $filename ?? null;
        $leave->save();

    // dd($leave);
        
          $req_token = DB::table('users')->where('id', $req_to)->first();

        if (!is_null($req_token->device_token)) {
            $role_info = DB::table('roles')->where('id', $user->role_id)->first();
            $taskTitle = $request->request_type . " Request";
            $taskBody = $user->name . " [" . $role_info->role . "] requested for " . $request->request_type;

            app(FirebaseService::class)->sendNotification($req_token->device_token, $taskTitle, $taskBody);

            Notification::create([
                'user_id' => $req_to,
                'noty_type' => 'leave',
                'type_id' => $leave->id,
                'title' => $taskTitle,
                'body' => $taskBody,
                'c_by' => $user->id
            ]);
        }

        // return response()->json([
        //     'success' => true,
        //     'message' => 'Leave Request Sent successfully',
        //     'token' => $req_token->device_token ?? null
        // ]);

        return redirect()->route('leave.index')->with([
            'status' => 'success',
            'message' => 'Leave Request Added successfully!'
        ]);
    }


    public function updateEscalate(Request $request)
    {
        DB::table('leaves')
            ->where('id', $request->id)
            ->update(['esculate_to' => 3, 'updated_at' => now()]);

        return response()->json(['message' => 'Escalated successfully!']);
    }

    public function send_not(Request $req)
    {

        // try {
        //     // Setup Google Client
        //     $client = new Client();
        //     $client->setAuthConfig(storage_path('app/firebase.json')); // Replace with your service account file path
        //     $client->addScope(FirebaseCloudMessaging::CLOUD_PLATFORM);

        //     $fcm = new FirebaseCloudMessaging($client);

        //     // Create Notification
        //     $notification = new FcmNotification();
        //     $notification->setTitle('Test Notification');
        //     $notification->setBody('This is a test notification from your Laravel app.');

        //     // Create Message
        //     $message = new Message();
        //     $message->setToken($req->not_token);
        //     $message->setNotification($notification);

        //     // Create Send Message Request
        //     $sendMessageRequest = new SendMessageRequest();
        //     $sendMessageRequest->setMessage($message);

        //     // Send Message
        //    $res =  $fcm->projects_messages->send('projects/rootments-app', $sendMessageRequest); // Replace with your project ID

        //     return response()->json(['success' => true,'res'=>$res]);
        // } catch (\Exception $e) {
        //     return response()->json(['success' => false, 'error' => $e->getMessage()]);
        // }


    }

    /**
     * Display the specified resource.
     */
    // public function get_leave_emp(Request $req)
    // {

    //     $startDate = Carbon::parse($req->st_date);

    //     $st_emp = DB::table('users')->where('store_id', '!=', null)->where('store_id', auth()->user()->store_id)->pluck('id')->toArray();

    //     if (auth()->user()->store_id == null) {
    //         $leave = Leave::where('start_date', $req->st_date)->where('user_id', auth()->user()->id)->where('status', 'Approved')->count();
    //     } else {
    //         $leave = Leave::where('start_date', $req->st_date)->whereIn('user_id', $st_emp)->where('status', 'Approved')->count();

    //         $per = DB::table('stores')->where('id', auth()->user()->store_id)->first();


    //         $allow = round((($per->leave_per) / 100) * count($st_emp));

    //         if ($leave >= $allow) {
    //             $lv = true;
    //         } else {
    //             $lv = false;
    //         }
            
    //         //  dd($allow);

    //     }


    //     $week_off = Leave::where('request_type', 'Week Off')
    //         ->where('status', 'Approved')
    //         ->where('user_id', auth()->user()->id)
    //         ->whereMonth('start_date', $startDate->month)
    //         ->whereYear('start_date', $startDate->year)
    //         ->get()
    //         ->sum(function ($leave) {
    //             $start = Carbon::parse($leave->start_date);
    //             $end = Carbon::parse($leave->end_date);
    //             return $start->diffInDays($end) + 1; // Inclusive count
    //         });

    //     $adjusted_week_off = 4 - $week_off;

    //     $daysToAdd = max($adjusted_week_off - 1, 0); // Ensure not negative

    //     // ($adjusted_week_off == 4) ? ($adjusted_week_off = 3) : $adjusted_week_off;

    //     $max_date = Carbon::parse($req->st_date)->addDays($daysToAdd)->toDateString();




    //     if ($req->header('Authorization')) {

    //         return response()->json([
    //             'data' =>  $lv ?? false,
    //             'allow' => $adjusted_week_off ?? null

    //         ]);
    //     } else {
    //         return response()->json([
    //             'leave' =>  $lv ?? null,
    //             'week_off' => $adjusted_week_off,
    //             'max_date' => $max_date
    //         ]);
    //     }
    // }
    
    public function get_leave_emp(Request $req)
{
    $startDate = Carbon::parse($req->st_date);
    $st_emp = DB::table('users')->where('store_id', '!=', null)->where('store_id', auth()->user()->store_id)->pluck('id')->toArray();
    
    // Initialize variables
    $lv = false;
    $allow = null;
    
    // Store-based leave checking
    if (auth()->user()->store_id == null) {
        $leave = Leave::where('start_date', $req->st_date)->where('user_id', auth()->user()->id)->where('status', 'Approved')->count();
    } else {
        $leave = Leave::where('start_date', $req->st_date)->whereIn('user_id', $st_emp)->where('status', 'Approved')->count();
        $per = DB::table('stores')->where('id', auth()->user()->store_id)->first();
        $allow = round((($per->leave_per) / 100) * count($st_emp));
        
        if ($leave >= $allow) {
            $lv = true;
        } else {
            $lv = false;
        }
    }

    // Week off calculation
    // $week_off = Leave::where('request_type', 'Week Off')
    //     ->where('status', 'Approved')
    //     ->where('user_id', auth()->user()->id)
    //     ->whereMonth('start_date', $startDate->month)
    //     ->whereYear('start_date', $startDate->year)
    //     ->get()
    //     ->sum(function ($leave) {
    //         $start = Carbon::parse($leave->start_date);
    //         $end = Carbon::parse($leave->end_date);
    //         return $start->diffInDays($end) + 1; // Inclusive count
    //     });
    
     $week_off = Leave::where('request_type', 'Week Off')
            ->where('status', 'Approved')
            ->where('user_id', auth()->user()->id)
            ->whereMonth('start_date', $startDate->month)
            ->whereYear('start_date', $startDate->year)
            ->get()
            ->sum(function ($leave) {
                if (empty($leave->end_date)) {
                    return 1; // only start_date, count 1 day
                }

                $start = Carbon::parse($leave->start_date);
                $end   = Carbon::parse($leave->end_date);

                if ($end->lt($start)) {
                    return 1; // invalid range, fallback 1
                }

                return $start->diffInDays($end) + 1; // normal inclusive
            });

    // $adjusted_week_off = 4 - $week_off;
    // $daysToAdd = max($adjusted_week_off - 1, 0); // Ensure not negative
    // $max_date = Carbon::parse($req->st_date)->addDays($daysToAdd)->toDateString();

    
        $adjusted_week_off = max(4 - $week_off, 0);   // <-- prevent negative
        $daysToAdd = max($adjusted_week_off - 1, 0); // still capped at 0
        $max_date = Carbon::parse($req->st_date)->addDays($daysToAdd)->toDateString();


    // Annual + Sick Leave Limit Check (20 days per year)
    $user = auth()->user();
    $year = date('Y');
    
    // Calculate total approved Annual + Sick leave days taken this year
    $leaveDaysTaken = Leave::where('user_id', $user->id)
        ->whereIn('request_type', ['Annual leave', 'Sick leave'])
        ->where('status', 'Approved') // Using 'status' field like your mobile API structure
        ->whereYear('start_date', $year)
        ->get()
        ->sum(function ($leave) {
            return Carbon::parse($leave->end_date)->diffInDays(Carbon::parse($leave->start_date)) + 1;
        });

    // Check if requesting for Annual or Sick leave and calculate if it exceeds limit
    $exceeds_annual_sick_limit = false;
    $exceeds_four_day_limit = false;
    $total_annual_sick_taken = $leaveDaysTaken;
    $requested_days = 0;
    $four_day_limit_message = '';
    
    // If request includes type and dates, check the limits
    if ($req->has('request_type') && $req->has('end_date')) {
        
        $requestStartDate = Carbon::parse($req->st_date);
        $requestEndDate = Carbon::parse($req->end_date);
        $requested_days = $requestEndDate->diffInDays($requestStartDate) + 1;
        
        // Check 4-day continuous limit for specific leave types
        $limitedLeaveTypes = ['Casual Leave', 'Sick leave', 'Annual leave'];
        
        if (in_array($req->request_type, $limitedLeaveTypes)) {
            if ($requested_days > 4) {
                $exceeds_four_day_limit = true;
                $four_day_limit_message = "Continuous {$req->request_type} cannot exceed 4 days. Current selection: {$requested_days} days.";
            }
        }
        
        // Check 20-day annual + sick leave limit
        if (in_array($req->request_type, ['Annual leave', 'Sick leave'])) {
            $totalAfterRequest = $leaveDaysTaken + $requested_days;
            
            if ($totalAfterRequest > 20) {
                $exceeds_annual_sick_limit = true;
            }
        }
    }

    // Calculate remaining days
    $remaining_days = 20 - $total_annual_sick_taken;
    
    // Response based on Authorization header
    if ($req->header('Authorization')) {
        return response()->json([
            'data' => $lv ?? false,
            'allow' => $adjusted_week_off ?? null,
            'annual_sick_limit' => [
                'total_taken' => $total_annual_sick_taken,
                'remaining_days' => $remaining_days,
                'exceeds_limit' => $exceeds_annual_sick_limit,
                'exceeds_four_day_limit' => $exceeds_four_day_limit,
                'limit' => 20
            ]
        ]);
    } else {
        return response()->json([
            'leave' => $lv ?? null,
            'week_off' => $adjusted_week_off,
            'max_date' => $max_date,
            'annual_sick_limit' => [
                'total_taken' => $total_annual_sick_taken,
                'remaining_days' => $remaining_days,
                'exceeds_limit' => $exceeds_annual_sick_limit,
                'exceeds_four_day_limit' => $exceeds_four_day_limit,
                'limit' => 20
            ]
        ]);
    }
}

    /**
     * Show the form for editing the specified resource.
     */
     
     public function checkLeaveLimit(Request $request)
{
    $user = auth()->user();
    $year = date('Y');
    $type = $request->type;
    $start = $request->start_date;
    $end = $request->end_date;

    if (!$start || !$end || !$type) {
        return response()->json(['error' => 'Invalid input'], 422);
    }

    $startDate = Carbon::parse($start);
    $endDate = Carbon::parse($end);
    $requestedDays = $endDate->diffInDays($startDate) + 1;

    // Only count approved leaves of type 'Annual Leave' or 'Sick Leave'
    $leaveDaysTaken = Leave::where('user_id', $user->id)
        ->whereIn('request_type', ['Annual leave', 'Sick leave'])
        ->where('request_status', 'Approved') // ✅ Only approved requests
        ->whereYear('start_date', $year)
        ->get()
        ->sum(function ($leave) {
            return Carbon::parse($leave->end_date)->diffInDays(Carbon::parse($leave->start_date)) + 1;
        });

    $totalAfterRequest = $leaveDaysTaken + $requestedDays;

    return response()->json([
        'total_taken' => $leaveDaysTaken,
        'requested_days' => $requestedDays,
        'total_after_request' => $totalAfterRequest,
        'exceeds_limit' => $totalAfterRequest > 20
    ]);
}


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
