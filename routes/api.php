<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

// get the leave employee and allow

Route::group(['middleware' => 'auth:sanctum'], function () {

   Route::post('get_leave_emp', [App\Http\Controllers\LeaveController::class, 'get_leave_emp'])->name('get_leave_emp');
});



Route::post('/verify_employee', [AuthController::class, 'verify_api']);
Route::post('/employee_range', [AuthController::class, 'employee_range']);


Route::group(['namespace' => 'App\Http\Controllers\Api'], function () {

   Route::post('/auth/login', 'AuthController@login')->name('auth.login');

   Route::post('/update_popup', 'AuthController@popup')->name('app.version');

   // leave request persons.....
   Route::get('leave_req', 'mobile_cnt@leave_req')->name('leave_req');



   Route::group(['middleware' => 'auth:sanctum'], function () {

      Route::post('/auth/logout', 'AuthController@logout')->name('auth.logout');

      Route::post('/auth/authpassword-update', 'AuthController@update')->name('change_password.update');

      // Task
      Route::get('tasks', 'TaskController@index')->name('tasks');
      Route::get('category', 'TaskController@getcategories')->name('category');
      Route::get('subcategory', 'TaskController@getsubcategories')->name('subcategory');
      Route::get('task-rolelist', 'TaskController@create')->name('task.rolelist');
      Route::post('tasks-store', 'TaskController@store')->name('tasks.store');
      Route::post('tasks-completedtaskstore', 'TaskController@completedtaskstore')->name('tasks.completedtaskstore');
      Route::post('update_task', 'TaskController@updateTaskStatus')->name('update.task');
      Route::post('task_extend', 'mobile_cnt@task_extend')->name('update.task_extend');
      Route::post('task_close', 'mobile_cnt@task_close')->name('task_close');
      Route::get('/auto_task_list', 'TaskController@auto_task_list')->name('auto_task_list');
      Route::get('/add_auto_task', 'TaskController@add_auto_task')->name('add_auto_task');
      Route::post('/store_auto_task', 'TaskController@store_auto_task')->name('store_auto_task');


      // Leave Request

      Route::get('leaverequest-list', 'TaskController@leavelist')->name('leaverequest.list');
      Route::get('reginationrequest-list', 'TaskController@reginationlist')->name('reginationrequest.list');
      Route::get('transferquest-list', 'TaskController@transferlist')->name('transferrequest.list');
      Route::post('leave-store', 'mobile_cnt@leavestore')->name('leave.store');
      // Route::post('regination-store','TaskController@reginationstore')->name('regination.store');
      Route::post('transfer-store', 'TaskController@transferstore')->name('transfer.store');
      Route::get('store-list', 'TaskController@storelist')->name('store.list');

      // resignation routes

      Route::post('resign-store', 'mobile_cnt@res_store')->name('resign-store'); // insert the resignation table
      Route::post('resign-show', 'mobile_cnt@resign_show')->name('resign-show'); // insert the resignation table



      //Notifications

      Route::get('noty-list', 'TaskController@notification_list')->name('noty.list');

      // Time line

      Route::get('tasktimeline', 'TaskController@tasktimeline')->name('tasktimeline.list');
      Route::post('task_cby', 'mobile_cnt@task_cby')->name('taskt.c_by');

      // Attendance staus

      Route::get('attd_row', 'mobile_cnt@attd_row')->name('attd_status');

      // Attendance Insert and update

      Route::post('attd_in', 'mobile_cnt@attd_in')->name('attd_in');
      Route::post('attd_out', 'mobile_cnt@attd_out')->name('attd_out');

      //walk in list and store

      Route::post('walkin_store', 'mobile_cnt@walkin_store')->name('walkin_store');
      Route::post('walkin_list', 'mobile_cnt@walkin_list')->name('walkin_list');
      Route::post('walkin_update', 'mobile_cnt@walkin_update')->name('walkin_update');
      Route::post('walkin_cat', 'mobile_cnt@walkin_cat')->name('walkin_cat');
      Route::post('walkin_cby', 'mobile_cnt@walkin_cby')->name('walkin_cby');


      Route::post('leave_weekoff', 'mobile_cnt@leave_weekoff')->name('leave_weekoff');


      Route::post('/assign_to', 'mobile_cnt@assign_to')->name('assign_to');

      // task create show API

      Route::post('/tasks_show', 'mobile_cnt@create_task_show')->name('tasks_show');

      // walkin contact

      Route::post('walkin-contact', 'mobile_cnt@walkin_contact')->name('store.walkincontact');

      // incomplete list

      Route::get('not-completed-task-list', 'mobile_cnt@not_completed_list')->name('task.not-completed-task');
      Route::get('completed-task-list', 'mobile_cnt@completed_list')->name('task.completed-task');
      
      // employee dashboard
      Route::get('employee_dashboard', 'mobile_cnt@emp_dahboard')->name('employee.emp_dahboard');
   });


   //post method HR
   Route::post('hr_list', 'TaskController@hr_list')->name('hr_list');
});
