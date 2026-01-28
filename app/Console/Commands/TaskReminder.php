<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Task; // Assuming you have a Task model
use Carbon\Carbon;
use App\Services\FirebaseService;
use App\Models\Notification;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class TaskReminder extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'task:reminder';
    protected $description = 'Send reminders for upcoming tasks';
    /**
     * The console command description.
     *
     * @var string
     */
    // protected $description = 'Command description';

    /**
     * Execute the console command.
     */

     public function __construct()
    {
        parent::__construct();
    }

   public function handle()
    {
            $tasks = Task::where('end_date', '>=', date('Y-m-d'))
                ->where('end_time', '<=', date('H:i', strtotime('+30 minutes')))
                ->where('reminder', false)
                ->get();

            foreach ($tasks as $task) {

                // Create Carbon instances
                $endTimeWithBuffer = Carbon::createFromFormat('H:i:s', $task->end_time)->addMinutes(30);
                $currentTime = Carbon::now();

                // Check if current time is greater than or equal to end_time + 30 minutes
                if ($currentTime->greaterThanOrEqualTo($endTimeWithBuffer)) {

                    $taskTitle = "Task Not Completed";
                    $taskBody = "Task needs to be complete: " . $task->task_title . " - " . $task->assign->name;

                    // OPTIONAL: Uncomment to send push notification

                    if (!is_null($task->assign->device_token)) {
                        app(FirebaseService::class)->sendNotification(
                            $task->assign->device_token,
                            $taskTitle,
                            $taskBody
                        );
                    }


                    // Create a notification in DB
                    Notification::create([
                        'user_id'   => $task->assign_by,
                        'noty_type' => 'task',
                        'type_id'   => $task->id,
                        'title'     => $taskTitle,
                        'body'      => $taskBody,
                        'c_by'      => 1
                    ]);

                    // Mark task as reminded
                    $task->reminder = true;
                    $task->save();

                    // Logging and console output
                    Log::info("Reminder sent for task: {$task->task_title} - {$currentTime}");
                    $this->info("Reminder sent for task: {$task->task_title}");
                }
            }

            return 0; // All done!
    }
}
