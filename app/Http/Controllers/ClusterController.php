<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\Task;
use App\Models\{Cluster, User, Role};
use Carbon\Carbon;
use App\Http\Controllers\DashBoardController;

class ClusterController extends Controller
{
    public function index()
    {

         $clusterquery = DB::table('m_cluster as mc')
            ->leftjoin('cluster_store as cs', 'cs.cluster_id', '=', 'mc.id')
            ->leftJoin('users', function ($join) {
                $join->on('users.id', '=', 'mc.cl_name');
            })
            ->select('mc.id', 'mc.cl_name', 'mc.location', DB::raw('COUNT(cs.cluster_id) as cl_count'), 'users.contact_no', 'users.email', 'users.name')
            ->groupBy('mc.id');

        if (!in_array(auth()->user()->role_id, [1, 2, 3])) {
            $clusterquery->where('users.id', '=', auth()->user()->id);
        }

        $cluster =  $clusterquery->get();
        
    
        return view('cluster.list', ['cluster' => $cluster]);
    }

    public function cluster_overview()
    {

        $user = Auth::user()->id;
        $cluster = DB::table('m_cluster as mc')
            ->leftJoin('users as user', 'user.id', '=', 'mc.cl_name') // Joining users table
            ->where('mc.cl_name', $user) // Filter by the cluster id
            ->select('user.name', 'user.contact_no', 'user.email', 'mc.alter_con as alter', 'user.address', 'user.pincode', 'mc.location', 'user.profile_image', 'mc.id') // Select the required fields from users table
            ->first(); // Get the first matching result

        // return $cluster;


        $cluster_list = DB::table('cluster_store as cs')
            ->leftJoin('stores as store', 'store.id', '=', 'cs.store_id') // Joining users table
            ->where('cs.cluster_id', $cluster->id)
            ->get();

        foreach ($cluster_list as $cs) {

            $st_man = DB::table('users')->where('role_id', 12)->where('store_id', $cs->store_id)->select('users.name')->first();

            $cs->st_name = $st_man ? $st_man->name : null;
        }

        $task_ext = DB::table('task_ext')->where('request_for', Auth::id())
            ->leftJoin('tasks as tk', 'task_ext.task_id', '=', 'tk.id')
            ->leftJoin('users as us', 'task_ext.c_by', '=', 'us.id')

            ->select(
                'task_ext.id',
                'task_ext.extend_date',
                'task_ext.c_remarks',
                'task_ext.created_at',
                'task_ext.category',
                'task_ext.status',
                'tk.task_title',
                'task_ext.attach',
                'us.name'
            )
            ->whereIn('task_ext.status', ['Pending', 'Close Request'])
            ->orderBy('task_ext.created_at', 'desc')
            ->get();


        $tast_ext = DB::table('task_ext')
            ->select('task_id', 'status')
            ->orderBy('id', 'desc')
            ->get()
            ->unique('task_id')
            ->keyBy('task_id');

        // //   return($cluster_list);
        return view('cluster.overview', ['cl_data' => $cluster_list, 'task_ext' => $task_ext,  'tast_ext' => $tast_ext]);
    }

