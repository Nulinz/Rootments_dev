<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\Leave;
use Carbon\Carbon;
use App\Models\Attendance;

class PayrollController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {

        $user_list = collect(); // This will set $u_list to an empty collection

        $dept = DB::table('roles')
            ->where('id', '!=', 1)
            ->select('role_dept')
            ->distinct()
            ->get();

        // stores for dropdown....

        $stores = DB::table('stores')->get();


        return view('payroll.list', ['u_list' => $user_list, 'dept' => $dept, 'stores' => $stores]);
    }

    public function drop_show(Request $req)
    {
        $sal_mon = $req->sal_mon;

        $sal = explode('-', $sal_mon);

        $year = $sal[0];
        $mon = $sal[1];

        $store = DB::table('stores') // Start by selecting all stores
            ->leftJoin('m_salary', function ($join) use ($mon, $year) {
                $join->on('stores.id', '=', 'm_salary.store') // Join on the store id
                    ->where('m_salary.month', '=', $mon)  // Filter by the provided month
                    ->where('m_salary.year', '=', $year); // Filter by the provided year
            })
            ->whereNull('m_salary.store') // Exclude stores that have matching records in m_salary
            ->select('stores.id', 'stores.store_name', 'stores.store_code') // Select the columns from stores table
            ->get(); // Retrieve the result

        // The $store will now contain only those stores that don't have a matching entry in the m_salary table for the given month and year




        // return $store;

        return response()->json($store);


        //   return view('payroll.list',['store'=>$store]);
    }
    /**
     * Show the form for creating a new resource.
     */

     
      public function store_per(Request $req)
    {
        $sal_mon = $req->month;

        $user_list = DB::table('users as us')
            ->leftJoin('roles', 'roles.id', '=', 'us.role_id')
            ->where('us.status', 1)
            ->leftJoin('attendance as a', function ($join) use ($sal_mon) {
                $join->on('a.user_id', '=', 'us.id')
                    ->whereRaw("DATE_FORMAT(a.c_on, '%Y-%m') = ?", [$sal_mon]);
            })
            ->leftJoin('attd_ot as ot', function ($join) use ($sal_mon) {
                $join->on('a.id', '=', 'ot.attd_id')
                    ->where('ot.status', '=', 'approved')
                    ->whereRaw("DATE_FORMAT(ot.created_at, '%Y-%m') = ?", [$sal_mon]);
            })
            ->leftJoin('salary_hold as sh', function ($join) use ($sal_mon) {
                $join->on('us.emp_code', '=', 'sh.emp_code')
                    ->where('sh.status', '=', 'OnHold')
                    ->where(DB::raw("DATE_FORMAT(sh.start_hold_date, '%Y-%m')"), '<=', $sal_mon)
                    ->where(function ($query) use ($sal_mon) {
                        $query->where(DB::raw("DATE_FORMAT(sh.end_hold_date, '%Y-%m')"), '>=', $sal_mon)
                            ->orWhereNull('sh.end_hold_date');
                    });
            })
            ->when($req->dept == 'Store', function ($join) use ($req) {
                return $join->leftJoin('stores', 'us.store_id', '=', 'stores.id')
                    ->where('us.store_id', $req->store);
            })
            ->when($req->dept != 'Store', function ($join) use ($req) {
                return $join->where('roles.role_dept', '=', $req->dept);
            })
            ->select(
                'us.id as emp_id',
                'us.name',
                'us.emp_code',
                'us.base_salary',
                'sh.status as hold_status',
                DB::raw('COUNT(a.id) as attendance_count'),
                DB::raw('SUM(CASE WHEN ot.cat = "late" THEN ot.amount ELSE 0 END) as total_late'),
                DB::raw('SUM(CASE WHEN ot.cat = "ot" THEN ot.amount ELSE 0 END) as total_ot')
            )
            ->groupBy('us.id')
            ->get();

        $sal = explode('-', $sal_mon);
        $year = $sal[0];
        $mon = $sal[1];

        // Enhanced leave calculation with paid/unpaid breakdown
        $user_list = $user_list->map(function ($user) use ($mon, $year, $req) {

            $twd = $req->twd;

            // STEP 1: Get leaves for the CURRENT MONTH only first
            $monthlyLeaves = Leave::where('user_id', $user->emp_id)
                ->where('request_status', 'Approved')
                ->where(function ($query) use ($mon, $year) {
                    $query->whereMonth('start_date', $mon)->whereYear('start_date', $year)
                        ->orWhereMonth('end_date', $mon)->whereYear('end_date', $year);
                })
                ->get();


            // Initialize monthly counters
            $monthlyAnnualSickDays = 0;
            $monthlyWeekOffDays = 0;
            $monthlyUnpaidDays = 0;
            $monthlyHalfDays = 0;

            // Calculate this month's leaves by type
            foreach ($monthlyLeaves as $leave) {
                // For leaves spanning multiple months, calculate only the days in current month
                $leaveStart = max($leave->start_date, "$year-$mon-01");
                $leaveEnd = min($leave->end_date, date("Y-m-t", strtotime("$year-$mon-01")));
                $leaveDays = $this->calculateLeaveDays($leaveStart, $leaveEnd);

                switch ($leave->request_type) {
                    case 'Annual Leave':
                    case 'Sick Leave':
                        $monthlyAnnualSickDays += $leaveDays;
                        break;
                    case 'Week Off':
                        $monthlyWeekOffDays += $leaveDays;
                        break;
                    case 'Casual Leave':
                        // case 'Permission':
                        $monthlyUnpaidDays += $leaveDays;
                        break;
                    case 'Half Day':
                        $monthlyHalfDays += 1;
                        break;
                }
            }

            // STEP 2: Get total Annual + Sick leave taken in OTHER MONTHS of the year
            $otherMonthsAnnualSick = Leave::where('user_id', $user->emp_id)
                ->where('request_status', 'Approved')
                ->whereIn('request_type', ['Annual Leave', 'Sick Leave'])
                ->whereYear('start_date', $year)
                ->where(function ($query) use ($mon, $year) {
                    // Exclude leaves that are in current month
                    $query->where(function ($q) use ($mon, $year) {
                        $q->whereMonth('start_date', '!=', $mon)
                            ->orWhereYear('start_date', '!=', $year);
                    })->where(function ($q) use ($mon, $year) {
                        $q->whereMonth('end_date', '!=', $mon)
                            ->orWhereYear('end_date', '!=', $year);
                    });
                })
                ->get()
                ->sum(function ($leave) {
                    return $this->calculateLeaveDays($leave->start_date, $leave->end_date);
                });

            // STEP 3: Calculate how much of this month's Annual/Sick should be paid
            // Check remaining balance from 20 days yearly limit
            $remainingPaidBalance = max(0, 20 - $otherMonthsAnnualSick);

            // Apply the remaining balance to this month's Annual/Sick leaves
            $paidAnnualSickThisMonth = min($monthlyAnnualSickDays, $remainingPaidBalance);
            $unpaidAnnualSickThisMonth = max(0, $monthlyAnnualSickDays - $paidAnnualSickThisMonth);

            // STEP 4: Handle Week off separately (4 days per month limit)
            $paidWeekOffThisMonth = min($monthlyWeekOffDays, 4);
            $unpaidWeekOffThisMonth = max(0, $monthlyWeekOffDays - 4);

            // STEP 5: Calculate final totals for this month
            $totalPaidLeaves = $paidAnnualSickThisMonth + $paidWeekOffThisMonth;
            $totalUnpaidLeaves = $monthlyUnpaidDays + $unpaidAnnualSickThisMonth + $unpaidWeekOffThisMonth;

            $user->present_day = ($user->attendance_count) - ($monthlyHalfDays / 2);

            // ABSENT DAYS = TWD - (present + paid + unpaid leaves + half-days)
            $absentDays = $twd - ($user->present_day + $totalPaidLeaves + $totalUnpaidLeaves + ($monthlyHalfDays / 2));
            $absentDays = max(0, $absentDays); // prevent negative

            // Add absent days into unpaid leaves
            $totalUnpaidLeaves += $absentDays;

            // Add leave data to user object
            $user->paid_leaves = $totalPaidLeaves;
            $user->unpaid_leaves = $totalUnpaidLeaves;
            $user->half_day_leaves = $monthlyHalfDays;


            // Debug information to verify calculations
            $user->debug_info = [
                'emp_code' => $user->emp_code,
                'other_months_annual_sick' => $otherMonthsAnnualSick,
                'this_month_annual_sick' => $monthlyAnnualSickDays,
                'remaining_paid_balance' => $remainingPaidBalance,
                'paid_annual_sick_this_month' => $paidAnnualSickThisMonth,
                'unpaid_annual_sick_this_month' => $unpaidAnnualSickThisMonth,
                'this_month_week_off' => $monthlyWeekOffDays,
                'paid_week_off_this_month' => $paidWeekOffThisMonth,
                'unpaid_week_off_this_month' => $unpaidWeekOffThisMonth,
                'total_paid' => $totalPaidLeaves,
                'total_unpaid' => $totalUnpaidLeaves
            ];

            return $user;
        });

        return view('payroll.list', [
            'u_list' => $user_list,
            'post_store' => $req->store,
            'post_mon' => $sal_mon,
            'twd' => $req->twd
        ]);
    }    
     
 
    private function calculateLeaveDays($start_date, $end_date)
    {
        $start = Carbon::parse($start_date);
        $end = Carbon::parse($end_date);
        return $start->diffInDays($end) + 1;
    }

    public function payroll_list()
    {
        //    $store =  DB::table('m_salary')
        //     ->groupBy('store')
        //     ->leftJoin('stores','stores.id','=','m_salary.store')
        //     ->select('stores.store_name','stores.id')
        //     ->get();

        $dept = DB::table('roles')
            ->where('id', '!=', 1)
            ->select('role_dept')
            ->distinct()
            ->get();

        // stores for dropdown....

        $store = DB::table('stores')->get();
        // return $store;
        return view('payroll.payroll_list', ['store' => $store, 'dept' => $dept]);
    }

    /**
     * Store a newly created resource in storage.
     */
     public function store(Request $req)
    {
        $sal_mon = $req->month;
        $sal = explode('-', $sal_mon);
        $year = $sal[0];
        $mon = $sal[1];

        foreach ($req->empId as $key => $value) {
            DB::table('m_salary')->insert([
                'month' => $mon,
                'year' => $year,
                'store' => $req->store,
                'emp_id' => $req->empId[$key],
                'salary' => $req->salary[$key],
                'total_work' => $req->totalWork[$key],
                'present' => $req->present[$key],
                // 'lop' => $req->lop[$key],   
                'paid_leave' => data_get($req->paidLeave, $key, 0),
                'unpaid_leave' => data_get($req->unpaidLeave, $key, 0),
                'incentive' => $req->incentive[$key],
                'ot' => $req->ot[$key],
                'deduct' => $req->deduct[$key],
                'bonus' => $req->bonus[$key],
                'advance' => $req->advance[$key],
                'status' => $req->salary_status[$key],
                'total' => $req->total[$key],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        return redirect()->route('payroll.payroll_list')->with([
            'status' => 'success',
            'message' => 'Salary Added successfully!'
        ]);
    }
    
    /**
     * Display the specified resource.
     */
    public function store_list(Request $req)
    {

        $store = $req->store;



        $store_lt = DB::table('m_salary') // Start by selecting all stores
            ->where('store', $store)
            ->select('m_salary.month')
            ->groupBy('store') // Select the columns from stores table
            ->get(); // Retrieve the result

        // The $store will now contain only those stores that don't have a matching entry in the m_salary table for the given month and year


        $monthMapping = [
            '01' => 'January',
            '02' => 'February',
            '03' => 'March',
            '04' => 'April',
            '05' => 'May',
            '06' => 'June',
            '07' => 'July',
            '08' => 'August',
            '09' => 'September',
            '10' => 'October',
            '11' => 'November',
            '12' => 'December',
        ];

        $monthArray = [];

        // Iterate through each record in the result
        foreach ($store_lt as $item) {
            // Convert the month to a two-digit format
            $monthNumber = str_pad($item->month, 2, '0', STR_PAD_LEFT);

            // If the month number exists in the $monthMapping array, add it to the $monthArray
            if (isset($monthMapping[$monthNumber])) {
                $monthArray[$monthNumber] = $monthMapping[$monthNumber];
            }
        }

        // return $monthArray;

        return response()->json($monthArray);


        //   return view('payroll.list',['store'=>$store]);
    }

    /**
     * Show the form for editing the specified resource.
     */


  public function salary_list(Request $req)
    {
        //  dd($req->all());

        $sal_mon = $req->month;

        $sal = explode('-', $sal_mon);

        $year = $sal[0];
        $mon = $sal[1];

        $sal_list = DB::table('m_salary as ms')->where('ms.month', $mon)
            ->leftJoin('users', 'users.id', '=', 'ms.emp_id')
            ->leftJoin('roles', 'roles.id', '=', 'users.role_id')
            ->when($req->dept == 'Store', function ($join) use ($req) {
                return $join->where('ms.store', $req->store);
            })
            ->when($req->dept != 'Store'  && $req->dept != 'All', function ($join) use ($req) {
                return $join->where('roles.role_dept', '=', $req->dept);
            })
            ->select('ms.*', 'users.name', 'users.emp_code', 'users.dept')
            ->get();

        return view('payroll.payroll_list', ['sal_list' => $sal_list]);
    }


    public function hold_list()
    {
        $hold_list = DB::table('salary_hold')->get();

        return view('payroll.salaryhold_list', ['hold_list' => $hold_list]);
    }

    public function add_hold()
    {

        return view('payroll.add_salary_hold');
    }

    public function getEmployeeName($emp_code)
    {
        $user = DB::table('users')->where('emp_code', $emp_code)->first();

        if ($user) {
            return response()->json(['success' => true, 'name' => $user->name]);
        } else {
            return response()->json(['success' => false]);
        }
    }

    public function store_hold(Request $req)
    {
        DB::table('salary_hold')->insert([
            'emp_code' => $req->emp_code,
            'emp_name' => $req->emp_name,
            'req_type' => $req->req_type,
            'reason' => $req->reason,
            'start_hold_date' => $req->start_hold_date,
            'end_hold_date' => $req->end_hold_date,
            'hold_note' => $req->hold_note,
            'c_by' => auth()->user()->id,
            'status' => 'OnHold',
            'created_at' => now(),
            'updated_at' => now()
        ]);

        return redirect()->route('payroll.salaryhold_list')->with([
            'status' => 'success',
            'message' => 'Salary Hold Successfully'
        ]);
    }

    public function hold_release(Request $req)
    {

        $store_id = DB::table('salary_hold')->where('id',  $req->hold_id)->update([
            'status' => $req->hold_status,
            'updated_at' => now()
        ]);

        return redirect()->route('payroll.salaryhold_list')->with([
            'status' => 'success',
            'message' => 'Hold released successfullly'
        ]);
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
