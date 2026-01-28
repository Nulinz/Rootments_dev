<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Models\Walkin;
use App\Models\Cluster;

class StoreController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = Auth::user();

        if ($user->role_id == 11) {

            $store = DB::table('m_cluster as mc')
                ->leftJoin('cluster_store as cs', 'cs.cluster_id', '=', 'mc.id')
                ->leftJoin('stores as st', 'st.id', '=', 'cs.store_id')
                ->select('st.id', 'st.store_code', 'st.store_name', 'st.store_mail', 'st.store_contact')
                ->where('cl_name', $user->id)
                ->get();
        } else {

            $query = DB::table('stores');

            if ($user->dept !== 'Admin' && $user->dept !== 'HR' && $user->dept !== 'Operation') {
                $query->where('id', $user->store_id);
            }

            $store = $query->get();
        }

        // dd($store);


        return view('store.list', ['store' => $store]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $max_id = DB::table('stores')->max('id');

        $store_no = 'STORE' . str_pad($max_id + 1, 2, '0', STR_PAD_LEFT);

        // $role_data= DB::table('roles')->groupBY('role')->get();

        $role_data = DB::table('roles')
            ->whereNotIn('id', [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11])
            ->groupBy('role')
            ->get();



        return view('store.add', ['store_no' => $store_no, 'role_data' => $role_data]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'store_name' => 'required|unique:stores,store_name',
            'store_contact' => 'required|unique:stores,store_contact',
        ]);

        $request->validate([
            'store_code' => 'required',
            'brand' => 'required',
            'store_name' => 'required',
            'store_contact' => 'required',
            'store_start_time' => 'required',
            'store_end_time' => 'required',
            'store_address' => 'required',
            'store_pincode' => 'required',
            'store_geo' => 'required',
        ]);

        $store = [];

        $store[] = DB::table('stores')->insertGetId([
            'store_code' => $request->store_code,
            'brand' => $request->brand,
            'store_name' => $request->store_name,
            'store_contact' => $request->store_contact,
            'store_mail' => $request->store_mail,
            'store_alt_contact' => $request->store_alt_contact,
            'store_start_time' => $request->store_start_time,
            'store_end_time' => $request->store_end_time,
            'store_address' => $request->store_address,
            'store_pincode' => $request->store_pincode,
            'store_geo' => $request->store_geo,
            'leave_per' => $request->leave_per,
            'created_at' => now(),
            'updated_at' => now(),
        ]);


        $items = [];
        foreach ($request->role_id as $key => $roleId) {

            foreach ($store as $store_ref_id) {
                $items[] = [
                    'store_ref_id' => $store_ref_id,
                    'role_id' => $roleId,
                    'req_count' => $request->req_count[$key],
                    'emp_count' => $request->emp_count[$key],
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
        }

        DB::table('store_lists')->insert($items);

        return redirect()->route('store.index')->with([
            'status' => 'success',
            'message' => 'Store Added successfully!'
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $store = DB::table('stores')->where('id', $id)->first();

        return view('store.profile', ['store' => $store]);
    }

    public function strlist($id)
    {
        $strenth = DB::table('store_lists')
            ->leftjoin('roles', 'store_lists.role_id', '=', 'roles.id')
            ->where('store_lists.store_ref_id', $id)
            ->select(
                'store_lists.*',
                'roles.role'
            )
            ->get();

        return view('store.strength', ['strenth' => $strenth]);
    }

    public function detailslist($id)
    {
        $employee = DB::table('users')
            ->leftjoin('stores', 'users.store_id', '=', 'stores.id')
            ->leftjoin('roles', 'users.role_id', '=', 'roles.id')
            ->where('users.store_id', $id)
            ->where('users.status', 1)
            ->select('users.emp_code', 'users.name', 'users.login_time', 'users.logout_time', 'roles.role', 'users.id as userId')
            ->get();

        return view('store.details', ['employee' => $employee]);
    }

    public function empview($userId)
    {
        $users = DB::table('users')
            ->leftJoin('stores', 'users.store_id', '=', 'stores.id')
            ->leftJoin('roles', 'users.role_id', '=', 'roles.id')
            ->where('users.id', $userId)
            ->select('users.id', 'users.profile_image', 'users.name', 'users.emp_code', 'users.contact_no', 'users.email', 'stores.store_name', 'roles.role', 'roles.role_dept', 'users.status as u_status')
            ->first();

        return view('employee.profile', ['users' => $users]);
    }
    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $store = DB::table('stores')->where('id', $id)->first();

        $role_data = DB::table('roles')->get();

        $storedata = DB::table('store_lists')
            ->join('stores', 'store_lists.store_ref_id', '=', 'stores.id')
            ->join('roles', 'store_lists.role_id', '=', 'roles.id')
            ->where('store_lists.store_ref_id', $store->id)
            ->select(
                'store_lists.*',
                'roles.id as role_id',
                'roles.role'
            )->get();


        return view('store.edit', ['store' => $store, 'role_data' => $role_data, 'storedata' => $storedata]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // Update store details
        DB::table('stores')
            ->where('id', $id)
            ->update([
                'store_code' => $request->store_code,
                'brand' => $request->brand,
                'store_name' => $request->store_name,
                'store_contact' => $request->store_contact,
                'store_mail' => $request->store_mail,
                'store_alt_contact' => $request->store_alt_contact,
                'store_start_time' => $request->store_start_time,
                'store_end_time' => $request->store_end_time,
                'store_address' => $request->store_address,
                'store_pincode' => $request->store_pincode,
                'store_geo' => $request->store_geo,
                'leave_per' => $request->leave_per,
                'updated_at' => now(),
            ]);

        // Get new role IDs from request
        $newRoleIds = $request->role_id ?? [];

        // Delete old roles that are no longer present in the request
        DB::table('store_lists')
            ->where('store_ref_id', $id)
            ->whereNotIn('role_id', $newRoleIds)
            ->delete();

        // Process the updated roles
        foreach ($newRoleIds as $key => $roleId) {
            $existingItem = DB::table('store_lists')
                ->where('store_ref_id', $id)
                ->where('role_id', $roleId)
                ->first();

            if ($existingItem) {
                // Update existing role entry
                DB::table('store_lists')
                    ->where('store_ref_id', $id)
                    ->where('role_id', $roleId)
                    ->update([
                        'req_count' => $request->req_count[$key],
                        'emp_count' => $request->emp_count[$key],
                        'updated_at' => now(),
                    ]);
            } else {
                // Insert new role entry
                DB::table('store_lists')->insert([
                    'store_ref_id' => $id,
                    'role_id' => $roleId,
                    'req_count' => $request->req_count[$key],
                    'emp_count' => $request->emp_count[$key],
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }

        return redirect()->route('store.index')->with([
            'status' => 'success',
            'message' => 'Store Updated successfully!'
        ]);
    }

    public function store_check(Request $req)
    {

        $st = DB::table('set_up')->where('st_code', $req->store)->first();

        // dd($st);

        return response()->json([
            'st_code' => $st->st_code ?? null,
            'data' => $st
        ], 200);
    }

    // public function walkinlist(Request $req)
    // {

    //     $cluster_check = DB::table('m_cluster as mc')->leftJoin('users', 'users.id', '=', 'mc.cl_name')->where('mc.cl_name', '=', auth()->user()->id)
    //         ->where('users.role_id', 12)->count();

    //     $auth_role = auth()->user()->role_id;

    //     $store = DB::table('stores as st')
    //         ->where('st.status', 1)
    //         ->when(true, function ($query) use ($auth_role, $cluster_check) {
    //             if (in_array($auth_role, [1, 2, 27])) {
    //                 return $query->select('st.id as stores_id', 'st.store_name as stores_name');
    //             }

    //             if ($auth_role == 10) {
    //                 return $query
    //                     ->join('cluster_store as cs', 'cs.store_id', '=', 'st.id')
    //                     ->join('m_cluster as mc', 'mc.id', '=', 'cs.cluster_id')
    //                     ->join('area_cluster as ac', 'ac.cluster_id', '=', 'mc.id')
    //                     ->join('m_area as ma', 'ma.id', '=', 'ac.area_id')
    //                     ->where('ma.a_man', auth()->user()->id)
    //                     ->select('st.id as stores_id', 'st.store_name as stores_name');
    //             }


    //             if ($auth_role == 11) {
    //                 return $query->join('cluster_store as cs', 'cs.store_id', '=', 'st.id')
    //                     ->join('m_cluster as ms', 'ms.id', '=', 'cs.cluster_id')
    //                     ->where('ms.cl_name', auth()->user()->id)
    //                     ->select('st.id as stores_id', 'st.store_name as stores_name');
    //             }

    //             if ($auth_role == 12) {

    //                 if ($cluster_check == 0) {
    //                     return $query->where('st.id', auth()->user()->store_id)
    //                         ->select('st.id as stores_id', 'st.store_name as stores_name');
    //                 } else {
    //                     return $query->join('cluster_store as cs', 'cs.store_id', '=', 'st.id')
    //                         ->join('m_cluster as ms', 'ms.id', '=', 'cs.cluster_id')
    //                         ->where('ms.cl_name', auth()->user()->id)
    //                         ->select('st.id as stores_id', 'st.store_name as stores_name');
    //                 }
    //             }
    //         })
    //         ->get();


    //     // dd($cluster_check);

    //     if ($req->isMethod('GET')) {

    //         return view('store.list-walkin', ['store' => $store]);
    //     } else {

    //         if ($req->filled('employee')) {
    //             $st_list[] = $req->employee;
    //         } else {
    //             $st_list = DB::table('users')->where('store_id', $req->store_list)->pluck('id')->toArray();
    //         }

    //         $start = Carbon::parse($req->startdate)->startOfDay();
    //         $end = Carbon::parse($req->enddate)->endOfDay();

    //         $wl_list = Walkin::with(['createdBy:id,name'])
    //             ->whereIn('c_by', $st_list)
    //             ->whereBetween('created_at', [$start, $end])
    //             ->whereIn('walk_status', $req->walk_status)
    //             ->get();

    //         //  dd($wl_list);

    //         return view('store.list-walkin', ['list' => $wl_list, 'store' => $store]);
    //     }
    // }


    public function walkinlist(Request $req)
    {

        $cluster_check = DB::table('m_cluster as mc')->leftJoin('users', 'users.id', '=', 'mc.cl_name')->where('mc.cl_name', '=', auth()->user()->id)
            ->where('users.role_id', 12)->count();

        $auth_role = auth()->user()->role_id;

        $store = DB::table('stores as st')
            ->where('st.status', 1)
            ->when(true, function ($query) use ($auth_role, $cluster_check) {
                if (in_array($auth_role, [1, 2, 6, 27])) {
                    return $query->select('st.id as stores_id', 'st.store_name as stores_name');
                }

                if ($auth_role == 10) {
                    return $query
                        ->join('cluster_store as cs', 'cs.store_id', '=', 'st.id')
                        ->join('m_cluster as mc', 'mc.id', '=', 'cs.cluster_id')
                        ->join('area_cluster as ac', 'ac.cluster_id', '=', 'mc.id')
                        ->join('m_area as ma', 'ma.id', '=', 'ac.area_id')
                        ->where('ma.a_man', auth()->user()->id)
                        ->select('st.id as stores_id', 'st.store_name as stores_name');
                }


                if (in_array($auth_role,  [11, 66])) {
                    return $query->join('cluster_store as cs', 'cs.store_id', '=', 'st.id')
                        ->join('m_cluster as ms', 'ms.id', '=', 'cs.cluster_id')
                        ->where('ms.cl_name', auth()->user()->id)
                        ->select('st.id as stores_id', 'st.store_name as stores_name');
                }

                if ($auth_role == 12) {

                    if ($cluster_check == 0) {
                        return $query->where('st.id', auth()->user()->store_id)
                            ->select('st.id as stores_id', 'st.store_name as stores_name');
                    } else {
                        return $query->join('cluster_store as cs', 'cs.store_id', '=', 'st.id')
                            ->join('m_cluster as ms', 'ms.id', '=', 'cs.cluster_id')
                            ->where('ms.cl_name', auth()->user()->id)
                            ->select('st.id as stores_id', 'st.store_name as stores_name');
                    }
                }
            })
            ->get();


        // dd($cluster_check);

        if ($req->isMethod('GET')) {

            return view('store.list-walkin', ['store' => $store]);
        } else {

            // 1. Parse Dates Correctly
            $start = Carbon::parse($req->startdate)->startOfDay();
            $end   = Carbon::parse($req->enddate)->endOfDay();

            // 2. Initialize Query
            $query = Walkin::with(['createdBy:id,name'])
                ->whereBetween('created_at', [$start, $end]);

            // 3. Filter by Store (Mandatory based on your form logic)
            if ($req->filled('store_list')) {
                $query->where('store_id', $req->store_list);
            }

            // 4. Filter by Employees (Optional)
            if ($req->filled('employees', [])) {
                $query->whereIn('c_by', $req->employees);
            }

            // 5. Filter by Status (Optional)
            $walk_status = $req->input('walk_status', []);
            if (!empty($walk_status)) {
                $query->whereIn('walk_status', $walk_status);
            }

            // 6. Execute
            $wl_list = $query->get();

            return view('store.list-walkin', ['list' => $wl_list, 'store' => $store]);
        }
    }



    public function store_target(Request $req)
    {
        $user_id = auth()->user()->id;

        // Step 1: Set default month/year to current if not provided
        $selectedMonth = now()->month;
        $selectedYear = now()->year;

        if ($req->month) {
            $sal = explode('-', $req->month);
            $selectedYear = $sal[0];
            $selectedMonth = $sal[1];
        }

        // Step 2: Get store IDs that already have targets set for selected month
        $stores_with_target = DB::table('target')
            ->whereYear('created_at', $selectedYear)
            ->whereMonth('month', $selectedMonth)
            ->pluck('store_id')
            ->toArray();

        // Step 3: Get stores without target for selected month
        $clusterIds = DB::table('m_cluster')
            ->where('cl_name', $user_id)
            ->pluck('id')
            ->toArray();

        $store_list = DB::table('stores as s')
            ->join('cluster_store as cs', 's.id', '=', 'cs.store_id')
            ->whereIn('cs.cluster_id', $clusterIds)
            ->where('s.status', 1)
            ->whereNotIn('s.id', $stores_with_target)
            ->pluck('s.id', 's.store_name')
            ->toArray();

        // $store_list = DB::table('stores')
        //     ->where('status', 1)
        //     ->whereNotIn('id', $stores_with_target)
        //     ->pluck('id', 'store_name')
        //     ->toArray();

        // Step 4: Get all target entries with store and creator info
        $target_list = DB::table('target')->orderBy('id', 'DESC')
            ->when(! in_array(auth()->user()->role_id, [2, 3, 4]), function ($query) {
                $query->where('c_by', auth()->id());
            })
            ->get()->map(function ($item) {
                $stores = DB::table('stores')->where('id', $item->store_id)->first();
                $c_by = DB::table('users')->where('id', $item->c_by)->first();

                $item->store_name = $stores->store_name ?? 'N/A';
                $item->cby_name = $c_by->name ?? 'N/A';

                return $item;
            });

        // Handle GET request
        if ($req->isMethod('GET')) {
            return view('store.store-target', [
                'stores' => $store_list,
                'tr_list' => $target_list
            ]);
        } else {
            $tar_mon = $req->month;
            $store = $req->store_id;

            $sal = explode('-', $tar_mon);
            $year = $sal[0];
            $mon = $sal[1];

            $exists = DB::table('target')
                ->where('month', $mon)
                ->where('store_id', $store)
                ->exists();

            if ($exists) {
                return redirect()->back()->with([
                    'status' => 'Failed',
                    'message' => 'Target Already Updated For the month',
                    'tr_list' => $target_list,
                    'stores' => $store_list
                ]);
            }

            DB::table('target')->insert([
                'store_id' => $req->store_id,
                'month' => $mon,
                'target_qty' => $req->target_qty,
                'target' => $req->target_val,
                'c_by' => $user_id,
                'status' => 'Active',
                'created_at' => now(),
                'updated_at' => now()
            ]);

            return redirect()->back()->with([
                'status' => 'success',
                'message' => 'Target Added Successfully',
                'tr_list' => $target_list,
                'stores' => $store_list
            ]);
        }
    }


    public function getEmployeesByStore(Request $request)
    {
        $employees = DB::table('users')
            // ->select('id', 'name')
            ->where('store_id', $request->input('store_id'))
            ->where('status', 1) // optional: filter only active
            ->get();

        return response()->json($employees);
    }


    //  public function audit_list()
    //     {

    //             $store_audit = DB::table('store_audit')
    //             ->leftJoin('stores as st', 'st.id', '=', 'store_audit.store_id')
    //             ->leftJoin('users as us', 'store_audit.c_by', '=', 'us.id')
    //             ->select(
    //                 'store_audit.id as audit_id',
    //                 'store_audit.store_id',
    //                 'store_audit.average_rating',
    //                 'store_audit.created_on',
    //                 'st.store_name',
    //                 'us.name as rated_by',
    //                 'us.role_id'
    //             )
    //             ->when(auth()->user()->role_id == 12, function ($query) {
    //                 return $query->where('store_audit.store_id', auth()->user()->store_id);
    //             })
    //             // ->when(in_array(auth()->user()->role_id, [1, 2, 3, 6, 7, 8, 9, 10, 11]), function ($query) {
    //             //     return $query->where('store_audit.c_by', auth()->user()->id);
    //             // })
    //             ->get();


    //         return view('store.store_audi', ['store_audit' => $store_audit]);
    //     }

    public function audit_list()
    {
        $user = auth()->user();
        $roleId = $user->role_id;

        $store_audit = DB::table('store_audit')
            ->leftJoin('stores as st', 'st.id', '=', 'store_audit.store_id')
            ->leftJoin('users as us', 'store_audit.c_by', '=', 'us.id')
            ->select(
                'store_audit.id as audit_id',
                'store_audit.store_id',
                'store_audit.average_rating',
                'store_audit.created_on',
                'st.store_name',
                'us.name as rated_by',
                'us.role_id'
            )
            // Role 12 → filter by store
            ->when($roleId == 12, function ($query) use ($user) {
                return $query->where('store_audit.store_id', $user->store_id);
            })
            // Role 11 → filter by created by
            ->when($roleId == 11, function ($query) use ($user) {
                return $query->where('store_audit.c_by', $user->id);
            })
            // Role 1,2,3 → no filter (view all) so no condition needed
            ->when(!in_array($roleId, [1, 2, 3, 11, 12]), function ($query) use ($user) {
                // Optional: Show nothing or only his created records
                return $query->where('store_audit.c_by', $user->id);
            })
            ->get();

        return view('store.store_audi', ['store_audit' => $store_audit]);
    }


    public function add_audit()
    {

        $store = DB::table('stores as st')
            ->where('st.status', 1)
            ->when(auth()->user()->role_id == 11, function ($query) {
                return  $query->join('cluster_store as cs', 'cs.store_id', '=', 'st.id')
                    ->join('m_cluster as ms', 'ms.id', '=', 'cs.cluster_id')
                    ->where('ms.cl_name', auth()->user()->id);
            })
            ->select('st.id as stores_id', 'st.store_name as stores_name')
            ->get();

        // dd($store);


        return view('store.add_audi', ['store' => $store]);
    }

    public function store_audit(Request $request)
    {
        $user = auth()->user()->id;

        $inputs = [
            $request->input('exterior_cleanliness'),
            $request->input('signage_board_condition'),
            $request->input('entry_display_compliance'),
            $request->input('floor_cleanliness'),
            $request->input('product_alignment'),
            $request->input('display_quality'),
            $request->input('lighting_aesthetics'),
            $request->input('music_ambience'),
            $request->input('in_store_temperature'),
            $request->input('uniform_grooming'),
            $request->input('badge_name_display'),
            $request->input('punctuality_attendance'),
            $request->input('staff_professionalism'),
            $request->input('greeting_etiquette'),
            $request->input('customer_waiting_time'),
            $request->input('product_presentation'),
            $request->input('style_suggestions'),
            $request->input('cleanliness_mirror'),
            $request->input('privacy_maintenance'),
            $request->input('staff_coordination'),
            $request->input('accessories_trial_support'),
            $request->input('accuracy_billing'),
            $request->input('discount_application'),
            $request->input('terms_conditions'),
            $request->input('stock_management'),
            $request->input('product_tag'),
            $request->input('defective_isolated'),
            $request->input('cleanliness_check'),
            $request->input('damage_reporting'),
            $request->input('return_delay_check'),
            $request->input('hang_returned_products'),
            $request->input('measurement_qc'),
            $request->input('booking_records'),
            $request->input('task_app_update'),
            $request->input('booking_process'),
            $request->input('repair_handling'),
            $request->input('brand_compliance'),
            $request->input('software_compliance'),
            $request->input('kpi_awareness'),
        ];

        // Filter valid numeric entries
        $validRatings = array_filter($inputs, function ($value) {
            return is_numeric($value);
        });

        // Calculate average
        $totalScore = array_sum($validRatings);
        $totalCriteria = count($validRatings);
        $averageRating = $totalCriteria > 0 ? round($totalScore / $totalCriteria, 2) : 0;

        // dd($averageRating);


        DB::table('store_audit')->insert([
            // Store Exterior & Signage
            'store_id' => $request->store_id,
            'exterior_cleanliness' => $request->input('exterior_cleanliness'),
            'signage_board_condition' => $request->input('signage_board_condition'),
            'entry_display_compliance' => $request->input('entry_display_compliance'),
            'exterior_remarks' => $request->input('store_signage_remarks'),

            // VM Standards
            'fixture_cleanliness' => $request->input('floor_cleanliness'),
            'product_alignment' => $request->input('product_alignment'),
            'mannequin_standards' => $request->input('display_quality'),
            'lighting_aesthetics' => $request->input('lighting_aesthetics'),
            'music_ambience' => $request->input('music_ambience'),
            'in_store_temperature' => $request->input('in_store_temperature'),
            'vm_remarks' => $request->input('vm_standards_remarks'),

            // Staff Presence
            'uniform_grooming' => $request->input('uniform_grooming'),
            'badge_name_display' => $request->input('badge_name_display'),
            'punctuality_attendance' => $request->input('punctuality_attendance'),
            'staff_professionalism' => $request->input('staff_professionalism'),
            'staff_remarks' => $request->input('staff_remarks'),

            // Guest Handling
            'greeting_etiquette' => $request->input('greeting_etiquette'),
            'customer_waiting_time' => $request->input('customer_waiting_time'),
            'product_presentation_handling' => $request->input('product_presentation'),
            'style_suggestions' => $request->input('style_suggestions'),
            'guest_remarks' => $request->input('guest_remarks'),

            // Trial Room
            'trialroom_cleanliness_mirror' => $request->input('cleanliness_mirror'),
            'privacy_maintenance' => $request->input('privacy_maintenance'),
            'staff_coordination' => $request->input('staff_coordination'),
            'accessories_trial_support' => $request->input('accessories_trial_support'),
            'trialroom_remarks' => $request->input('trial_room_remarks'),

            // Billing System
            'billing_accuracy' => $request->input('accuracy_billing'),
            'discount_application' => $request->input('discount_application'),
            'terms_conditions_explanation' => $request->input('terms_conditions'),
            'billing_remarks' => $request->input('billing_remarks'),

            // Inventory System
            'stock_management' => $request->input('stock_management'),
            'product_tag' => $request->input('product_tag'),
            'defective_products_isolated' => $request->input('defective_isolated'),
            'inventory_remarks' => $request->input('inventory_remarks'),

            // Returned Garments
            'return_cleanliness_check' => $request->input('cleanliness_check'),
            'damage_reporting' => $request->input('damage_reporting'),
            'return_delay_check' => $request->input('return_delay_check'),
            'hang_in_designated_area' => $request->input('hang_returned_products'),
            'return_remarks' => $request->input('returned_garments_remarks'),

            // Software Usage & Documents
            'measurement_books_qc_checklist' => $request->input('measurement_qc'),
            'booking_rentout_return_records' => $request->input('booking_records'),
            'task_mgmt_app_update' => $request->input('task_app_update'),
            'software_remarks' => $request->input('software_remarks'),

            // SOP Compliance
            'sop_booking_rentout_return' => $request->input('booking_process'),
            'sop_alteration_repair_handling' => $request->input('repair_handling'),
            'sop_brand_compliance' => $request->input('brand_compliance'),
            'sop_software_compliance' => $request->input('software_compliance'),
            'sop_kpi_awareness' => $request->input('kpi_awareness'),
            'sop_remarks' => $request->input('sop_remarks'),

            // Auditor Remarks
            'audit_acknowledged' => $request->input('audit_acknowledged'),
            'action_plan' => $request->input('action_plan'),
            'average_rating' => $averageRating,

            'c_by' => $user,
            'created_on' => now(),
            'updated_on' => now()
        ]);

        return redirect()->route('store.audit')->with(['status' => 'success', 'message' => 'Audit submitted!']);
    }

    public function audit_view($id)
    {

        $audit_row = DB::table('store_audit')->where('id', $id)->first();

        $store_id = $audit_row->store_id;


        $store_audit = DB::table('store_audit')
            ->leftJoin('stores as st', 'st.id', '=', 'store_audit.store_id')
            ->where('store_audit.store_id', $store_id)
            ->select('store_audit.*', 'st.store_name as store_name')
            ->orderBy('store_audit.created_on', 'desc')
            ->first();

        return view('store.audit_view', ['store_audit' => $store_audit]);
    }

    public function store_walkin()
    {
        $user = auth()->user();

        $query = DB::table('walkin')
            ->leftJoin('users as us', 'walkin.c_by', '=', 'us.id')
            ->leftJoin('stores as st', 'walkin.store_id', '=', 'st.id')
            ->select('walkin.*', 'st.store_name', 'us.name as emp_name')
            ->orderByDesc('walkin.created_at');

        if ($user->role_id == 12) {
            $query->where('walkin.store_id', $user->store_id);
        }

        $walkin_list = $query->get();

        return view('store.walkin', ['walkin' => $walkin_list]);
    }


    public function add_walkin()
    {

        $store_id = auth()->user()->store_id;

        $store_by = DB::table('users')
            ->where('store_id', $store_id)
            ->where('status', 1)
            ->get();

        return view('store.add_walkin', [
            'creating' => $store_by,
            'count' => 0,
            'name' => '',
            'repeat_count' => 0,
        ]);
    }

    // public function add_walkin_check(Request $req)
    // {
    //      $store_id = auth()->user()->store_id;

    //     // Normalize input
    //     $contact = preg_replace('/\D/', '', $req->contact);
    //     $contact = substr($contact, -10); // last 10 digits only

    //     // Check if customer exists
    //     $record = DB::table('walkin')
    //         ->where('contact', $contact)
    //         ->where('store_id', $store_id)
    //         ->first();

    //     $exists = $record ? 1 : 0;

    //     // Always fetch latest record (even if Loss)
    //     $latest = DB::table('walkin')
    //         ->where('contact', $contact)
    //         ->where('store_id', $store_id)
    //         ->orderByDesc('id')
    //         ->first();

    //     // Check if latest status is Loss
    //     $is_loss = ($latest && strtolower($latest->walk_status) == 'loss');

    //     // Check repeat count for today
    //     $c_time = Carbon::today()->toDateString();
    //     $repeat_count = DB::table('walkin')
    //         ->where('contact', $contact)
    //         ->where('store_id', $store_id)
    //         ->whereDate('created_at', $c_time)
    //         ->count();

    //     // Return response
    //     return response()->json([
    //         'exists' => $exists,
    //         'name' => $record->name ?? '',
    //         'repeat_count' => $repeat_count,
    //         'is_loss' => $is_loss,
    //     ]);
    // }


    // public function add_walkin_store(Request $req)
    // {
    //     $store_id = auth()->user()->store_id;

    //     $walkin_store = DB::table('walkin')->insert([
    //         'store_id' => $store_id,
    //         'name' => $req->cus_name,
    //         'contact' => $req->contact,
    //         'f_date'  => $req->fun_date,
    //         'walk_status' => $req->walk_status,
    //         'remark' => $req->remarks,
    //         'c_by' => $req->c_for,
    //         'created_at' => now(),
    //         'updated_at' => now()
    //     ]);
    //     return redirect()->route('store.walkin')->with([
    //         'status' => 'success',
    //         'message' => 'Walkin Added Successfully'
    //     ]);
    // }

    public function add_walkin_check(Request $req)
    {
        $store_id = auth()->user()->store_id;

        // Normalize input
        $contact = preg_replace('/\D/', '', $req->contact);
        $contact = substr($contact, -10); // last 10 digits only

        // Check if customer exists
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

        // Return response
        return response()->json([
            'exists' => $exists,
            'name' => $record->name ?? '',
            'repeat_count' => $repeat_count,
            'is_loss' => $is_loss,
        ]);
    }

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

    // NEW METHOD: Get subcategories based on category and store brand
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

    public function add_walkin_store(Request $req)
    {
        $store_id = auth()->user()->store_id;

        // Get category and subcategory names instead of IDs
        $category_name = null;
        $subcategory_name = null;

        if ($req->category) {
            $category = DB::table('walkin_cat')
                ->where('id', $req->category)
                ->first();
            $category_name = $category ? $category->cat : null;
        }

        if ($req->subcategory) {
            $subcategory = DB::table('walkin_cat')
                ->where('id', $req->subcategory)
                ->first();
            $subcategory_name = $subcategory ? $subcategory->sub : null;
        }

        // Calculate repeat count for today
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

        $walkin_store = DB::table('walkin')->insert([
            'store_id' => $store_id,
            'name' => $req->cus_name,
            'contact' => $req->contact,
            'f_date' => $req->fun_date,
            'walk_status' => $req->walk_status,
            'remark' => $req->remarks,
            'c_by' => $req->c_for,
            'cat' => $category_name, // Store category name in cat column
            'sub' => $subcategory_name, // Store subcategory name in sub column
            'repeat_count' => $repeat_count_to_store, // Store repeat count only if customer has repeated
            'created_at' => now(),
            'updated_at' => now()
        ]);

        return redirect()->route('store.walkin')->with([
            'status' => 'success',
            'message' => 'Walkin Added Successfully'
        ]);
    }

    public function walkin_status(Request $req)
    {
        $type = $req->type;
        $brand = auth()->user()->store_rel->brand;

        // if ($type === 'Booked' || $type === 'Booking & Rentout') {
        //     $items = DB::table('walkin_cat')->where('brand', $brand)->select('cat', 'sub')->get();
        // } else {
        //     $items = DB::table('walkin_cat')->where('brand', 'LOSS')->select('cat', 'sub')->get();
        // }

        if ($type === 'Booked' || $type === 'Booking & Rentout') {
            $items = DB::table('walkin_cat')->where('brand', $brand)->select('cat', 'sub')->get();
        } elseif ($type === 'Loss') {
            $items = DB::table('walkin_cat')->where('brand', 'LOSS')->select('cat', 'sub')->get();
        } else {
            $items = DB::table('walkin_cat')->where('brand', 'OTHER')->select('cat', 'sub')->get();
        }

        $result = [];

        foreach ($items as $item) {
            $result[$item->cat][] = $item->sub;
        }

        return response()->json($result);
    }

    // public function walkin_status(Request $req)
    // {
    //     $type = $req->type;
    //     $brand = auth()->user()->store_rel->brand;

    //     if ($type === 'Booked' || $type === 'Booking & Rentout') {
    //         $items = DB::table('walkin_cat')->where('brand', $brand)->select('cat', 'sub')->get();
    //     } else {
    //         $items = DB::table('walkin_cat')->where('brand', 'LOSS')->select('cat', 'sub')->get();
    //     }

    //     $result = [];

    //     foreach ($items as $item) {
    //         $result[$item->cat][] = $item->sub;
    //     }

    //     return response()->json($result);
    // }

    public function walkin_status_update(Request $req)
    {
        $walk_update =  DB::table('walkin')->where('id', $req->walk_id)->update([
            "walk_status" => $req->status,
            "cat" => $req->cat ?? null,
            "sub" => $req->sub_cat ?? null,
            "remark" => $req->remarks ?? null,
            "updated_at" => now()
        ]);

        return  back()->with([
            'status' => 'success',
            'message' => 'Walkin Updated Successfully'
        ]);
    }

    public function  mnt_update_list()
    {
        $user = auth()->user();
        $role_id = $user->role_id;

        $upd_list = DB::table('maintenance_update as mu')
            ->leftJoin('users as us', 'mu.c_by', '=', 'us.id')
            ->leftJoin('stores as st', 'mu.store_id', '=', 'st.id')
            ->select('mu.*', 'us.name', 'st.store_name')
            ->when($role_id == 12, function ($query) use ($user) {
                return $query->where('mu.store_id', $user->store_id);
            })
            ->get();

        return view('store.maintain-upd-list', ['update_list' => $upd_list]);
    }
    public function mnt_update($id)
    {
        return view('store.maintain_update', compact('id'));
    }

    public function mnt_update_store(Request $req)
    {

        $filenames = [];

        if ($req->hasFile('mnt_file')) {
            foreach ($req->file('mnt_file') as $file) {
                if ($file->isValid()) {
                    $filename = time() . '_' . uniqid() . '_' . $file->getClientOriginalName();
                    $file->move(('assets/images/Task'), $filename);
                    $filenames[] = $filename;
                }
            }
        }

        // Store filenames as comma-separated (or consider JSON)
        $filenames_string = !empty($filenames) ? implode(',', $filenames) : null;


        $update = DB::table('maintenance_update')->insert([
            'task_id' => $req->task_id,
            'store_id' => $req->store_id,
            'staff_arr' => $req->staff_arr,
            'work_comp' => $req->work_comp,
            'mnt_file' => $filenames_string,
            'end_time' => $req->end_time,
            'mnt_update' => $req->mnt_update,
            'comments' => $req->comments,
            'c_by' => $req->c_by,
            'created_at' => now(),
            'updated_at' => now()
        ]);

        $task_mnt = DB::table('maintain_req')->where('id', $req->task_id)->update([
            'status' => 'Completed'
        ]);

        return redirect()->route('maintain.update_list')->with([
            'status' => 'success',
            'message' => 'Maintenance update created successfully!'
        ]);

        //         return redirect()->back()->with([
        //     'status' => 'success',
        //     'message' => 'Maintenance update created successfully!'
        // ]);

        // return redirect()->route('repair.index')->with([
        //     'status' => 'success',
        //     'message' => 'Maintenance update created successfully!'
        // ]);
    }

    public function dsr_sale_list(Request $req)
    {
        $user = auth()->user();
        $store_id = $user->store_id;
        $role_id = $user->role_id;

        $count = DB::table('employee_workupdate')
            ->whereDate('created_at', now())
            ->where('store_id', $store_id)
            ->count();

        $list = DB::table('employee_workupdate')
            ->leftJoin('users', 'employee_workupdate.emp_id', '=', 'users.id')
            ->leftJoin('stores', 'employee_workupdate.store_id', '=', 'stores.id')
            ->where('employee_workupdate.store_id', $store_id)
            ->whereDate('employee_workupdate.created_at', now())
            ->select('employee_workupdate.*', 'users.name as username', 'stores.store_name')
            ->orderByDesc('employee_workupdate.created_at')
            ->get();

        return view('store.dsr-sales-list', ['count' => $count, 'list' => $list]);
    }

    public function dsr_salesc_reate()
    {
        $sto_emp = DB::table('users')
            ->where('store_id', auth()->user()->store_id)
            ->whereIn('role_id', [12, 13, 14, 15, 16, 53])
            ->where('status', 1)
            ->select('name', 'id')->get();

        return view('store.dsr-sales-create', ['sto_emp' => $sto_emp]);
    }

    public function dsr_sales_store(Request $req)
    {
        // Initialize variables once outside the loop
        $store_id = auth()->user()->store_id;
        $today = now();
        $currentMonth = $today->month;
        $currentYear = $today->year;
        $currentDay = $today->day;
        $dataToInsert = [];

        // Pre-fetch all employee targets in a single query
        $targets = DB::table('employee_target')
            ->whereMonth('created_at', $currentMonth)
            ->whereYear('created_at', $currentYear)
            ->whereIn('emp_id', $req->emp_id)
            ->get()
            ->keyBy('emp_id');

        // Pre-fetch all previous day's MTD data in a single query
        $previousDayDate = $today->copy()->subDay();
        $previousMtdData = DB::table('employee_workupdate')
            ->whereIn('emp_id', $req->emp_id)
            ->whereDate('created_at', $previousDayDate)
            ->select(
                'emp_id',
                'shoe_bill_mtd',
                'shoe_qty_mtd',
                'shirt_bill_mtd',
                'shirt_qty_mtd'
            )
            ->get()
            ->keyBy('emp_id');

        // Loop through the submitted data
        foreach ($req->emp_id as $i => $emp_id) {

            // Get previous MTD values, defaulting to 0 if not found
            $previous_mtd = $previousMtdData->get($emp_id);
            $shoe_bill_mtd_previous = $previous_mtd->shoe_bill_mtd ?? 0;
            $shoe_qty_mtd_previous = $previous_mtd->shoe_qty_mtd ?? 0;
            $shirt_bill_mtd_previous = $previous_mtd->shirt_bill_mtd ?? 0;
            $shirt_qty_mtd_previous = $previous_mtd->shirt_qty_mtd ?? 0;

            // If it's the first day of the month, reset all previous MTDs to 0
            if ($today->day == 1) {
                $shoe_bill_mtd_previous = 0;
                $shoe_qty_mtd_previous = 0;
                $shirt_bill_mtd_previous = 0;
                $shirt_qty_mtd_previous = 0;
            }

            // Get today's values from the request
            $ftd_shoe_bill = $req->shoe_bill[$i];
            $ftd_shoe_qty = $req->shoe_qty[$i];
            $ftd_shirt_bill = $req->shirt_bill[$i];
            $ftd_shirt_qty = $req->shirt_qty[$i];

            // Get target values from the pre-fetched collection, defaulting to 0 if not found
            $target = $targets->get($emp_id);
            $shoe_tgt = $target->shoe_value ?? 0;
            $shirt_tgt = $target->shirt_value ?? 0;

            // --- Calculate new MTD values with negative FTD handling (for both bill and qty) ---
            // Shoe Bill
            $new_shoe_bill_mtd = ($ftd_shoe_bill < 0)
                ? max(0, $shoe_bill_mtd_previous + $ftd_shoe_bill)
                : $shoe_bill_mtd_previous + $ftd_shoe_bill;

            // Shoe Quantity
            $new_shoe_qty_mtd = ($ftd_shoe_qty < 0)
                ? max(0, $shoe_qty_mtd_previous + $ftd_shoe_qty)
                : $shoe_qty_mtd_previous + $ftd_shoe_qty;

            // Shirt Bill
            $new_shirt_bill_mtd = ($ftd_shirt_bill < 0)
                ? max(0, $shirt_bill_mtd_previous + $ftd_shirt_bill)
                : $shirt_bill_mtd_previous + $ftd_shirt_bill;

            // Shirt Quantity
            $new_shirt_qty_mtd = ($ftd_shirt_qty < 0)
                ? max(0, $shirt_qty_mtd_previous + $ftd_shirt_qty)
                : $shirt_qty_mtd_previous + $ftd_shirt_qty;


            // Calculate achievement percentage based on the NEW MTD value, handling division by zero
            $shoe_ach = ($shoe_tgt > 0) ? ($new_shoe_qty_mtd / $shoe_tgt) * 100 : 0;
            $shirt_ach = ($shirt_tgt > 0) ? ($new_shirt_qty_mtd / $shirt_tgt) * 100 : 0;

            // Prepare data for bulk insert
            $dataToInsert[] = [
                'emp_id' => $emp_id,
                'store_id' => $store_id,
                'shoe_bill_ftd' => $ftd_shoe_bill,
                'shoe_bill_mtd' => $new_shoe_bill_mtd,
                'shoe_qty_ftd' => $ftd_shoe_qty,
                'shoe_qty_mtd' => $new_shoe_qty_mtd,
                'shoe_tgt' => $shoe_tgt,
                'shoe_ach' => $shoe_ach,
                'shirt_bill_ftd' => $ftd_shirt_bill,
                'shirt_bill_mtd' => $new_shirt_bill_mtd,
                'shirt_qty_ftd' => $ftd_shirt_qty,
                'shirt_qty_mtd' => $new_shirt_qty_mtd,
                'shirt_tgt' => $shirt_tgt,
                'shirt_ach' => $shirt_ach,
                'c_by' => auth()->user()->id,
                'created_at' => now(),
                'updated_at' => now()
            ];
        }

        // Perform a single bulk insert
        DB::table('employee_workupdate')->insert($dataToInsert);


        $count = DB::table('employee_workupdate')
            ->whereDate('created_at', date("Y-m-d"))
            ->where('store_id', $store_id)
            ->count();

        $list = DB::table('employee_workupdate')
            ->where('store_id', $store_id)
            ->orderBy('id', 'DESC')
            ->get();

        return redirect()->route('dsr.sale.list')->with([
            'status' => 'success',
            'message' => 'Work updated Successfully',
            'count' => $count,
            'list' => $list
        ]);
    }


    public function dsr_rental_list(Request $req)
    {
        $store_id = auth()->user()->store_id;

        $count = DB::table('emp_perf_workupdate')
            ->whereDate('created_at', now())
            ->where('store_id', $store_id)
            ->count();

        $list = DB::table('emp_perf_workupdate')
            ->leftJoin('users', 'emp_perf_workupdate.emp_id', '=', 'users.id')
            ->leftJoin('stores', 'emp_perf_workupdate.store_id', '=', 'stores.id')
            ->where('emp_perf_workupdate.store_id', $store_id)
            ->select('emp_perf_workupdate.*', 'users.name as username', 'stores.store_name')
            ->orderByDesc('emp_perf_workupdate.created_at')
            ->whereDate('emp_perf_workupdate.created_at', now())
            ->get();

        // $total_count = DB::table('emp_perf_work_total')
        //     ->whereDate('created_at', now())
        //     ->where('store_id', $store_id)
        //     ->count();

        $targets = DB::table('target')
            ->where('store_id', $store_id)
            ->where('month', now()->month)
            ->first();

        $today = now();
        $currentMonth = $today->month;
        $currentDay = $today->day;

        // LY-MTD values
        $lastYear = $today->year - 1;

        $ly_d = DB::table('ly_daywise')
            ->whereYear('date', $lastYear)
            ->whereMonth('date', $currentMonth)
            ->whereDay('date', $currentDay)
            ->where('store_id', $store_id)
            ->select(
                DB::raw('SUM(bill_mtd) as ly_bill'),
                DB::raw('SUM(qty_mtd) as ly_qty'),
                DB::raw('SUM(walkin_mtd) as ly_walk')
            )
            ->first();

        return view('store.dsr-rental-list', [
            'list' => $list,
            'count' => $count,
            'targets' => $targets,
            'ly_d' => $ly_d
        ]);
    }

    public function dsr_rental_cerate()
    {
        $sto_emp = DB::table('users')
            ->where('store_id', auth()->user()->store_id)
            ->whereIn('role_id', [12, 13, 14, 15, 16, 53])
            ->where('status', 1)
            ->select('name', 'id')
            ->get();

        $count = DB::table('emp_perf_workupdate')
            ->whereDate('created_at', now())
            ->where('store_id', auth()->user()->store_id)
            ->count();


        return view('store.dsr-rental-create', ['sto_emp' => $sto_emp, 'count' => $count]);
    }



    public function dsr_rental_store(Request $req)
    {

        // Convert the selected date into a Carbon instance
        // $selectedDate = \Carbon\Carbon::parse($req->date)->startOfDay();



        $store_id = auth()->user()->store_id;
        $today = now();
        $currentMonth = $today->month;
        $currentYear = $today->year;
        $currentDay = $today->day;
        $dataInsert = [];

        foreach ($req->emp_id  as $i => $emp_id) {


            // Use copy() to prevent modifying the original $today object
            $yesterday = $today->copy()->subDay()->toDateString();

            // Set targetDate based on whether it's the first of the month
            if ($today->day == 1) {
                $targetDate = $today->toDateString();
            } else {
                $targetDate = $yesterday;
            }

            $lastYear = $today->year - 1;
            // LY-MTD values
            $ly_d = DB::table('ly_daywise')
                ->whereYear('date', $lastYear)
                ->whereMonth('date', $currentMonth)
                ->whereDay('date', $currentDay)
                ->where('store_id', $store_id)
                ->select(
                    DB::raw('SUM(bill_mtd) as ly_bill'),
                    DB::raw('SUM(qty_mtd) as ly_qty'),
                    DB::raw('SUM(walkin_mtd) as ly_walk')
                    // DB::raw('SUM(loss) as ly_loss'),
                )
                ->first();

            // MTD values from the PREVIOUS day's record
            $mtd = DB::table('emp_perf_workupdate')
                ->whereDate('created_at', $targetDate)
                ->where('store_id', $store_id)
                ->where('emp_id', $emp_id)
                ->select(
                    'b_mtd',
                    'q_mtd',
                    'v_mtd',
                    // 'k_mtd',
                    'w_mtd'
                )
                ->first() ?? (object)[
                    'b_mtd' => 0,
                    'q_mtd' => 0,
                    'v_mtd' => 0,
                    // 'k_mtd' => 0,
                    'w_mtd' => 0,
                ];

            // If it's the first day of the month, reset the MTD values to zero
            if (now()->day == 1) {
                $mtd->b_mtd = 0;
                $mtd->q_mtd = 0;
                $mtd->v_mtd = 0;
                // $mtd->k_mtd = 0;
                $mtd->w_mtd = 0;
            }

            $walkin = DB::table('walkin')
                ->whereDate('created_at', now())
                ->where('store_id', $store_id)
                ->whereNotIn('walk_status', ['Trial', 'Return', 'Rentout', 'Revisit Booking', 'Revisit Loss', 'Reissue'])
                ->where('c_by', $emp_id)
                ->count();

            // targets performance
            $targets = DB::table('emp_performance_target')
                ->whereMonth('created_at', $currentMonth)
                ->whereYear('created_at', $currentYear)
                ->where('emp_id', $emp_id)
                ->first();

            // today ftd values
            $b_ftd = $req->bill_ftd[$i];
            $qty_ftd = $req->qty_ftd[$i];
            $value_ftd = $req->value_ftd[$i];
            // $kpi_ftd = $req->kpi_ftd[$i];

            // MTD values: Correctly adding FTD to previous day's MTD
            // $b_mtd = $mtd->b_mtd + $b_ftd;
            // $q_mtd = $mtd->q_mtd + $qty_ftd;
            // $v_mtd = $mtd->v_mtd + $value_ftd;
            // $k_mtd = $mtd->k_mtd + $kpi_ftd;
            // $w_mtd = $mtd->w_mtd + $walkin;


            $b_mtd = ($b_ftd < 0)
                ? max(0, $mtd->b_mtd + $b_ftd)  // subtract if negative, prevent going below 0
                : $mtd->b_mtd + $b_ftd;

            $q_mtd = ($qty_ftd < 0)
                ? max(0, $mtd->q_mtd + $qty_ftd)
                : $mtd->q_mtd + $qty_ftd;

            $v_mtd = ($value_ftd < 0)
                ? max(0, $mtd->v_mtd + $value_ftd)
                : $mtd->v_mtd + $value_ftd;

            $w_mtd = $mtd->w_mtd + $walkin;

            //LY values
            $b_ly = $ly_d->ly_bill ?? 0;
            $q_ly = $ly_d->ly_qty ?? 0;
            $w_ly = $ly_d->ly_walk ?? 0;

            // l2l calculations
            $b_l2l = ($b_ly != 0) ? (($b_mtd / $b_ly) - 1) * 100 : 0;
            $q_l2l = ($q_ly != 0) ? (($q_mtd / $q_ly) - 1) * 100 : 0;
            $w_l2l = ($w_ly != 0) ? (($w_mtd / $w_ly) - 1) * 100 : 0;

            // ABS calculations
            $ABS = ($b_mtd != 0) ? $q_mtd / $b_mtd : 0;

            //ABV calculations
            $ABV = ($b_mtd != 0) ? $v_mtd / $b_mtd : 0;

            // values
            $tgt_value = $targets->target ?? 0;
            $tgt_qty = $targets->target_qty ?? 0;

            //ACH
            $ACH = ($tgt_value != 0) ? ($v_mtd / $tgt_value) * 100 : 0;

            // FTD loss of sales
            $f_loss = ($walkin - $b_ftd);

            // FMTD loss of sales
            $m_loss = ($w_mtd - $b_mtd);

            // Conversion
            $conv = ($w_mtd != 0) ? (($b_mtd / $w_mtd) * 100) : 0;

            $dataInsert[] = [
                'store_id' => $store_id,
                'emp_id' => $emp_id,
                'b_ftd' => $b_ftd,
                'b_mtd' => $b_mtd,
                'b_ly' => $b_ly,
                'b_ltl' => $b_l2l,
                'q_ftd' => $qty_ftd,
                'q_mtd' => $q_mtd,
                'q_ly' => $q_ly,
                'q_ltl' => $q_l2l,
                'v_ftd' => $value_ftd,
                'v_mtd' => $v_mtd,
                'v_ly' => 0,
                'v_ltl' => 0,
                // 'k_ftd' => $kpi_ftd,
                // 'k_mtd' => $k_mtd,
                // 'k_lymtd' => 0,
                // 'k_ltl' => 0,
                'abs' => $ABS,
                'abv' => $ABV,
                'tgt_value' => $tgt_value,
                'ach_per' => $ACH,
                'tgt_qty' => $tgt_qty,
                'w_ftd' => $walkin,
                'w_mtd' => $w_mtd,
                'w_ly'  => $w_ly,
                'w_ltl' => $w_l2l,
                'los_ftd' => $f_loss,
                'los_mtd' => $m_loss,
                'conversion' => $conv,
                'c_by' => auth()->user()->id,
                'created_at' => now(),
                'updated_at' => now()
            ];
        }

        DB::table('emp_perf_workupdate')->insert($dataInsert);



        return to_route('dsr.rental.list')->with([
            'status' => 'success',
            'message' => 'Work update created'
        ])
            ->withHeaders([
                'Cache-Control' => 'no-store, no-cache, must-revalidate, max-age=0',
                'Pragma' => 'no-cache',
            ]);
    }


    public function dsr_rental_report(Request $request)
    {
        $authUser = auth()->user();
        $store = DB::table('stores')
            ->leftJoin('users', 'stores.id', '=', 'users.store_id')
            ->leftJoin('cluster_store', 'stores.id', '=', 'cluster_store.store_id')
            ->leftJoin('m_cluster', 'cluster_store.cluster_id', '=', 'm_cluster.id')
            ->leftJoin('area_cluster', 'm_cluster.id', '=', 'area_cluster.cluster_id') // Join the area_cluster table
            ->where('stores.status', 1)
            ->when(true, function ($query) use ($authUser) {
                if (in_array($authUser->role_id, [1, 2, 6, 66])) {
                    // Admins or Managers - show all stores
                    return $query;
                } elseif ($authUser->role_id == 11) {
                    // Cluster Head - only stores where they are assigned as cl_name
                    return $query->where('m_cluster.cl_name', $authUser->id);
                } elseif ($authUser->role_id == 12) {
                    // Employee - only their assigned store(s)
                    return $query->where('users.id', $authUser->id);
                } elseif ($authUser->role_id == 10) {
                    // Area Manager - only stores under clusters assigned to their area
                    return $query->whereIn('m_cluster.id', function ($subQuery) use ($authUser) {
                        $subQuery->select('cluster_id')
                            ->from('area_cluster')
                            ->whereIn('area_id', function ($inner) use ($authUser) {
                                $inner->select('id')
                                    ->from('m_area')
                                    ->where('a_man', $authUser->id);
                            });
                    });
                }
            })
            ->select(
                'stores.id as stores_id',
                'stores.store_name as stores_name',
                'm_cluster.cl_name as cluster_head_id'
            )
            ->distinct()
            ->get();

        // // This block of code only runs if the form is submitted via POST
        if ($request->isMethod('post') && $authUser->role_id == 12) {

            // Capture inputs
            $month       = $request->input('month');
            $date        = $request->input('date');
            $storeId     = $request->input('store_list');
            $employeeId  = $request->input('employee_id');

            // Base query for performance
            $query = DB::table('emp_perf_workupdate as epw')
                ->leftJoin('users as emp', 'emp.id', '=', 'epw.emp_id')
                ->leftJoin('target as tgt', function ($join) use ($month, $storeId) {
                    $parsedMonth = \Carbon\Carbon::parse($month);
                    $join->on('tgt.store_id', '=', 'epw.store_id')
                        ->whereMonth('tgt.created_at', '=', $parsedMonth->month)
                        ->whereYear('tgt.created_at', '=', $parsedMonth->year)
                        ->where('tgt.store_id', '=', $storeId);
                })
                ->select(
                    'epw.created_at',
                    'epw.store_id',
                    'epw.b_ftd',
                    'epw.b_mtd',
                    'epw.b_ly',
                    'epw.b_ltl',
                    'epw.q_ftd',
                    'epw.q_mtd',
                    'epw.q_ly',
                    'epw.q_ltl',
                    'epw.v_ftd',
                    'epw.v_mtd',
                    'epw.v_ly',
                    'epw.v_ltl',
                    'epw.k_ftd',
                    'epw.k_mtd',
                    'epw.k_lymtd',
                    'epw.k_ltl',
                    'epw.abs',
                    'epw.abv',
                    'epw.tgt_value',
                    'epw.ach_per',
                    'epw.tgt_qty',
                    'epw.w_ftd',
                    'epw.w_mtd',
                    'epw.w_ly',
                    'epw.w_ltl',
                    'epw.los_ftd',
                    'epw.los_mtd',
                    'epw.los_ltl',
                    'epw.los_ly',
                    'epw.conversion',
                    'epw.status',
                    'epw.c_by',
                    'epw.updated_at',
                    'emp.name as username',
                    'tgt.target as target_value',
                    'tgt.target_qty as target_qty'
                );

            // Apply filters
            if (!empty($date)) {
                $query->whereDate('epw.created_at', $date);
            }

            if (!empty($month)) {
                $parsedMonth = \Carbon\Carbon::parse($month);
                $query->whereMonth('epw.created_at', $parsedMonth->month)
                    ->whereYear('epw.created_at', $parsedMonth->year);
            }

            if (!empty($storeId)) {
                $query->whereIn('epw.store_id', (array)$storeId);
            }

            $employeeIds = $request->employee_list;

            if (!empty($employeeIds)) {
                $query->whereIn('epw.emp_id', $employeeIds);
            }

            $list = $query->get();

            // Extract store and date values from request
            $storeId = $request->store_list;
            $selectedDate = $request->date;
            $selectedMonth = $request->month;

            // Initialize LY data
            $lyData = (object)[
                'ly_bill' => 0,
                'ly_qty' => 0,
                'ly_walk' => 0,
                'ly_loss' => 0,
            ];

            // Fetch LY data based on selected filter
            if (!empty($selectedDate)) {
                // Get same date last year
                $lastYearDate = \Carbon\Carbon::parse($selectedDate)->subYear()->toDateString();

                $lyRecord = DB::table('ly_daywise')
                    ->where('store_id', $storeId)
                    ->whereDate('date', '=', $lastYearDate)
                    ->first();

                if ($lyRecord) {
                    $lyData->ly_bill = $lyRecord->bill_mtd ?? 0;
                    $lyData->ly_qty = $lyRecord->qty_mtd ?? 0;
                    $lyData->ly_walk = $lyRecord->walkin_mtd ?? 0;
                    $lyData->ly_loss = $lyRecord->loss ?? 0;
                }
            } elseif (!empty($selectedMonth)) {
                // Parse selected month and year
                $parsedMonth = \Carbon\Carbon::parse($selectedMonth);
                $month = $parsedMonth->month;
                $year = $parsedMonth->year;

                // Get list of distinct days in current year's performance data
                $daysWithData = DB::table('emp_perf_workupdate')
                    ->select(DB::raw('DATE(created_at) as perf_date'))
                    ->whereMonth('created_at', $month)
                    ->whereYear('created_at', $year)
                    ->where('store_id', $storeId)
                    ->distinct()
                    ->pluck('perf_date')
                    ->toArray();

                // Convert those days to same days last year
                $lastYearDates = array_map(function ($date) {
                    return \Carbon\Carbon::parse($date)->subYear()->toDateString();
                }, $daysWithData);

                // Now sum LY data only for those last year dates
                $lyRecord = DB::table('ly_daywise')
                    ->select(
                        DB::raw('SUM(bill_mtd) as ly_bill'),
                        DB::raw('SUM(qty_mtd) as ly_qty'),
                        DB::raw('SUM(walkin_mtd) as ly_walk')
                        // DB::raw('SUM(loss) as ly_loss')
                    )
                    ->where('store_id', $storeId)
                    ->whereIn(DB::raw('DATE(date)'), $lastYearDates)
                    ->first();

                if ($lyRecord) {
                    $lyData->ly_bill = $lyRecord->ly_bill ?? 0;
                    $lyData->ly_qty = $lyRecord->ly_qty ?? 0;
                    $lyData->ly_walk = $lyRecord->ly_walk ?? 0;
                    $lyData->ly_loss = $lyRecord->ly_loss ?? 0;
                }
            }

            $targets = DB::table('target')
                ->where('store_id', $storeId)
                ->where('month', now()->month)
                ->first();

            // Return view with all data
            return view('store.dsr-rental-report', [
                'store'   => $store,
                'list'    => $list,
                'ly_d'    => $lyData, // make sure this variable is used in your blade
                'targets' => $targets
            ]);
        }

        if ($request->isMethod('post') && in_array($authUser->role_id, [1, 2, 6, 11, 66])) {

            $month     = $request->input('month');
            $date      = $request->input('date');
            $storeIds  = $request->input('store_list', []); // array of IDs

            $results   = [];

            foreach ($storeIds as $storeId) {
                $query = DB::table('emp_perf_workupdate as epw')
                    ->where('epw.store_id', $storeId);

                // Apply filters
                if (!empty($date)) {
                    $query->whereDate('epw.created_at', $date);
                }
                if (!empty($month)) {
                    $parsedMonth = \Carbon\Carbon::parse($month);
                    $query->whereMonth('epw.created_at', $parsedMonth->month)
                        ->whereYear('epw.created_at', $parsedMonth->year);
                }

                // Aggregate values
                $list = $query->select(
                    DB::raw('SUM(epw.b_ftd) as b_ftd'),
                    DB::raw('SUM(epw.b_mtd) as b_mtd'),
                    DB::raw('SUM(epw.b_ly) as b_ly'),
                    DB::raw('SUM(epw.b_ltl) as b_ltl'),
                    DB::raw('SUM(epw.q_ftd) as q_ftd'),
                    DB::raw('SUM(epw.q_mtd) as q_mtd'),
                    DB::raw('SUM(epw.q_ly) as q_ly'),
                    DB::raw('SUM(epw.q_ltl) as q_ltl'),
                    DB::raw('SUM(epw.v_ftd) as v_ftd'),
                    DB::raw('SUM(epw.v_mtd) as v_mtd'),
                    DB::raw('SUM(epw.v_ly) as v_ly'),
                    DB::raw('SUM(epw.v_ltl) as v_ltl'),
                    DB::raw('SUM(epw.k_ftd) as k_ftd'),
                    DB::raw('SUM(epw.k_mtd) as k_mtd'),
                    DB::raw('SUM(epw.k_lymtd) as k_lymtd'),
                    DB::raw('SUM(epw.k_ltl) as k_ltl'),
                    DB::raw('SUM(epw.abs) as abs'),
                    DB::raw('SUM(epw.abv) as abv'),
                    DB::raw('SUM(epw.tgt_value) as tgt_value'),
                    DB::raw('SUM(epw.ach_per) as ach_per'),
                    DB::raw('SUM(epw.tgt_qty) as tgt_qty'),
                    DB::raw('SUM(epw.w_ftd) as w_ftd'),
                    DB::raw('SUM(epw.w_mtd) as w_mtd'),
                    DB::raw('SUM(epw.w_ly) as w_ly'),
                    DB::raw('SUM(epw.w_ltl) as w_ltl'),
                    DB::raw('SUM(epw.los_ftd) as los_ftd'),
                    DB::raw('SUM(epw.los_mtd) as los_mtd'),
                    DB::raw('SUM(epw.conversion) as conversion'),
                    DB::raw('MAX(epw.created_at) as created_at')
                )->first();

                // Store info
                $storeInfo = DB::table('stores')->where('id', $storeId)->first();
                $list->username = $storeInfo->store_name ?? 'Selected Store';

                /**
                 * Fetch LY Data for this store only
                 */
                $lyData = (object)[
                    'ly_bill' => 0,
                    'ly_qty'  => 0,
                    'ly_walk' => 0,
                ];

                if (!empty($date)) {
                    $lastYearDate = \Carbon\Carbon::parse($date)->subYear()->toDateString();
                    $lyRecord = DB::table('ly_daywise')
                        ->where('store_id', $storeId)
                        ->whereDate('date', '=', $lastYearDate)
                        ->first();

                    if ($lyRecord) {
                        $lyData->ly_bill = $lyRecord->bill_mtd ?? 0;
                        $lyData->ly_qty  = $lyRecord->qty_mtd ?? 0;
                        $lyData->ly_walk = $lyRecord->walkin_mtd ?? 0;
                    }
                } elseif (!empty($month)) {
                    $parsedMonth = \Carbon\Carbon::parse($month);
                    $daysWithData = DB::table('emp_perf_workupdate')
                        ->select(DB::raw('DATE(created_at) as perf_date'))
                        ->whereMonth('created_at', $parsedMonth->month)
                        ->whereYear('created_at', $parsedMonth->year)
                        ->where('store_id', $storeId)
                        ->distinct()
                        ->pluck('perf_date')
                        ->toArray();

                    $lastYearDates = array_map(function ($d) {
                        return \Carbon\Carbon::parse($d)->subYear()->toDateString();
                    }, $daysWithData);

                    $lyRecord = DB::table('ly_daywise')
                        ->select(
                            DB::raw('SUM(bill_mtd) as ly_bill'),
                            DB::raw('SUM(qty_mtd) as ly_qty'),
                            DB::raw('SUM(walkin_mtd) as ly_walk')
                        )
                        ->where('store_id', $storeId)
                        ->whereIn(DB::raw('DATE(date)'), $lastYearDates)
                        ->first();

                    if ($lyRecord) {
                        $lyData->ly_bill = $lyRecord->ly_bill ?? 0;
                        $lyData->ly_qty  = $lyRecord->ly_qty ?? 0;
                        $lyData->ly_walk = $lyRecord->ly_walk ?? 0;
                    }
                }

                // attach LY to current store row
                $list->ly_bill = $lyData->ly_bill;
                $list->ly_qty  = $lyData->ly_qty;
                $list->ly_walk = $lyData->ly_walk;

                /**
                 * ðŸ”¹ Fetch targets (per store)
                 */
                $targets = DB::table('target')
                    ->where('store_id', $storeId)
                    ->where('month', now()->month)
                    ->first();

                $list->target_value = $targets->tgt_value ?? 0;
                $list->target_qty   = $targets->tgt_qty ?? 0;

                // Push into results
                $results[] = $list;
            }

            // Pass all stores to view
            return view('store.dsr-rental-report', [
                'store' => $store,
                'list'  => $results // each store row has its own ly + target data
            ]);
        }


        // This code runs on the initial GET request to show the empty form
        return view('store.dsr-rental-report', ['store' => $store]);
    }

    public function dsr_sale_report(Request $request)
    {

        $authUser = auth()->user();
        $store = DB::table('stores')
            ->leftJoin('users', 'stores.id', '=', 'users.store_id')
            ->leftJoin('cluster_store', 'stores.id', '=', 'cluster_store.store_id')
            ->leftJoin('m_cluster', 'cluster_store.cluster_id', '=', 'm_cluster.id')
            ->leftJoin('area_cluster', 'm_cluster.id', '=', 'area_cluster.cluster_id') // Join the area_cluster table
            ->where('stores.status', 1)
            ->when(true, function ($query) use ($authUser) {
                if (in_array($authUser->role_id, [1, 2, 6, 66])) {
                    // Admins or Managers - show all stores
                    return $query;
                } elseif ($authUser->role_id == 11) {
                    // Cluster Head - only stores where they are assigned as cl_name
                    return $query->where('m_cluster.cl_name', $authUser->id);
                } elseif ($authUser->role_id == 12) {
                    // Employee - only their assigned store(s)
                    return $query->where('users.id', $authUser->id);
                } elseif ($authUser->role_id == 10) {
                    // Area Manager - only stores under clusters assigned to their area
                    return $query->whereIn('m_cluster.id', function ($subQuery) use ($authUser) {
                        $subQuery->select('cluster_id')
                            ->from('area_cluster')
                            ->whereIn('area_id', function ($inner) use ($authUser) {
                                $inner->select('id')
                                    ->from('m_area')
                                    ->where('a_man', $authUser->id);
                            });
                    });
                }
            })
            ->select(
                'stores.id as stores_id',
                'stores.store_name as stores_name',
                'm_cluster.cl_name as cluster_head_id'
            )
            ->distinct()
            ->get();


        // Operation 1: Role ID 12 - Show individual employee reports
        if ($request->isMethod('post') && $authUser->role_id == 12) {
            $month = $request->input('month');
            $date = $request->input('date');
            $storeId = $request->input('store_list');
            $employeeId = $request->input('employee_id');
            $query = DB::table('employee_workupdate as ew')
                ->leftJoin('users as emp', 'emp.id', '=', 'ew.emp_id')
                ->select(
                    'ew.created_at',
                    'ew.store_id',
                    'ew.emp_id',
                    'ew.shoe_bill_ftd',
                    'ew.shoe_bill_mtd',
                    'ew.shoe_qty_ftd',
                    'ew.shoe_qty_mtd',
                    'ew.shoe_tgt',
                    'ew.shoe_ach',
                    'ew.shirt_bill_ftd',
                    'ew.shirt_bill_mtd',
                    'ew.shirt_qty_ftd',
                    'ew.shirt_qty_mtd',
                    'ew.shirt_tgt',
                    'ew.shirt_ach',
                    'ew.c_by',
                    'ew.updated_at',
                    'emp.name as username'
                );
            if (!empty($date)) {
                $query->whereDate('ew.created_at', $date);
            }
            if (!empty($month)) {
                $parsedMonth = \Carbon\Carbon::parse($month);
                $query->whereMonth('ew.created_at', $parsedMonth->month)
                    ->whereYear('ew.created_at', $parsedMonth->year);
            }
            if (!empty($storeId)) {
                $query->where('ew.store_id', $storeId);
            }
            $employeeIds = $request->input('employee_list');

            if (! empty($employeeIds)) {
                $query->whereIn('ew.emp_id', $employeeIds);
            }
            $list = $query->get();
            return view('store.dsr-sales-report', ['store' => $store, 'list' => $list]);
        }

        // Operation 2 & 3: Role IDs 1, 2, 11 - Show consolidated store report
        if ($request->isMethod('post') && in_array($authUser->role_id, [1, 2, 6, 11, 66])) {
            $month = $request->input('month');  // yyyy-mm format
            $date = $request->input('date');
            $storeIds = $request->input('store_list', []); // multiple store IDs

            if (empty($storeIds) || (empty($date) && empty($month))) {
                return redirect()->back()->withErrors('Please select a date or month and at least one store.');
            }

            $list = [];

            foreach ($storeIds as $storeId) {
                $query = DB::table('employee_workupdate as ew')
                    ->leftJoin('stores as s', 's.id', '=', 'ew.store_id')
                    ->where('ew.store_id', $storeId);

                // If date is selected -> filter by that date
                if (!empty($date)) {
                    $query->whereDate('ew.created_at', $date);
                }

                // If month is selected -> filter by month range
                if (!empty($month)) {
                    $startOfMonth = \Carbon\Carbon::createFromFormat('Y-m', $month)->startOfMonth();
                    $endOfMonth   = \Carbon\Carbon::createFromFormat('Y-m', $month)->endOfMonth();
                    $query->whereBetween('ew.created_at', [$startOfMonth, $endOfMonth]);
                }

                $consolidatedData = $query->select(
                    's.store_name as storeName',
                    DB::raw('SUM(ew.shoe_bill_ftd) as total_shoe_bill_ftd'),
                    DB::raw('SUM(ew.shoe_bill_mtd) as total_shoe_bill_mtd'),
                    DB::raw('SUM(ew.shoe_qty_ftd) as total_shoe_qty_ftd'),
                    DB::raw('SUM(ew.shoe_qty_mtd) as total_shoe_qty_mtd'),
                    DB::raw('SUM(ew.shoe_tgt) as total_shoe_tgt'),
                    DB::raw('SUM(ew.shirt_bill_ftd) as total_shirt_bill_ftd'),
                    DB::raw('SUM(ew.shirt_bill_mtd) as total_shirt_bill_mtd'),
                    DB::raw('SUM(ew.shirt_qty_ftd) as total_shirt_qty_ftd'),
                    DB::raw('SUM(ew.shirt_qty_mtd) as total_shirt_qty_mtd'),
                    DB::raw('SUM(ew.shirt_tgt) as total_shirt_tgt'),
                    DB::raw('MAX(ew.created_at) as created_at')
                )
                    ->groupBy('s.store_name')
                    ->first();

                if ($consolidatedData) {
                    $shoeAch = ($consolidatedData->total_shoe_tgt > 0)
                        ? ($consolidatedData->total_shoe_qty_mtd / $consolidatedData->total_shoe_tgt) * 100
                        : 0;

                    $shirtAch = ($consolidatedData->total_shirt_tgt > 0)
                        ? ($consolidatedData->total_shirt_qty_mtd / $consolidatedData->total_shirt_tgt) * 100
                        : 0;

                    $list[] = (object)[
                        'username' => $consolidatedData->storeName,
                        'shoe_bill_ftd' => $consolidatedData->total_shoe_bill_ftd,
                        'shoe_bill_mtd' => $consolidatedData->total_shoe_bill_mtd,
                        'shoe_qty_ftd' => $consolidatedData->total_shoe_qty_ftd,
                        'shoe_qty_mtd' => $consolidatedData->total_shoe_qty_mtd,
                        'shoe_tgt' => $consolidatedData->total_shoe_tgt,
                        'shoe_ach' => $shoeAch,
                        'shirt_bill_ftd' => $consolidatedData->total_shirt_bill_ftd,
                        'shirt_bill_mtd' => $consolidatedData->total_shirt_bill_mtd,
                        'shirt_qty_ftd' => $consolidatedData->total_shirt_qty_ftd,
                        'shirt_qty_mtd' => $consolidatedData->total_shirt_qty_mtd,
                        'shirt_tgt' => $consolidatedData->total_shirt_tgt,
                        'shirt_ach' => $shirtAch,
                        'created_at' => $consolidatedData->created_at,
                    ];
                }
            }

            return view('store.dsr-sales-report', [
                'list' => $list,
                'store' => $store
            ]);
        }

        return view('store.dsr-sales-report', ['store' => $store]);
    }
}
