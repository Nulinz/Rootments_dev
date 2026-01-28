<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use App\Models\Notification;
use App\Models\User;
use App\Services\FirebaseService;

class InsertAutoTasks extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tasks:insert-auto-tasks';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Insert todays auto_tasks into tasks table';

    /**
     * Execute the console command.
     */
    // public function handle()
    // {
    //     // $todayDay = Carbon::today()->day;

    //     // $autoTasks = DB::table('auto_tasks')
    //     //     ->whereRaw('DAY(start_date) = ?', [$todayDay])
    //     //     ->get();
        
    //      $todayDate = Carbon::today()->toDateString(); 

    //     $autoTasks = DB::table('auto_tasks')
    //         ->whereDate('start_date', $todayDate)
    //         ->get();
            
    //          foreach ($autoTasks as $task) {
    //         // Step 1: Prepare the data array
    //         $taskData = [
    //             'task_title'       => $task->task_title,
    //             'category_id'      => $task->category_id,
    //             'subcategory_id'   => $task->subcategory_id,
    //             'assign_to'        => $task->assign_to,
    //             'task_description' => $task->task_description,
    //             'start_date'       => $task->start_date,
    //             'priority'         => $task->priority,
    //             'task_file'        => $task->task_file,
    //             'assign_by'        => $task->created_by,
    //             'created_at'       => now(),
    //             'updated_at'       => now(),
    //             // 'f_id' will be set after insertion
    //         ];

    //         // Step 2: Insert the task and get the inserted ID
    //         $insertedId = DB::table('tasks')->insertGetId($taskData);

    //         // Step 3: Now, use the inserted ID to update the f_id
    //         DB::table('tasks')
    //             ->where('id', $insertedId)
    //             ->update(['f_id' => $insertedId]);
    //     }

    //     // foreach ($autoTasks as $task) {
    //     //     // Step 1: Insert task without f_id and get inserted ID
    //     //     $insertedId = DB::table('tasks')->insertGetId([
    //     //         'task_title'      => $task->task_title,
    //     //         'category_id'     => $task->category_id,
    //     //         'subcategory_id'  => $task->subcategory_id,
    //     //         'assign_to'       => $task->assign_to,
    //     //         'task_description' => $task->task_description,
    //     //         'start_date'      => $task->start_date,
    //     //         'priority'        => $task->priority,
    //     //         'task_file'       => $task->task_file,
    //     //         'assign_by'       => $task->created_by,
    //     //         'created_at'      => now(),
    //     //         'updated_at'      => now(),
    //     //     ]);

    //     //     // Step 2: Update the same row with f_id = inserted id
    //     //     DB::table('tasks')
    //     //         ->where('id', $insertedId)
    //     //         ->update(['f_id' => $insertedId]);
    //     // }


    //     $this->info("Inserted " . count($autoTasks) . " tasks from auto_tasks.");
    // }
    
     public function handle()
    {
        $todayDate = Carbon::today()->toDateString();

        $autoTasks = DB::table('auto_tasks')
            ->whereDate('start_date', $todayDate)
            ->get();

        foreach ($autoTasks as $task) {
            // Step 1: Prepare the data array
            $taskData = [
                'task_title'       => $task->task_title,
                'category_id'      => $task->category_id,
                'subcategory_id'   => $task->subcategory_id,
                'assign_to'        => $task->assign_to,
                'task_description' => $task->task_description,
                'start_date'       => $task->start_date,
                'priority'         => $task->priority,
                'task_file'        => $task->task_file,
                'assign_by'        => $task->created_by,
                'created_at'       => now(),
                'updated_at'       => now(),
            ];

            // Step 2: Insert the task and get the inserted ID
            $insertedId = DB::table('tasks')->insertGetId($taskData);

            // Step 3: Update f_id
            DB::table('tasks')
                ->where('id', $insertedId)
                ->update(['f_id' => $insertedId]);

            // Step 4: Send notification
            try {
                $user = User::find($task->assign_to);
                $creator = User::find($task->created_by);

                if ($user && $user->device_token && $creator) {
                    // Fix: get role using creator's role_id
                    $role_get = DB::table('roles')->where('id', $creator->role_id)->first();

                    $taskTitle = "New Auto Task Assigned";
                    $taskBody  = "You have been assigned a new task: "
                        . $task->task_title . " by "
                        . $creator->name . " [" . ($role_get->role ?? '') . "]";

                    // Push notification
                    app(FirebaseService::class)->sendNotification(
                        $user->device_token,
                        $taskTitle,
                        $taskBody
                    );

                    // DB notification
                    Notification::create([
                        'user_id'   => $task->assign_to,
                        'noty_type' => 'task',
                        'type_id'   => $insertedId,
                        'title'     => $taskTitle,
                        'body'      => $taskBody,
                        'c_by'      => $task->created_by
                    ]);
                }
            } catch (\Exception $e) {
                // Log::error('Auto Task Notification Error: ' . $e->getMessage());
            }
        }
        $this->info("Inserted " . count($autoTasks) . " tasks from auto_tasks.");
    }
}