    public function cluster_mydashboard()
    {
        $authId = Auth::user()->id;

        $user = Auth::user();

        $role = Role::find($user->role_id);

        $store = DB::table('stores')->where('id', $user->store_id)->first();

        $employeesQuery = DB::table('users')
            ->select('id', 'name')
            ->whereNotNull('role_id');

        if (!in_array($user->dept, ['Admin', 'HR']) && $store) {
            $employeesQuery->where('store_id', $store->id);
        }

        $employeesQuery->where('id', '!=', $user->id);

        $employees = $employeesQuery->get();


        $tasks_todo = DB::table('tasks')
            ->leftJoin('categories', 'tasks.category_id', '=', 'categories.id')
            ->leftJoin('sub_categories', 'tasks.subcategory_id', '=', 'sub_categories.id')
            ->leftJoin('roles as assigned_role', 'tasks.assign_to', '=', 'assigned_role.id')
            ->leftJoin('roles as assigned_by_role', 'tasks.assign_by', '=', 'assigned_by_role.id')
            ->leftJoin('users as assigned_by_user', 'tasks.assign_by', '=', 'assigned_by_user.id')
            ->where('tasks.assign_to', $authId)
            ->where('tasks.task_status', 'To Do')
            ->select(
                'tasks.*',
                'categories.category',
                'sub_categories.subcategory',
                'assigned_role.role as assigned_role',
                'assigned_by_role.role as task_assigned',
                'assigned_by_user.name as assigned_by'
            )
            ->orderBy('tasks.id', 'DESC')
            ->get();

        $tasks_todo_count = DB::table('tasks')
            ->where('assign_to', $authId)
            ->where('task_status', 'To Do')
            ->count();

        $tasks_inprogress = DB::table('tasks')
            ->leftJoin('categories', 'tasks.category_id', '=', 'categories.id')
            ->leftJoin('sub_categories', 'tasks.subcategory_id', '=', 'sub_categories.id')
            ->leftJoin('roles as assigned_role', 'tasks.assign_to', '=', 'assigned_role.id')
            ->leftJoin('roles as assigned_by_role', 'tasks.assign_by', '=', 'assigned_by_role.id')
            ->leftJoin('users as assigned_by_user', 'tasks.assign_by', '=', 'assigned_by_user.id')
            ->where('tasks.assign_to', $authId)
            ->where('tasks.task_status', 'In Progress')
            ->select(
                'tasks.*',
                'categories.category',
                'sub_categories.subcategory',
                'assigned_role.role as assigned_role',
                'assigned_by_role.role as task_assigned',
                'assigned_by_user.name as assigned_by'
            )
            ->orderBy('tasks.id', 'DESC')
            ->get();

        $tasks_inprogress_count = DB::table('tasks')
            ->where('assign_to', $authId)
            ->where('task_status', 'In Progress')
            ->count();

        $tasks_onhold = DB::table('tasks')
            ->leftJoin('categories', 'tasks.category_id', '=', 'categories.id')
            ->leftJoin('sub_categories', 'tasks.subcategory_id', '=', 'sub_categories.id')
            ->leftJoin('roles as assigned_role', 'tasks.assign_to', '=', 'assigned_role.id')
            ->leftJoin('roles as assigned_by_role', 'tasks.assign_by', '=', 'assigned_by_role.id')
            ->leftJoin('users as assigned_by_user', 'tasks.assign_by', '=', 'assigned_by_user.id')
            ->where('tasks.assign_to', $authId)
            ->where('tasks.task_status', 'On Hold')
            ->select(
                'tasks.*',
                'categories.category',
                'sub_categories.subcategory',
                'assigned_role.role as assigned_role',
                'assigned_by_role.role as task_assigned',
                'assigned_by_user.name as assigned_by'
            )
            ->orderBy('tasks.id', 'DESC')
            ->get();

        $tasks_onhold_count = DB::table('tasks')
            ->where('assign_to', $authId)
            ->where('task_status', 'On Hold')
            ->count();

        $tasks_complete = DB::table('tasks')
            ->leftJoin('categories', 'tasks.category_id', '=', 'categories.id')
            ->leftJoin('sub_categories', 'tasks.subcategory_id', '=', 'sub_categories.id')
            ->leftJoin('roles as assigned_role', 'tasks.assign_to', '=', 'assigned_role.id')
            ->leftJoin('roles as assigned_by_role', 'tasks.assign_by', '=', 'assigned_by_role.id')
            ->leftJoin('users as assigned_by_user', 'tasks.assign_by', '=', 'assigned_by_user.id')
            ->where('tasks.assign_to', $authId)
            ->where('tasks.task_status', 'Completed')
            ->select(
                'tasks.*',
                'categories.category',
                'sub_categories.subcategory',
                'assigned_role.role as assigned_role',
                'assigned_by_role.role as task_assigned',
                'assigned_by_user.name as assigned_by'
            )
            ->orderBy('tasks.id', 'DESC')
            ->get();


        $tasks_complete_count = DB::table('tasks')
            ->where('assign_to', $authId)
            ->where('task_status', 'Completed')
            ->count();

        return view('generaldashboard.mydashboard', ['tasks_todo' => $tasks_todo, 'tasks_todo_count' => $tasks_todo_count, 'tasks_inprogress' => $tasks_inprogress, 'tasks_inprogress_count' => $tasks_inprogress_count, 'tasks_onhold' => $tasks_onhold, 'tasks_onhold_count' => $tasks_onhold_count, 'tasks_complete' => $tasks_complete, 'tasks_complete_count' => $tasks_complete_count, 'employees' => $employees, 'role' => $role]);


        // return view ('cluster.mydashboard');
    }

