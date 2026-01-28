<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class WorkUpdateController extends Controller
{
    public function abstractlist()
    {
        // $list = DB::table('work_update as wp')
        // ->join('stores as s', 'wp.store_id', '=', 's.id')  // Assuming 'store_id' is the column in wp to reference store.
        // ->whereMonth('wp.created_at', date("m"))
        // ->whereYear('wp.created_at', date("Y"))  // Optional, to get rows only from this year.
        // ->select('wp.*','s.store_name','s.store_code')
        // ->groupBy('s.id')  // Grouping by store
        // ->orderBy('wp.created_at', 'DESC')  // Get the latest
        // ->get();

        $list = DB::table('work_update as wu')
    ->join(
        DB::raw('(SELECT store_id, MAX(created_at) AS latest_created_at FROM work_update GROUP BY store_id) AS latest'),
        function($join) {
            $join->on('wu.store_id', '=', 'latest.store_id')
                 ->on('wu.created_at', '=', 'latest.latest_created_at');
        })
    ->join('stores as s', 'wu.store_id', '=', 's.id') // Joining the store table
    ->select('wu.*', 's.store_name', 's.store_code')  // Selecting required fields
    ->whereMonth('wu.created_at', date('m')) // Filtering for current month
    ->whereYear('wu.created_at', date('Y'))  // Optional: Filtering for current year
    ->get();

        //    dd($list);


        return view('workupdate.abstract-list',['list'=>$list]);
    }

    public function reportlist()
    {
        $store = DB::table('stores')->get();

        return view('workupdate.report-workupdate',['stores'=>$store]);
    }

    public function store(Request $request)
    {
        //
    }

    public function daily_work(Request $req)
    {
        $list = DB::table('work_update')->where('store_id',$req->store)->whereDate('created_at',$req->date)->get();

        $store = DB::table('stores')->get();

        // dd($list);

        return view('workupdate.report-workupdate',['list'=>$list,'stores'=>$store]);

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
    
    
    public function hr_workupdate()
    {

        $user = auth()->user(); // Get logged-in user

        if (in_array($user->role_id, [1, 2])) {
            // Admin/Manager: show all records
            $hr_work = DB::table('hr_workupdate')
                ->leftJoin('users', 'hr_workupdate.c_by', '=', 'users.id')
                ->select('hr_workupdate.*', 'users.name as created',)
                ->get();
        } else {
            // Regular user: show only their own records
            $hr_work = DB::table('hr_workupdate')
                ->where('c_by', $user->id)
                ->leftJoin('users', 'hr_workupdate.c_by', '=', 'users.id')
                ->select('hr_workupdate.*', 'users.name as created',)
                ->get();
        }

        return view('workupdate.hr-worklist', ['hr_work' => $hr_work]);
    }

    public function hr_workadd()
    {
        $user_id = auth()->user()->id;

        $month = now()->month;

        $total_tasks = DB::table('tasks')
            ->where('assign_to', $user_id)
            ->whereMonth('created_at', $month)
            ->count();


        $tasks_completed = DB::table('tasks')
            ->where('assign_to', $user_id)
            ->whereIn('task_status', ['Completed'])
            ->whereMonth('updated_at', $month)
            ->count();

        $total_emp = DB::table('users')
            ->whereIn('status', [1])
            ->count();

        $inv_emp = DB::table('resignations as rs')
            ->leftJoin('resign_list as rl', 'rs.id', '=', 'rl.res_id')
            ->whereIn('formality', ['Termination'])
            ->whereMonth('rl.created_at', $month)
            ->count();

        $store_emp = DB::table('users')
            ->whereIn('status', ['1'])
            ->where('dept', 'Store')
            ->count();

        // calculations

        // tasks
        // $t_c =  round(($tasks_completed / $total_tasks) * 100, 4);

        // // attrition
        // $attr = round(($inv_emp / $total_emp) * 100, 4);

        // // retention
        // $ret = round(($total_emp / $store_emp) * 100, 4);

        // Task Completion %
        $t_c = ($total_tasks > 0)
            ? round(($tasks_completed / $total_tasks) * 100, 4)
            : 0;

        // Attrition Rate %
        $attr = ($total_emp > 0)
            ? round(($inv_emp / $total_emp) * 100, 4)
            : 0;

        // Retention Rate %
        $ret = ($store_emp > 0)
            ? round(($total_emp / $store_emp) * 100, 4)
            : 0;

        return view('workupdate.hr-addworkupdate', ['t_c' => $t_c, 'attr' => $attr, 'ret' => $ret]);
    }
    public function hr_storework(Request $req)
    {
        $user = auth()->user()->id;

        DB::table('hr_workupdate')->insert([
            'attrition_rate' => $req->att_ratio,
            'retention_rate' => $req->ret_ratio,
            'hiring_completion' => $req->hire_completion,
            'task_completion' => $req->task_comp,
            'spendings' => $req->spending,
            'c_by' => $user,
            'created_at' => now(),
            'updated_at' => now()
        ]);

        return to_route('hr_workupdate-list', [
            'status' => 'success',
            'message' => 'Created Successfully'
        ]);
    }
}
