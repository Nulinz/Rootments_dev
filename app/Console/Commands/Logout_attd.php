<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class Logout_attd extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'attendance:logout';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Logout all currently logged-in attendance records';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $updatedCount = DB::table('attendance AS a')
        ->whereNull('a.out_add')
        ->whereNotNull('a.in_location')
        ->whereDate('a.c_on', date("Y-m-d"))
        ->leftJoin('users AS u', 'a.user_id', '=', 'u.id')
        ->leftJoin('stores AS s', 'u.store_id', '=', 's.id')
        ->update([
            'a.out_location' => DB::raw('a.in_location'),
            'a.out_add' => DB::raw('a.in_add'),
            'a.out_time' => DB::raw('CASE
                WHEN u.store_id IS NOT NULL AND s.store_end_time IS NOT NULL THEN s.store_end_time
                ELSE u.end_time
            END'),
        ]);

            Log::info("Successfully logged out all active attendance records.");

        $this->info('Successfully logged out all active attendance records.');

        return 0; // Indicates successful execution
    }
}
