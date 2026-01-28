<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RetirementController extends Controller
{
    public function retire_list()
    {
        //     $ret_qry = db::table('retirement as rt')
        //         ->leftJoin('users', 'rt.c_by', '=', 'users.id')
        //         ->select('rt.*', 'rt.c_by as created');

        //     if (auth()->user()->role_id != 2) {
        //         $ret_qry->where('rt.c_by', '=', auth()->user()->id);
        //     }

        //     $ret_list = $ret_qry->get();


        $ret_qry = db::table('retirement as rt')
            ->leftJoin('users', 'rt.c_by', '=', 'users.id')
            ->select('rt.*', 'rt.c_by as created')
            ->where('rt.c_by', '=', auth()->user()->id)
            ->get();

        return view('retirement.retirement-list', ['ret_list' => $ret_qry]);
    }

    public function add_retire()
    {
        $retire = DB::table('users')->where('id', 2)->select('name', 'id')->get();

        return view('retirement.add-retirement', ['retire' => $retire]);
    }
    public function store_retire(Request $req)
    {

        DB::table('retirement')->insert([
            'emp_code' => $req->emp_code,
            'emp_name' => $req->emp_name,
            'req_date' => $req->req_date,
            'req_type' => $req->req_type,
            'reason' => $req->reason,
            'req_to' => $req->req_to,
            'c_by' => auth()->user()->id,
            'status' => 'Pending',
            'created_at' => now(),
            'updated_at' => now()
        ]);

        return  redirect()->route('retirement.retire_list')->with([
            'status' => 'success',
            'Message' => 'Retirement request created'
        ]);
    }
}
