<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AreaController extends Controller
{

    public function list()
    {
        $list = DB::table('m_area')
            ->leftJoin('users', 'users.id', '=', 'm_area.a_man')
            ->leftJoin('area_cluster as ac', 'ac.area_id', '=', 'm_area.id')
            ->select('m_area.id', 'users.name', 'm_area.location', 'users.contact_no', 'users.email', DB::raw('COUNT(ac.id) as cluster_count'))
            ->groupBy('m_area.a_man')
            ->get();

        //  return $list;
        return view('area.list', ['list' => $list]);
    }

    public function create()
    {
        $am = DB::table('users')->where('users.role_id', 10)->where('status', 1)->select('id', 'name')->get();

        $cluster = DB::table('m_cluster as mc')
            ->leftJoin('users', 'users.id', '=', 'mc.cl_name') // LEFT JOIN with the stores table
            ->leftJoin('cluster_store', 'cluster_store.cluster_id', '=', 'mc.id')
            ->leftJoin('area_cluster', 'area_cluster.cluster_id', '=', 'mc.id')
            ->whereNull('area_cluster.cluster_id')->select('mc.id', 'users.name', 'mc.location', 'users.contact_no', 'users.email', DB::raw('COUNT(cluster_store.id) as cluster_count'))->groupBy('users.id', 'mc.id')->get();

        // return $cluster;

        return view('area.add', ['am' => $am, 'cluster' => $cluster]);
    }

    public function area_overview()
    {
        $user = auth()->user();

        $list_cluster = DB::table('m_area as ma')
            ->leftJoin('area_cluster as ac', 'ac.area_id', '=', 'ma.id')
            ->leftJoin('m_cluster as mc', 'mc.id', '=', 'ac.cluster_id')
            ->leftJoin('users', 'users.id', '=', 'mc.cl_name')
            ->select('users.name', 'mc.id')
            ->where('ma.a_man', $user->id)->get();

        // Initialize an array to hold cluster ids
        $cluster_ids = [];

        // Loop through the list_cluster results to extract the cluster ids
        foreach ($list_cluster as $cluster) {
            $cluster_ids[] = $cluster->id;
        }

        $store = DB::table('cluster_store as cs')
            ->leftjoin('stores as st', 'st.id', '=', 'cs.store_id')
            ->where('cs.cluster_id', $cluster_ids[0])
            ->select('st.store_code', 'st.store_name', 'st.store_geo')
            ->get();


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
        //   return $store;

        return view('area.overview', ['list' => $list_cluster, 'store' => $store, 'task_ext' => $task_ext,  'tast_ext' => $tast_ext]);
    }

    public function area_kpi(Request $request)
    {
        return view('area.areakpidashboard');
    }

    public function area_per(Request $req)
    {
        $data = DB::table('users')->where('id', $req->area_per)->select('contact_no', 'email', 'address', 'district', 'state', 'pincode')->first();




        return response()->json($data, 200);
        // return view('');
    }



    public function cluster_store(Request $req)
    {

        $store = DB::table('cluster_store as cs')
            ->leftjoin('stores as st', 'st.id', '=', 'cs.store_id')
            ->where('cs.cluster_id', $req->cluster)
            ->select('st.store_code', 'st.store_name', 'st.store_geo')
            ->get();

        // return $store;

        return response()->json($store, 200);
    }

    public function show(string $id)
    {
        // $id = $req->id;

        $area = DB::table('m_area as ma')
            ->leftJoin('users as user', 'user.id', '=', 'ma.a_man') // Joining users table
            ->where('ma.id', $id) // Filter by the cluster id
            ->select('ma.id', 'user.name', 'user.contact_no', 'user.email', 'ma.alter', 'user.address', 'user.pincode', 'ma.location', 'user.profile_image') // Select the required fields from users table
            ->first(); // Get the first matching result


        $cluster_list = DB::table('area_cluster as ac')
            ->leftJoin('m_area as ma', 'ma.id', '=', 'ac.area_id')  // Joining the area table
            ->leftJoin('m_cluster as mc', 'mc.id', '=', 'ac.cluster_id')  // Joining the cluster table
            ->leftJoin('users as us', 'us.id', '=', 'mc.cl_name')  // Joining the users table
            ->leftJoin('cluster_store', 'cluster_store.cluster_id', '=', 'ac.cluster_id')  // Joining the cluster_store table
            ->select(
                'us.name',
                'us.contact_no',
                'us.email',
                'mc.location',
                'mc.id',
                DB::raw('COUNT(cluster_store.id) as cluster_store_count')  // Count the number of cluster_store records
            )
            ->where('ac.area_id', $area->id)  // Correctly filtering based on the area_id in the area_cluster table
            ->groupBy('us.id', 'mc.id')  // Grouping by user and cluster to get the count per user/cluster pair
            ->get();

        //   return($cluster_list);

        return view('area.profile', ['area' => $area, 'list' => $cluster_list]);
    }

    public function edit(string $id)
    {
        return view('area.edit');
    }

    public function create_area(Request $req)
    {

        $create = DB::table('m_area')->insertGetId([
            'a_man' => $req->areaname,
            'alter' => $req->altcontact,
            'location' => $req->arealoc,
            'created_at' => now(),  // Don't manually include these!
            'updated_at' => now()   // Don't manually include these!

        ]);

        foreach ($req->cl_id as $cluster) {

            $create_list = DB::table('area_cluster')->insert([
                'area_id' => $create,
                'cluster_id' => $cluster,
                'created_at' => now(),  // Don't manually include these!
                'updated_at' => now()   // Don't manually include these!
            ]);
        }

        if ($create && $create_list) {
            return redirect()->route('area.list')->with([
                'status' => 'success',
                'message' => 'Area Added successfully!'
            ]);
        } else {

            return redirect()->route('area.list')->with([
                'status' => 'Failure',
                'message' => 'Area Failed to Add!'
            ]);
        }
    }

    public function update(Request $request, string $id)
    {
        //
    }

    public function destroy(string $id)
    {
        //
    }
}
