<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\Attendance;
use App\Models\Leave;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class Attd_cnt extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function daily_attd(Request $req)
    {


        // Fetch all stores for the dropdown
        $stores = DB::table('stores')->get();

        $dept = DB::table('roles')
            ->where('id', '!=', 1)
            ->select('role_dept')
            ->distinct()
            ->get();

        // Initialize the attendance list as an empty collection
        $list = collect();

        // If the form has been submitted (POST request), filter the attendance data
        if ($req->isMethod('post') && $req->has('dept') && $req->has('date')) {


            $list = DB::table('attendance')
                ->leftJoin('users', 'users.id', '=', 'attendance.user_id')
                ->leftJoin('roles', 'roles.id', '=', 'users.role_id')
                ->when($req->dept == 'Store', function ($query) use ($req) {
                    return $query->leftJoin('stores', 'users.store_id', '=', 'stores.id')
                        ->where('users.store_id', $req->stores);
                })
                ->whereDate('attendance.c_on', '=', $req->date)
                ->when($req->dept != 'Store', function ($query) use ($req) {
                    return $query->where('roles.role_dept', '=', $req->dept);
                })
                ->where('users.status', '=', '1')
                ->select(
                    'users.name',
                    'users.emp_code',
                    'roles.role',
                    'attendance.in_time',
                    'attendance.in_location',
                    'attendance.out_time',
                    'attendance.status',
                    'attendance.out_location',
                    'attendance.id as attd_id',
                    'users.id as u_id',
                    $req->dept == 'Store' ? 'stores.id as s_id' : DB::raw('NULL as s_id') // Conditional select for stores.id
                )
                ->get();

        }

        // Return the view with the stores and the filtered (or empty) attendance list
        return view('attendance.daily_list', ['stores' => $stores, 'lists' => $list, 'dept' => $dept]);
    }

    public function del_attn($id)
    {
        DB::table('attd_ot')->where('attd_id', $id)->delete();

        DB::table('attendance')->where('id', $id)->delete();


        return redirect()->back()->with(
            'success',
            'Attendance Deleted Successfully'
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function monthly_attd(Request $req)
    {
        // Fetch all stores for the dropdown
        $stores = DB::table('stores')->get();

        $dept = DB::table('roles')
            ->where('id', '!=', 1)
            ->select('role_dept')
            ->distinct()
            ->get();

        // Initialize the attendance list as an empty collection
        $attendanceCounts = collect();

        // If the form has been submitted (POST request), filter the attendance data
        if ($req->isMethod('post') && $req->has('dept') && $req->has('month')) {
            $mon = $req->month;
            $sl_mon = explode('-', $mon);

            $month = $sl_mon[1];
            $year = $sl_mon[0];
            $attendanceCounts = DB::table('attendance')
                ->whereMonth('attendance.c_on', $month)  // Filter by the month
                ->whereYear('attendance.c_on', $year)    // Filter by the year
                ->join('users', 'attendance.user_id', '=', 'users.id')  // Join users table with attendance based on user_id
                ->leftJoin('roles', 'roles.id', '=', 'users.role_id')
                ->when($req->dept == 'Store', function ($query) use ($req) {
                    return $query->leftJoin('stores', 'users.store_id', '=', 'stores.id')
                        ->where('users.store_id', $req->stores);
                })

                ->when($req->dept != 'Store', function ($query) use ($req) {
                    return $query->where('roles.role_dept', '=', $req->dept);
                })
                // ->where('users.store_id', $req->stores)     // Filter users by store_id (you can pass $store_id from the request)
                ->groupBy('attendance.user_id', 'users.name', 'users.store_id')  // Group by user_id, user name, and store_id
                ->select('attendance.user_id', 'users.name', 'users.emp_code', 'users.store_id', DB::raw('COUNT(*) as attd_count'), 'roles.role')
                ->get();
        }

        // return $req;
        // Return the view with the stores and the filtered (or empty) attendance list
        return view('attendance.monthly_list', ['stores' => $stores, 'lists' => $attendanceCounts, 'dept' => $dept]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function individual_attd()
    {
        $dept = DB::table('roles')
            ->where('id', '!=', 1)
            ->select('role_dept')
            ->distinct()
            ->get();

        $stores = DB::table('stores')->get();

        return view('attendance.individual_list', ['stores' => $stores, 'dept' => $dept]);
    }


    // Add this helper function within your controller or in a dedicated helper file
    function format_hours_and_minutes($decimal_hours)
    {
        if ($decimal_hours == null) {
            return '00:00';
        }
        $hours = floor($decimal_hours);
        $minutes = round(($decimal_hours - $hours) * 60);
        return sprintf('%02d:%02d', $hours, $minutes);
    }

    public function overtime_attd()
    {
        // Get the first day and last day of the current month
        $startOfMonth = now()->startOfMonth();
        $endOfMonth = now()->endOfMonth();

        // -------------------------------
        // Step 1: Get total OT time per user for current month (where cat = 'ot')
        // -------------------------------
        $ot_totals = DB::table('attd_ot as over')
            ->leftJoin('attendance as at', 'at.id', '=', 'over.attd_id')
            ->where('over.cat', 'ot')
            ->whereBetween('at.c_on', [$startOfMonth, $endOfMonth])
            ->select(
                'at.user_id',
                DB::raw('SUM(over.time) as total_ot_time')
            )
            ->groupBy('at.user_id')
            ->pluck('total_ot_time', 'user_id');

        // -------------------------------
        // Step 2: Get weekly OT totals per user
        // -------------------------------
        $weekly_ot_totals = DB::table('attd_ot as over')
            ->leftJoin('attendance as at', 'at.id', '=', 'over.attd_id')
            ->where('over.cat', 'ot')
            ->whereBetween('at.c_on', [$startOfMonth, $endOfMonth])
            ->select(
                'at.user_id',
                'at.c_on',
                DB::raw('SUM(over.time) as weekly_ot_time'),
                DB::raw('YEARWEEK(at.c_on, 1) as year_week') // ISO week format
            )
            ->groupBy('at.user_id', 'year_week')
            ->get()
            ->groupBy('user_id');

        // -------------------------------
        // Step 3: Get all pending OT entries
        // -------------------------------
        // $att_ot = DB::table('attd_ot as over')
        //     ->leftJoin('attendance as at', 'at.id', '=', 'over.attd_id')
        //     ->leftJoin('users', 'users.id', '=', 'at.user_id')
        //     ->leftJoin('roles', 'roles.id', '=', 'users.role_id')
        //     ->leftJoin('stores', 'stores.id', '=', 'users.store_id')
        //     ->select(
        //         'users.id as user_id',
        //         'users.name',
        //         'users.emp_code',
        //         'roles.role',
        //         'stores.store_name',
        //         'over.cat',
        //         'over.time',
        //         'over.id',
        //         'at.c_on',
        //         DB::raw('YEARWEEK(at.c_on, 1) as year_week')
        //     )
        //     ->where('over.status', 'pending')
        //     ->orderByDesc('over.id')
        //     ->get();
        
         $currentMonth = Carbon::now()->month;
        $currentYear = Carbon::now()->year;

        $att_ot = DB::table('attd_ot as over')
            ->leftJoin('attendance as at', 'at.id', '=', 'over.attd_id')
            ->leftJoin('users', 'users.id', '=', 'at.user_id')
            ->leftJoin('roles', 'roles.id', '=', 'users.role_id')
            ->leftJoin('stores', 'stores.id', '=', 'users.store_id')
            ->select(
                'users.id as user_id',
                'users.name',
                'users.emp_code',
                'roles.role',
                'stores.store_name',
                'over.cat',
                'over.time',
                'over.id',
                'at.c_on',
                'at.in_time',
                'at.out_time',
                DB::raw('YEARWEEK(at.c_on, 1) as year_week')
            )
            ->where('over.status', 'pending')
            ->whereMonth('at.c_on', $currentMonth)
            ->whereYear('at.c_on', $currentYear)
            ->orderByDesc('over.id')
            ->get();


        // -------------------------------
        // Step 4: Add extra data to each pending OT entry
        // -------------------------------
        foreach ($att_ot as $entry) {
            $userId = $entry->user_id;
            $entryWeek = $entry->year_week;

            // Get total OT time for this user in the current month
            $totalOtTime = (float) ($ot_totals[$userId] ?? 0);
            $entryTime = (float) $entry->time;

            // Calculate weekly OT for the specific week of this entry
            $weeklyOtTime = 0;
            if (isset($weekly_ot_totals[$userId])) {
                foreach ($weekly_ot_totals[$userId] as $weekData) {
                    if ($weekData->year_week == $entryWeek) {
                        $weeklyOtTime = (float) $weekData->weekly_ot_time;
                        break;
                    }
                }
            }

            // Add calculated & formatted data to the entry object
            $entry->total_ot_time = $totalOtTime;
            $entry->weekly_ot_time = $weeklyOtTime;
            $entry->formatted_total_ot = $this->format_hours_and_minutes($totalOtTime);
            $entry->formatted_weekly_ot = $this->format_hours_and_minutes($weeklyOtTime);
            $entry->formatted_ot_time = $this->format_hours_and_minutes($entryTime);

            // Flag if total OT exceeds 32 hours for the month
            $entry->ot_exceeded = $totalOtTime > 32;

            // Flag if weekly OT exceeds 8 hours and calculate excess
            $entry->weekly_ot_exceeded = $weeklyOtTime > 8;
            $entry->weekly_ot_excess = $weeklyOtTime > 8 ? $weeklyOtTime - 8 : 0;
            $entry->formatted_weekly_excess = $this->format_hours_and_minutes($entry->weekly_ot_excess);
        }

        return view('attendance.overtime_list', ['ot_lists' => $att_ot]);
    }

    public function get_store_per(Request $req)
    {
        $emp_list = DB::table('users')
            ->when($req->dept == 'Store', function ($query) use ($req) {
                return $query->where('store_id', $req->store_id);  // If dept is Store, filter by store_id
            }, function ($query) use ($req) {
                $role_list = DB::table('roles')->where('role_dept', $req->dept)->pluck('id');  // If dept is not Store, get role IDs
                return $query->whereIn('role_id', $role_list);  // Filter users by role IDs
            })
            ->where('users.status', 1)
            ->select('id', 'name')  // Select the required columns
            ->get();

        // dd($emp_list);

        return response()->json($emp_list, 200);
    }


    public function ot_approve(Request $req)
    {

        $prime_id = $req->input('ot_id');
        $ot_amount = $req->input('ot_amount');

        $updated = DB::table('attd_ot')
            ->where('id', $prime_id)
            ->update(['status' => 'approved', 'amount' => $ot_amount]);

        if ($updated) {
            return redirect()->route('attendance.overtime')->with(['success' => true, 'message' => 'OT or Late approved!']);
        }

        return redirect()->route('attendance.overtime')->with('attendance.overtime', ['success' => false, 'message' => 'User not found or already approved!']);
    }



    public function get_ind_attd(Request $req)
    {
        // Fetch departments
        $dept = DB::table('roles')
            ->where('id', '!=', 1)
            ->select('role_dept')
            ->distinct()
            ->get();

        // Fetch stores
        $stores = DB::table('stores')
            ->where('id', '!=', 1)
            ->select('id', 'store_name')
            ->where('status', 1)
            ->get();

        // Initialize variables to avoid undefined variable errors
        $results = [];
        $selected_dept = $req->input('dept', null);
        $selected_store = $req->input('stores', null);
        $selected_employee = $req->input('employee', null);
        $selected_month = $req->input('month', null);

        // if ($req->isMethod('post') && $req->filled('dept') && $req->filled('employee') && $req->filled('month')) {
        //     $mon = $req->month;
        //     $sl_mon = explode('-', $mon);
        //     $month = $sl_mon[1];
        //     $year = $sl_mon[0];

        //     // Get all dates in the selected month
        //     $startDate = \Carbon\Carbon::create($year, $month, 1)->startOfMonth();
        //     $endDate = $startDate->copy()->endOfMonth();
        //     $dates = [];
        //     for ($date = $startDate; $date->lte($endDate); $date->addDay()) {
        //         $dates[] = $date->format('Y-m-d');
        //     }

        //     // Fetch attendance records
        //     $attendance = DB::table('attendance')
        //         ->join('users', 'attendance.user_id', '=', 'users.id')
        //         ->leftJoin('roles', 'roles.id', '=', 'users.role_id')
        //         ->where('attendance.user_id', $req->employee)
        //         ->whereMonth('attendance.c_on', $month)
        //         ->whereYear('attendance.c_on', $year)
        //         ->select(
        //             'attendance.in_location',
        //             'attendance.out_location',
        //             'attendance.in_time',
        //             'attendance.out_time',
        //             DB::raw('DATE_FORMAT(attendance.c_on, "%d-%m-%Y") as date')
        //         )
        //         ->orderBy('attendance.c_on', 'DESC')
        //         ->get()
        //         ->keyBy('date');

        //     // Fetch approved leaves properly (group where conditions)
        //     $leaves = DB::table('leaves')
        //         ->where('user_id', $req->employee)
        //         ->where('status', 'approved')
        //         ->where(function ($q) use ($startDate, $endDate) {
        //             $q->whereBetween('start_date', [$startDate->format('Y-m-d'), $endDate->format('Y-m-d')])
        //                 ->orWhereBetween('end_date', [$startDate->format('Y-m-d'), $endDate->format('Y-m-d')])
        //                 ->orWhere(function ($q2) use ($startDate, $endDate) {
        //                     $q2->where('start_date', '<=', $startDate->format('Y-m-d'))
        //                         ->where('end_date', '>=', $endDate->format('Y-m-d'));
        //                 });
        //         })
        //         ->select('start_date', 'end_date', 'request_type')
        //         ->get();

        //     // Generate leave dates
        //     $leaveDates = [];
        //     foreach ($leaves as $leave) {
        //         $start = \Carbon\Carbon::parse($leave->start_date);
        //         $end = \Carbon\Carbon::parse($leave->end_date);
        //         for ($date = $start->copy(); $date->lte($end); $date->addDay()) {
        //             if ($date->month == $month && $date->year == $year) {
        //                 $leaveDates[$date->format('d-m-Y')] = $leave->request_type;
        //             }
        //         }
        //     }

        //     // Build results for each day in the month
        //     foreach ($dates as $date) {
        //         $formattedDate = \Carbon\Carbon::parse($date)->format('d-m-Y');
        //         $result = [
        //             'date' => $formattedDate,
        //             'type' => 'Absent',
        //             'leave_type' => '-',
        //             'in_location' => '-',
        //             'out_location' => '-',
        //             'in_time' => '-',
        //             'out_time' => '-'
        //         ];

        //         // Priority 1: Attendance
        //         if (isset($attendance[$formattedDate])) {
        //             $result['type'] = 'Present';
        //             $result['in_location'] = $attendance[$formattedDate]->in_location ?? '-';
        //             $result['out_location'] = $attendance[$formattedDate]->out_location ?? '-';
        //             $result['in_time'] = $attendance[$formattedDate]->in_time ?? '-';
        //             $result['out_time'] = $attendance[$formattedDate]->out_time ?? '-';
        //         }
        //         // Priority 2: Leave
        //         elseif (isset($leaveDates[$formattedDate])) {
        //             $result['type'] = 'Leave';
        //             $result['leave_type'] = $leaveDates[$formattedDate];
        //         }

        //         $results[] = $result;
        //     }
        // }

        if ($req->isMethod('post') && $req->filled('dept') && $req->filled('employee') && $req->filled('month')) {
            $mon = $req->month;
            [$year, $month] = explode('-', $mon);
            $month = (int) $month;
            $year = (int) $year;

            $startDate = Carbon::create($year, $month, 1)->startOfMonth();
            $endDate = $startDate->copy()->endOfMonth();

            // build array of dates in Y-m-d for iteration
            $dates = [];
            for ($d = $startDate->copy(); $d->lte($endDate); $d->addDay()) {
                $dates[] = $d->format('Y-m-d');
            }

            // Attendance keyed by d-m-Y (same format used later)
            $attendance = DB::table('attendance')
                ->join('users', 'attendance.user_id', '=', 'users.id')
                ->leftJoin('roles', 'roles.id', '=', 'users.role_id')
                ->where('attendance.user_id', $req->employee)
                // narrow attendance by exact month-range (safer than whereMonth/whereYear)
                ->whereBetween(DB::raw('DATE(attendance.c_on)'), [$startDate->format('Y-m-d'), $endDate->format('Y-m-d')])
                ->select(
                    'attendance.in_location',
                    'attendance.out_location',
                    'attendance.in_time',
                    'attendance.out_time',
                    DB::raw('DATE_FORMAT(attendance.c_on, "%d-%m-%Y") as date')
                )
                ->orderBy('attendance.c_on', 'DESC')
                ->get()
                ->keyBy('date');

            // --- Fetch leaves that overlap the selected month ---
            // Simple, correct overlap test: start_date <= month_end AND end_date >= month_start
            $leaves = DB::table('leaves')
                ->where('user_id', $req->employee)
                ->where(function ($q) {
                    // try common "approved" columns/values - adjust/remove if your DB uses a single column
                    $q->where('status', 'approved')
                        ->orWhere('request_status', 'approved')
                        ->orWhere('status', 1);
                })
                ->where('start_date', '<=', $endDate->format('Y-m-d'))
                ->where('end_date', '>=', $startDate->format('Y-m-d'))
                ->select('start_date', 'end_date', 'request_type')
                ->get();

            // Build map of leave dates in format d-m-Y => request_type
            $leaveDates = [];
            foreach ($leaves as $leave) {
                $s = Carbon::parse($leave->start_date);
                $e = Carbon::parse($leave->end_date);
                // use copy() to avoid mutating $s
                for ($d = $s->copy(); $d->lte($e); $d->addDay()) {
                    if ($d->month == $month && $d->year == $year) {
                        $leaveDates[$d->format('d-m-Y')] = $leave->request_type ?? 'Leave';
                    }
                }
            }

            // Optional debug (temporary): write to log so you can inspect results in storage/logs/laravel.log
            Log::debug('Attendance keys', array_keys($attendance->toArray()));
            Log::debug('Leave rows', $leaves->toArray());
            Log::debug('LeaveDates map', $leaveDates);

            // Build final results (attendance takes precedence over leave)
            foreach ($dates as $date) {
                $formattedDate = Carbon::parse($date)->format('d-m-Y');
                $result = [
                    'date' => $formattedDate,
                    'type' => 'Absent',
                    'leave_type' => '-',
                    'in_location' => '-',
                    'out_location' => '-',
                    'in_time' => '-',
                    'out_time' => '-'
                ];

                if (isset($attendance[$formattedDate])) {
                    $a = $attendance[$formattedDate];
                    $result['type'] = 'Present';
                    $result['in_location'] = $a->in_location ?? '-';
                    $result['out_location'] = $a->out_location ?? '-';
                    $result['in_time'] = $a->in_time ?? '-';
                    $result['out_time'] = $a->out_time ?? '-';
                } elseif (isset($leaveDates[$formattedDate])) {
                    $result['type'] = 'Leave';
                    $result['leave_type'] = $leaveDates[$formattedDate];
                }

                $results[] = $result;
            }
        }

        // If the request is AJAX, return JSON
        if ($req->ajax()) {
            return response()->json($results);
        }

        // Return the view
        return view('attendance.individual_list', [
            'dept' => $dept,
            'stores' => $stores,
            'att_data' => $results,
            'selected_dept' => $selected_dept,
            'selected_store' => $selected_store,
            'selected_employee' => $selected_employee,
            'selected_month' => $selected_month
        ]);
    }

    public function getStorePersonnel(Request $request)
    {
        $query = DB::table('users')
            ->leftJoin('roles', 'users.role_id', '=', 'roles.id')
            ->select('users.id', 'users.name')
            ->where('users.status', 1);

        if ($request->filled('dept')) {
            $query->where('roles.role_dept', $request->dept);
        }

        if ($request->filled('store_id') && $request->dept === 'Store') {
            $query->where('users.store_id', $request->store_id);
        }

        $employees = $query->get();

        return response()->json($employees);
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

    // public function ot_report(Request $req)
    // {

    //     $dept = DB::table('roles')
    //         ->where('id', '!=', 1)
    //         ->select('role_dept')
    //         ->distinct()
    //         ->get();

    //     $stores = DB::table('stores')
    //         ->where('id', '!=', 1)
    //         ->select('id', 'store_name')
    //         ->where('status', 1)->get();

    //     $att_ot = [];

    //     if ($req->isMethod('post')) {


    //         $att_ot = DB::table('attd_ot as over')
    //             ->leftJoin('attendance as at', 'at.id', '=', 'over.attd_id')
    //             ->leftJoin('users', 'users.id', '=', 'at.user_id')
    //             ->leftJoin('roles', 'roles.id', '=', 'users.role_id')
    //             ->leftJoin('stores', 'stores.id', '=', 'users.store_id')
    //             ->select(
    //                 'users.id as user_id',
    //                 'users.name',
    //                 'users.emp_code',
    //                 'roles.role',
    //                 'stores.store_name',
    //                 'over.cat',
    //                 'over.time',
    //                 'over.id',
    //                 'at.c_on',
    //                 DB::raw('YEARWEEK(at.c_on, 1) as year_week')
    //             )
    //             ->where('over.status', 'pending')
    //             ->orderByDesc('over.id')
    //             ->get();
    //     }

    //     return view('attendance.otlate_report', ['dept' => $dept, 'stores' =>  $stores, 'att_ot' => $att_ot]);
    // }

    public function ot_report(Request $req)
    {
        // Fetch departments
        $dept = DB::table('roles')
            ->where('id', '!=', 1)
            ->select('role_dept')
            ->distinct()
            ->get();

        // Fetch stores
        $stores = DB::table('stores')
            ->where('id', '!=', 1)
            ->select('id', 'store_name')
            ->where('status', 1)
            ->get();

        // Initialize att_ot as an empty array
        $att_ot = [];

        if ($req->isMethod('post') && $req->filled('dept') && $req->filled('employee') && $req->filled('month')) {
            // Build the query for att_ot
            $query = DB::table('attd_ot as over')
                ->leftJoin('attendance as at', 'at.id', '=', 'over.attd_id')
                ->leftJoin('users', 'users.id', '=', 'at.user_id')
                ->leftJoin('roles', 'roles.id', '=', 'users.role_id')
                ->leftJoin('stores', 'stores.id', '=', 'users.store_id')
                ->where('users.status', 1)
                ->select(
                    'users.id as user_id',
                    'users.name',
                    'users.emp_code',
                    'roles.role',
                    'stores.store_name',
                    'over.cat',
                    'over.time',
                    'over.id',
                    'at.c_on',
                    DB::raw('YEARWEEK(at.c_on, 1) as year_week')
                )
                ->orderBy('over.created_at', 'ASC')
                ->where('over.status', 'pending');

            // Apply mandatory filters
            // $query->where('roles.role_dept', $req->dept)
            //     ->where('users.id', $req->employee)
            //     ->whereRaw('DATE_FORMAT(at.c_on, "%Y-%m") = ?', [$req->month]);

            $query->where('roles.role_dept', $req->dept)
                ->whereRaw('DATE_FORMAT(at.c_on, "%Y-%m") = ?', [$req->month]);

            if ($req->employee !== 'all') {
                $query->where('users.id', $req->employee);
            }


            // Apply store filter only if department is 'Store' and store is provided
            if ($req->dept === 'Store' && $req->filled('stores')) {
                $query->where('users.store_id', $req->stores);
            }

            // Execute the query
            $att_ot = $query->orderByDesc('over.id')->get();
        }

        return view('attendance.otlate_report', [
            'dept' => $dept,
            'stores' => $stores,
            'att_ot' => $att_ot,
            'selected_dept' => $req->dept,
            'selected_store' => $req->stores,
            'selected_employee' => $req->employee,
            'selected_month' => $req->month
        ]);
    }
    
      public function manager_attendance_report(Request $req)
    {
        $employees = User::where('store_id', auth()->user()->store_id)
            ->where('status', 1)->get();

        if ($req->isMethod('post')) {

            $month = $req->month;                  // 2025-01
            $employee_id = $req->employee_id;

            // First day & last day of month
            $start = Carbon::parse($month . '-01');
            $end = Carbon::parse($month . '-01')->endOfMonth();

            // Get employees (all or selected)
            $empQuery = User::where('store_id', auth()->user()->store_id)
                ->where('status', 1);

            if ($employee_id != 'all') {
                $empQuery->where('id', $employee_id);
            }

            $emp_data = $empQuery->get();

            // Prepare full monthly data
            $final_data = [];

            foreach ($emp_data as $emp) {
                $days = [];

                for ($date = $start->copy(); $date <= $end; $date->addDay()) {

                    $att = Attendance::where('user_id', $emp->id)
                        ->whereDate('c_on', $date->format('Y-m-d'))
                        ->first();

                    $leave = Leave::where('user_id', $emp->id)
                        ->whereDate('start_date', '<=', $date)
                        ->whereDate('end_date', '>=', $date)
                        ->first();

                    if ($att) {
                        $status = "Present";
                    } elseif ($leave) {
                        $status = "On Leave";
                    } else {
                        $status = "Absent";
                    }

                    $days[] = [
                        'name' => $emp->name,
                        'date' => $date->format('d-m-Y'),
                        'status' => $status,
                        'leave_type' => $leave->request_type ?? '',
                        'in_location' => $att->in_location ?? '',
                        'in_time' => $att->in_time ?? '',
                        'out_location' => $att->out_location ?? '',
                        'out_time' => $att->out_time ?? '',
                    ];
                }

                $final_data[] = $days;
            }

            return view('attendance.ind_attd_report', [
                'employees' => $employees,
                'emp_data' => $final_data,
                'selected_month' => $month,
                'selected_employee' => $employee_id
            ]);
        }

        return view('attendance.ind_attd_report', [
            'employees' => $employees,
            'emp_data' => [],
        ]);
    }
}
