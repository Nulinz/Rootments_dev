<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Resignation;
use Illuminate\Support\Facades\Auth;
use App\Services\FirebaseService;
use App\Models\Notification;
use App\Http\Controllers\trait\common;

class ResignationController extends Controller
{
    use common;
    /**
     * Display a listing of the resource.
     */


        public function index()
    {
        $user_id = Auth::user()->id;

        $resgination = DB::table('resignations')
        ->where('resignations.created_by',$user_id)
        ->leftjoin('users','users.id','=','resignations.emp_id')
        // ->leftJoin('resignations as rs','rs.emp_id','=', 'users.id')
        ->select('resignations.*','users.emp_code')

        ->get();

        return view('resgination.list',['resgination'=>$resgination]);
    }

    /**
     * Show the form for creating a new resource.
     */
   public function create()
    {
        $show = $this->role_dept();

        // Log::info($show);

        $list = DB::table('users')
            ->whereIn('role_id', $show)
            ->where('users.status', 1)
            ->when($show == [12], function ($query) {
                return $query->where('store_id', auth()->user()->store_id);
            })
            ->select('users.id', 'users.name')
            ->get();

        $user = auth()->user();
        $store = DB::table('stores')->where('id', $user->store_id)->first();
        // $hr = DB::table('users')->whereIn('role_id', [3,4,5])->select('users.id','users.name')->get();

        $hr_list = $list ?? null;

        return view('resgination.add', ['hr_list' => $hr_list]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
         $user_id = auth()->user();


            $resgination = new Resignation();
            $resgination->emp_id =$request->emp_id;
            $resgination->emp_name =$request->emp_name;
            $resgination->store_id =$request->store_id;
            $resgination->loc =$request->loc;
            $resgination->res_date =$request->res_date;
            $resgination->res_reason =$request->res_reason;
            $resgination->created_by= $user_id->id;
            $resgination->request_to = $request->request_to;
            $res_save = $resgination->save();

            $req_token  = DB::table('users')->where('id',$request->request_to)->first();

            if (!is_null($req_token->device_token)) {

                $role_get = DB::table('roles')->where('id', $user_id->role_id)->first();

                $taskTitle = "Resignation Request";

                $taskBody = $user_id->name."[".$user_id->role_rel->role."] Requested for Resignation";

                $response = app(FirebaseService::class)->sendNotification($req_token->device_token,$taskTitle,$taskBody);

                Notification::create([
                    'user_id' => $req_token->id,
                    'noty_type' => 'resignation',
                    'type_id' => $resgination->id,
                    'title'=> $taskTitle,
                    'body'=> $taskBody,
                    'c_by'=>auth()->user()->id
                ]);

                // dd($response);
            } // notification end


        return redirect()->route('resignation.index')->with([
            'status' => $res_save ? 'success' : 'failed',
            'message' => $res_save ? 'Resgination Request Added successfully!' : 'Resgination Request Failed to Add!'
        ]);
    }

    public function updateEscalate(Request $request)
    {
        DB::table('resignations')
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
