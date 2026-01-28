<?php

use Illuminate\Support\Facades\Crypt;
use Illuminate\Contracts\Encryption\DecryptException;


if (!function_exists('hasAccess')) {
    /**
     * Check if the user has access to a specific menu item.
     *
     * @param string $rol?
     * @param string $menuItem
     * @return bool
     */
    function hasAccess($role, $menuItem)
    {
        $menuItems = [
             'store' => [1, 2, 3, 4, 5, 6, 10, 11, 12, 64, 66], // Roles allowed for HR section
            'employee' => [1, 2, 3, 4, 5, 6, 11, 12, 41, 64, 66], //37 Roles allowed for CRM section
            'area' => [1, 2, 3, 4, 5, 6, 10, 64], // Roles allowed for CRM section
            'cluster' => [1, 2, 3, 4, 5, 6, 64], // Roles allowed for Task section
            'all_task' => [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 26, 30, 37, 41, 64, 66],
            'task' => [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21, 22, 23, 24, 25, 26, 27, 28, 29, 30, 31, 32, 33, 34, 35, 36, 37, 38, 39, 40, 41, 42, 43, 44, 45, 46, 47, 48, 49, 50, 51, 52, 53, 54, 55, 56, 57, 63, 64, 66],
            'recruitment' => [3, 4, 5, 11, 66],
            'payroll' => [1, 2, 3, 4, 5, 6, 64],
            'attendance' => [1, 2, 3, 4, 5, 6, 64],
            'request' => [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21, 22, 23, 24, 25, 26, 27, 28, 29, 30, 31, 32, 33, 34, 35, 36, 37, 38, 39, 40, 41, 42, 43, 44, 45, 46, 47, 48, 49, 50, 51, 52, 53, 54, 55, 56, 57, 63, 64, 66],
            'approval' => [1, 2, 3, 4, 5, 6, 10, 11, 12, 7, 30, 37, 41, 64, 66],
            'recruit_req' => [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 64, 66],
            'cat/sub' => [1, 2, 3, 4, 5],
            'leave' => [10, 11, 12, 7, 30, 37, 41, 66],
            'mob_task' => [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 30, 37, 41, 64, 66],
            'resign' => [10, 11, 12, 7, 30, 37, 41],
            'store_setup' => [1, 2, 3, 6, 30, 64],
            'maintain_req' => [1, 2, 10, 11, 12, 30, 66],
            'work_update' => [1, 2, 3, 4, 5],
            'DSR' => [1, 2, 4, 5, 6, 11, 12, 64, 66],
            'all_manager' => [3, 6, 7, 8, 9, 10, 11, 12, 30, 37, 41, 64, 66],
            'st_manager' => [12],
            'store_target' => [1, 2, 10, 11, 66],
            'walk_in' => [1, 2, 6, 10, 11, 12, 27, 64, 66],
            'store_audit' => [1, 2, 3, 4, 5, 6, 7, 9, 10, 11, 12, 30, 32, 37, 46, 64, 66],
            'maintanance_update' => [1, 2, 12, 30],
            // 'performance' => [1, 2, 3, 6, 11, 16, 66],
            'performance' => [1, 2, 3, 6, 12, 16, 13, 14, 15, 17, 19, 50, 53, 64],
            'purchase' => [1, 2, 41, 7],
            'pur_req' => [1, 2, 10, 11, 37, 41, 66],
            'ret_req' => [1, 2, 3]

        ];
        return in_array($role, $menuItems[$menuItem]);
    }
}
function enc($par)
{
    return Crypt::encrypt($par);
}

function dec($par)
{
    return Crypt::decrypt($par);
}

 // 'store' => [1, 2, 3, 4, 5, 6, 10, 11, 12], // Roles allowed for HR section
            // 'employee' => [1, 2, 3, 4, 5, 6, 11, 12, 41], //37 Roles allowed for CRM section
            // 'area' => [1, 2, 3, 4, 5, 6, 10], // Roles allowed for CRM section
            // 'cluster' => [1, 2, 3, 4, 5, 6], // Roles allowed for Task section
            // 'all_task' => [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 26, 30, 37, 41],
            // 'task' => [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21, 22, 23, 24, 25, 26, 27, 28, 29, 30, 31, 32, 33, 34, 35, 36, 37, 38, 39, 40, 41, 42, 43, 44, 45, 46, 47, 48, 49, 50, 51, 52, 53],
            // 'recruitment' => [3, 4, 5],
            // 'payroll' => [1, 2, 3, 4, 5, 6],
            // 'attendance' => [1, 2, 3, 4, 5, 6],
            // 'request' => [ 1, 2,3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21, 22, 23, 24, 25, 26, 27, 28, 29, 30, 31, 32, 33, 34, 35, 36, 37, 38, 39, 40, 41, 42, 43, 44, 45, 46, 47, 48, 49, 50, 51, 52, 53],
            // 'approval' => [1, 2, 3, 4, 5, 6, 10, 11, 12, 7, 30, 37, 41],
            // 'recruit_req' => [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12],
            // 'cat/sub' => [1, 2, 3, 4, 5],
            // 'leave' => [10, 11, 12, 7, 30, 37, 41],
            // 'mob_task' => [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 30, 37, 41],
            // 'resign' => [10, 11, 12, 7, 30, 37, 41],
            // 'store_setup' => [1, 2, 3, 6, 30],
            // 'maintain_req' => [1, 2, 10, 11, 30],
            // 'work_update' => [1, 2, 3, 4, 5, 11],
            // 'DSR' => [1, 2, 3, 4, 5, 6, 11, 12],
            // 'all_manager' => [3, 6, 7, 8, 9, 10, 11, 12, 30, 37, 41],
            // 'st_manager' => [12],
            // 'store_target' => [1, 2, 10, 11],
            // 'walk_in' => [1, 2, 6, 10, 11, 12, 27],
            // 'store_audit' => [1, 2, 3, 4, 5, 6, 7, 9, 10, 11, 12, 30, 32, 37, 46],
            // 'maintanance_update' => [1, 2, 12, 30],
            // // 'performance' => [1, 2, 3, 6, 11, 16],
            // 'performance' => [1, 2, 3, 6, 11, 12, 16, 13, 14, 15, 17, 19, 50, 53],
            // 'purchase' => [1, 2, 41, 7],
            // 'pur_req' => [1, 2, 10, 11, 37, 41],
            // 'ret_req' => [1, 2, 3]
?>