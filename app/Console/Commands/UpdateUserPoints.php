<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use App\Models\User;
use Illuminate\Console\Command;

class UpdateUserPoints extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'user:update-user-points';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update user points if the point expiration date has passed';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $now = Carbon::now();

        $expiredUsers = User::where('point_expiration_date', '<', $now)
            ->whereNotNull('point_expiration_date')
            ->get();

        foreach ($expiredUsers as $user) {
            $user->point = 0;
            $user->point_expiration_date = null;
            $user->save();

            $this->info("User ID {$user->id} points reset to 0 and expiration date cleared.");
        }

        $this->info('User points updated successfully.');
        return 0;
    }
}
