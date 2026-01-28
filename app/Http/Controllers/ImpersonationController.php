<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Session;

class ImpersonationController extends Controller
{

    public function start($id)
    {
        $userToImpersonate = User::findOrFail($id);

        if (auth()->user()->id === $userToImpersonate->id) {
            return redirect()->back()->with('error', 'You cannot impersonate yourself.');
        }

        // Save current admin ID in session
        session(['impersonator_id' => auth()->id()]);

        Auth::login($userToImpersonate);

        return redirect()->route($this->getDashboardRoute($userToImpersonate->role_id))
            ->with([
                'status' => 'success',
                'message' => 'Now Logged in as ' . $userToImpersonate->name]);
    }

    public function stop()
    {
        // Check if we have an impersonator stored
        if (session()->has('impersonator_id')) {
            $impersonatorId = session('impersonator_id');
            $impersonator = User::find($impersonatorId);

            if ($impersonator) {
                Auth::login($impersonator); // Switch back to original user
            }

            // Remove the impersonator_id from the session
            session()->forget('impersonator_id');

            return redirect()->route($this->getDashboardRoute($impersonator->role_id))
               ->with([
                    'status' => 'success',
                    'message' => 'Returned to your account.'
                ]);
        }

        // If no impersonator_id found, just log out
        Auth::logout();
        return redirect()->route('login')->with([
             'status' => 'success',
            'message', 'You have been logged out.']
            );
    }

    // public function stop()
    // {
    //     return 'Stop method from controller!';
    // }

    // public function stop()
    // {
    //     $impersonatorId = session('impersonator_id');

    //     if ($impersonatorId) {
    //         Auth::logout();
    //         Auth::loginUsingId($impersonatorId);
    //         session()->forget('impersonator_id');

    //         return redirect()->route($this->getDashboardRoute(auth()->user()->role_id))
    //             ->with('status', 'Returned to your admin account.');
    //     }

    //     return redirect()->route('login')->with('error', 'Impersonation session not found.');
    // }

    private function getDashboardRoute($roleId)
    {
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

        return $routes[$roleId] ?? 'mydash.dashboard';
    }

    // public function impersonate($id)
    // {
    //     $user = User::findOrFail($id);
    //     $adminId = auth()->id(); // Get admin ID before logout

    //     Auth::logout(); // This clears the session

    //     Auth::login($user); // Log in as the impersonated user

    //     // Now store admin ID in session AFTER new login
    //     session(['impersonated_by' => $adminId]);

    //     $routes = [
    //         1 => 'gm.dashboard',
    //         2 => 'gm.dashboard',
    //         3 => 'hr.dashboard',
    //         4 => 'hr.dashboard',
    //         5 => 'hr.dashboard',
    //         6 => 'operation.dashboard',
    //         7 => 'fin.index',
    //         10 => 'area.dashboard',
    //         11 => 'cluster.dashboard',
    //         12 => 'dashboard',
    //         30 => 'maintain.index',
    //         37 => 'warehouse.index',
    //         41 => 'purchase.index',
    //     ];

    //     $route = $routes[$user->role_id] ?? 'mydash.dashboard';

    //     return redirect()->route($route)->with([
    //         'status' => 'success',
    //         'message' => 'Welcome ' . Auth::user()->name,
    //     ]);
    // }


    // public function stop()
    // {
    //     \Log::info('Stop method reached in ImpersonationController');
    //     return 'Stop route hit from controller!';
    // }


    // public function stop()
    // {
    //     // $adminId = session('impersonated_by');

    //     // if ($adminId) {
    //     //     Auth::loginUsingId($adminId);
    //     //     session()->forget(['impersonated_by', 'impersonated_store_id']);
    //     //     return redirect()->route('admin.dashboard')->with('status', 'Impersonation ended.');
    //     // }

    //     // return redirect()->route('login')->with('error', 'Not impersonating.');
    //     $adminId = session('impersonated_by');

