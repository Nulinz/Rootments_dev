<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AuthenticationController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'emp_code' => 'required',
            'password' => 'required',
        ]);

        $emp_code = $request->emp_code;
        $password = $request->password;

        $user = User::where('emp_code', $emp_code)->where('status', 1)->first();


        if ($user) {


            if (Auth::attempt(['emp_code' => $emp_code, 'password' => $password])) {

                // $user = DB::table('users')
                //     ->leftJoin('roles', 'users.role_id', '=', 'roles.id')
                //     ->where('users.id', Auth::id())
                //     ->select('roles.role')
                //     ->first();

                // $user = Auth::user()->load('role_rel'); // After fetching the user, eager load the role

                // dd($user);

                $routes = [
                    1 => 'gm.dashboard',
                    2 => 'gm.dashboard',
                    3 => 'hr.dashboard',
                    4 => 'hr.dashboard',
                    5 => 'hr.dashboard',
                    6 => 'operation.dashboard',
                    7 => 'fin.index',
                    10 => 'area.dashboard',
                    11 => 'cluster.dashboard',
                    12 => 'dashboard',
                    30 => 'maintain.index',
                    37 => 'warehouse.index',
                    41 => 'purchase.index',
                ];

                // $route = $routes[$user->role_id] ?? 'mydash.dashboard';
                $route = $routes[auth()->user()->role_id] ?? 'mydash.dashboard';




                return redirect()->route($route)->with([
                    'status' => 'success',
                    'message' => 'Welcome ' . Auth::user()->name,

                ]);
            }

            return redirect()->route('login')->with(['status' => 'error', 'message' => 'Invalid Password']);
        }


        return redirect()->route('login')->with(['status' => 'error', 'message' => 'Invalid Emp Code']);
    }

    public function logout()
    {
        if (Auth::check()) {
            $user = Auth::user();

            Auth::logout();
        }

        session()->flush();

        return redirect()->route('login')->with(['status' => 'success', 'message' => 'Logged Out']);
    }
    
    // public function find_store(Request $req)
    // {
        
    //     // dd($req);

    //     // $walkins = DB::table('walkin')->get();

    //     // foreach ($walkins as $walkin) {
    //     //     $user = DB::table('users')->where('id', $walkin->c_by)->first();

    //     //     if ($user) {
    //     //         DB::table('walkin')->where('id', $walkin->id)->update([
    //     //             'store_id' => $user->store_id ?? 0
    //     //         ]);
    //     //     }
    //     // }
    //     // if (Auth::check()) {
    //     //     $user = Auth::user();

    //     //     Auth::logout();
    //     // }

    //     // session()->flush();

    //     // return redirect()->route('login')->with(['status' => 'success', 'message' => 'Logged Out']);
    // }


}
