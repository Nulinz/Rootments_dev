<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Auth;



class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = new User();
        $user->emp_code = 'EMP' . str_pad(rand(1, 9999), 4, '0', STR_PAD_LEFT);
        $user->name = 'Venkatesh';
        $user->contact_no = '8667444193';
        $user->email = 'admin@gmail.com';
        $user->password = Hash::make('123456');
        $user->dob = '1990-01-01';
        $user->gender = 'Male';
        $user->marital_status = 'Single';
        $user->aadhar_no = '1234-5678-9012';
        $user->address = '123, ABC Street, XYZ City';
        $user->district = 'XYZ District';
        $user->state = 'ABC State';
        $user->pincode = '123456';
        $user->profile_image = 'profile.jpg';
        $user->qulification = 'Bachelor of Technology';
        $user->job_tittle = 'Software Engineer';
        $user->job_type = 'Full-Time';
        $user->exprience = '5 years';
        $user->pro_skill = 'PHP, Laravel, JavaScript';
        $user->pre_start_date = '2020-06-01';
        $user->bank_name = 'ABC Bank';
        $user->bank_holder_name = 'Venkatesh';
        $user->ac_no = '123456789012';
        $user->ifcs_code = 'ABC1234';
        $user->acount_type = 'Savings';
        $user->bank_branch = 'XYZ Branch';
        $user->base_salary = '50000';
        $user->house_rent_allowance = '10000';
        $user->conveyance = '2000';
        $user->medical = '3000';
        $user->speical = '1500';
        $user->other = '500';
        $user->pro_fund = '6000';
        $user->emp_state_insurance = '1000';
        $user->profession_tax = '200';
        $user->income_tax = '5000';
        $user->performance_bonus = '5000';
        $user->net_salary = '67000';
        $user->aadhar_img = 'aadhar.jpg';
        $user->agreement = 'agreement.pdf';
        $user->role_id = 1;
        $user->status = 1;
        $user->login_time = now()->setTimezone('Asia/Kolkata')->format('H:i:s');
        $user->login_status='1';
        $user->save();
    }

}
