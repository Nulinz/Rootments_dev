<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class ResignController extends Controller
{

    public function list()
    {
      $app =   DB::table('resignations as rs')->where('rs.status','Approved')
      ->leftJoin('users','users.id','=','rs.created_by')
      ->leftJoin('roles','roles.id','=','users.role_id')
      ->select('rs.*','users.emp_code','users.name','roles.*','rs.id as res_id')
      ->get();

        $app = $app->map(function($new){

           $st =  DB::table('resign_list')->where('res_id',$new->res_id)->latest()->select('status','formality')->first();

           if ($st) {
                $new->status = $st->status;
                $new->for_status = $st->formality;
            } else {
                // If no status found, set it to null (or a default value)
                $new->status = null;
                $new->for_status = null;
            }

           return $new;

        //    dd($st);

        });

        // dd($app);

        return view('resign.list',['list'=>$app]);

    }

    public function profile(Request $req)
    {
        $pro =   DB::table('resignations as rs')->where('rs.id',$req->id)
      ->leftJoin('users','users.id','=','rs.created_by')
      ->leftJoin('roles','roles.id','=','users.role_id')
      ->select('rs.*','users.emp_code','users.name','roles.*','rs.id as res_id')
      ->first();

      $for_list = DB::table('resign_list as rl')->where('res_id',$req->id)
      ->leftJoin('users',function($join){

        $join->on('users.id','=','rl.c_by')
        ->whereNotNull('rl.c_by');


    })
    ->select('users.name','rl.*')
    ->get();

    $for_arr = DB::table('resign_list as rl')->where('res_id',$req->id)->pluck('formality')->toArray(); // Convert to array;


    //   dd($for_list);

        return view('resign.profile',['pro'=>$pro,'for_list'=> $for_list,'for_arr'=>$for_arr]);
    }

    /**
     * Store a newly created resource in storage.
     */
    // public function formality(Request $req)
    //  {
    //     $user = Auth::user();

    //     $res_lt = DB::table('resign_list')->insertGetId([
    //         'res_id' => $req->res_id,
    //         'formality' => $req->formal_type,
    //         'status' => 'Completed',
    //         'review' => $req->review,
    //         'c_by' => $user->id,
    //         'created_at' => now(),
    //         'updated_at' => now(),

    //     ]);

    //     $res_file = null;

    //     $path = 'assets/images/resign_docs/';

    //     if ($req->hasFile('attachment')) {
    //         $res_file = $req->file('attachment');
    //         // $name = date('y') . '-' . Str::upper(Str::random(8)) . '.' . $file->getClientOriginalExtension();
    //         $cer_ext = $res_file->getClientOriginalExtension();
    //         $res_name = uniqid('resign_file_') . '.' . $cer_ext; // Generate a unique filename

    //         $res_file->move($path, $res_name);

    //         $up_file = DB::table('resign_list')->where('id', $res_lt)
    //             ->update(['file' => ($path . $res_name)]);
    //     }

    //     if (strcasecmp(trim($req->formal_type), 'Exit Completed') === 0) {
    //         $resignation = DB::table('resignations')->where('id', $req->res_id)->first();

    //         $res_user = $resignation->emp_id;

    //         $up_activate = DB::table('users')->where('id', $res_user)->update(['status' => 2]);
    //     }



    //     if ($req->formal_type == 'Termination') {

    //         $user_inactive = DB::table('resignations')->where('id', $req->res_id)->first();

    //         $up_activate = DB::table('users')->where('id', $user_inactive->created_by)->update(['status' => 2]);
    //     }

    //     if ($res_lt) {
    //         // Redirect back with a success message if insertion is successful
    //         return redirect()->back()->with([
    //             'status' => 'success',
    //             'message' => 'Resignation Formality Updated successfully!'
    //         ]);
    //     }

    //     // If insertion fails, you can redirect with an error message
    //     return redirect()->back()->with([
    //         'status' => 'error',
    //         'message' => 'There was an issue with the operation.'
    //     ]);
    // }
    
     public function formality(Request $req)
    {
        $user = Auth::user();

        $res_lt = DB::table('resign_list')->insertGetId([
            'res_id' => $req->res_id,
            'formality' => $req->formal_type,
            'status' => 'Completed',
            'review' => $req->review,
            'c_by' => $user->id,
            'created_at' => now(),
            'updated_at' => now(),

        ]);

        $res_file = null;

        $path = 'assets/images/resign_docs/';

        if ($req->hasFile('attachment')) {
            $res_file = $req->file('attachment');
            // $name = date('y') . '-' . Str::upper(Str::random(8)) . '.' . $file->getClientOriginalExtension();
            $cer_ext = $res_file->getClientOriginalExtension();
            $res_name = uniqid('resign_file_') . '.' . $cer_ext; // Generate a unique filename

            $res_file->move($path, $res_name);

            $up_file = DB::table('resign_list')->where('id', $res_lt)
                ->update(['file' => ($path . $res_name)]);
        }
        
         DB::table('resignations')->where('id', $req->res_id)->update([
            'end_date' => $req->end_date,
        ]);

        if (strcasecmp(trim($req->formal_type), 'Exit Completed') === 0) {
            $resignation = DB::table('resignations')->where('id', $req->res_id)->first();

            $res_user = $resignation->emp_id;

            $up_activate = DB::table('users')->where('id', $res_user)->update(['status' => 2]);
        }



        if ($req->formal_type == 'Termination') {

            $user_inactive = DB::table('resignations')->where('id', $req->res_id)->first();

            $up_activate = DB::table('users')->where('id', $user_inactive->created_by)->update(['status' => 2]);
        }

        if ($res_lt) {
            // Redirect back with a success message if insertion is successful
            return redirect()->back()->with([
                'status' => 'success',
                'message' => 'Resignation Formality Updated successfully!'
            ]);
        }

        // If insertion fails, you can redirect with an error message
        return redirect()->back()->with([
            'status' => 'error',
            'message' => 'There was an issue with the operation.'
        ]);
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
