<?php

namespace App\Http\Controllers\trait;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

trait common
{
    // A sample method inside the trait
    public function attd_index($dept)
    {
        // \Log::info($message);
        $user = Auth::user();

        $departments = is_array($dept) ? $dept : [$dept];

        $emp = DB::table('roles')->whereIn('role_dept', $departments)
            ->leftJoin('users as us', 'us.role_id', '=', 'roles.id')
            ->leftJoin('attendance', function ($query) {
                $query->on('us.id', '=', 'attendance.user_id')
                    ->whereDate('attendance.c_on', Carbon::today());
            })
            ->where('us.id', '!=', $user->id)
            ->where('us.status', '=', 1)  // Added this condition for users with status = 1
            ->select(
                'us.id as user_id',
                'us.name',
                'us.profile_image',
                'attendance.in_time',
                'attendance.c_on',
                'attendance.attend_status',
                'attendance.out_time',
                'attendance.status',
                'attendance.in_location'
            )
            ->get();

        return $emp;
    }

    // Another sample method inside the trait
    public function get_emp_dept($dept, $st = 1)
    {
        // \Log::info($message);

        $user = Auth::user();

        if (($dept == 'HR') || ($dept == 'Admin') || ($dept == 'Operation')) {

            $emp = DB::table('users as us')->where('us.status', '=', $st)
                ->whereNotNull('us.role_id')
                ->leftJoin('stores', 'us.store_id', '=', 'stores.id')
                ->leftJoin('roles', 'roles.id', '=', 'us.role_id')
                ->select(
                    'us.id',
                    'us.name',
                    'us.emp_code',
                    'us.email',
                    'us.contact_no',
                    'roles.role',
                    'roles.role_dept',
                    'stores.store_name'
                )
                ->get();
        } else {

            $emp = DB::table('roles')->where('role_dept', $dept)
                ->leftJoin('users as us', 'us.role_id', '=', 'roles.id')
                ->whereNotNull('us.role_id')
                ->select(
                    'us.id',
                    'us.name',
                    'us.emp_code',
                    'us.email',
                    'us.contact_no',
                    'roles.role',
                    'roles.role_dept'
                )
                ->get();

            //  dd($emp->toSql());

        }



        return $emp;
    }


