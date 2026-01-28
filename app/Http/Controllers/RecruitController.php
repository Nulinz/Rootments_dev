<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Services\FirebaseService;
use App\Models\Notification;

class RecruitController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function list(Request $request)
    {

        $job = DB::table('job_posting as jp')
            ->leftJoin('recruitments as rc', 'rc.id', '=', 'jp.rec_id')
            ->leftjoin('roles', function ($join) {
                $join->on('roles.id', '=', 'rc.role'); // Join on store_id and store_ref_id
            })
            ->select(
                'jp.*',
                'roles.role as roll',
                'roles.role_dept as dept',
                'rc.exp',
                'rc.loc',
                'rc.role as rec_role',
                'jp.status as jp_status'
            )
            ->orderBy('jp.id', 'DESC')->get();
        //    dd($job);
        return view('recruit.list', ['list' => $job]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        try {
            $rec = DB::table('recruitments as rc')
                ->leftJoin('roles', 'rc.request_to', '=', 'roles.id')
                ->where('rc.status', 'Approved')
                ->select(
                    'rc.id'

                )
                ->get();
        } catch (\Exception $e) {
            dd($e);
        }
        return view('recruit.create', ['rec' => $rec]);
    }

    public function rec_data(Request $req)
    {
        try {
            $rec_data = DB::table('recruitments as rc')
                ->leftJoin('roles', 'rc.role', '=', 'roles.id')
                ->where('rc.id', $req->rec)
                ->select(
                    'rc.id',
                    'rc.dept',
                    'rc.role',
                    'rc.loc',
                    'rc.vacancy',
                    'rc.request_to',
                    'rc.exp',
                    'rc.description',
                    'rc.status',
                    'roles.role',
                    'rc.res_date',
                    'rc.exp'

                )

                ->get();
        } catch (\Exception $e) {
            dd($e);
        }

        return response()->json($rec_data, 200);
    }

    public function post_update(Request $req)
    {


        $user = auth()->user();

        $req->validate([
            'job_id' => 'required',
            'status' => 'required',
        ]);

        try {


            $job_update = DB::table('job_posting')
                ->where('id', $req->job_id)
                ->update([
                    'status' => $req->status
                ]);

            $job = DB::table('job_posting as jp')
                ->leftJoin('recruitments as rc', 'rc.id', '=', 'jp.id')
                ->leftjoin('roles', function ($join) {
                    $join->on('roles.id', '=', 'rc.role'); // Join on store_id and store_ref_id
                })

                ->select(
                    'jp.*',
                    'roles.role',
                    'roles.role_dept',
                    'rc.exp',
                    'rc.loc',
                    'jp.status as jp_status'
                )
                ->orderBy('jp.id', 'DESC')->get();

            $job_post = DB::table('job_posting')->where('id', $req->job_id)->first();

            $rec_post = DB::table('recruitments')->where('id', $job_post->rec_id)->first();

            $hr_asst = DB::table('users')->where('role_id', 5)->first();

            $req_token_lt  = DB::table('users')->whereIn('id', [$rec_post->c_by, $hr_asst->id])->get();

            foreach ($req_token_lt as $req_token) {

                if (!is_null($req_token->device_token)) {
                    $taskTitle = "Recruitment Request Status";

                    $taskBody = "REC" . $rec_post->id . " Job Post has " . $req->status;

                    $response = app(FirebaseService::class)->sendNotification($req_token->device_token, $taskTitle, $taskBody);

                    Notification::create([
                        'user_id' => $req_token->id,
                        'noty_type' => 'recruitment',
                        'type_id' => $req->job_id,
                        'title' => $taskTitle,
                        'body' => $taskBody,
                        'c_by' => $user->id
                    ]);
                } // notification end

            } // end foreach.....


            return back()->with(['status' => 'success', 'message' => 'JobPost updated successfully!', 'list' => $job]);
        } catch (\Exception $e) {

            dd($e);
            // return response()->json(['error' => 'Failed to update Recruitment.'], 500);
        }
    }

    public function candidate_profile(Request $request)
    {

        $job_pro = DB::table('job_apply as ja')->where('ja.id', $request->id)
            ->leftJoin('job_posting as jp', 'jp.id', '=', 'ja.job_id')
            ->leftJoin('recruitments as rc', 'rc.id', '=', 'jp.rec_id')
            ->leftjoin('roles', function ($join) {
                $join->on('roles.id', '=', 'rc.role'); // Join on store_id and store_ref_id
            })
            ->select('ja.*', 'jp.*', 'rc.*', 'roles.role as rl', 'ja.id as jobid')
            ->first();

        $hr = DB::table('users')->whereIn('role_id', [3, 4, 5])->select('name', 'id')->get();

        $round_list = DB::table('rounds')->where('app_id', $request->id)
            ->leftJoin('users', function ($join) {

                $join->on('users.id', '=', 'rounds.c_by')
                    ->whereNotNull('rounds.c_by');
            })
            ->select('users.name', 'rounds.*')
            ->get();


        //  dd($round_list);


        return view('recruit.candidate_profile', ['pro' => $job_pro, 'assign_to' => $hr, 'round_list' => $round_list]);
    }

    public function add_interview(Request $request)
    {
        // return view('recruit.add_interview');
    }

    public function edit_interview(Request $request)
    {
        return view('recruit.edit_interview');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $req)
    {
        $user = Auth::user();

        try {

            $rec = DB::table('job_posting')->insertGetId([
                'rec_id' => $req->rec_id,
                'job_title' => $req->jobtitle,
                'responsibility' => $req->resp,
                'job_type' => $req->jobtype,
                'job_desc' => $req->jobdesp,
                'hrs' => $req->workhours,
                'salary' => $req->slryrange,
                'benefits' => $req->benefits,
                'post_date' => $req->postdate,
                'req_to' => 3,
                'c_by' => $user->id,
                'created_at' => now(),
                'updated_at' => now()

            ]);


            $req_token  = DB::table('users')->where('role_id', 3)->first();

            if ($req_token->device_token) {

                $taskTitle = "Recruitment Request Status";

                $taskBody = "Kindly Approve the job Post for Recruitment-REC" . $rec;

                $response = app(FirebaseService::class)->sendNotification($req_token->device_token, $taskTitle, $taskBody);

                Notification::create([
                    'user_id' => $req_token->id,
                    'noty_type' => 'recruitment',
                    'type_id' => $rec,
                    'title' => $taskTitle,
                    'body' => $taskBody,
                    'c_by' => auth()->user()->id
                ]);
            } // notification end

        } catch (\Exception $e) {
            dd($e);
        }

        if ($rec) {
            return back()->with(['status' => 'success', 'message' => 'Recuritment Added Successfully']);
        } else {
            return back()->with(['status' => 'Failed', 'message' => 'Recuritment Failed  to Add!!']);
        }
    }

    /**
     * Display the specified resource.
     */
    public function profile(Request $req)
    {
        $pro = $req->id;

        $list = DB::table('job_posting as jp')->where('jp.id', $pro)
            ->leftJoin('recruitments as rc', 'rc.id', '=', 'jp.rec_id')
            ->leftjoin('roles as ro', function ($join) {
                $join->on('ro.id', '=', 'rc.role'); // Join on store_id and store_ref_id
            })


            ->select(
                'jp.*',
                'ro.role',
                'ro.role_dept',
                'rc.exp',
                'rc.loc',
                'jp.id as jp_id'

                // 'jp.id as job_id',
                // 'jp.status as jp_status',
                // 'jp.job_title',
                // 'jp.responsibility',
                // 'jp.job_type',
                // 'jp.job_desc',
                // 'jp.hrs',
                // 'jp.salary',
                // 'jp.post_date',
                // 'jp.rec_id',

            )
            ->orderBy('jp.id', 'DESC')->first();

        // dd($list);

        $ap_list = DB::table('job_apply')->where('job_id', $pro)
            ->select(
                'id',
                'name',
                'job_location',
                'contact',
                'email',
                'work_exp',
                'skill',
                'edu',
                'certify',
                'resume',
                'status'
            )->get();

        $total_appiled = $ap_list->count();
        $total_resume = $ap_list->whereNotNull('resume')->count();

        // dd($ap_list);


        $sc_list = DB::table('job_apply')
            ->where('job_id', $pro)
            ->where('job_apply.status', 'Screening')
            ->leftJoin('rounds as ro', 'ro.app_id', '=', 'job_apply.id') // Join with rounds table
            ->select(
                'job_apply.*',  // Get all columns from the job_apply table
                'ro.round', // Get the round from the rounds table
                'ro.status as round_status', // Get the status of the round
                'ro.created_at as round_created_at' // Get the created_at date of the round (or you can use it to sort)
            )
            ->groupBy('job_apply.name')
            ->get();

        $sc_list = $sc_list->map(function ($jobApply) {
            // Now that we have joined the rounds, let's process each user with their rounds

            // Fetch the rounds for this particular user (job application)
            $rounds = DB::table('rounds')
                ->where('app_id', $jobApply->id)  // Find rounds related to this job application
                // Order by the latest created round
                ->get();  // Get all rounds for this job application

            // If there are any rounds, attach them to the job application
            $jobApply->rounds = $rounds;

            return $jobApply;
        });

        // shortlist app

        // $short_list = DB::table('job_apply as jp')->where('jp.job_id','=',$pro)
        //                 ->leftJoin('rounds as rs','rs.app_id','=','jp.id')
        //                 ->where('rs.status', 'Completed')
        //                 ->get();

        $short_list = DB::table('rounds as rs')
            ->where('rs.status', 'Completed')
            ->distinct()->select('rs.app_id')
            ->leftJoin('job_apply as jp', 'jp.id', '=', 'rs.app_id')
            ->where('jp.job_id', '=', $pro)

            ->select('jp.*')
            ->get();

        $hold_list = DB::table('rounds as rs')
            ->where('rs.status', 'Hold')
            ->distinct()->select('rs.app_id')
            ->leftJoin('job_apply as jp', 'jp.id', '=', 'rs.app_id')
            ->where('jp.job_id', '=', $pro)
            ->select('jp.*', 'rs.id as round_id')
            ->get();

        $reject_list = DB::table('rounds as rs')
            ->where('rs.status', 'Rejected')
            ->distinct()->select('rs.app_id')
            ->leftJoin('job_apply as jp', 'jp.id', '=', 'rs.app_id')
            ->where('jp.job_id', '=', $pro)
            ->select('jp.*', 'rs.id as round_id')
            ->get();

        // $new_list = $short_list->map(function($short_list) {
        //     // Fetch job details for each app_id
        //     $list = DB::table('job_apply')->where('id', $short_list->app_id)->first(); // Use 'first()' to get a single job entry

        //     return $list; // Return the job_apply entry
        // });

        // To get the result as an array of job_apply objects:

        //   dd($short_list);



        //  dd($new_list);

        return view('recruit.profile', ['list' => $list, 'ap_list' => $ap_list, 'sc_list' => $sc_list, 'short' => $short_list, 'hold' => $hold_list, 'reject' => $reject_list, 'total_appiled' => $total_appiled, 'total_resume' => $total_resume]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Request $req)
    {

        $job = DB::table('job_posting as jp')->where('jp.id', $req->id)
            ->leftJoin('recruitments as rc', 'rc.id', '=', 'jp.id')
            ->leftjoin('roles', function ($join) {
                $join->on('roles.id', '=', 'rc.role'); // Join on store_id and store_ref_id
            })

            ->select(
                'jp.*',
                'roles.role',
                'roles.role_dept',
                'rc.exp',
                'rc.loc',
                'jp.status as jp_status',
                'jp.id as post_id'
            )
            ->orderBy('jp.id', 'DESC')->first();

        //  dd($job);

        return view('recruit.edit', ['edit' => $job]);
    }

    public function post_application(Request $request)
    {
        $list = DB::table('job_posting as jp')->where('jp.id', $request->id)
            ->leftJoin('recruitments as rc', 'rc.id', '=', 'jp.id')
            ->leftjoin('roles as ro', function ($join) {
                $join->on('ro.id', '=', 'rc.role'); // Join on store_id and store_ref_id
            })


            ->select(
                'jp.*',
                'ro.role',
                'ro.role_dept',
                'rc.exp',
                'rc.loc'
            )
            ->orderBy('jp.id', 'DESC')->first();
            
        $location = DB::table('stores')
            ->select('store_geo')->where('status', 1)->get();

        return view('recruit.application_form', ['id' => $request->id, 'list' => $list, 'location' => $location]);
    }

    public function post_app_store(Request $req)
    {

        $validator = Validator::make($req->all(), [
            'location' => 'required',
            'name' => 'required|string|max:255',
            'job_id' => 'required',
            'dob' => 'required|date_format:Y-m-d',
            'email' => 'required|email|max:255',
            'contactno' => 'required|digits:10', // Adjusted to validate 10-digit contact number
            'address' => 'required|string|max:500',
            'city' => 'required|string|max:255',
            'state' => 'required|string|max:255',
            'pincode' => 'required|digits:6', // Pincode should be 6 digits
            'edubkgrnd' => 'nullable|string|max:500',
            'workexp' => 'nullable|string|max:500',
            'skills' => 'nullable|string|max:500',
             'certifications' => 'nullable|file|mimes:pdf,jpg,png,jpeg|max:5120', // Validate file type and size for certifications
            'resume' => 'required|file|mimes:pdf,doc,docx,jpg,png,jpeg|max:5120', // Validate resume upload (pdf/doc)
        ]);

        // return redirect()->back()->with([
        //     'status' => 'success',
        //     'message' => 'Job post updated successfully!'
        // ]);


        if ($validator->fails()) {

            // dd($validator);

            // dd($validator);
            // If validation fails, return the errors and redirect back
            return redirect()->back()->withErrors($validator)->withInput();
        }



        $insert = DB::table('job_apply')->insertGetId([
            'job_id' => $req->job_id,
            'job_location' => $req->location,
            'name' => $req->name,
            'dob' => $req->dob,
            'email' => $req->email,
            'contact' => $req->contactno,
            'add' => $req->address,
            'city' => $req->city,
            'state' => $req->state,
            'pincode' => $req->pincode,
            'edu' => $req->edubkgrnd,
            'work_exp' => $req->workexp,
            'skill' => $req->skills,
            'notice' => $req->noticeperiod,
            'created_at' => now(),
            'updated_at' => now(),

        ]);

        $cer_name = null;
        $res_name = null;

        $path = 'assets/images/job_docs/';

        if ($req->hasFile('certifications')) {
            $cer_file = $req->file('certifications');
            // $name = date('y') . '-' . Str::upper(Str::random(8)) . '.' . $file->getClientOriginalExtension();
            $cer_ext = $cer_file->getClientOriginalExtension();
            $cer_name = uniqid('certificate_file_') . '.' . $cer_ext; // Generate a unique filename

            $cer_file->move($path, $cer_name);
        }

        if ($req->hasFile('resume')) {
            $res_file = $req->file('resume');
            // $name = date('y') . '-' . Str::upper(Str::random(8)) . '.' . $file->getClientOriginalExtension();
            $res_ext = $res_file->getClientOriginalExtension();
            $res_name = uniqid('resume_file_') . '.' . $res_ext; // Generate a unique filename

            $res_file->move($path, $res_name);

            // $task->task_file = $path . $filename;
        }

        $updateData = [];

        if ($cer_name) {
            $updateData['certify'] = $path . $cer_name;
        }

        if ($res_name) {
            $updateData['resume'] = $path . $res_name;
        }



        if (!empty($updateData)) {
            $up_file = DB::table('job_apply')->where('id', $insert)
                ->update($updateData);
        }




        if ($insert) {
            return redirect()->back()->with(['status' => 'success', 'message' => 'Post Apllied Successfully']);
        } else {
            return redirect()->back()->with(['status' => 'Failed', 'message' => 'Post Failed  to Apply!!']);
        }
    }

    public function update_screen(Request $request)
    {
        $request->validate([
            'ap_id' => 'required',
            'status' => 'required',
        ]);

        try {
            $user = auth()->user();

            $recruit = DB::table('job_apply')->where('id', $request->ap_id)
                ->update([
                    'status' => $request->status
                ]);

            // $recruit->status = $request->status;

            // $recruit->save();

            // $notification = Notification::create([
            //             'user_id' => $recruit->emp_id,
            //             'noty_type' => 'recuriment',
            //             'type_id' => $request->id,
            //         ]);

            return response()->json(['message' => 'Candidate updated successfully!'], 200);
        } catch (\Exception $e) {

            dd($e);
            // return response()->json(['error' => 'Failed to update Recruitment.'], 500);
        }
    }


    /**
     * Update the specified resource in storage.
     */
    public function add_round(Request $req)
    {
        $user = Auth::user();

        $round = DB::table('rounds')->insert([
            'app_id' => $req->app_id,
            'round' => $req->round,
            'status' => $req->pop_status,
            'assign_to' => $req->assignto,
            'review' => $req->review,
            'c_by' => $user->id,
            'created_at' => now(),
            'updated_at' => now(),

        ]);

        if ($round) {
            // Redirect back with a success message if insertion is successful
            return redirect()->back()->with([
                'status' => 'success',
                'message' => 'Operation completed successfully!'
            ]);
        }

        // If insertion fails, you can redirect with an error message
        return redirect()->back()->with([
            'status' => 'error',
            'message' => 'There was an issue with the operation.'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function job_post_edit(Request $req)
    {
        $update = DB::table('job_posting')->where('id', $req->id)
            ->update([
                'job_title' => $req->jobtitle,
                'responsibility' => $req->resp,
                'job_type' => $req->jobtype,
                'job_desc' => $req->jobdesp,
                'hrs' => $req->workhours,
                'salary' => $req->slryrange,
                'benefits' => $req->benefits,
                'post_date' => $req->postdate,
                'updated_at' => now()

            ]);

        if ($update) {
            // Redirect back with a success message if insertion is successful
            return redirect()->back()->with([
                'status' => 'success',
                'message' => 'Job Posting Updated successfully!'
            ]);
        }

        // If insertion fails, you can redirect with an error message
        return redirect()->back()->with([
            'status' => 'error',
            'message' => 'There was an issue with the operation.'
        ]);
    }


    public function hold_update(Request $req)
    {


        $round = DB::table('rounds')
            ->where('id', $req->round_id)
            ->update(['status' => 'Completed']);

        return redirect()->back()->with([
            'status' => 'success',
            'message' => 'Employee Updated.'
        ]);
    }
}