    public function cluster_strength()
    {

        $user = Auth::user()->id;
        $cluster = DB::table('m_cluster as mc')
            ->leftJoin('users as user', 'user.id', '=', 'mc.cl_name') // Joining users table
            ->where('mc.cl_name', $user) // Filter by the cluster id
            ->select('mc.id') // Select the required fields from users table
            ->first(); // Get the first matching result

        //  $cluster_list= DB::table('cluster_store as cs')
        // ->leftJoin('stores as store', 'store.id', '=', 'cs.store_id') // Joining users table
        // ->leftJoin('store_lists as sl', function($join) {
        //     $join->on('sl.store_ref_id', '=', 'cs.store_id');// Join on store_id and store_ref_id
        // })
        // ->where('cs.cluster_id',$cluster->id)
        // ->select('store.store_code','sl.role_id','sl.req_count','sl.emp_count') // Select the required fields from users table
        // ->get();
        $store_list = DB::table('cluster_store as cs')
            ->leftJoin('stores as store', 'store.id', '=', 'cs.store_id') // Joining users table
            ->where('cs.cluster_id', $cluster->id)
            ->select('store.store_code', 'store.id', 'store.store_name') // Select the required fields from users table
            ->get();

        $role = [12, 13, 14, 15, 16, 17, 18, 19]; // List of role IDs
        foreach ($store_list as $sl) {
            // Initialize an empty array to store the final roles for this store
            $store_roles = [
                'sl' => $sl->store_code, // Store the store code as 'sl'
                'st_name' => $sl->store_name, // Store the store code as 'sl'
                'roles' => [] // This will hold the roles array
            ];

            foreach ($role as $r) {
                // Fetch the role data for the current store and role
                $rol_req = DB::table('store_lists')
                    ->where('store_ref_id', $sl->id)
                    ->where('role_id', $r)
                    ->select('role_id', 'req_count', 'emp_count')
                    ->first(); // Use first() to get a single result

                // Check if the result is empty (i.e., no data for this role)
                if (!$rol_req) {
                    // If no data, create a default entry with 0 counts
                    $rol_req = (object) [
                        'role_id' => $r,
                        'req_count' => 0,
                        'emp_count' => 0
                    ];
                }

                // Add the role data to the roles array
                $store_roles['roles'][] = [
                    'role_id' => $rol_req->role_id,
                    'req_count' => $rol_req->req_count,
                    'emp_count' => $rol_req->emp_count
                ];
            }

            // Add the store's roles with their data to the final list
            $sl_list[] = $store_roles;
        }

        // return $sl_list;

        return view('cluster.strength', ['store_list' => $sl_list]);
    }

    public function create(Request $req)
    {
        $create = DB::table('m_cluster')->insertGetId([
            'cl_name' => $req->clustername,
            'alter_con' => $req->altcontact,
            'location' => $req->storeloc,
            'created_at' => now(),  // Don't manually include these!
            'updated_at' => now()   // Don't manually include these!

        ]);

        foreach ($req->store as $st_id) {

            $create_list = DB::table('cluster_store')->insert([
                'cluster_id' => $create,
                'store_id' => $st_id,
                'created_at' => now(),  // Don't manually include these!
                'updated_at' => now()   // Don't manually include these!
            ]);
        }

        if ($create && $create_list) {
            return redirect()->route('cluster.index')->with([
                'status' => 'success',
                'message' => 'Cluster Added successfully!'
            ]);
        } else {

            return redirect()->route('cluster.index')->with([
                'status' => 'Failure',
                'message' => 'Cluster Failed to Add!'
            ]);
        }
    }

