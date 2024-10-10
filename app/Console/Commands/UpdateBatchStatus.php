<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Batch;
use Carbon\Carbon;

class UpdateBatchStatus extends Command
{
    protected $signature = 'batch:update-status';
    protected $description = 'Update batch status based on expiry date';

    public function handle()
    {
        $this->updateExpiringSoon();
        $this->updateExpired();

        $this->info('Batch statuses updated successfully.');
        \Log::info('Updating batch statuses...');
    }

    private function updateExpiringSoon()
    {
        $expiringSoonDate = Carbon::now()->addDays(15);

        Batch::where('status', 'Active')
            ->where('expiry_date', '<=', $expiringSoonDate)
            ->where('expiry_date', '>', Carbon::now())
            ->update(['status' => 'Expiring Soon']);
    }

    private function updateExpired()
    {
        Batch::where('expiry_date', '<=', Carbon::now())
            ->whereIn('status', ['Active', 'Expiring Soon'])
            ->update(['status' => 'Expired']);
    }
}
