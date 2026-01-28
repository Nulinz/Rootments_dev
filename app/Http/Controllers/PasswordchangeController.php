<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class PasswordchangeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $auth = Auth::user();


        $user = User::find($auth->id);

        return view('settings.password', ['user' => $user]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
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
        $request->validate([
            'password' => [
                'sometimes',
                'nullable',
                'min:6' ],
        ]);

        $emp_code = $request->emp_code;
        $password = $request->password;

        $user = User::where('emp_code', $emp_code)->first();

        if ($user) {
            if ($password) {
                if (Hash::check($password, $user->password)){
                    return redirect()->back()->with
                    (['status' => 'error', 'message' => 'You Entered Old Password']);
                }
            }

            if($password) {
                $user->password = Hash::make($password);
            }
            $user->save();

            return redirect()->back()-> with
            (['status' => 'success', 'message' => 'Password Updated Successfully']);

        }

        return redirect()->back()->with(['status' => 'error', 'message' => 'Unauthroized Profile']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