    public function drop_show()
    {
        // $cluster = DB::table('users')
        // ->leftjoin('m_cluster','users.id','!=','m_cluster.cl_name')
        // ->where('role_id','11')->where('status','1')->select('id','name')->get();

        $cluster = DB::table('users')
            ->leftJoin('m_cluster', 'users.id', '=', 'm_cluster.cl_name')  // Correct LEFT JOIN condition
            ->whereIn('users.role_id', [11, 12])  // Filter users with role_id = 11
            ->whereNull('m_cluster.cl_name')  // Exclude users whose ID is in the cl_name column
            ->where('users.status', 1)
            ->select('users.id', 'users.name')
            ->get();

        $store = DB::table('stores')
            ->leftJoin('cluster_store as cs', 'cs.store_id', '=', 'stores.id') // LEFT JOIN with the stores table
            ->leftJoin('users', function ($join) {
                $join->on('users.store_id', '=', 'stores.id') // Join on store_id and store_ref_id
                    ->where('users.role_id', 12); // Additional condition for users' role_id = 12
            })
            ->whereNull('cs.store_id')
             ->where('users.status', 1)
            ->select(
                'stores.id as st_id',
                'stores.store_code',
                'stores.store_name',
                'users.name as user_name', // Select the user name from the users table
                'stores.store_geo',
                'stores.store_contact'
            )
            ->get(); // Execute the query and get the result

        // $new_obg = [];

        // foreach ($store as $st) {

        //     $count = DB::table('cluster_store')->where('store_id', $st->store_ref_id)->count();

        //     if ($count > 0) {
        //         continue;
        //     } else {
        //         $new_obg[] = $st;
        //     }
        // }


        // $arr = [
        //     'data' => $data,
        //     'store' => $new_obg,
        // ];

        //  return $store;

        return view('cluster.add', ['cluster' => $cluster, 'stores' => $store]);
    }

    public function cluster_det(Request $req)
    {
        $data = DB::table('users')->where('id', $req->cluster_per)->select('contact_no', 'email', 'address', 'district', 'state', 'pincode')->first();

        // $store = DB::table('stores')->where('status','1')->select('*')->get();


        return response()->json($data, 200);
        // return view('');
    }

    public function show(string $id)
    {
        $cluster = DB::table('m_cluster as mc')
            ->leftJoin('users as user', 'user.id', '=', 'mc.cl_name') // Joining users table
            ->where('mc.id', $id) // Filter by the cluster id
            ->select('user.name', 'user.contact_no', 'user.email', 'mc.alter_con as alter', 'user.address', 'user.pincode', 'mc.location', 'user.profile_image') // Select the required fields from users table
            ->first(); // Get the first matching result


        $cluster_list = DB::table('cluster_store as cs')
            ->leftJoin('stores as store', 'store.id', '=', 'cs.store_id') // Joining users table
            ->where('cluster_id', $id)->get();

        foreach ($cluster_list as $cs) {

                $st_man = DB::table('users')->where('role_id', 12)->where('store_id', $cs->store_id)->where('status', 1)->select('users.name')->first();


            $cs->st_name = $st_man ? $st_man->name : null;
        }



        //  return $cluster_list;
        return view('cluster.profile', ['mc' => $cluster, 'clust_store' => $cluster_list]);
    }


