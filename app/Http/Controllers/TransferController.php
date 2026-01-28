<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Transfer;
use Illuminate\Support\Facades\Auth;
use App\Models\Notification;

class TransferController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user_id = Auth::user()->id;

        $transfer = DB::table('transfers')
                    ->leftJoin('stores as from_stores', 'from_stores.id', '=', 'transfers.fromstore_id')
                    ->leftJoin('stores as to_stores', 'to_stores.id', '=', 'transfers.tostore_id')
                    ->leftJoin('users', 'users.id', '=', 'transfers.emp_id')
                    ->select(
                        'transfers.*',
                        'from_stores.store_name as from_store_name',
                        'to_stores.store_name as to_store_name',
                        'users.emp_code'
                    )
                    ->where('transfers.created_by',$user_id)
                    ->get();


        return view('transfer.list',['transfer'=>$transfer]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {

        $store=DB::table('stores')->get();

        return view('transfer.add',['store'=>$store]);
    }

    public function getempname(Request $request)
    {
        $user_id = auth()->user()->id;

        $empname = DB::table('users')
            ->leftjoin('stores','users.store_id','=','stores.id')
            ->select('users.id', 'users.emp_code', 'users.name','stores.id as store_id','stores.store_code','stores.store_name')
            ->where('users.id', $user_id)
            ->first();

        return response()->json($empname);
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $user_id = auth()->user()->id;

          $role_get = DB::table('roles')
            ->leftJoin('users', 'users.role_id', '=', 'roles.id')
            ->select('roles.id', 'roles.role', 'roles.role_dept')
            ->where('users.id', $user_id)
            ->first();


        if ($role_get) {
            $transfer = new Transfer();
            $transfer->emp_id =$request->emp_id;
            $transfer->emp_name =$request->emp_name;
            $transfer->fromstore_id =$request->fromstore_id;
            $transfer->tostore_id =$request->tostore_id;
            $transfer->transfer_date =$request->transfer_date;
            $transfer->transfer_description =$request->transfer_description;
            $transfer->created_by =$user_id;


            $role = $role_get->role;
            $role_dept = $role_get->role_dept;

            $manager_departments = ['Operation', 'Finance', 'IT', 'Sales/Marketing', 'Area', 'Cluster'];

            if ($role === 'Store Manager' && $role_dept === 'Store') {
                $transfer->request_to = 3;
            } elseif ($role === 'Manager') {
                if ($role_dept === 'HR') {
                    $transfer->request_to = 1;
                } elseif (in_array($role_dept, $manager_departments)) {
                    $transfer->request_to = 3;
                } else {
                    $transfer->request_to = 12;
                }
            } elseif ($role === 'Managing Director') {
                $transfer->request_to = 3;
            } else {
                $transfer->request_to = 12;
            }

            $transfer->save();
            
            Notification::create([
                'user_id'   => $user->id,
                'noty_type' => 'recruitments',
                'type_id'   => $recruitment_id, // Corrected this
            ]);
        }

        return redirect()->route('transfer.index')->with([
            'status' => 'success',
            'message' => 'Transfer Request Added successfully!'
        ]);

    }

    public function updateEscalate(Request $request)
    {
        DB::table('transfers')
            ->where('id', $request->id)
            ->update(['esculate_to' => 3, 'updated_at' => now()]);

        return response()->json(['message' => 'Escalated successfully!']);
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
}
