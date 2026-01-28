<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PerformanceController extends Controller
{
    public function hr_performance()
    {
        $man_perf = DB::table('hr_performance as hp')
            ->leftJoin('users as us', 'hp.manager_id', '=', 'us.id')
            ->select('hp.*', 'us.name as emp_name')
            ->get();


        return view('performance.hr-performance', ['manager' => $man_perf]);
    }
    public function hr_addperformance()
    {
        $managers = DB::table('users')
            ->select('users.name', 'users.id', 'users.emp_code')
            ->where('role_id', 12)
            ->get();

        return view('performance.add-hr-performance', ['managers' => $managers]);
    }

    public function hr_storeperformance(Request $request)
    {
        DB::table('hr_performance')->insert([
            'manager_id' => $request->manager,
            'staff_attrition' => $request->staff_attrition,
            'staff_remark' => $request->staff_remark,
            'hiring' => $request->hiring,
            'hiring_remark' => $request->hiring_remark,
            'task_completion' => $request->task_completion,
            'task_remark' => $request->task_remark,
            'created_by' => Auth::id(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return to_route('performance.hr_performance')->with([
            'status' => 'success',
            'message' => 'Manager Performance submitted successfully'
        ]);
    }


    public function cluster_performance()
    {

        $clu_list = DB::table('cluster_performances')
            ->leftJoin('users', 'cluster_performances.manager_id', '=', 'users.id')
            ->leftJoin('stores', 'cluster_performances.store_id', '=', 'stores.id')
            ->select('cluster_performances.*', 'users.name as name', 'stores.store_name as store_name')
            ->get();

        return view('performance.cluster-performance', ['clu_list' => $clu_list]);
    }
    public function cluster_addperformance()
    {
        $managers = DB::table('users')
            ->select('users.name', 'users.id', 'users.emp_code')
            ->where('role_id', 12)
            ->get();


        // dd($managers);

        return view('performance.add-cluster-performance', ['managers' => $managers]);
    }

    public function getEmployeePerformance(Request $request)
    {
        $employeeId = $request->employee_id;

        $data = DB::table('employee_performances')
            ->where('employee_id', $employeeId)
            ->first();

        return response()->json($data);
    }
    public function cluster_storeperformance(Request $request)
    {

        $man_id = DB::table('users')->where('id', $request->manager_id)->first();

        $store_id = $man_id->store_id;

        DB::table('cluster_performances')->insert([
            'manager_id' => $request->manager_id,
            'store_id' => $store_id,
            'total_walkins' => $request->total_walkins,
            'ly_mtd_walkins' => $request->ly_mtd_walkins,
            'l2l_walkins' => $request->l2l_walkins,

            'total_bills' => $request->total_bills,
            'ly_mtd_bills' => $request->ly_mtd_bills,
            'l2l_bills' => $request->l2l_bills,

            'total_quantity' => $request->total_quantity,
            'ly_mtd_quantity' => $request->ly_mtd_quantity,
            'l2l_quantity' => $request->l2l_quantity,

            'abs' => $request->abs,
            'ly_mtd_abs' => $request->ly_mtd_abs,
            'l2l_abs' => $request->l2l_abs,

            'kpi_points' => $request->kpi_points,
            'ly_mtd_kpi' => $request->ly_mtd_kpi,
            'l2l_kpi' => $request->l2l_kpi,

            'conversion_percent' => $request->conversion_percent,
            'ly_mtd_conversion' => $request->ly_mtd_conversion,
            'l2l_conversion' => $request->l2l_conversion,

            'tgt' => $request->tgt,
            'tgt_achievement_percent' => $request->tgt_achievement_percent,
            'contribution' => $request->contribution,

            'total_extra_leaves' => $request->total_extra_leaves,
            'total_sick_leaves' => $request->total_sick_leaves,

            'customer_relation' => $request->customer_relation,
            'team_management' => $request->team_management,
            'google_review' => $request->google_review,
            'training_completion' => $request->training_completion,
            'damage_control' => $request->damage_control,
            'product_quality' => $request->product_quality,
            'staff_training' => $request->staff_training,
            'daily_photos' => $request->daily_photos,
            'created_by' => auth()->user()->id,
            'created_at' => now(),
            'updated_at' => now()
        ]);

        return view('performance.cluster-performance', [
            'status' => 'success',
            'message' => 'Performance data saved successfully'
        ]);
    }
    public function cluster_viewperformance($id)
    {
        $clu_view = DB::table('cluster_performances')
            ->leftJoin('users', 'cluster_performances.manager_id', '=', 'users.id')
            ->leftJoin('stores', 'cluster_performances.store_id', '=', 'stores.id')
            ->select('cluster_performances.*', 'users.name as name', 'stores.store_name as store_name', 'users.emp_code', 'users.contact_no', 'users.email')
            ->where('cluster_performances.id', $id)
            ->first();

        return view('performance.cluster-viewperformance', ['clu_view' => $clu_view]);
    }

    public function opearation_performance()
    {
        $man_list = DB::table('operation_performances as op')
            ->leftJoin('users as us', 'op.manager_id', '=', 'us.id')
            ->select('op.*', 'us.name as emp_name')
            ->get();

        return view('performance.operation-performance', ['man_list' => $man_list]);
    }
    public function opearation_addperformance()
    {
        $managers = DB::table('users')
            ->select('users.name', 'users.id', 'users.emp_code')
            ->where('role_id', 12)
            ->get();

        return view('performance.add-operation-performance', ['managers' => $managers]);
    }

    public function opearation_storeperformance(Request $request)
    {
        DB::table('operation_performances')->insert([
            'manager_id' => $request->manager,
            'sop_adherence' => $request->sop_adherence,
            'sop_remark' => $request->sop_remark,
            'damage_control' => $request->damage_control,
            'damage_remark' => $request->damage_remark,
            'product_quality' => $request->product_quality,
            'product_remakr' => $request->product_remakr,
            'staff_training' => $request->staff_training,
            'training_remakr' => $request->training_remakr,
            'daily_photos' => $request->daily_photos,
            'photos_remark' => $request->photos_remark,
            'created_by' => auth()->user()->id,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return view('performance.operation-performance', [
            'status' => 'success',
            'message' => 'Performance record saved successfully'
        ]);
    }
    public function opearation_viewperformance(Request $req, $id)
    {
        $opr_list = DB::table('operation_performances as op')
            ->leftJoin('users as us', 'op.manager_id', '=', 'us.id')
            ->select(
                'op.*',
                'us.name as emp_name',
                'us.emp_code',
                'us.email',
                'us.contact_no'
            )
            ->where('op.id', $id)
            ->first();

        return view('performance.operation-viewperformance', ['performance' => $opr_list]);
    }

    public function employee_performance()
    {
          $user = auth()->user();

        $query = DB::table('employee_performance as ep')
            ->leftJoin('users as us', 'ep.emp_name', '=', 'us.id')
            ->select('ep.*', 'us.name as name');

        if ($user->role_id == 12) {
            // Show all employee performance in the same store as the user
            $query->where('us.store_id', $user->store_id);
        } else {
            // Show only performance records created by this user
            $query->where('ep.created_by', $user->id);
        }

        $update_list = $query->orderByDesc('ep.created_at')->get();


        return view('performance.employee-performance', ['perf_list' => $update_list]);
    }
    public function employee_addperformance()
    {
        $user = auth()->user()->id;

        $emp = DB::table('users')->where('id', $user)->select('users.name')->first();

        return view('performance.add-employee-performance', ['emp' => $emp]);
    }
    public function employee_storeperformance(Request $request)
    {
        $user_id = auth()->user()->id;

        DB::table('employee_performance')->insert([
            'emp_name' => $user_id,
            'customer_feedback' => $request->customer_feedback,
            'team_work' => $request->team_work,
            'google_review' => $request->google_review,
            'sop_adherence' => $request->sop_adherence,
            'created_by' => $user_id,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return to_route('performance.employee_performance')->with([
            'status' => 'success',
            'message' => 'Performance added successfully'
        ]);
    }

    public function getManagerPerformanceData(Request $request)
    {
        $managerId = $request->manager_id;

        // Get user details (if needed)
        $user = DB::table('users')->where('id', $managerId)->first();
        $store_id = $user->store_id;
        $user_id = $user->id;

        // dd($user);

        // Get current month
        $currentMonth = now()->format('m');
        $currentDay = now()->format('d');

        $today = now();
        if ($today->day == 1) {
            $targetDate = $today->toDateString();
        } else {
            $targetDate = $today->subDay()->toDateString();
        }

        $sums = DB::table('work_update')
            ->whereDate('created_at', $targetDate)
            ->where('store_id', auth()->user()->store_id)
            ->select(
                DB::raw('SUM(b_mtd) as b_mtd_sum'),
                DB::raw('SUM(q_mtd) as q_mtd_sum'),
                DB::raw('SUM(w_mtd) as w_mtd_sum'),
                DB::raw('SUM(los_mtd) as los_mtd_sum'),
                DB::raw('SUM(abs_mtd) as abs_mtd_sum'),
                DB::raw('SUM(c_mtd) as c_mtd_sum'),
                DB::raw('SUM(k_mtd) as k_mtd_sum'),
            )
            ->first();

        $tgt = DB::table('target')->where('store_id', $store_id)->where('month', $currentMonth)->first();

        $ly_d = DB::table('ly_daywise')
            ->whereMonth('date', $currentMonth)
            ->whereDay('date', $currentDay)
            ->where('store_id', $store_id)
            ->select(
                DB::raw('SUM(bill) as ly_bill'),
                DB::raw('SUM(qty) as ly_qty')
            )
            ->first();

        $totalWalkins = DB::table('walkin')
            ->where('store_id', $store_id)
            ->whereMonth('created_at', $currentMonth)
            ->whereDate('created_at', $currentDay)
            ->count();

        $totalSickLeaveDays = DB::table('leaves')
            ->where('user_id', $user_id)
            ->whereMonth('start_date', Carbon::now()->month)
            ->whereYear('start_date', Carbon::now()->year)
            ->where('reason', 'sick leave')  // or ->where('leave_type', 'sick leave') based on your column name
            ->select(DB::raw('SUM(DATEDIFF(end_date, start_date) + 1) as total_days'))
            ->value('total_days');

        $totalOtherLeaveDays = DB::table('leaves')
            ->where('user_id', $user_id)
            ->whereMonth('start_date', Carbon::now()->month)
            ->whereYear('start_date', Carbon::now()->year)
            ->whereNotIn('reason', ['sick leave', 'week off'])  // exclude sick and week off
            ->select(DB::raw('SUM(DATEDIFF(end_date, start_date) + 1) as total_days'))
            ->value('total_days');


        // // Calculate ABS
        // $abs = $walkinsData->total_quantity > 0 && $walkinsData->total_bills > 0
        //     ? $walkinsData->total_quantity / $walkinsData->total_bills
        //     : 0;

        // // Calculate L2L values (e.g., walkins)
        // $l2l_walkins = $lyDaywise->ly_mtd_walkins > 0
        //     ? $walkinsData->total_walkins / $lyDaywise->ly_mtd_walkins
        //     : 0;

        // $l2l_bills = $ly_d->

        // $l2l_quantity = $lyDaywise->ly_mtd_quantity > 0
        //     ? $walkinsData->total_quantity / $lyDaywise->ly_mtd_quantity
        //     : 0;

        // $l2l_abs = $lyDaywise->ly_mtd_abs > 0
        //     ? $abs / $lyDaywise->ly_mtd_abs
        //     : 0;

        // $l2l_conversion = $lyDaywise->ly_mtd_conversion > 0
        //     ? ($target->conversion_percent ?? 0) / $lyDaywise->ly_mtd_conversion
        //     : 0;

        $ltl_walkins =  $ly_d->ly_mtd_walkins / $sums->w_mtd_sum;

        $ltl_bills =  $ly_d->ly_bill / $sums->b_mtd_sum;

        $ltl_qty = $ly_d->ly_qty / $sums->q_mtd_sum;

        $abs = $sums->q_mtd_sum /  $sums->b_mtd_sum;

        $abs_ly = $ly_d->ly_qty / $ly_d->ly_bill;

        $ltl_abs =  $abs_ly / $abs;

        // $ltl_kpi =  / 

        $conversion = $sums->w_mtd_sum / $sums->b_mtd_sum;
        // $tgt_per =  ($target->target /  $sums->b_mtd_sum) * 100;




        // $con_ly = $sums / $ly_bill;

        return response()->json([
            'total_walkins' => $totalWalkins,
            'ty_mtd_walkins' => $sums->w_mtd_sum,
            // 'ly_mtd_walkins' => $ly_d->ly_mtd_walkins,
            'l2l_walkins' => $ltl_walkins,

            'total_bills' => $sums->b_mtd_sum,
            'ly_mtd_bills' => $ly_d->ly_bill,
            'l2l_bills' => $ltl_bills,

            'total_quantity' => $sums->q_mtd_sum,
            'ly_mtd_quantity' => $ly_d->ly_qty,
            'l2l_quantity' => $ltl_qty,

            'abs' => $abs,
            'ly_mtd_abs' => $abs_ly,
            'l2l_abs' => $ltl_abs,

            // // 'kpi_points' => $lyDaywise->ly_mtd_kpi,
            // 'ly_mtd_kpi' => $lyDaywise->ly_mtd_kpi,
            // 'l2l_kpi' => 1, // Adjust if needed

            'conversion_percent' =>  $conversion,
            // 'ly_mtd_conversion' => $lyDaywise->ly_mtd_conversion,
            // 'l2l_conversion' => $l2l_conversion,



            'tgt' => $target->target ?? 0,
            'tgt_achievement_percent' => $tgt_per ?? 0,

            'contribution' => $target->contribution ?? 0,
            'total_extra_leaves' => $totalOtherLeaveDays,
            'total_sick_leaves' => $totalSickLeaveDays,
        ]);
    }
}