    public function edit($id)
    {

        // $cluster_avail = DB::table('users')
        //     ->leftJoin('m_cluster', 'users.id', '=', 'm_cluster.cl_name')  // Correct LEFT JOIN condition
        //     ->whereIn('users.role_id', [11, 12])  // Filter users with role_id = 11
        //     ->whereNull('m_cluster.cl_name')  // Exclude users whose ID is in the cl_name column
        //     ->select('users.id', 'users.name')
        //     ->get();

        // Step 1: Get the current cluster with related stores
        $check = Cluster::with('cluster_store')->where('id', $id)->first();

        $cluster_avail = DB::table('users')
            ->leftJoin('m_cluster', 'users.id', '=', 'm_cluster.cl_name')
            ->whereIn('users.role_id', [11, 12])
            ->where(function ($query) use ($check) {
                $query->whereNull('m_cluster.cl_name')
                    ->orWhere('users.id', $check->cl_name);
            })
            ->select('users.id', 'users.name')
            ->get();

        // Step 2: Initialize variables
        $stores = collect(); // default empty collection
        $excludedStores = [];

        // Step 3: Proceed if cluster is found
        if ($check) {
            // Get the user assigned to the cluster
            $user = User::find($check->cl_name);
            $check->user_name = $user->name ?? null;
            $check->user_id = $user->id ?? null;
            $check->user_data = $user ?? null;

            // Get all store IDs used in other clusters (excluding this cluster)
            $excludedStores = DB::table('cluster_store')
                ->where('cluster_id', '!=', $id)
                ->pluck('store_id')
                ->toArray();

            // Get available stores not in other clusters and active
            $stores = DB::table('stores')
                ->whereNotIn('id', $excludedStores)
                ->where('status', 1)
                ->get();


            // Get store IDs assigned to this cluster
            $matchedStoreIds = $check->cluster_store->pluck('store_id')->toArray();

            foreach ($stores as $store) {
                $store->is_matched = in_array($store->id, $matchedStoreIds);
            }


            // // Mark matched stores inside this cluster
            // foreach ($check->cluster_store as $storeLink) {
            //     $storeLink->is_matched = $user && $storeLink->store_id == $user->store_id;
            // }
        }



        // $edit_cluster = DB::table('m_cluster as mc')
        //     ->where('mc.id', $id)
        //     ->leftJoin('cluster_store as cs', 'cs.cluster_id', '=', 'mc.id')
        //     ->leftJoin('stores as s', 's.id', '=', 'cs.store_id') // ← Join to store table
        //     ->leftJoin('users', function ($join) {
        //         $join->on('users.id', '=', 'mc.cl_name');
        //     })
        //     ->get();


        // dd($check->toArray());


        return view('cluster.edit', ['check' => $check, 'cluster' => $cluster_avail, 'stores' => $stores]);
    }


    public function update(Request $req)
    {

        // dd($req->toArray());
        $update = DB::table('m_cluster')->where('id', $req->edit_id)->update([
            'cl_name' => $req->clustername,
            'alter_con' => $req->altcontact,
            'location' => $req->storeloc,
        ]);

        // Step 2: Insert new stores, avoid duplicates
        $newStoresAdded = false;

        foreach ($req->store as $st_id) {
            $exists = DB::table('cluster_store')
                ->where('cluster_id', $req->edit_id)
                ->where('store_id', $st_id)
                ->exists();

            if (!$exists) {
                DB::table('cluster_store')->insert([
                    'cluster_id' => $req->edit_id,
                    'store_id'   => $st_id,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                $newStoresAdded = true;
            }
        }


        if ($update || $newStoresAdded) {
            return redirect()->route('cluster.index')->with([
                'status' => 'success',
                'message' => 'Cluster Added successfully!'
            ]);
        } else {

            return redirect()->route('cluster.index')->with([
                'status' => 'Failure',
                'message' => 'Cluster Failed to Add!'
            ]);
        }
    }
    public function cluster_delete(Request $req, $id)
    {
        $deleted = DB::table('m_cluster')->where('id', $id)->delete();

        DB::table('cluster_store')->where('cluster_id', $id)->delete();

        if ($deleted) {
            return redirect()->route('cluster.index')->with([
                'status' => 'success',
                'message' => 'Cluster deleted successfully!'
            ]);
        } else {
            return redirect()->route('cluster.index')->with([
                'status' => 'Failure',
                'message' => 'Cluster deletion failed!'
            ]);
        }
    }
}
