<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashBoardController;
use App\Http\Controllers\RecruitController;
use App\Http\Controllers\AuthenticationController;
use App\Http\Controllers\ImpersonationController;
use Illuminate\Support\Facades\DB;


// Route::get('find_store', [AuthenticationController::class, 'find_store'])->name('find_store');

Route::get('post_application/{id}', [RecruitController::class, 'post_application'])->name('post_application');

Route::post('job_app_store', [RecruitController::class, 'post_app_store'])->name('job_app_store');

Route::view('/', 'login')->name('login');

// // admin impersonat
Route::middleware(['web', 'auth'])->group(function () {
    Route::get('/impersonate/stop', [ImpersonationController::class, 'stop'])
        ->name('impersonate.stop');

    Route::get('/impersonate/{id}', [ImpersonationController::class, 'start'])
        ->name('impersonate.start');
});

Route::group(['namespace' => 'App\Http\Controllers'], function () {

    Route::post('login', 'AuthenticationController@login')->name('login.submit');
    Route::get('logout', 'AuthenticationController@logout')->name('logout');

    Route::post('send_not', 'LeaveController@send_not')->name('send_not');

    Route::middleware(['auth', 'asm'])->group(function () {

        // Store Dashboard
        Route::get('dashboard', 'DashBoardController@index')->name('dashboard');
        Route::get('dashboard/checkin', 'attd_cnt@attd_row')->name('dashboard.checkin');
        Route::get('dashboard/checkin', 'attd_cnt@attd_row')->name('dashboard.checkin');
        Route::get('store-dashboard', 'DashBoardController@generalstoreindex')->name('store.dashboard');
        Route::get('mydash-dashboard', 'DashBoardController@mydashboardindex')->name('mydash.dashboard');
        Route::post('update_task', 'DashBoardController@updateTaskStatus')->name('update.task');
        Route::post('store_dashboard', 'DashBoardController@useragainststask')->name('store.usertaskdashboard');
        Route::post('attendance-approve', 'DashBoardController@attendanceApprove')->name('attendance.approve');
        Route::get('/chart/walkin-status', 'DashBoardController@walkinStatus')->name('chart.walkinStatus');


        // GM Dashboard
        Route::get('gm-dashboard', 'GMController@index')->name('gm.dashboard');
        Route::get('gm-mydashboard', 'GMController@mydashboard')->name('gm.mydashboard');

        // Operational Dashboard
        Route::get('operation-dashboard', 'OperationController@index')->name('operation.dashboard');
        Route::get('operation-mydashboard', 'DashBoardController@mydashboardindex')->name('operation.mydashboard');

        // HR Dashboard
        Route::get('hr-dashboard', 'HrDashBoardController@index')->name('hr.dashboard');
        Route::get('hr-mydashboard', 'HrDashBoardController@mydashboard')->name('hr.mydashboard');
        Route::get('hrkpi-dashboard', 'HrDashBoardController@kpidashboard')->name('hrkpi.dashboard');

        // Settings
        Route::get('settings', 'SettingsController@index')->name('settings');
        Route::get('category', 'SettingsController@categorylist')->name('category');
        Route::post('category-store', 'SettingsController@categorystore')->name('category.store');
        Route::post('update-status/{id}', 'SettingsController@updateStatus')->name('update.status');
        Route::get('subcategory', 'SettingsController@subcategoryList')->name('subcategory');
        Route::post('sub-category-store', 'SettingsController@subcategorystore')->name('subcategory.store');
        Route::post('sub-update-status/{id}', 'SettingsController@subupdateStatus')->name('subupdate.status');
        Route::get('roles', 'SettingsController@roleList')->name('roles');
        Route::post('role-store', 'SettingsController@rolestore')->name('role.store');
        Route::post('role-update-status/{id}', 'SettingsController@roleupdateStatus')->name('roleupdate.status');
        Route::get('password', 'SettingsController@passwordList')->name('password');
        Route::post('settings-update', 'SettingsController@passwordupdate')->name('change_password.update');
        Route::get('theme-list', 'SettingsController@themeList')->name('theme');
        Route::get('permissions', 'SettingsController@permissionList')->name('permission');
        Route::post('permission.store', 'SettingsController@permissionstore')->name('permission.store');
        Route::get('permission.filter/{token}', 'SettingsController@filter')->name('permission.filter');

        Route::get('assign_asm', 'SettingsController@assign_asm')->name('assign.assign_asm');
        Route::post('get_assistant', 'SettingsController@get_asm')->name('get_assistant');
        Route::post('insert_asm', 'SettingsController@insert_asm')->name('insert_asm');

        // Store
        Route::get('store-list', 'StoreController@index')->name('store.index');
        Route::get('store-add', 'StoreController@create')->name('store.add');
        Route::post('storea-store', 'StoreController@store')->name('store');
        Route::get('store-view/{id}', 'StoreController@show')->name('store.view');
        Route::get('store-edit/{id}', 'StoreController@edit')->name('store.edit');
        Route::post('store-update/{id}', 'StoreController@update')->name('store.update');
        Route::get('store-strength/{id}', 'StoreController@strlist')->name('store.strength');
        Route::get('store-details/{id}', 'StoreController@detailslist')->name('store.details');
        Route::get('store-viewemp/{id}', 'StoreController@empview')->name('store.viewemp');
        Route::post('store-check', 'StoreController@store_check')->name('store.check');
        Route::get('maintenance_update_list', 'StoreController@mnt_update_list')->name('maintain.update_list');
        Route::get('maintenance_update/{id}', 'StoreController@mnt_update')->name('maintain.update');
        Route::post('maintenance_update', 'StoreController@mnt_update_store')->name('store.maintain_update_store');
        // Route::get('store-workupdatelist', 'StoreController@workupdatelist')->name('store.workupdatelist');
        // Route::get('add-workupdate', 'StoreController@addworkupdate')->name('store.addworkupdate');
        // Route::match(['get', 'post'], 'staff_sale_work_report', 'StoreController@staff_s_work_report')->name('store.staff_work_sales_report');
        // Route::get('staff_workupdate_list', 'StoreController@staff_workupdate_list')->name('store.staff_workupdate_list');
        // Route::get('staff_workupdate', 'StoreController@staff_workupdate')->name('store.staff_workupdate');
        // Route::post('store_staff_workupdate', 'StoreController@store_staff_workupdate')->name('store.store_staff_workupdate');
        // Route::match(['get', 'post'], 'staff_work_report', 'StoreController@staff_p_work_report')->name('store.staff_work_performance_report');

        // dsr sales
        Route::get('dsr-sales-list', 'StoreController@dsr_sale_list')->name('dsr.sale.list');
        Route::get('dsr-sales-store', 'StoreController@dsr_salesc_reate')->name('dsr.sales.create');
        Route::post('dsr-sales-store', 'StoreController@dsr_sales_store')->name('dsr.sales.store');
        Route::match(['get', 'post'], 'dsr_sale_report', 'StoreController@dsr_sale_report')->name('dsr.sale.report');
        // dsr rental
        Route::get('dsr-rental-list', 'StoreController@dsr_rental_list')->name('dsr.rental.list');
        Route::get('dsr-rental-create', 'StoreController@dsr_rental_cerate')->name('dsr.rental.create');
        Route::post('dsr-rental-store', 'StoreController@dsr_rental_store')->name('dsr.rental.store');
        Route::match(['get', 'post'], 'dsr_rental_report', 'StoreController@dsr_rental_report')->name('dsr.rental.report');


        // Walkin
        Route::get('store-walkin', 'StoreController@store_walkin')->name('store.walkin');
        Route::get('add-walkin', 'StoreController@add_walkin')->name('store.add_walkin');
        Route::post('check-walkin', 'StoreController@add_walkin_check')->name('store.walkin_check');
        Route::post('add-walkin', 'StoreController@add_walkin_store')->name('store.add_walkin_store');
        Route::post('walkin-status', 'StoreController@walkin_status')->name('store.walkin_status');
        Route::post('walkin-status-update', 'StoreController@walkin_status_update')->name('store.walkin_status_update');
        Route::get('store-get-categories', 'StoreController@get_categories')->name('store.get_categories');
        Route::get('store-get-subcategories', 'StoreController@get_subcategories')->name('store.get_subcategories');


        Route::get('store-report', 'StoreController@walkinlist')->name('store.walkinlist');
        Route::post('store-report', 'StoreController@walkinlist')->name('store.walkinlist');
        Route::get('store-target', 'StoreController@store_target')->name('store.target');
        Route::post('store-target', 'StoreController@store_target')->name('store.target');

        Route::get('store_audit', 'StoreController@audit_list')->name('store.audit');
        Route::get('add_audit', 'StoreController@add_audit')->name('store.add_audit');
        Route::post('add_audit', 'StoreController@store_audit')->name('store.store_audit');
        Route::get('audit_view/{id}', 'StoreController@audit_view')->name('store.audit_view');

        Route::get('store_audit', 'StoreController@audit_list')->name('store.audit');
        Route::get('add_audit', 'StoreController@add_audit')->name('store.add_audit');
        Route::post('add_audit', 'StoreController@store_audit')->name('store.store_audit');
        Route::get('audit_view/{id}', 'StoreController@audit_view')->name('store.audit_view');

        Route::get('store_audit', 'StoreController@audit_list')->name('store.audit');
        Route::get('add_audit', 'StoreController@add_audit')->name('store.add_audit');
        Route::post('add_audit', 'StoreController@store_audit')->name('store.store_audit');
        Route::get('audit_view/{id}', 'StoreController@audit_view')->name('store.audit_view');

        // Route::post('walk_list', 'StoreController@walk_list')->name('walk_list');
        Route::get('/get-employees-by-store', 'StoreController@getEmployeesByStore')->name('get.employees.by.store');

        // Employee
        Route::get('employee-list', 'EmployeeController@index')->name('employee.index');
        Route::get('employee-termination-list', 'EmployeeController@term_list')->name('employee.term_list');
        Route::get('employee-resignation-list', 'EmployeeController@resignation')->name('employee.resignation');
        Route::get('employee-add', 'EmployeeController@create')->name('employee.add');
        Route::post('employee-store', 'EmployeeController@store')->name('employee.store');
        Route::get('employee-jobdetails/{id}', 'EmployeeController@jobindex')->name('jobdetails');
        Route::post('get-role', 'EmployeeController@getrole')->name('get_role');
        Route::post('employee-jobstore/{id}', 'EmployeeController@jobdetailstore')->name('employee.jobstore');
        Route::get('employee-bankdetails/{id}', 'EmployeeController@bankindex')->name('bankdetails');
        Route::post('employee-bankstore/{id}', 'EmployeeController@bankdetailstore')->name('employee.bankstore');
        Route::get('employee-view/{id}', 'EmployeeController@show')->name('employee.view');
        Route::get('employee-details/{id}', 'EmployeeController@empdetails')->name('employee.details');
        Route::get('employee-salary/{id}', 'EmployeeController@salary')->name('employee.salary');
        Route::get('employee-remark/{id}', 'EmployeeController@remarks')->name('employee.remark');
        Route::get('employee-edit/{id}', 'EmployeeController@edit')->name('employee.edit');
        Route::post('employee-update/{id}', 'EmployeeController@update')->name('employee.update');
        Route::get('employee-jobedit/{id}', 'EmployeeController@jobedit')->name('employee.jobedit');
        Route::post('employee-jobupdate/{id}', 'EmployeeController@jobdetailupdate')->name('employee.jobupdate');
        Route::get('employee-bankedit/{id}', 'EmployeeController@bankedit')->name('employee.bankedit');
        Route::post('employee-bankupdate/{id}', 'EmployeeController@bankdetailupdate')->name('employee.bankupdate');
        Route::get('employee-active/{emp_id}', 'EmployeeController@emp_active')->name('emp_active');
        Route::any('employee_leave_report', 'EmployeeController@emp_leave_report')->name('employee.leave_report');
        Route::any('employee_dashboard', 'EmployeeController@emp_dahboard')->name('employee.emp_dahboard');
        Route::any('employee_target', 'EmployeeController@emp_target')->name('employee.emp_target');
        Route::any('employee_perf_target', 'EmployeeController@emp_performance_target')->name('employee.emp_performance_target');


        // Task
        Route::get('task-list', 'TaskController@index')->name('task.index');
        Route::get('completed-task-list', 'TaskController@completed_list')->name('task.completed-task');
        Route::get('not-completed-task-list', 'TaskController@not_completed_list')->name('task.not-completed-task');
        Route::get('task-add', 'TaskController@create_task')->name('task.add');
        Route::get('task-add/cluster', 'TaskController@create')->name('task.add.cluster');
        Route::post('get-subcategories', 'TaskController@getSubcategories')->name('get_sub_cat');
        Route::post('task-store', 'TaskController@store')->name('task.store');
        Route::get('task-view/{id}', 'TaskController@show')->name('task.view');
        Route::post('task-extent', 'TaskController@task_extend')->name('task.extend');
        Route::post('task-ext', 'TaskController@task_ext')->name('task.ext');
        Route::post('task_ext_update', 'TaskController@task_ext_update')->name('task_ext_update');
        Route::post('completedtaskstore', 'TaskController@completedtaskstore')->name('completedtaskstore');
        Route::post('/del_task', 'TaskController@del_task')->name('del_task');
        Route::get('/auto_task_list', 'TaskController@auto_task_list')->name('auto_task_list');
        Route::get('/add_auto_task', 'TaskController@add_auto_task')->name('add_auto_task');
        Route::post('/store_auto_task', 'TaskController@store_auto_task')->name('store_auto_task');


        // Leave Request
        Route::get('leave-list', 'LeaveController@index')->name('leave.index');
        Route::get('leave-add', 'LeaveController@create')->name('leave.add');
        Route::post('leave-store', 'LeaveController@store')->name('leave.store');
        Route::post('update-leaveescalate', 'LeaveController@updateEscalate')->name('update.leaveescalate');
        Route::post('get_leave_emp', 'LeaveController@get_leave_emp')->name('get_leave_emp');
        Route::post('/leave/check-limit', 'LeaveController@checkLeaveLimit')->name('leave.checkLimit');


        // Repair Request
        Route::get('repair_list', 'RepairController@index')->name('repair.index');
        Route::get('maintenance-add', 'RepairController@create')->name('repair.add');
        Route::post('maintenance-store', 'RepairController@store')->name('repair.store');

        // Transfer Request
        Route::get('transfer-list', 'TransferController@index')->name('transfer.index');
        Route::get('transfer-add', 'TransferController@create')->name('transfer.add');
        Route::post('transfer-store', 'TransferController@store')->name('transfer.store');
        Route::post('get-empname', 'TransferController@getempname')->name('get_emp_name');
        Route::post('update-transferescalate', 'TransferController@updateEscalate')->name('update.transferescalate');

        // Resignation Request
        Route::get('resignation-list', 'ResignationController@index')->name('resignation.index');
        Route::get('resignation-add', 'ResignationController@create')->name('resignation.add');
        Route::post('resignation-store', 'ResignationController@store')->name('resignation.store');
        Route::post('update-reginescalate', 'ResignationController@updateEscalate')->name('update.reginescalate');

        // Recruitment Request
        Route::get('recruitment-list', 'RecruitmentController@index')->name('recruitment.index');
        Route::get('recruitment-add', 'RecruitmentController@create')->name('recruitment.add');
        Route::post('recruitment-role', 'RecruitmentController@get_roles')->name('recruitment.role');
        // Route::post('recruitment-role', 'RecruitmentController@store')->name('recruitment.add');
        Route::post('recruitment-store', 'RecruitmentController@store')->name('recruitment.store');

        // Request Approval
        Route::get('approve-list', 'ApproveController@index')->name('approve.index');
        Route::get('approveleave-list', 'ApproveController@leaveindex')->name('approveleave.index');
        Route::get('approverepair-list', 'ApproveController@repairindex')->name('approverepair.index');
        Route::get('approvetransfer-list', 'ApproveController@transferindex')->name('approvetransfer.index');
        Route::get('approveresgin-list', 'ApproveController@resginindex')->name('approveresgin.index');
        Route::get('approverecruit-list', 'ApproveController@recruitindex')->name('approverecruit.index');
        Route::post('approveleave-update', 'ApproveController@updateLeave')->name('approveleave.update');
        Route::post('approveleaveesulate-update', 'ApproveController@updateLeave')->name('approveleaveesulate.update');
        Route::post('approvelrepair-update', 'ApproveController@updaterepair')->name('approvelrepair.update');
        Route::post('approvelresgin-update', 'ApproveController@updateresgin')->name('approvelresgin.update');
        Route::post('approvelrecurit-update', 'ApproveController@updaterecuirt')->name('approvelrecurit.update');
        Route::post('approveltransfer-update', 'ApproveController@updatetransfer')->name('approveltransfer.update');
        Route::get('approvepurchase_order', 'ApproveController@purchase_order')->name('approvepurchase_order');
        Route::post('storepurchase_order', 'ApproveController@storepurchase_order')->name('storepurchase_order');
        Route::post('storepurchaseapprove_order', 'ApproveController@storepurchaseapprove_order')->name('storepurchaseapprove_order');
        Route::get('retirement_list', 'ApproveController@retirement_list')->name('approve.retirement_list');
        Route::post('retirement_approve', 'ApproveController@retirement_approve')->name('approve.retirement_approve');


        // Cluster
        Route::get('cluster-list', 'ClusterController@index')->name('cluster.index');
        Route::get('cluster-create', 'ClusterController@drop_show')->name('cluster.new');
        // Route::get('cluster-edit/{id}', 'ClusterController@edit_show')->name('cluster.edit');
        Route::get('cluster-profile/{id}', 'ClusterController@show')->name('cluster.profile');
        Route::get('cluster-edit/{id}', 'ClusterController@edit')->name('cluster.edit');
        Route::post('cluster-edit-update', 'ClusterController@update')->name('cluster.edit_update');
        Route::post('cluster-submit', 'ClusterController@create')->name('cluster.submit');
        Route::get('cluster-overview', 'ClusterController@cluster_overview')->name('cluster.dashboard');
        Route::get('cluster-mydashboard', 'ClusterController@cluster_mydashboard')->name('cluster.mydashboard');
        Route::get('cluster-strength', 'ClusterController@cluster_strength')->name('cluster.strength');
        Route::get('cluster-delete/{id}', 'ClusterController@cluster_delete')->name('cluster.delete');

        // AJAX Route
        Route::post('/get_area_per', 'AreaController@area_per')->name('get_area_per');
        Route::post('/get_cluster_store', 'AreaController@cluster_store')->name('get_cluster_store');

        Route::post('/get_cluster_per', 'ClusterController@cluster_det')->name('get_cluster_per');
        Route::post('/get_store_per', 'Attd_cnt@get_store_per')->name('get_store_per');

        Route::post('payroll-drop', 'PayrollController@drop_show')->name('payroll.drop');
        Route::post('payroll-list', 'PayrollController@store_per')->name('payroll.listPerson');
        Route::post('payroll-list-insert', 'PayrollController@store')->name('payroll.insert');
        Route::post('payroll-drop-store', 'PayrollController@store_list')->name('payroll.store_list');
        Route::get('salary-hold-list', 'PayrollController@hold_list')->name('payroll.salaryhold_list');
        Route::get('add-salary-hold', 'PayrollController@add_hold')->name('payroll.add_salaryhold');
        Route::post('store-salary-hold', 'PayrollController@store_hold')->name('payroll.store_salaryhold');
        Route::get('/get-employee-name/{emp_code}', 'PayrollController@getEmployeeName');
        Route::post('hold-release', 'PayrollController@hold_release')->name('payroll.hold_release');

        Route::post('/get_ind_attd', 'Attd_cnt@get_ind_attd')->name('get_ind_attd');

        // Payroll
        Route::get('payroll-list', 'PayrollController@index')->name('payroll.index');
        Route::get('view-salary', 'PayrollController@payroll_list')->name('payroll.payroll_list');
        Route::post('view-salary', 'PayrollController@salary_list')->name('salary.list');

        // Attendance
        Route::get('daily-attendance', 'Attd_cnt@daily_attd')->name('attendance.daily');
        Route::post('daily-attendance', 'Attd_cnt@daily_attd')->name('attendance.list');
        Route::post('del_attn/{id}', 'Attd_cnt@del_attn')->name('attendance.delete');
        Route::get('monthly-attendance', 'Attd_cnt@monthly_attd')->name('attendance.monthly');
        Route::post('monthly-attendance', 'Attd_cnt@monthly_attd')->name('attendance.monthly_list');
        Route::get('individual-attendance', 'Attd_cnt@individual_attd')->name('attendance.individual');
        Route::get('overtime-attendance', 'Attd_cnt@overtime_attd')->name('attendance.overtime');
        Route::post('get-coordinates', 'location_cnt@index')->name('get.coordinates');
        Route::post('attendance-ot', 'Attd_cnt@ot_approve')->name('ot.approve');
        Route::match(['get', 'post'], 'attendance-ot-report', 'Attd_cnt@ot_report')->name('attendance.ot.report');
        Route::post('/attendance/individual', 'Attd_cnt@get_ind_attd')->name('get_ind_attd');
        Route::post('/get-store-personnel', 'Attd_cnt@getStorePersonnel')->name('get_store_per');

        Route::match(['get', 'post'], 'attendance-ind-report', 'Attd_cnt@manager_attendance_report')->name('attendance.ind_report');

        // Job Posting
        Route::get('recruit-list', 'RecruitController@list')->name('recruit.list');
        Route::get('recruit-add', 'RecruitController@create')->name('recruit.add');
        Route::get('recruit-edit/{id}', 'RecruitController@edit')->name('recruit.edit');
        Route::get('recruit-profile/{id}', 'RecruitController@profile')->name('recruit.profile');
        Route::get('recruit-candidate/{id}', 'RecruitController@candidate_profile')->name('recruit.candidate_profile');
        Route::get('recruit-add-interview', 'RecruitController@add_interview')->name('recruit.add_interview');
        Route::get('recruit-edit-interview', 'RecruitController@edit_interview')->name('recruit.edit_interview');
        Route::get('hold_update/{round?}', 'RecruitController@hold_update')->name('hold_update');
        Route::post('recruit-data', 'RecruitController@rec_data')->name('recruit.data');
        Route::post('job_post_add', 'RecruitController@store')->name('job_post_add');
        Route::post('job_post_edit/{id}', 'RecruitController@job_post_edit')->name('job_post_edit');
        Route::post('job_post_up', 'RecruitController@post_update')->name('job_post_up');
        // Route::post('job_app_store', 'RecruitController@post_app_store')->name('job_app_store');

        Route::post('update_screen', 'RecruitController@update_screen')->name('update_screen');
        Route::post('add_round', 'RecruitController@add_round')->name('add_round');

        // Resignation
        Route::get('resign-list', 'ResignController@list')->name('resign.list');
        Route::get('resign-profile/{id}', 'ResignController@profile')->name('resign.profile');
        Route::post('resign-formality', 'ResignController@formality')->name('resign.formality');

        // Store Setup
        Route::get('setup-list', 'StoreSetupController@list')->name('setup.list');
        Route::get('setup-create', 'StoreSetupController@create')->name('setup.add');
        Route::post('setup-store', 'StoreSetupController@store')->name('setup.store');
        Route::get('setup-profile/{tab?}/{id}', 'StoreSetupController@profile')->name('setup.profile');
        Route::post('setup-list-store', 'StoreSetupController@set_list_store')->name('set.liststore');
        Route::post('liststore-update', 'StoreSetupController@setlist_update')->name('liststore.update');
        Route::get('liststore-new/{id}', 'StoreSetupController@store_new')->name('liststore.new');

        // Area
        Route::get('area-list', 'AreaController@list')->name('area.list');
        Route::get('area-create', 'AreaController@create')->name('area.add');
        Route::post('area-create', 'AreaController@create_area')->name('area.create');
        Route::get('area-profile/{id}', 'AreaController@show')->name('area.profile');
        Route::get('area-edit', 'AreaController@edit')->name('area.edit');
        Route::get('area-overview', 'AreaController@area_overview')->name('area.dashboard');
        Route::get('area-mydashboard', 'AreaController@area_mydashboard')->name('area.mydashboard');
        Route::get('area-kpidashboard', 'AreaController@area_kpi')->name('area.kpidashboard');

        // Work Update
        Route::get('abstract-list', 'WorkUpdateController@abstractlist')->name('workupdate.abstract-list');
        Route::get('report-list', 'WorkUpdateController@reportlist')->name('workupdate.report-list');
        Route::post('daily-work', 'WorkUpdateController@daily_work')->name('daily.work');
        Route::get('hr-workupdate', 'WorkUpdateController@hr_workupdate')->name('hr_workupdate-list');
        Route::get('hr-addwork', 'WorkUpdateController@hr_workadd')->name('hr_addwork');
        Route::post('hr-addwork', 'WorkUpdateController@hr_storework')->name('hr_storework');


        // Finance
        Route::get('finance_index', 'fin_cnt@index')->name('fin.index');

        // Maintainence
        Route::get('maintain_index', 'maintain_cnt@index')->name('maintain.index');
        Route::get('maintenance_task/{id}', 'maintain_cnt@task')->name('maintain.task');
        Route::get('maintenance_list', 'maintain_cnt@list')->name('maintain.list');
        Route::get('maintenance_profile/{id}', 'maintain_cnt@profile')->name('maintain.profile');

        // Warehouse
        Route::get('warehouse_index', 'warehouse_cnt@index')->name('warehouse.index');

        // Purchase
        Route::get('purchase_index', 'purchase_cnt@index')->name('purchase.index');
        Route::get('p_request_list', 'purchase_cnt@purchase_list')->name('p_request_list');
        Route::get('add_purchase', 'purchase_cnt@add_purchase')->name('purchase.add_purchase');
        Route::post('store_purchase', 'purchase_cnt@store_purchase')->name('purchase.store_purchase');
        Route::get('vendor_list', 'purchase_cnt@vendor')->name('purchase.vendor_list');
        Route::get('add_vendor', 'purchase_cnt@add_vendor')->name('purchase.add_vendor');
        Route::post('store_vendor', 'purchase_cnt@store_vendor')->name('purchase.store_vendor');
        Route::get('vendor_profile/{id}', 'purchase_cnt@vendor_profile')->name('purchase.vendor_profile');
        Route::get('product_list', 'purchase_cnt@product')->name('purchase.product_list');
        Route::get('add_product', 'purchase_cnt@add_product')->name('purchase.add_product');
        Route::post('store_product', 'purchase_cnt@store_product')->name('purchase.store_product');
        Route::get('product_profile/{id}', 'purchase_cnt@product_profile')->name('purchase.product_profile');
        Route::get('purchase_order_list', 'purchase_cnt@po_list')->name('purchase.purchase_order_list');
        Route::get('add_purchase_po', 'purchase_cnt@po_add')->name('purchase.add_purchase_po');
        Route::post('store_purchase_po', 'purchase_cnt@po_store')->name('purchase.store_purchase_po');
        Route::get('store_purchase_po', 'purchase_cnt@po_store')->name('purchase.store_purchase_po');
        Route::post('update_purchase_po', 'purchase_cnt@update_po')->name('purchase.update_purchase_po');
        Route::post('update_purchase_pofin', 'purchase_cnt@update_pofin')->name('purchase.update_purchase_pofin');
        Route::get('pp_profile/{id}', 'purchase_cnt@po_profile')->name('purchase.po_profile');

        // // performce
        Route::get('hr_performance', 'PerformanceController@hr_performance')->name('performance.hr_performance');
        Route::get('hr_addperformance', 'PerformanceController@hr_addperformance')->name('performance.hr_addperformance');
        Route::post('hr_storeperformance', 'PerformanceController@hr_storeperformance')->name('performance.hr_storeperformance');
        Route::get('cluster_performance', 'PerformanceController@cluster_performance')->name('performance.cluster_performance');
        Route::get('cluster_addperformance', 'PerformanceController@cluster_addperformance')->name('performance.cluster_addperformance');
        Route::post('cluster_storeperformance', 'PerformanceController@cluster_storeperformance')->name('performance.cluster_storeperformance');
        Route::get('cluster_viewperformance/{id}', 'PerformanceController@cluster_viewperformance')->name('performance.cluster_viewperformance');
        Route::get('opearation_performance', 'PerformanceController@opearation_performance')->name('performance.opearation_performance');
        Route::get('opearation_addperformance', 'PerformanceController@opearation_addperformance')->name('performance.opearation_addperformance');
        Route::post('opearation_storeperformance', 'PerformanceController@opearation_storeperformance')->name('performance.opearation_storeperformance');
        Route::get('opearation_viewperformance/{id}', 'PerformanceController@opearation_viewperformance')->name('performance.opearation_viewperformance');
        Route::get('employee_performance', 'PerformanceController@employee_performance')->name('performance.employee_performance');
        Route::get('employee_addperformance', 'PerformanceController@employee_addperformance')->name('performance.employee_addperformance');
        Route::post('employee_storeperformance', 'PerformanceController@employee_storeperformance')->name('performance.employee_storeperformance');

        // Retirement
        Route::get('retire_list', 'RetirementController@retire_list')->name('retirement.retire_list');
        Route::get('add_retire', 'RetirementController@add_retire')->name('retirement.add_retire');
        Route::post('store_retire', 'RetirementController@store_retire')->name('retirement.store_retire');
    });
});
