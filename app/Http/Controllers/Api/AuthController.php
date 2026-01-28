<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class AuthController extends Controller
{

    // Manually set API token
    private $apiToken = 'Bearer RootX-production-9d17d9485eb772e79df8564004d4a4d4';


    /**
     * Login method to authenticate user and return token.
     */
    public function login(Request $request)
    {
        $request->validate([
            'emp_code' => 'required',
            'password' => 'required',
            // 'device_token' => 'required',
        ]);

        //  $user = User::with('role')->where('emp_code', $request->emp_code)->first();

        // $user = User::where('emp_code', $request->emp_code)->first();
          $user = User::where('emp_code', $request->emp_code)->where('status', 1)->first();
            
        if (!$user) {
            return response()->json(['error' => 'User not registered.'], 404);
        }

        if (!Hash::check($request->password, $user->password)) {
            return response()->json(['error' => 'Invalid credentials'], 401);
        }


        $user->device_token = $request->device_token;
        $user->save();

        $role = DB::table('roles')->where('id', $user->role_id)->select('role')->first();

        $token = $user->createToken('token')->plainTextToken;

        $profileImageUrl = $user->profile_image && file_exists($user->profile_image) ? url($user->profile_image) : null;

        return response()->json([
            'status' => 'success',
            'data' => [
                'id' => $user->id,
                'name' => $user->name,
                'emp_code' => $user->emp_code,
                'role' => $role->role ?? null,
                'role_id' => $user->role_id ?? null,
                'store_id' => $user->store_id ?? null,
                'profile_image_url' => $profileImageUrl,
                'token' => $token,
                'store_geo' => $user->store_rel->store_cordinates ?? '',
            ],
        ]);
    }


    /**
     * Logout method to invalidate user session.
     */
    public function logout(Request $request)
    {
        $user = User::find(Auth::id()); // Find the authenticated user by ID

        //   /  dd($user);

        if ($user) {
            $user->device_token = null; // Set device_token to null
            $user->save(); // Save the updated user


            $request->user()->currentAccessToken()->delete();

            return response()->json([
                'status' => 'success',
                'message' => 'Successfully logged out.',
            ]);
        }

        return response()->json(['error' => 'Unauthorized'], 401);
    }

    public function update(Request $request)
    {
        $request->validate([
            'password' => ['sometimes', 'nullable', 'min:6'],
        ]);

        $user_id = $request->input('user_id');
        $password = $request->password;

        $user = User::where('id', $user_id)->first();

        if (!$user) {
            return response()->json(['status' => 'error', 'message' => 'Unauthorized Profile'], 404);
        }

        if ($password) {
            if (Hash::check($password, $user->password)) {
                return response()->json(['status' => 'error', 'message' => 'You Entered Old Password'], 400);
            }

            $user->password = Hash::make($password);
            $user->save();
        }

        return response()->json(['status' => 'success', 'message' => 'Password Updated Successfully'], 200);
    }


    public function popup(Request $request)
    {

        return response()->json(['version' => '1.0.9'], 200);
    }



    public function verify_api(Request $request)
    {
        // Check for token in header
        $authHeader = $request->header('Authorization');
        if ($authHeader !== $this->apiToken) {
            return response()->json([
                'status' => 'fail',
                'message' => 'Unauthorized: Invalid or missing token'
            ], 401);
        }

        // // Validate input
        // $request->validate([
        //     'employeeId' => 'required|string',
        //     'password' => 'required|string'
        // ]);

        // Find employee
        $employee = User::with(['role_rel:id,role', 'store_rel:id,store_name'])->where('emp_code', $request->employeeId)->where('status', 1)->first();

        if (!$employee) {
            return response()->json([
                'status' => 'fail',
                'message' => 'Invalid Employee'
            ], 401);
        }

        if (!$employee || !Hash::check($request->password, $employee->password)) {
            return response()->json([
                'status' => 'fail',
                'message' => 'Invalid credentials'
            ], 401);
        }

        // $token = 'RootX-' . app()->environment() . '-admin-' . bin2hex(random_bytes(16));

        // Success
        return response()->json([
            'status' => 'success',
            'data' => [
                'employeeId' => $employee->emp_code,
                'name' => $employee->name,
                'role' => $employee->role_rel->role ?? 'No Role',
                'Store' => $employee->store_rel->store_name ?? 'No Store'
            ]
        ]);
    }


    // rsponse range employee

    public function employee_range(Request $request)
    {
        // Check for token in header
        $authHeader = $request->header('Authorization');
        if ($authHeader !== $this->apiToken) {
            return response()->json([
                'status' => 'fail',
                'message' => 'Unauthorized: Invalid or missing token'
            ], 401);
        }

        // dd($request->all());
        $employeeQuery = User::with(['role_rel:id,role', 'store_rel:id,store_name'])->where('status', 1);

        // Option 1: Use range from startEmpId to endEmpId
        if ($request->filled('startEmpId') && $request->filled('endEmpId')) {
            // $emp =  $employeeQuery->whereBetween('emp_code', [$request->startEmpId, $request->endEmpId])->select('name', 'emp_code')->get();

            $startNum = (int) filter_var($request->startEmpId, FILTER_SANITIZE_NUMBER_INT);
            $endNum = (int) filter_var($request->endEmpId, FILTER_SANITIZE_NUMBER_INT);

            $employeeQuery->whereRaw("CAST(SUBSTRING(emp_code, 4) AS UNSIGNED) BETWEEN ? AND ?", [$startNum, $endNum]);
        }
        // Option 2: Use list of employeeIds
        elseif ($request->filled('employeeIds') && is_array($request->employeeIds)) {
            $employeeQuery->whereIn('emp_code', $request->employeeIds);
        } else {
            return response()->json([
                'status' => 'fail',
                'message' => 'Invalid input: provide either start/end ID or employeeIds array.'
            ], 400);
        }

        // // Get users and transform output
        // $employees = $employeeQuery->get(['name', 'emp_code', 'role_id', 'store_id'])->map(function ($item) {
        //     return [
        //         'name'       => $item->name,
        //         'emp_code'   => $item->emp_code,
        //         'role_name'  => optional($item->role_rel)->role ?? 'No Role',
        //         'store_name ' => optional($item->store_rel)->store_name ?? 'No Store',
        //     ];
        // });
        
          // Get users and transform output
        $employees = $employeeQuery->get(['name', 'emp_code', 'role_id', 'email', 'contact_no', 'pre_start_date', 'store_id'])->map(function ($item) {
            return [
                'name'       => $item->name,
                'emp_code'   => $item->emp_code,
                'role_name'  => optional($item->role_rel)->role ?? 'No Role',
                'email' => $item->email  ?? 'No Email',
                'phone' => $item->contact_no,
                'start date' => $item->pre_start_date,
                'store_name' => optional($item->store_rel)->store_name ?? 'No Store',
            ];
        });

        return response()->json([
            'status' => 'success',
            'data' => $employees
        ]);
    }
}
