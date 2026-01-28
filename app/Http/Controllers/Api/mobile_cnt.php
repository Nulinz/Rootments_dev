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


class mobile_cnt extends Controller
{
    use common;


    public function  create_task_show()
    {

        $user = auth()->user()->role_id;

        $arr = hasAccess($user, 'mob_task');

        return response()->json(["data" => $arr], 200);
    }

    public function assign_to()
    {

        $user = auth()->user();
        $r_id = $user->role_id;

        $cluster_check = DB::table('m_cluster as mc')
            ->leftJoin('users', 'users.id', '=', 'mc.cl_name')
            ->where('mc.cl_name', '=', $user->id)
            ->where('users.role_id', 12)
            ->count();




        $arr = $this->role_arr();



        $list =  DB::table('users')
            ->leftJoin('roles', 'roles.id', '=', 'users.role_id')
            ->select('users.name', 'roles.role', 'roles.role_dept', 'users.id', 'users.store_id');


        if (($r_id >= 12 && $r_id <= 19)) {

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

        return response()->json(["data" => $list], 200);
    } // function end

    public function attd_row()
    {

        $user_check = Auth::user()->id;

        $attd = DB::table('attendance')->where('user_id', $user_check)->whereDate('c_on', date('Y-m-d'))->count();

        if ($attd == 0) {
            $val = 'attd_in';
        } else {
            $attd_ch = DB::table('attendance')->where('user_id', $user_check)->whereDate('c_on', date('Y-m-d'))->orderBy('id', 'desc')->first();
            if (is_null($attd_ch->out_time)) {
                $val = 'attd_out';
            } else {
                $val = 'attd_mark';
            }
        }

        return response()->json([
            'status' => 'Success',
            'data' => [$attd_ch->in_time ?? null, $attd_ch->out_time ?? null, $val],
        ]);
    }

    public function attd_in(Request $req)
    {

        //  $user_check = $req->id;
        $user_check = Auth::user()->id;


        //   11.6754571,78.1320422

        $input = explode(',', $req->loc);

        // Get latitude and longitude from the request
        $latitude =  $input[0];
        $longitude = $input[1];

        // Google API Key
        $googleApiKey = env('GOOGLE_MAPS_API_KEY');

        // Make the request to the Google Geocoding API
        $response = Http::get("https://maps.googleapis.com/maps/api/geocode/json", [
            'latlng' => "{$latitude},{$longitude}",
            'key' => $googleApiKey
        ]);

        // // Decode the response
        $location = $response->json();


        // Check if the response was successful
        if ($location['status'] === 'OK') {
            $district = null;

            $result = $location['results'][0];
            $formattedAddress = $result['formatted_address'];

            foreach ($location['results'][0]['address_components'] as $component) {
                // Look for the district (usually administrative_area_level_2) or locality (city)
                if (in_array('administrative_area_level_2', $component['types'])) {
                    $district = $component['long_name'];  // District
                    break;
                }

                // If no district is found, you can try to use locality as a fallback
                if (in_array('locality', $component['types'])) {
                    $district = $component['long_name'];  // Locality (City or Town)
                    break;
                }
            } // end foreach
        }

        $inserted  = DB::table('attendance')->insertGetId([
            'user_id' => $user_check,
            'attend_status' => 'Present',
            'in_location' => $district ?? 0,
            'in_add' => $formattedAddress ?? 0,
            'in_time' => now()->format('H:i:s'),
            'c_on' => now()->format('Y-m-d'),
            'status' => 'Active'
        ]);



        $c_time = Carbon::now(); // Get the current time using Carbon

        $start_time = Carbon::parse(!is_null(Auth::user()->store_id) ? Auth::user()->store_rel->store_start_time : Auth::user()->st_time);

        // Calculate the 5-minute range (+5 and -5 minutes)
        $start_time_plus_5 = $start_time->copy()->addMinutes(10);
        $start_time_minus_5 = $start_time->copy()->subMinutes(10);

        if (!($c_time >= $start_time_minus_5 && $c_time <= $start_time_plus_5)) {

            $late = $start_time->diff($c_time)->format('%H:%I');

            if ($c_time >= $start_time_plus_5) {

                DB::table('attd_ot')->insert([
                    'attd_id' => $inserted,
                    'cat' => 'late',
                    'time' => $late,
                    'status' => 'pending',
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
            }
        }



        if ($inserted) {
            return response()->json(['status' => 'Success',], 200);
        } else {
            return response()->json(['status' => 'Failure'], 500);
        }
    }

    public function attd_out(Request $req)
    {

        $user_check = Auth::user()->id;

        $attd = DB::table('attendance')->where('user_id', $user_check)->whereDate('c_on', date('Y-m-d'))->orderBy('id', 'desc')->first();



        $input = explode(',', $req->loc);

        // Get latitude and longitude from the request
        $latitude =  $input[0];
        $longitude = $input[1];

        // Google API Key
        $googleApiKey = env('GOOGLE_MAPS_API_KEY');

        // Make the request to the Google Geocoding API
        $response = Http::get("https://maps.googleapis.com/maps/api/geocode/json", [
            'latlng' => "{$latitude},{$longitude}",
            'key' => $googleApiKey
        ]);

        // // Decode the response
        $location = $response->json();


        // Check if the response was successful
        if ($location['status'] === 'OK') {
            $district = null;

            $result = $location['results'][0];
            $formattedAddress = $result['formatted_address'];

            foreach ($location['results'][0]['address_components'] as $component) {
                // Look for the district (usually administrative_area_level_2) or locality (city)
                if (in_array('administrative_area_level_2', $component['types'])) {
                    $district = $component['long_name'];  // District
                    break;
                }

                // If no district is found, you can try to use locality as a fallback
                if (in_array('locality', $component['types'])) {
                    $district = $component['long_name'];  // Locality (City or Town)
                    break;
                }
            }
        }



        $check_out =  DB::table('attendance')
            ->where('id', $attd->id)
            ->update([
                'out_time' => now()->format('H:i:s'),
                'out_location' => $district,
                'out_add' => $formattedAddress,
                'u_by' => now()->format('Y-m-d')
            ]);


        //  if(!is_null(Auth::user()->store_id)){

        //     $st_time = DB::table('stores')->where('id',Auth::user()->store_id)->select('stores.store_start_time','stores.store_end_time')->first();

        //     // dd($st_time);

        //   }

        $c_time = Carbon::now(); // Get the current time using Carbon

        $end_time = Carbon::parse(!is_null(Auth::user()->store_id) ? Auth::user()->store_rel->store_end_time : Auth::user()->end_time);


        //  if(!is_null(Auth::user()->store_id)){
        //     $end_time = $st_time->store_end_time;
        //  }else{
        //     $end_time = '18:00:00';
        //  }

        if ($c_time > $end_time) {
            // Define the two times
            $time1 = Carbon::createFromFormat('H:i:s', $end_time);
            $time2 = Carbon::createFromFormat('H:i:s', $c_time);

            // Calculate the difference
            $ot = $time1->diff($time2)->format('%H:%I');


            // // Calculate the difference
            // $diff = $time1->diff($time2);

            // $ot = $diff->format('%H:%I');

            DB::table('attd_ot')->insert([
                'attd_id' => $attd->id,
                'cat' => 'ot',
                'time' => $ot,
                'status' => 'pending',
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }


        if ($check_out) {
            return response()->json(['status' => 'Success'], 200);
        } else {
            return response()->json(['status' => 'Failure'], 500);
        }
    }


    // public function leavestore(Request $request)
    // {
    //     $user = auth()->user();

    //     $role_get = DB::table('roles')
    //         ->leftJoin('users', 'users.role_id', '=', 'roles.id')
    //         ->select('roles.id', 'roles.role', 'roles.role_dept')
    //         ->where('users.id', $user->id)
    //         ->first();

    //     $r_dept = $user->dept;
    //     $r_id = $user->role_id;

    //     $arr = [];

    //     switch ($r_dept) {
    //         case 'HR':
    //         case 'Operation':
    //         case 'Area':
    //         case 'Cluster':
    //         case 'IT':
    //             $arr = [3];
    //             break;
    //         case 'Finance':
    //             $arr = ($r_id == 7) ? [3] : [7];
    //             break;
    //         case 'Sales/Marketing':
    //             $arr = [3, 4, 5];
    //             break;
    //         case 'Store':
    //             $arr = ($r_id == 12) ? [3] : [12];
    //             break;
    //         case 'Maintenance':
    //             $arr = ($r_id == 30) ? [3] : [30];
    //             break;
    //         case 'Warehouse':
    //             $arr = ($r_id == 37) ? [3] : [37];
    //             break;
    //         case 'Purchase':
    //             $arr = ($r_id == 41) ? [3] : [41];
    //             break;
    //     }

    //     $list = DB::table('users')
    //         ->whereIn('role_id', $arr)
    //         ->where('status', 1)
    //         ->when($arr == [12], function ($query) use ($user) {
    //             return $query->where('store_id', $user->store_id);
    //         })
    //         ->select('id')
    //         ->first();

    //     if (!$list) {
    //         return response()->json([
    //             'success' => false,
    //             'message' => 'No approver found for this department/role.'
    //         ]);
    //     }

    //     $req_to = $list->id;

    //     $leave = new Leave();

    //     if ($request->request_type != 'Permission') {
    //         $leave->start_date = $request->start_date;
    //         $leave->end_date = $request->end_date;
    //     } else {
    //         $leave->start_date = $request->start_date;
    //         $leave->end_date = $request->start_date;
    //     }

    //     $leave->request_type = $request->request_type;
    //     $leave->reason = $request->reason;
    //     $leave->start_time = $request->start_time;
    //     $leave->end_time = $request->end_time;
    //     $leave->user_id = $user->id;
    //     $leave->created_by = $user->id;
    //     $leave->request_to = $req_to;
    //     $leave->save();

    //     $req_token = DB::table('users')->where('id', $req_to)->first();

    //     if (!is_null($req_token->device_token)) {
    //         $role_info = DB::table('roles')->where('id', $user->role_id)->first();
    //         $taskTitle = $request->request_type . " Request";
    //         $taskBody = $user->name . " [" . $role_info->role . "] requested for " . $request->request_type;

    //         app(FirebaseService::class)->sendNotification($req_token->device_token, $taskTitle, $taskBody);

    //         Notification::create([
    //             'user_id' => $req_to,
    //             'noty_type' => 'leave',
    //             'type_id' => $leave->id,
    //             'title' => $taskTitle,
    //             'body' => $taskBody,
    //             'c_by' => $user->id
    //         ]);
    //     }

    //     return response()->json([
    //         'success' => true,
    //         'message' => 'Leave Request Sent successfully',
    //         'token' => $req_token->device_token ?? null
    //     ]);
    // }

    public function leavestore(Request $request)
    {

        // log::info('all files', ['request_data' => $request->allFiles()]);
        $user = auth()->user();

        $role_get = DB::table('roles')
            ->leftJoin('users', 'users.role_id', '=', 'roles.id')
            ->select('roles.id', 'roles.role', 'roles.role_dept')
            ->where('users.id', $user->id)
            ->first();

        $r_dept = $user->dept;
        $r_id = $user->role_id;

        $arr = [];

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

        $leave = new Leave;

        if ($request->request_type != 'Permission') {
            $leave->start_date = $request->start_date;
            $leave->end_date = $request->end_date;
        } else {
            $leave->start_date = $request->start_date;
            $leave->end_date = $request->start_date;
        }

        if ($request->request_type == 'Sick Leave' && $request->hasFile('task_file')) {
            $image = $request->file('task_file');

            // Log::info('Medical file upload initiated', ['file' => $image, 'file_name' => $image->getClientOriginalName()]);

            if ($image->isValid()) {
                $filename = time() . '_' . $image->getClientOriginalName();
                $image->move('assets/images/Medical', $filename);
                // $leave->medical = $filename;
            } else {
                return back()->with('error', 'Uploaded file is invalid.');
            }
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

        $req_token = DB::table('users')->where('id', $req_to)->first();

        if (!is_null($req_token->device_token)) {
            $role_info = DB::table('roles')->where('id', $user->role_id)->first();
            $taskTitle = $request->request_type . ' Request';
            $taskBody = $user->name . ' [' . $role_info->role . '] requested for ' . $request->request_type;

            app(FirebaseService::class)->sendNotification($req_token->device_token, $taskTitle, $taskBody);

            Notification::create([
                'user_id' => $req_to,
                'noty_type' => 'leave',
                'type_id' => $leave->id,
                'title' => $taskTitle,
                'body' => $taskBody,
                'c_by' => $user->id,
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Leave Request Sent successfully',
            // 'token' => $req_token->device_token ?? null,
        ]);
    }

    public function res_store(Request $request)
    {
        $user_id = auth()->user();

        //   $role_get = DB::table('roles')
        //     ->leftJoin('users', 'users.role_id', '=', 'roles.id')
        //     ->select('roles.id', 'roles.role', 'roles.role_dept')
        //     ->where('users.id', $user_id->id)
        //     ->first();

        // if ($role_get) {

        $ApproverId = 2;

        $resgination = new Resignation;
        $resgination->emp_id = $request->emp_id;
        $resgination->emp_name = $request->emp_name;
        $resgination->store_id = $request->store_id;
        $resgination->res_date = $request->res_date;
        $resgination->res_reason = $request->res_reason;
        $resgination->created_by = $user_id->id;
        $resgination->request_to = $ApproverId;
        $res_save = $resgination->save();

        $approver  = DB::table('users')->where('id', $ApproverId)->first();

        if ($res_save && $approver && !empty($approver->device_token)) {


            $role_get = DB::table('roles')->where('id', $user_id->role_id)->first();

            $taskTitle = "Resignation Request";

            $taskBody = $user_id->name . "[" . $user_id->role_rel->role . "] Requested for Resignation";

            $response = app(FirebaseService::class)->sendNotification($req_token->device_token, $taskTitle, $taskBody);

            Notification::create([
                'user_id' => $req_token->id,
                'noty_type' => 'resignation',
                'type_id' => $resgination->id,
                'title' => $taskTitle,
                'body' => $taskBody,
                'c_by' => auth()->user()->id
            ]);

            // dd($response);
        } // notification end

        // }

        return response()->json([
            'status' => $res_save ? 'success' : 'failed',
            'message' => $res_save ? 'Resgination Request Added successfully!' : 'Resgination Request Failed to Add!'
        ]);
    }


    public function resign_show()
    {
        $user_id = Auth::user()->id;

        $resgination = DB::table('resignations')
            ->where('resignations.created_by', $user_id)
            ->leftjoin('users', 'users.id', '=', 'resignations.emp_id')
            // ->leftJoin('resignations as rs','rs.emp_id','=', 'users.id')
            ->select('resignations.*', 'users.emp_code')

            ->get();



        return response()->json([

            'data' => [
                'resgination' => $resgination
            ]

        ]);
    }

    public function leave_req(Request $req)
    {
        // try {
        // Fetch the HR data from the database
        //  $show = $this->role_dept();

        //  $user = Auth::user();
        // Fetch user from database
        // $user = DB::table('users')->where('id', $req->user_id)->first();

        $user = DB::table('users')->where('id', $req->user_id)->first();


        // Check if the user exists before continuing
        if (!$user) {
            return response()->json([
                'status' => 'Failed',
                'message' => 'User not found',
            ], 404);
        }

        $r_dept = $user->dept;
        $r_id = $user->role_id;

        // Initialize the $arr variable
        $arr = [];

        // Simplify the switch statement
        switch ($r_dept) {
            case 'HR':
                $arr = [1, 2];
                break;
            case 'Operation':
                $arr = [3, 4, 5];
                break;
            case 'Finance':
                $arr = ($r_id == 7) ? [3, 4, 5] : [7];
                break;
            case 'IT':
                $arr = [3, 4, 5];
                break;
            case 'Sales/Marketing':
                $arr = [3, 4, 5];
                break;
            case 'Area':
                $arr = [3, 4, 5];
                break;
            case 'Cluster':
                $arr = [3, 4, 5];
                break;
            case 'Store':
                $arr = ($r_id == 12) ? [3, 4, 5] : [12];
                break;
            case 'Maintenance':
                $arr = ($r_id == 30) ? [3, 4, 5] : [30];
                break;
            case 'Warehouse':
                $arr = ($r_id == 37) ? [3, 4, 5] : [37];
                break;
            case 'Purchase':
                $arr = ($r_id == 41) ? [3, 4, 5] : [41];
                break;
            case 'IT':
                $arr = [3];
                break;
            default:
                // If no department matched, return an error
                return response()->json([
                    'status' => 'Failed',
                    'message' => 'Invalid department',
                ], 400);
        }

        // Query the users table based on the roles
        $list = DB::table('users')
            ->where('status', 1)
            ->whereIn('role_id', $arr)
            ->when($arr == [12], function ($query) use ($user) {
                return $query->where('store_id', $user->store_id);
            })
            ->select('users.id', 'users.name')
            ->get();

        // Return the data in a successful response
        if ($list->isNotEmpty()) {
            return response()->json([
                'status' => 'Success',
                'data' => $list
            ], 200);
        } else {
            return response()->json([
                'status' => 'Failed',
                'data' => null
            ], 500);
        }
    }

    // store the walkin

    // public function walkin_store(Request $req)
    // {
    //     $store_id =  auth()->user()->store_id;

    //     if (auth()->user()->role_id == 12) {

    //         $man = auth()->user()->id;
    //     } else {
    //         $man = null;
    //     }

    //     $ins =  DB::table('walkin')->insert([
    //         "store_id" => $store_id,
    //         "name" => $req->name,
    //         "contact" => $req->contact,
    //         "f_date" => $req->f_date,
    //         "walk_status" => $req->walk_status ?? null,
    //         "cat" => $req->cat ?? null,
    //         "sub" => $req->sub ?? null,
    //         "remark" => $req->remark ?? null,
    //         "manager" => $man ?? null,
    //         "c_by" => $req->cby,
    //         "created_at" => now(),
    //         "updated_at" => now()

    //     ]);

    //     return response()->json([
    //         'status' => $ins ? 'success' : 'failed',
    //         'message' => $ins ? 'Walkin  Added successfully!' : 'Walkin Request Failed to Add!'
    //     ]);
    // }

    public function walkin_store(Request $req)
    {
        $store_id = auth()->user()->store_id;

        if (auth()->user()->role_id == 12) {
            $man = auth()->user()->id;
        } else {
            $man = null;
        }

        // Get category and subcategory names instead of IDs (same as web code)
        $category_name = null;
        $subcategory_name = null;

        if ($req->cat) {
            $category = DB::table('walkin_cat')
                ->where('id', $req->cat)
                ->first();
            $category_name = $category ? $category->cat : null;
        }

        if ($req->sub) {
            $subcategory = DB::table('walkin_cat')
                ->where('id', $req->sub)
                ->first();
            $subcategory_name = $subcategory ? $subcategory->sub : null;
        }

        // Calculate repeat count for today (same as web code)
        $contact = preg_replace('/\D/', '', $req->contact);
        $contact = substr($contact, -10); // last 10 digits only

        $c_time = Carbon::today()->toDateString();
        $repeat_count = DB::table('walkin')
            ->where('contact', $contact)
            ->where('store_id', $store_id)
            ->whereDate('created_at', $c_time)
            ->count();

        // Only store repeat_count if it's greater than 0 (meaning there are previous records today)
        $repeat_count_to_store = $repeat_count > 0 ? ($repeat_count + 1) : null;

        $ins = DB::table('walkin')->insert([
            "store_id" => $store_id,
            "name" => $req->name,
            "contact" => $req->contact,
            "f_date" => $req->f_date,
            "walk_status" => $req->walk_status ?? null,
            "cat" => $category_name, // Store category name instead of ID
            "sub" => $subcategory_name, // Store subcategory name instead of ID
            "remark" => $req->remark ?? null,
            "manager" => $man ?? null,
            "c_by" => $req->cby,
            "repeat_count" => $repeat_count_to_store, // Add repeat count logic
            "created_at" => now(),
            "updated_at" => now()
        ]);

        return response()->json([
            'status' => $ins ? 'success' : 'failed',
            'message' => $ins ? 'Walkin Added successfully!' : 'Walkin Request Failed to Add!'
        ]);
    }


    public function walkin_list(Request $req)
    {

        if (auth()->user()->role_id == 12) {

            $list =  DB::table('walkin')->join('users', 'walkin.c_by', '=', 'users.id')
                ->where(function ($query) {
                    $query->where('walkin.c_by', auth()->user()->id)
                        ->orWhere('walkin.manager', auth()->user()->id);
                })
                ->select('walkin.*', 'users.name as created_by_name')
                ->get();
        } else {
            $list =  DB::table('walkin')->where('c_by', auth()->user()->id)->get();
        }



        $st_id = auth()->user()->store_id;

        //  $cby_list =  DB::table('users')->where('store_id',$st_id)->get();



        return response()->json([
            'status' => $list ? 'success' : 'failed',
            'data' => $list,
            //  'cby'=>$cby_list
        ]);
    }

    // walkin cby..////////

    public function walkin_cby(Request $req)
    {


        $st_id = auth()->user()->store_id;

        $cby_list =  DB::table('users')->where('store_id', $st_id)->get();



        return response()->json([
            'status' => $cby_list ? 'success' : 'failed',
            'data' => $cby_list,

        ]);
    }

    public function walkin_update(Request $req)
    {

        $up =  DB::table('walkin')->where('id', $req->walk_id)->update([
            "walk_status" => $req->walk_status,
            "cat" => $req->cat ?? null,
            "sub" => $req->sub ?? null,
            "remark" => $req->remark ?? null,
            "updated_at" => now()

        ]);

        return response()->json([
            'status' => $up ? 'success' : 'failed',
            'message' => $up ? 'Walkin  Updated successfully!' : 'Walkin  Failed to Updated!'
        ]);
    }

    // task list created by for gm and agm

    public function task_cby(Request $req)
    {

        $list =  Task::with(['cat:id,category', 'sub:id,subcategory', 'assign:id,name,role_id', 'c_by:id,name,role_id'])->where('assign_by', auth()->user()->id)->get()
            ->map(function ($item) {

                $item['category'] = $item->cat->category ?? null;
                $item['subcategory'] = $item->sub->subcategory ?? null;
                // $item['assigned_role']=>$item->category->category ?? null;
                // $item['task_assigned']=>$item->category->category ?? null;
                $item['assigned_by'] = $item->c_by->name ?? null;
                $item['assigned_by_role'] = $item->c_by->role_rel->role ?? null;
                $item['assigned_to'] = $item->assign->name ?? null;
                $item['assigned_to_role'] = $item->assign->role_rel->role ?? null;
                $item['task_file'] = $item->task_file ? url($item->task_file) : null;

                $item->makeHidden(['cat', 'sub', 'assign', 'c_by', 'category_id', 'subcategory_id', 'assign_to', 'assign_by']);

                return $item;
            });

        // dd($list);

        return response()->json(['data' => $list]);
    }

    // week off status

    public function leave_weekoff()
    {

        $today = Carbon::today();

        $week_off = Leave::where('request_type', 'Week Off')
            ->where('status', 'Approved')
            ->whereMonth('created_at', $today->month)
            ->whereYear('created_at', $today->year)
            ->where('user_id', auth()->user()->id)
            ->get()
            ->sum(function ($leave) {
                $start = Carbon::parse($leave->start_date);
                $end = Carbon::parse($leave->end_date);
                return $start->diffInDays($end) + 1; // inclusive of start and end dates
            });

        return response()->json(['data' => $week_off]);
    }
    // walk in cat and sub  status

    // public function walkin_cat(Request $req)
    // {

    //     $type = $req->type;

    //     // $type ='category';

    //     $brand = auth()->user()->store_rel->brand;

    //     if (($type == 'Booked') or ($type == 'Booking & Rentout')) {

    //         $list = DB::table('walkin_cat')->where('brand', $brand)->select('cat', 'sub')->get();
    //     } else {
    //         $list = DB::table('walkin_cat')->where('brand', 'LOSS')->select('cat', 'sub')->get();
    //     }


    //     return response()->json(['data' => $list]);
    // }

    public function walkin_cat(Request $req)
    {
        $type = $req->type;
        // $type ='category';
        $brand = auth()->user()->store_rel->brand;

        if (($type == 'Booked') or ($type == 'Booking & Rentout') or ($type == 'New Booking')) {
            $list = DB::table('walkin_cat')->where('brand', $brand)->select('cat', 'sub')->get();
        } else {
            $list = DB::table('walkin_cat')->where('brand', 'LOSS')->select('cat', 'sub')->get();
        }

        return response()->json(['data' => $list]);
    }

    // check the walkin contact

    // public function walkin_contact(Request $req)
    // {


    //     $store_id = auth()->user()->store_id;

    //     $record = DB::table('walkin')
    //         ->where('contact', $req->contact)
    //         ->where('store_id', $store_id)
    //         ->first(); // Gets a single record or null

    //     $counts = $record ? 1 : 0;

    //     if ($counts == 1) {

    //         $new_rec =  DB::table('walkin')
    //             ->where('contact', $req->contact)
    //             ->where('store_id', $store_id)
    //             ->orderBy('id', 'DESC')
    //             ->first();

    //         $counts =  ($new_rec->walk_status == 'Loss') ? 0 : 1;

    //         // $counts = ($new_rec > 0) ? 0 : 1;
    //     }

    //     $c_time = Carbon::today()->toDateString();

    //     $repeat_count =  DB::table('walkin')
    //         ->where('contact', $req->contact)
    //         ->where('store_id', $store_id)
    //         ->whereDate('created_at', $c_time)
    //         ->count();

    //     return response()->json([
    //         'data' => [
    //             'count' => $counts,
    //             'name' => $record->name ?? null,
    //             'repeat_count' => $repeat_count,
    //             // 'loss_counts' => $loss_counts
    //         ]
    //     ]);
    // }


    public function walkin_contact(Request $req)
    {
        $store_id = auth()->user()->store_id;

        // Normalize contact number (last 10 digits only)
        $contact = preg_replace('/\D/', '', $req->contact);
        $contact = substr($contact, -10);

        $record = DB::table('walkin')
            ->where('contact', $contact)
            ->where('store_id', $store_id)
            ->first();

        $exists = $record ? 1 : 0;

        // Always fetch latest record (even if Loss)
        $latest = DB::table('walkin')
            ->where('contact', $contact)
            ->where('store_id', $store_id)
            ->orderByDesc('id')
            ->first();

        // Check if latest status contains 'Loss' (case insensitive)
        // This will match: Loss, RevisitLoss, or any status containing 'loss'
        $is_loss = false;
        if ($latest && $latest->walk_status) {
            $status_lower = strtolower($latest->walk_status);
            $is_loss = (strpos($status_lower, 'loss') !== false);
        }

        // Check repeat count for today
        $c_time = Carbon::today()->toDateString();
        $repeat_count = DB::table('walkin')
            ->where('contact', $contact)
            ->where('store_id', $store_id)
            ->whereDate('created_at', $c_time)
            ->count();

        return response()->json([
            'data' => [
                'exists' => $exists,
                'name' => $record->name ?? null,
                'repeat_count' => $repeat_count,
                'is_loss' => $is_loss
            ]
        ]);
    }

    // NEW: Get unique categories based on store brand
    public function get_categories()
    {
        try {
            $user = auth()->user();
            $store_id = $user->store_id;

            // Get store brand
            $store = DB::table('stores')
                ->where('id', $store_id)
                ->first();

            if (!$store) {
                return response()->json(['categories' => []]);
            }

            // Get unique categories based on brand (no duplicates)
            $categories = DB::table('walkin_cat')
                ->where('brand', $store->brand)
                ->whereNotNull('cat')
                ->where('cat', '!=', '')
                ->select(DB::raw('MIN(id) as id'), 'cat')
                ->groupBy('cat')
                ->orderBy('cat')
                ->get();

            return response()->json(['categories' => $categories]);
        } catch (\Exception $e) {
            return response()->json(['categories' => [], 'error' => $e->getMessage()]);
        }
    }

    // NEW: Get subcategories based on category and store brand
    public function get_subcategories(Request $request)
    {
        try {
            $user = auth()->user();
            $store_id = $user->store_id;
            $category_id = $request->category_id;

            // Get store brand
            $store = DB::table('stores')
                ->where('id', $store_id)
                ->first();

            if (!$store || !$category_id) {
                return response()->json(['subcategories' => []]);
            }

            // Get the category name
            $category = DB::table('walkin_cat')
                ->where('id', $category_id)
                ->first();

            if (!$category) {
                return response()->json(['subcategories' => []]);
            }

            // Get unique subcategories based on brand and category (no duplicates)
            $subcategories = DB::table('walkin_cat')
                ->where('brand', $store->brand)
                ->where('cat', $category->cat)
                ->whereNotNull('sub')
                ->where('sub', '!=', '')
                ->select(DB::raw('MIN(id) as id'), 'sub')
                ->groupBy('sub')
                ->orderBy('sub')
                ->get();

            return response()->json(['subcategories' => $subcategories]);
        } catch (\Exception $e) {
            return response()->json(['subcategories' => [], 'error' => $e->getMessage()]);
        }
    }


    public function not_completed_list(Request $request)
    {

        $list = Task::with(['cat:id,category', 'sub:id,subcategory', 'assign:id,name,role_id', 'c_by:id,name,role_id'])
            ->where('assign_by', auth()->user()->id)
            ->whereNotIn('task_status', ['Close', 'Assigned'])
            ->whereRaw('DATE(end_date) < ?', [now()])
            ->orderBy('id', 'DESC')
            ->get()
            ->map(function ($item) {

                $item['category'] = $item->cat->category ?? null;
                $item['subcategory'] = $item->sub->subcategory ?? null;
                $item['assigned_by'] = $item->c_by->name ?? null;
                $item['assigned_by_role'] = $item->c_by->role_rel->role ?? null;
                $item['assigned_to'] = $item->assign->name ?? null;
                $item['assigned_to_role'] = $item->assign->role_rel->role ?? null;
                $item['task_file'] = $item->task_file ? url($item->task_file) : null;

                $item->makeHidden(['cat', 'sub', 'assign', 'c_by', 'category_id', 'subcategory_id', 'assign_to', 'assign_by']);

                return $item;
            });

        return response()->json(['data' => $list]);
    }

    public function completed_list(Request $request)
    {

        $list = Task::with(['cat:id,category', 'sub:id,subcategory', 'assign:id,name,role_id', 'c_by:id,name,role_id'])
            ->where('assign_by', auth()->user()->id)
            ->whereIn('task_status', ['Close', 'Assigned'])
            ->whereRaw('DATE_ADD(end_date, INTERVAL 15 DAY) >= ?', [now()])
            ->orderBy('id', 'DESC')
            ->get()
            ->map(function ($item) {

                $item['category'] = $item->cat->category ?? null;
                $item['subcategory'] = $item->sub->subcategory ?? null;
                $item['assigned_by'] = $item->c_by->name ?? null;
                $item['assigned_by_role'] = $item->c_by->role_rel->role ?? null;
                $item['assigned_to'] = $item->assign->name ?? null;
                $item['assigned_to_role'] = $item->assign->role_rel->role ?? null;
                $item['task_file'] = $item->task_file ? url($item->task_file) : null;

                $item->makeHidden(['cat', 'sub', 'assign', 'c_by', 'category_id', 'subcategory_id', 'assign_to', 'assign_by']);

                return $item;
            });

        return response()->json(['data' => $list]);
    }

    public function task_extend(Request $req)
    {

        $user = Auth::user();
        $task = Task::findOrFail($req->task_pop);

        $task1 = Task::findOrFail($task->f_id);

        $task_ext = DB::table('task_ext')->insert([
            'task_id'     => $req->task_pop,
            'request_for' => $task1->assign_by ?? null,
            'extend_date' => $req->task_end,
            'status'      => 'Pending',
            'c_remarks'   => $req->remarks,
            'category' => $req->category,
            'c_by'        => $user->id,
            'created_at'  => now(),
            'updated_at'  => now(),
        ]);


        if ($task_ext) {
            $user = User::findOrFail($task1->assign_by);

            if ($user->device_token) {

                $role_get = DB::table('roles')->where('id', auth()->user()->role_id)->first();

                $taskTitle = "Task Extend Requested";
                $taskBody = "You have a Extend Request: " . $task->task_title . " by " . auth()->user()->name . "[" . $role_get->role . "]";


                $response = app(FirebaseService::class)->sendNotification(
                    $user->device_token,
                    $taskTitle,
                    $taskBody
                );

                Notification::create([
                    'user_id' => $task1->assign_by,
                    'noty_type' => 'task',
                    'type_id' => $task->id,
                    'title' => $taskTitle,
                    'body' => $taskBody,
                    'c_by' => auth()->user()->id
                ]);
            }
        }

        // $task = Task::findOrFail($req->task_pop);

        // $task->update(['end_date' => $req->task_end]);

        return response()->json(['status' => 'success', 'data' => 'Task end date updated successfully.']);
    }


    public function task_close(Request $req)
    {

        // dd($req->all());
        // $user = Auth::user();
        $user = Auth::user();
        $task = Task::findOrFail($req->task_pop);

        $task1 = Task::findOrFail($task->f_id);

        // Handle file upload
        if ($req->hasFile('task_file')) {
            $image = $req->file('task_file');

            if ($image->isValid()) {
                $filename = time() . '_' . $image->getClientOriginalName();
                $image->move('assets/images/Task', $filename);
            } else {
                return back()->with('error', 'Uploaded file is invalid.');
            }
        } else {
            return back()->with('error', 'Please upload a file to close the task.');
        }

        // dd($filename);

        // Insert close request
        $task_close = DB::table('task_ext')->insert([
            'task_id'     => $req->task_pop,
            'request_for' => $task1->assign_by ?? null,
            'attach'      => $filename,
            'status'      => 'Close Request',
            'c_remarks'   => $req->remarks,
            'category' => $req->category,
            'c_by'        => $user->id,
            'created_at'  => now(),
            'updated_at'  => now(),
        ]);

        if ($task_close) {
            $user = User::findOrFail($task1->assign_by);

            if ($user->device_token) {

                $role_get = DB::table('roles')->where('id', auth()->user()->role_id)->first();

                $taskTitle = "Task Close Requested";
                $taskBody = "You have a Close Request: " . $task->task_title . " by " . auth()->user()->name . "[" . $role_get->role . "]";


                $response = app(FirebaseService::class)->sendNotification(
                    $user->device_token,
                    $taskTitle,
                    $taskBody
                );

                Notification::create([
                    'user_id' => $task1->assign_by,
                    'noty_type' => 'task',
                    'type_id' => $task->id,
                    'title' => $taskTitle,
                    'body' => $taskBody,
                    'c_by' => auth()->user()->id
                ]);
            }

            return response()->json(['status' => 'success', 'data' => 'Task close request successfull.']);
        }
    }

    public function emp_dahboard()
    {
        if (auth()->user()->role_id == 12) {

            $emp_sale_record = DB::table('employee_workupdate')
                ->where('store_id', auth()->user()->store_id)
                ->whereMonth('created_at', now()->month)
                ->whereYear('created_at', now()->year)
                ->selectRaw('
            SUM(shoe_bill_mtd) as shoe_bill_mtd,
            SUM(shoe_qty_mtd) as shoe_qty_mtd,
            SUM(shoe_ach) as shoe_ach,
            SUM(shirt_bill_mtd) as shirt_bill_mtd,
            SUM(shirt_qty_mtd) as shirt_qty_mtd,
            SUM(shirt_ach) as shirt_ach,
            shoe_tgt as shoe_tgt,
            shirt_tgt as shirt_tgt
        ')
                ->first();

            $emp_perf_record = DB::table('emp_perf_workupdate')
                ->where('store_id', auth()->user()->store_id)
                ->whereMonth('created_at', now()->month)
                ->whereYear('created_at', now()->year)
                ->selectRaw('
                SUM(b_mtd) as b_mtd,
                SUM(q_mtd) as q_mtd,
                SUM(v_mtd) as v_mtd,
                tgt_value  as tgt_value,
                tgt_qty as tgt_qty,
                SUM(ach_per) as ach_per,
                SUM(w_ftd) as w_ftd,
                SUM(los_ftd) as los_ftd,
                SUM(conversion) as conv
            ')
                ->first();
        } else {

            $emp_sale_record = DB::table('employee_workupdate')
                ->where('emp_id', auth()->id())
                ->whereMonth('created_at', now()->month)
                ->whereYear('created_at', now()->year)
                ->selectRaw('
        SUM(shoe_bill_mtd) as shoe_bill_mtd,
        SUM(shoe_qty_mtd) as shoe_qty_mtd,
        SUM(shoe_ach) as shoe_ach,
        SUM(shirt_bill_mtd) as shirt_bill_mtd,
        SUM(shirt_qty_mtd) as shirt_qty_mtd,
        SUM(shirt_ach) as shirt_ach,
        shoe_tgt as shoe_tgt,
        shirt_tgt as shirt_tgt
    ')
                ->first();

            $emp_perf_record = DB::table('emp_perf_workupdate')
                ->where('emp_id', auth()->user()->id)
                ->whereMonth('created_at', now()->month)
                ->whereYear('created_at', now()->year)
                ->selectRaw('
                SUM(b_mtd) as b_mtd,
                SUM(q_mtd) as q_mtd,
                SUM(v_mtd) as v_mtd,
                tgt_value  as tgt_value,
                tgt_qty as tgt_qty,
                SUM(ach_per) as ach_per,
                SUM(w_ftd) as w_ftd,
                SUM(los_ftd) as los_ftd,
                SUM(conversion) as conv
            ')
                ->first();
        }

        $year = now()->year;
        $month = now()->month;


        $annualSickCount = DB::table('leaves')
            ->where('user_id', auth()->user()->id)
            ->whereYear('created_at', $year)
            ->whereIn('request_type', ['Annual Leave', 'Sick Leave'])
            ->where('status', 'Approved')
            ->selectRaw('SUM(DATEDIFF(end_date, start_date) + 1) as total_days')
            ->value('total_days') ?? 0; // fallback to 0 if no records


        $weekOffDays = DB::table('leaves')
            ->where('user_id', auth()->user()->id)
            ->whereYear('created_at', $year)
            ->whereMonth('created_at', $month)
            ->where('request_type', 'Week Off')
            ->where('status', 'Approved')
            ->selectRaw('SUM(DATEDIFF(end_date, start_date) + 1) as total_days')
            ->value('total_days') ?? 0;

        $delayedTasks = Task::where('assign_to', auth()->id())
            ->whereBetween('created_at', [now()->startOfMonth(), now()->endOfMonth()])
            ->whereIn('task_status', ['Completed', 'Assigned', 'Close', 'pending'])
            ->selectRaw('
                    SUM(CASE 
                        WHEN DATE(task_completed) > DATE(end_date) THEN 1 
                        ELSE 0 
                    END) AS delayed_task,
                    SUM(CASE 
                        WHEN DATE(task_completed) <= DATE(end_date) THEN 1 
                        ELSE 0 
                    END) AS on_time
                ')
            ->first();

        $pending = Task::where('assign_to', auth()->id())
            ->whereBetween('created_at', [now()->startOfMonth(), now()->endOfMonth()])
            ->selectRaw('
                    SUM(CASE 
                        WHEN task_status IN ("To Do", "In Progress","On Hold") THEN 1 
                        ELSE 0 
                    END) AS pending_task,
                    SUM(CASE 
                        WHEN task_status IN ("Completed", "Assigned","Close","pending") THEN 1 
                        ELSE 0 
                    END) AS completed
                ')
            ->first();

        $tasks_state = [
            'delayed_task' => $delayedTasks->delayed_task ?? 0,
            'on_time' => $delayedTasks->on_time ?? 0,
            'pending_task' => $pending->pending_task ?? 0,
            'completed' => $pending->completed ?? 0,
        ];


        return response()->json(['emp_sale_record' => $emp_sale_record, 'emp_perf_record' => $emp_perf_record, 'annualSickCount' => $annualSickCount, 'weekOffCount' => $weekOffDays, 'tasks_state' => $tasks_state]);
    }
}
