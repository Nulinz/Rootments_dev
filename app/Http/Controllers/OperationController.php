<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class OperationController extends Controller
{
    public function index()
    {

        $user = auth()->user();

        $list_cluster = DB::table('m_cluster as mc')
            ->leftJoin('users as us', 'us.id', '=', 'mc.cl_name')
            ->select('us.name', 'mc.id as mc_id')->get();


        //Attendance Overview

        $overview = DB::table('users')
            ->leftJoin('attendance', function ($join) {
                $join->on('users.id', '=', 'attendance.user_id')
                    ->whereDate('attendance.c_on', Carbon::today());
            })
            ->select(
                'users.id as user_id',
                'users.name',
                'users.profile_image',
                'attendance.in_time',
                'attendance.user_id',
                'attendance.attend_status',
                'attendance.out_time',
                'attendance.status',
                'attendance.in_location'
            )
            ->whereIn('users.role_id', [10, 11])
            ->where('users.id', '!=', $user->id)
            ->where('users.status', 1)
            ->get();

        $task_ext = DB::table('task_ext')
            ->leftJoin('tasks as tk', 'task_ext.task_id', '=', 'tk.id')
            ->leftJoin('users as us', 'task_ext.c_by', '=', 'us.id')

            ->select(
                'task_ext.id',
                'task_ext.extend_date',
                'task_ext.c_remarks',
                'task_ext.created_at',
                'task_ext.category',
                'task_ext.status',
                'tk.task_title',
                'task_ext.attach',
                'us.name'
            )
            ->whereIn('task_ext.status', ['Pending', 'Close Request'])
            ->where('tk.assign_by', Auth::id())
            ->orderBy('task_ext.created_at', 'desc')
            ->get();


        $tast_ext = DB::table('task_ext')
            ->select('task_id', 'status')
            ->orderBy('id', 'desc')
            ->get()
            ->unique('task_id')
            ->keyBy('task_id');

        return view('operation.overview', [
            'overview' => $overview,
            'list' => $list_cluster,
            'store' => $store ?? null,
            'task_ext' => $task_ext,
            'tast_ext' => $tast_ext
        ]);
    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        //
    }

    public function show(string $id)
    {
        //
    }

    public function edit(string $id)
    {
        //
    }

    public function update(Request $request, string $id)
    {
        //
    }

    public function destroy(string $id)
    {
        //
    }
}