    // Another sample method inside the trait
    public function role_arr()
    {
        // \Log::info($message);
        $user = Auth::user();
        $r_id = $user->role_id;

        $cluster_check = DB::table('m_cluster as mc')
            ->leftJoin('users', 'users.id', '=', 'mc.cl_name')
            ->where('mc.cl_name', '=', $user->id)
            ->where('users.role_id', 12)
            ->count();

        switch ($r_id) {
            case 1:
                $arr = [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21, 22, 23, 24, 25, 26, 27, 28, 29, 30, 31, 32, 33, 34, 35, 36, 37, 38, 39, 40, 41, 42, 43, 44, 45, 46, 47, 48, 49, 50, 51, 52, 53, 54, 55, 56, 57, 63, 66];
                break;
            case 2:
                $arr = [2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21, 22, 23, 24, 25, 26, 27, 28, 29, 30, 31, 32, 33, 34, 35, 36, 37, 38, 39, 40, 41, 42, 43, 44, 45, 46, 47, 48, 49, 50, 51, 52, 53, 54, 55, 56, 57, 63, 66];
                break;
            case 3:
            case 4:
            case 5:
                $arr = [3, 4, 5, 26, 27, 6, 7, 8, 9, 10, 11, 12, 13, 30, 37, 41, 43, 45, 46, 48, 26, 27, 51, 52, 66];
                break;
            case 6:
                $arr = [3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 30, 37, 41, 29, 46, 66];
                break;
            case 7:
                $arr = [7, 25, 44];
                break;
            case 10:
                $arr = [3, 4, 5, 10, 26, 27, 6, 7, 8, 9, 10, 11, 12, 13, 30, 37, 41, 43, 66];
                break;
            case 11:
                $arr = [11, 12, 66];
                break;
            case 12:
                if ($cluster_check == 0) {
                    $arr = [12, 13, 14, 15, 16, 17, 18, 19, 53];
                } else {
                    $arr = [12, 13, 14, 15, 16, 17, 18, 19, 53];
                }

                break;
            case 13:
            case 14:
            case 15:
            case 16:
            case 17:
            case 18:
            case 19:
            case 50:
                $arr = [12, 13, 14, 15, 16, 17, 18, 19, 50];  // Array from 12 to 19
                // $arr = array_diff($arr, [$r_id]); // Exclude the current role ID
                break;
            case 25:
                $arr = [7, 44, 25];
                break;
            case 30:
                $arr = [30, 31, 35, 36, 49];
                break;
            case 31:
            case 34:
            case 35:
            case 36:
            case 49:
                $arr = [30, 31, 34, 35, 36, 49];
                // $arr = array_diff($arr, [$r_id]); // Exclude $r_id
                break;
            case 37:
                $arr = [37, 38, 39, 40];
                break;
            case 38:
            case 39:
            case 40:
                $arr = [37, 38, 39, 40];
                // $arr = array_diff($arr, [$r_id]); // Exclude $r_id
                break;
            case 41:
                $arr = [41, 42, 47];
                break;
            case 42:
            case 47:
                $arr = [42, 41, 47];
                break;
            case 29:
            case 46:
                $arr = [6, 29, 46];
                break;
            case 43:
            case 45:
            case 26:
            case 27:
            case 48:
            case 51:
            case 52:
                $arr = [3, 4, 5, 26, 27, 43, 45, 48, 51, 52];
                // $arr = array_diff($arr, [$r_id]); // Exclude $r_id
                break;
            case 44:
                $arr = [44, 7, 25];
                break;
            case 53:
                $arr = [15, 16];
                break;
            case 54:
            case 55:
            case 56:
            case 57:
            case 63:
                $arr = [54, 55, 56, 57, 63];
                break;
            case 66:
                $arr = [12, 13, 14, 15, 16, 17, 18, 19, 53];
                break;
        }

        // $inactiveRoleIds = DB::table('users')->whereIn('role_id', $arr)
        // ->where('status', 2)
        // ->pluck('role_id')
        // ->unique()
        // ->toArray();

        //  // Filter out the inactive role IDs from the original array
        //  $f_roles = array_diff($arr, $inactiveRoleIds);

        //   dd($f_roles);


        return $arr;
    }



    // return dept for leave create request

    public function role_dept()
    {
        // \Log::info($message);
        $user = Auth::user();
        $r_dept = $user->dept;
        $r_id = $user->role_id;

        // $cluster_check = DB::table('m_cluster as mc')
        // ->leftJoin('users','users.id','=','mc.cl_name')
        // ->where('mc.cl_name','=',$user->id)
        // ->where('users.role_id',12)
        // ->count();

        switch ($r_dept) {
            case ($r_dept == 'HR'):
                $arr = [1, 2];
                break;
            case ($r_dept == 'Operation'):
                $arr = [3, 4, 5];
                break;
            case ($r_dept == 'Finance'):
                $arr = ($r_id == 7) ? [3, 4, 5] : [7];
                break;
            case ($r_dept == 'IT'):
                $arr = [3, 4, 5];
                break;
            case ($r_dept == 'Sales/Marketing'):
                $arr = [3, 4, 5];
                break;
            case ($r_dept == 'Area'):
                $arr = [3, 4, 5];
                break;
            case ($r_dept == 'Cluster'):
                $arr = [3, 4, 5];
                break;
            case ($r_dept == 'Store'):
                $arr = ($r_id == 12) ? [3, 4, 5] : [12];
                break;
            case ($r_dept == 'Maintenance'):
                $arr = ($r_id == 30) ? [3, 4, 5] : [30];
                break;
            case ($r_dept == 'Warehouse'):
                $arr = ($r_id == 37) ? [3, 4, 5] : [37];
                break;
            case ($r_dept == 'Purchase'):
                $arr = ($r_id == 41) ? [3, 4, 5] : [41];
                break;
        }


        return $arr;
    }
}
