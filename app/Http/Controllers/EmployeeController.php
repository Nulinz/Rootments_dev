<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Role;
use App\Models\Task;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\trait\common;

class EmployeeController extends Controller
{
    use common;
    /**
     * Display a listing of the resource.
     */

    public function index()
    {
        $user = Auth::user();
        if ($user->role_id == 12) {
            $query = DB::table('users')
                ->leftJoin('stores', 'users.store_id', '=', 'stores.id')
                ->leftJoin('roles', 'users.role_id', '=', 'roles.id')
                ->leftJoin('resignations as rs', 'users.id', '=', 'rs.emp_id')
                ->leftJoin('resign_list as rl', 'rs.id', '=', 'rl.res_id')
                ->where('users.status', 1)
                ->where('users.store_id', $user->store_id)
                ->select(
                    'users.id',
                    'users.name',
                    'users.emp_code',
                    'users.contact_no',
                    'users.email',
                    'stores.store_name',
                    'roles.role',
                    'roles.role_dept'
                )
                ->whereNotIn('users.id', function ($subquery) {
                    $subquery->select('rs.emp_id')
                        ->from('resign_list as rl')
                        ->join('resignations as rs', 'rs.id', '=', 'rl.res_id')
                        ->where('rl.formality', 'Exit Completed');
                })
                ->distinct();
            $employees = $query->get();
        } elseif ($user->role_id == 11) {
            $query = DB::table('users')
                ->leftJoin('stores', 'users.store_id', '=', 'stores.id')
                ->leftJoin('roles', 'users.role_id', '=', 'roles.id')
                ->leftJoin('resignations as rs', 'users.id', '=', 'rs.emp_id')
                ->leftJoin('resign_list as rl', 'rs.id', '=', 'rl.res_id')
                ->where('users.status', 1)
                ->join('cluster_store', 'users.store_id', '=', 'cluster_store.store_id') // must be inner join to restrict to cluster stores
                ->join('m_cluster', 'cluster_store.cluster_id', '=', 'm_cluster.id')
                ->where('m_cluster.cl_name', $user->id)
                ->select(
                    'users.id',
                    'users.name',
                    'users.emp_code',
                    'users.contact_no',
                    'users.email',
                    'stores.store_name',
                    'roles.role',
                    'roles.role_dept'
                )
                ->whereNotIn('users.id', function ($subquery) {
                    $subquery->select('rs.emp_id')
                        ->from('resign_list as rl')
                        ->join('resignations as rs', 'rs.id', '=', 'rl.res_id')
                        ->where('rl.formality', 'Exit Completed');
                })
                ->distinct();
            $employees = $query->get();
        } elseif ($user->dept == 'Admin' || $user->dept == 'HR' ||  $user->role_id == 6) {
            $query = DB::table('users')
                ->leftJoin('stores', 'users.store_id', '=', 'stores.id')
                ->leftJoin('roles', 'users.role_id', '=', 'roles.id')
                ->leftJoin('resignations as rs', 'users.id', '=', 'rs.emp_id')
                ->leftJoin('resign_list as rl', 'rs.id', '=', 'rl.res_id')
                ->where('users.status', 1)
                ->select(
                    'users.id',
                    'users.name',
                    'users.emp_code',
                    'users.contact_no',
                    'users.email',
                    'stores.store_name',
                    'roles.role',
                    'roles.role_dept'
                )
                ->whereNotIn('users.id', function ($subquery) {
                    $subquery->select('rs.emp_id')
                        ->from('resign_list as rl')
                        ->join('resignations as rs', 'rs.id', '=', 'rl.res_id')
                        ->where('rl.formality', 'Exit Completed');
                })
                ->distinct()
                ->orderByRaw("CAST(SUBSTRING(users.emp_code, 4) AS UNSIGNED) ASC");

            $employees = $query->get();
        }

        return view('employee.list', ['employees' => $employees]);
    }

    public function term_list()
    {
        $user = Auth::user();

        $query = DB::table('users')
            ->leftJoin('stores', 'users.store_id', '=', 'stores.id')
            ->leftJoin('roles', 'users.role_id', '=', 'roles.id')
            ->leftJoin('resignations as rs', 'users.id', '=', 'rs.emp_id')
            ->where('users.status', 2)
            ->select(
                'users.id',
                'users.name',
                'users.emp_code',
                'users.contact_no',
                'users.email',
                'users.ter_date',
                'stores.store_name',
                'roles.role',
                'roles.role_dept'
            )
            ->whereNotIn('users.id', function ($subquery) {
                $subquery->select('emp_id')->from('resignations');
            })
            ->orWhere('rs.status', 'Rejected');

        $employees = $query->get();

        return view('employee.Termination_list', ['employees' => $employees]);
    }

