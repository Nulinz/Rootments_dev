<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Services\FirebaseService;
use App\Models\Notification;

class StoreSetupController extends Controller
{
    public function list()
    {

        $list = DB::table('set_up')->get();

        // dd($list);
        return view('setup.list',['list'=>$list]);
    }

    public function create()
    {
        return view('setup.add');
    }

    public function profile($tab = null,Request $req)
    {
         $pro = DB::table('set_up')->where('id',$req->id)->first();

         $set_list = DB::table('e_setup')->where('set_id',$req->id)->get();

        //  $cmp = DB::table('e_setup')->where('cat','')->where('sub','')

        // if($tab=='details'){

        // }

        //  dd($pro);

        return view('setup.profile',['tab'=>$tab,'pro'=> $pro,'list'=>$set_list]);
    }

    public function store(Request $req)
    {
        $user = Auth::user();

        $ins = DB::table('set_up')->insertGetId([
            'st_name'=>$req->storename,
            'st_add'=>$req->address,
            'st_city'=>$req->city,
            'st_state'=>$req->state,
            'st_pin'=>$req->pincode,
            'st_loc'=>$req->geolocation,
            'status'=>'Active',
            'c_by'=>$user->id,
            'created_at'=>now(),
            'updated_at'=>now(),
        ]);

        $for_token  = DB::table('users')->whereIn('role_id',[1,2])->get();

        foreach($for_token as $req_token){

        if (!is_null($req_token->device_token)) {

            $taskTitle ="Store Setup Request";

            $role_get = DB::table('roles')->where('id', auth()->user()->role_id)->first();


            $taskBody = "New Store Setup has created by ".auth()->user()->name."[".$role_get->role."] - ". $req->storename;

            $response = app(FirebaseService::class)->sendNotification($req_token->device_token,$taskTitle,$taskBody);

            Notification::create([
                'user_id' => $req_token->id ?? 0,
                'noty_type' => 'Store Setup',
                'type_id' => $ins,
                'title'=> $taskTitle,
                'body'=> $taskBody,
                'c_by'=>$user->id
            ]);
         } // notification end

    } // foreach end....

        $list = DB::table('set_up')->get();

        if($ins){
            return response()->view('setup.list',['status'=>'sucess','message'=>'Store set Up added Successfully','list'=>$list]);
        }else{
            return response()->view('setup.list',['status'=>'Failed','message'=>'Store set Up Failed to add']);
        }
    }


    public function set_list_store(Request $req,)
    {
        $user = Auth::user();

        $ins = DB::table('e_setup')->insertGetId([
            'set_id'=>$req->set_id,
            'cat'=>$req->setupcat,
            'sub'=>$req->setupsubcat,
            'remark'=>$req->remarks,
            'status'=>'Active',
            'c_by'=>$user->id,
            'created_at'=>now(),
            'updated_at'=>now(),
        ]);

        $path = 'assets/images/setup_docs/';

        if ($req->hasFile('attachment')) {
            $cer_file = $req->file('attachment');
            // $name = date('y') . '-' . Str::upper(Str::random(8)) . '.' . $file->getClientOriginalExtension();
                $cer_ext = $cer_file->getClientOriginalExtension();
                $cer_name = uniqid('setup_file_') . '.' . $cer_ext; // Generate a unique filename

            $cer_file->move($path, $cer_name);

            $f_path = $path.$cer_name;


        }

        if (!empty($f_path)) {
            $up_file = DB::table('e_setup')->where('id', $ins)
                ->update(['file'=>$f_path]);
        }


        if(($req->setupcat=='Store Furniture & Fittings Setup')&&($req->setupsubcat=='Cash Counter & Storage Units')){

                    $req_token  = DB::table('users')->whereIn('role_id',[3])->first();

                    $setup_table = DB::table('set_up')->where('id',$req->set_id)->first();


                if (!is_null($req_token->device_token)) {

                    $taskTitle ="Store Setup Completed";
                    $taskBody = $setup_table->st_name." Store Setup Process Completed ";

                    $response = app(FirebaseService::class)->sendNotification($req_token->device_token,$taskTitle,$taskBody);

                    Notification::create([
                        'user_id' => $req_token->id ?? 0,
                        'noty_type' => 'Store Setup',
                        'type_id' => $req->set_id,
                        'title'=> $taskTitle,
                        'body'=> $taskBody,
                        'c_by'=>$user->id
                    ]);
                } // notification end

        }// if close for HR nottification





        if($ins){
            return back()->with(['status'=>'sucess','message'=>'Store set Up added Successfully']);
        }else{
            return back()->with(['status'=>'Failed','message'=>'Store set Up Failed to add']);
        }
    }

    public function edit(string $id)
    {
        //
    }

    public function setlist_update(Request $req)
    {
        $up = DB::table('e_setup')->where('id',$req->e_id)
        ->update([
            'status'=>$req->status,
            's_remark'=>$req->s_remark
        ]);

        $set_up = DB::table('e_setup')->where('id',$req->e_id)->first();

        $st_name1 = DB::table('set_up')->where('id',$set_up->set_id)->first();

        $req_token  = DB::table('users')->where('role_id',[30])->first();


        if (!is_null($req_token->device_token)) {
            $taskTitle ="Store Setup Update";
            $taskBody = $st_name1->st_name."-".$set_up->sub." has ".$req->status." by GM/AGM";

            $response = app(FirebaseService::class)->sendNotification($req_token->device_token,$taskTitle,$taskBody);

            Notification::create([
                'user_id' => $req_token->id ?? 0,
                'noty_type' => 'E_store',
                'type_id' => $req->e_id,
                'title'=> $taskTitle,
                'body'=> $taskBody,
                'c_by'=>auth()->user()->id
            ]);
         } // notification end


        if($up){
            return back()->with(['status'=>'sucess','message'=>'Store set Up Updated Successfully']);
        }else{
            return back()->with(['status'=>'Failed','message'=>'Store set Up Failed to Update']);
        }
    }

    public function store_new(Request $req)
    {

        $max_id = DB::table('stores')->max('id');

        $store_no = 'STORE' . str_pad($max_id + 1, 2, '0', STR_PAD_LEFT);

       $up = DB::table('set_up')->where('id',$req->id)->update(['status'=>'Complete','st_code'=>$store_no]);


       if($up){
         return redirect('store-add')->with(['status'=>'sucess']);
       }

    }
}