    //     if ($adminId) {
    //         Auth::loginUsingId($adminId);
    //         session()->forget(['impersonated_by', 'impersonated_store_id']);

    //         $admin = auth()->user();

    //         // Optional: role-based redirect
    //         $routes = [
    //             1 => 'gm.dashboard',
    //             2 => 'gm.dashboard',
    //             3 => 'hr.dashboard',
    //             4 => 'hr.dashboard',
    //             // etc...
    //         ];

    //         $route = $routes[$admin->role_id] ?? 'gm.dashboard';

    //         // Check if route exists before redirecting
    //         if (!Route::has($route)) {
    //             return abort(404, "Route {$route} not found for role_id {$admin->role_id}");
    //         }

    //         return redirect()->route($route)->with('status', 'Impersonation ended.');
    //     }

    //     return redirect()->route('login')->with('error', 'Not impersonating.');
    // }


    // private function getRedirectRoute($roleId)
    // {
    //     $routes = [
    //         1 => 'gm.dashboard',
    //         2 => 'gm.dashboard',
    //         3 => 'hr.dashboard',
    //         4 => 'hr.dashboard',
    //         5 => 'hr.dashboard',
    //         6 => 'operation.dashboard',
    //         7 => 'fin.index',
    //         10 => 'area.dashboard',
    //         11 => 'cluster.dashboard',
    //         12 => 'dashboard',
    //         30 => 'maintain.index',
    //         37 => 'warehouse.index',
    //         41 => 'purchase.index',
    //     ];

    //     return $routes[$roleId] ?? 'mydash.dashboard';
    // }

    // public function impersonate(User $id)
    // {
    //     // $store_id = DB::table('users')->where('id', $user)->select('store_id');
    //     $user = DB::table('users')->where('id', $id)->first();

    //     // Only allow admin (role_id = 1)
    //     if (!auth()->check() || auth()->user()->role_id !== 1) {
    //         return redirect()->route('dashboard')->with('error', 'Only admin can impersonate.');
    //     }

    //     // Store admin's ID before impersonating
    //     session()->put('impersonated_by', auth()->id());

    //     // Store the impersonated user's store_id if exists
    //     if ($user->store_id) {
    //         session()->put('impersonated_store_id', $user->store_id);
    //     }

    //     // Login as the impersonated user
    //     Auth::login($user);

    //     return redirect()->route($this->getRedirectRoute($user->role_id))
    //         ->with('status', 'Now viewing as ' . $user->name);
    // }

    // public function impersonate($id)
    // {
    //     $user = User::findOrFail($id);
    //     Auth::logout();

    //     $adminId = auth()->id();

    //     Auth::login($user);

    //     // Store admin ID in session to remember who impersonated
    //     session(['impersonated_by' => $adminId]);

    //     if ($user) {


    //         $routes = [
    //             1 => 'gm.dashboard',
    //             2 => 'gm.dashboard',
    //             3 => 'hr.dashboard',
    //             4 => 'hr.dashboard',
    //             5 => 'hr.dashboard',
    //             6 => 'operation.dashboard',
    //             7 => 'fin.index',
    //             10 => 'area.dashboard',
    //             11 => 'cluster.dashboard',
    //             12 => 'dashboard',
    //             30 => 'maintain.index',
    //             37 => 'warehouse.index',
    //             41 => 'purchase.index',
    //         ];

    //         // $route = $routes[$user->role_id] ?? 'mydash.dashboard';
    //         $route = $routes[auth()->user()->role_id] ?? 'mydash.dashboard';




    //         return redirect()->route($route)->with([
    //             'status' => 'success',
    //             'message' => 'Welcome ' . Auth::user()->name,

    //         ]);

    //         return redirect()->route('login')->with(['status' => 'error', 'message' => 'Invalid Password']);
    //     }


    //     // return redirect()->route('dashboard')->with('error', 'Only admin can impersonate.');
    // }
}