    public function resignation()
    {
        $res_list = DB::table('resignations as res')
            ->leftjoin('resign_list', 'res.id', '=', 'resign_list.res_id')
            ->leftJoin('users', 'res.emp_id', '=', 'users.id')
            ->leftJoin('stores', 'users.store_id', '=', 'stores.id')
            ->leftJoin('roles', 'users.role_id', '=', 'roles.id')
            ->select(
                'res.*',
                'stores.store_name',
                'users.emp_code',
                'users.id as user_id',
                'users.contact_no',
                'users.email',
                'roles.role',
                'roles.role_dept',
                'users.pre_start_date',
                'users.pre_start_date',
            )
            ->where('resign_list.formality', 'Exit Completed')
            ->get();

        return view('employee.resignation-list', ['res_list' => $res_list]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $max_id = DB::table('users')->max('id');

        $emp_no = 'Emp' . str_pad($max_id + 1, 2, '0', STR_PAD_LEFT);

        return view('employee.add', ['emp_no' => $emp_no]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)

    {
        $request->validate([
            'contact_no' => 'required|unique:users,contact_no',
            'email' => 'required|unique:users,email',
            'aadhar_no' => 'required|unique:users,aadhar_no',

        ]);



        $user = new User();
        $user->name = $request->name;
        $user->contact_no = $request->contact_no;
        $user->guardian_no = $request->guardian_contact_no;
        $user->email = $request->email;
        $user->password = Hash::make($request->password);
        $user->emp_code = $request->emp_code;
        $user->dob = $request->dob ?? null;
        $user->gender = $request->gender;
        $user->marital_status = $request->marital_status ?? null;
        $user->aadhar_no = $request->aadhar_no;
        $user->address = $request->address;
        $user->district = $request->district;
        $user->state = $request->state;
        $user->pincode = $request->pincode;

        if ($request->hasFile('profile_image')) {
            $file = $request->file('profile_image');
            $name = date('y') . '-' . Str::upper(Str::random(8)) . '.' . $file->getClientOriginalExtension();
            $path = 'assets/images/Employee/';
            $file->move($path, $name);

            $user->profile_image = $path . $name;
        }
        $user->save();

        $lastInsertedId = $user->id;

        return redirect()->route('jobdetails', ['id' => $lastInsertedId])->with([
            'status' => 'success',
            'message' => 'Basic Details Added successfully!'
        ]);
    }

    public function jobindex($id)
    {
        $store = DB::table('stores')->get();

        $dept = DB::table('roles')
            ->where('id', '!=', 1)
            ->select('role_dept')
            ->distinct()
            ->get();

        return view('employee.jobdetail', ['store' => $store, 'dept' => $dept, 'id' => $id]);
    }

    public function getrole(Request $request)
    {

        $request->validate([
            'dept_id' => 'required',
        ]);

        $roles = DB::table('roles')
            ->where('role_dept', $request->dept_id)
            ->select('id', 'role')
            ->get();

        // dd($roles);

        return response()->json($roles);
    }


    public function jobdetailstore(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $user->qulification = $request->qulification;
        $user->job_tittle = $request->job_tittle;
        $user->job_type = $request->job_type;
        $user->exprience = $request->exprience;
        $user->pre_start_date = $request->pre_start_date;
        $user->pro_skill = $request->pro_skill;
        $user->st_time = $request->intime;
        $user->end_time = $request->outtime;

        if ($request->hasFile('aadhar_img')) {
            $file = $request->file('aadhar_img');
            $name = date('y') . '-' . Str::upper(Str::random(8)) . '.' . $file->getClientOriginalExtension();
            $path = 'assets/images/Employee/';
            $file->move($path, $name);

            $user->aadhar_img = $path . $name;
        }

        if ($request->hasFile('agreement')) {
            $file = $request->file('agreement');
            $name = date('y') . '-' . Str::upper(Str::random(8)) . '.' . $file->getClientOriginalExtension();
            $path = 'assets/images/Employee/';
            $file->move($path, $name);

            $user->agreement = $path . $name;
        }

        $user->dept = $request->dept;
        $user->role_id = $request->role_id;
        $user->store_id = $request->store_id;
        $user->save();

        return redirect()->route('bankdetails', ['id' => $id])->with([
            'status' => 'success',
            'message' => 'Employee Job Details Added successfully!'
        ]);
    }

    public function bankindex($id)
    {
        return view('employee.bankdetails', ['id' => $id]);
    }

    public function bankdetailstore(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $user->bank_name = $request->bank_name;
        $user->bank_holder_name = $request->bank_holder_name;
        $user->ac_no = $request->ac_no;
        $user->ifcs_code = $request->ifcs_code;
        $user->acount_type = $request->acount_type;
        $user->bank_branch = $request->bank_branch;
        $user->base_salary = $request->base_salary;
        $user->house_rent_allowance = $request->house_rent_allowance;
        $user->conveyance = $request->conveyance;
        $user->medical = $request->medical;
        $user->speical = $request->speical;
        $user->other = $request->other;
        $user->pro_fund = $request->pro_fund;
        $user->emp_state_insurance = $request->emp_state_insurance;
        $user->profession_tax = $request->profession_tax;
        $user->income_tax = $request->income_tax;
        $user->performance_bonus = $request->performance_bonus;
        $user->net_salary = $request->net_salary;
        $user->save();

        return redirect()->route('employee.index', ['status' => 1])->with([
            'status' => 'success',
            'message' => 'Employee Bank Details Added successfully!'
        ]);
    }
    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $users = DB::table('users')
            ->leftJoin('stores', 'users.store_id', '=', 'stores.id')
            ->leftJoin('roles', 'users.role_id', '=', 'roles.id')
            ->where('users.id', $id)
            ->select('users.id', 'users.profile_image', 'users.name', 'users.emp_code', 'users.contact_no', 'users.guardian_no', 'users.email', 'stores.store_name', 'roles.role', 'roles.role_dept', 'users.status as u_status')
            ->first();

        return view('employee.profile', ['users' => $users]);
    }

    public function empdetails($id)
    {
        $users = DB::table('users')
            ->leftJoin('stores', 'users.store_id', '=', 'stores.id')
            ->leftJoin('roles', 'users.role_id', '=', 'roles.id')
            ->where('users.id', $id)
            ->select('users.*', 'stores.store_name', 'roles.role', 'roles.role_dept')
            ->first();

        return view('employee.empdetails', ['users' => $users]);
    }

    public function salary($id)
    {
        return view('employee.salary');
    }

    public function remarks($id)
    {
        return view('employee.remark');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $employee = DB::table('users')->where('id', $id)->first();

        return view('employee.edit', ['employee' => $employee]);
    }

    public function jobedit(string $id)
    {
        $employee = DB::table('users')->where('id', $id)->first();

        $store = DB::table('stores')->get();

        $dept = DB::table('roles')
            ->where('id', '!=', 1)
            ->select('role_dept')
            ->distinct()
            ->get();

        $assgin = DB::table('roles')
            ->where('id', '!=', 1)
            ->get();

        return view('employee.jobdetailsedit', ['employee' => $employee, 'store' => $store, 'dept' => $dept, 'assgin' => $assgin]);
    }

    public function bankedit(string $id)
    {
        $employee = DB::table('users')->where('id', $id)->first();

        return view('employee.bankdetailsedit', ['employee' => $employee]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $user = User::find($id);
        $user->name = $request->name;
        $user->contact_no = $request->contact_no;
        $user->guardian_no = $request->guardian_no;
        $user->email = $request->email;
        $user->emp_code = $request->emp_code;
        $user->dob = $request->dob;
        $user->gender = $request->gender;
        $user->marital_status = $request->marital_status;
        $user->aadhar_no = $request->aadhar_no;
        $user->address = $request->address;
        $user->district = $request->district;
        $user->state = $request->state;
        $user->pincode = $request->pincode;

        if ($request->hasFile('profile_image')) {
            $file = $request->file('profile_image');
            $name = date('y') . '-' . Str::upper(Str::random(8)) . '.' . $file->getClientOriginalExtension();
            $path = 'assets/images/Employee/';
            $file->move($path, $name);

            $user->profile_image = $path . $name;
        }
        $user->save();

        return redirect()->route('employee.view', ['id' => $id])->with([
            'status' => 'success',
            'message' => 'Basic Details Added successfully!'
        ]);
    }
    public function jobdetailupdate(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $user->qulification = $request->qulification;
        $user->job_tittle = $request->job_tittle;
        $user->job_type = $request->job_type;
        $user->exprience = $request->exprience;
        $user->pre_start_date = $request->pre_start_date;
        $user->pro_skill = $request->pro_skill;
        $user->st_time = $request->intime;
        $user->end_time = $request->outtime;
        if ($request->hasFile('aadhar_img')) {
            $file = $request->file('aadhar_img');
            $name = date('y') . '-' . Str::upper(Str::random(8)) . '.' . $file->getClientOriginalExtension();
            $path = 'assets/images/Employee/';
            $file->move($path, $name);

            $user->aadhar_img = $path . $name;
        }

        if ($request->hasFile('agreement')) {
            $file = $request->file('agreement');
            $name = date('y') . '-' . Str::upper(Str::random(8)) . '.' . $file->getClientOriginalExtension();
            $path = 'assets/images/Employee/';
            $file->move($path, $name);

            $user->agreement = $path . $name;
        }


        $user->dept = $request->dept;
        $user->role_id = $request->role_id;
        $user->store_id = $request->store_id;
        $user->save();

        return redirect()->route('employee.view', ['id' => $id])->with([
            'status' => 'success',
            'message' => 'Employee Job Updated Added successfully!'
        ]);
    }
    public function bankdetailupdate(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $user->bank_name = $request->bank_name;
        $user->bank_holder_name = $request->bank_holder_name;
        $user->ac_no = $request->ac_no;
        $user->ifcs_code = $request->ifcs_code;
        $user->acount_type = $request->acount_type;
        $user->bank_branch = $request->bank_branch;
        $user->base_salary = $request->base_salary;
        $user->house_rent_allowance = $request->house_rent_allowance;
        $user->conveyance = $request->conveyance;
        $user->medical = $request->medical;
        $user->speical = $request->speical;
        $user->other = $request->other;
        $user->pro_fund = $request->pro_fund;
        $user->emp_state_insurance = $request->emp_state_insurance;
        $user->profession_tax = $request->profession_tax;
        $user->income_tax = $request->income_tax;
        $user->performance_bonus = $request->performance_bonus;
        $user->net_salary = $request->net_salary;
        $user->save();

        return redirect()->route('employee.view', ['id' => $id])->with([
            'status' => 'success',
            'message' => 'Employee Bank Details Added successfully!'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function emp_active(Request $req)
    {
        $up = DB::table('users')->where('id', $req->emp_id)->update([
            'status' => $req->ter_remarks ? 2 : 1,
            'ter_remarks' => $req->ter_remarks ?? null,
            'ter_date' => now()
        ]);

        if ($up) {
            return back()->with(['status' => 'success', 'message' => 'Employee Activated Successfully']);
        } else {
            return back()->with(['status' => 'Failure', 'message' => 'Employee Activation Failed']);
        }
    }
    public function emp_leave_report(Request $req)
    {
        $store_id = auth()->user()->store_id;

        $dept = DB::table('roles')
            ->where('id', '!=', 1)
            ->select('role_dept')
            ->distinct()
            ->get();

        $stores = DB::table('stores')->get();

        $emp_leaves = DB::table('users')
            ->where('store_id', $store_id)
            ->where('status', 1)
            ->select('id', 'name', 'emp_code')
            ->get();


        if ($req->isMethod('post')) {

            $start_date = $req->startdate;
            $end_date = $req->enddate;
            $user_id = $req->emp_id;

            $query = DB::table('leaves')
                ->leftJoin('users as us', 'us.id', '=', 'leaves.user_id')
                ->select('leaves.*', 'us.name', 'us.emp_code')
                ->whereIn('leaves.status', ['Approved', 'Rejected'])
                ->whereDate('start_date', '<=', $end_date)
                ->whereDate('end_date', '>=', $start_date);

            // 🔥 If ALL employees selected
            if ($user_id !== 'all') {
                $query->where('leaves.user_id', $user_id);
            } else {
                // show all users in this store only
                $query->whereIn('leaves.user_id', $emp_leaves->pluck('id'));
            }

            $leaves = $query->get();

            return view('employee.leave_report', [
                'leaves' => $leaves,
                'employee' => $emp_leaves,
                'dept' => $dept,
                'stores' => $stores,
                'selected_emp' => $user_id,  // Keep selection
            ]);
        }

        return view('employee.leave_report', [
            'leaves' => [],
            'employee' => $emp_leaves
        ]);
    }

    public function emp_dahboard()
    {
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
            // ->whereDate('created_at', now()->subDay())   
            ->selectRaw('
                 SUM(b_ftd) as b_ftd,
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

        $year = now()->year;
        $month = now()->month;


        $annualSickCount = DB::table('leaves')
            ->where('user_id', auth()->user()->id)
            ->whereYear('created_at', $year)
            ->whereIn('request_type', ['Annual Leave', 'Sick Leave'])
            ->where('request_status', 'Approved')
            ->selectRaw('SUM(DATEDIFF(end_date, start_date) + 1) as total_days')
            ->value('total_days') ?? 0; // fallback to 0 if no records


        $weekOffDays = DB::table('leaves')
            ->where('user_id', auth()->user()->id)
            ->whereYear('created_at', $year)
            ->whereMonth('created_at', $month)
            ->where('request_type', 'Week Off')
            ->where('request_status', 'Approved')
            ->selectRaw('SUM(DATEDIFF(end_date, start_date) + 1) as total_days')
            ->value('total_days') ?? 0; // default to 0 if null


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

        return view('employee.emp_dahboard', ['emp_sale_record' => $emp_sale_record, 'emp_perf_record' => $emp_perf_record, 'annualSickCount' => $annualSickCount, 'weekOffCount' => $weekOffDays, 'tasks_state' => $tasks_state]);
    }

    public function emp_target(Request $req)
    {

        $store_id = auth()->user()->store_id;
        $UserId = auth()->id();

        $currentMonth = now()->format('Y-m');

        // Get employees who already have targets for current month
        $targetedEmpIds = DB::table('employee_target')
            ->where('store_id', $store_id)
            ->where('month', $currentMonth)
            ->pluck('emp_id')
            ->toArray();

        $emp_list = DB::table('users')
            ->where('store_id', $store_id)
            // ->where('id', '!=', $UserId)
            ->whereIn('role_id', [12, 13, 14, 16, 15, 53])
            ->whereNotIn('id', $targetedEmpIds)
            ->where('status', 1)
            ->get();

        // $man_id = auth()->user()->store_id;

        $emp_target = DB::table('employee_target')->where('employee_target.store_id', $store_id)
            ->leftJoin('stores', 'employee_target.store_id', '=', 'stores.id')
            ->leftJoin('users', 'employee_target.emp_id', '=', 'users.id')
            ->select('employee_target.*', 'stores.store_name', 'users.name as user_name')
            ->get();

        if ($req->isMethod('post')) {

            $store = DB::table('users')->where('id', $req->emp_id)->select('store_id')->first();
            $store_id = $store->store_id;

            $emp_store = DB::table('employee_target')->insert([
                'emp_id' => $req->emp_id,
                'store_id' => $store_id,
                'month' => $req->month,
                'shoe_value' => $req->shoe_value,
                'shoe_qty' => $req->shoe_qty,
                'shirt_value' => $req->shirt_value,
                'shirt_qty' => $req->shirt_qty,
                'c_by' => auth()->user()->id,
                'status' => 'Active',
                'created_at' => now(),
                'updated_at' => now()
            ]);

            return  redirect()->route('employee.emp_target', ['emp_list' => [], 'emp_store' => $emp_store, 'emp_target' => $emp_target]);
        }

        return view('employee.employee_target', ['emp_list' => $emp_list, 'emp_store' => [], 'emp_target' => $emp_target]);
    }

    public function emp_performance_target(Request $req)
    {

        $store_id = auth()->user()->store_id;
        $UserId = auth()->id();
        $currentMonth = now()->format('Y-m');

        // // Get employees who already have targets for current month
        $targetedEmpIds = DB::table('emp_performance_target')
            ->where('store_id', $store_id)
            ->where('month', $currentMonth)
            ->pluck('emp_id')
            ->toArray();

        $emp_list = DB::table('users')
            ->where('store_id', $store_id)
            // ->where('id', '!=', $UserId)
            ->whereIn('role_id', [12, 13, 14, 16, 15, 53])
            ->whereNotIn('id', $targetedEmpIds)
            ->where('status', 1)
            ->get();

        // // $man_id = auth()->user()->store_id;

        $emp_target = DB::table('emp_performance_target')
            ->where('emp_performance_target.store_id', $store_id)
            ->leftJoin('stores', 'emp_performance_target.store_id', '=', 'stores.id')
            ->leftJoin('users as emp', 'emp_performance_target.emp_id', '=', 'emp.id')
            ->leftJoin('users as creator', 'emp_performance_target.c_by', '=', 'creator.id')
            ->select(
                'emp_performance_target.*',
                'stores.store_name',
                'emp.name as user_name',
                'creator.name as cby_name'
            )
            ->get();


        if ($req->isMethod('post')) {

            $emp_store = DB::table('emp_performance_target')->insert([
                'emp_id' => $req->emp_id,
                'store_id' => $store_id,
                'month' => $req->month,
                'target_qty' => $req->target_qty,
                'target' => $req->target_val,
                'c_by' => auth()->user()->id,
                'status' => 'Active',
                'created_at' => now(),
                'updated_at' => now()
            ]);

            return  redirect()->route('employee.emp_performance_target', ['emp_list' => [], 'emp_store' => $emp_store, 'emp_target' => $emp_target]);
        }

        return view('employee.employee_performance_target', ['emp_list' => $emp_list, 'emp_store' => [], 'emp_target' => $emp_target]);
    }
}
