<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use App\Models\Batch;
use App\Models\Promotion;
use Illuminate\Console\Command;

class UpdateStatusPromotion extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:update-status-promotion';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $now = Carbon::now();

        Promotion::where('end_date', '<', $now)
            ->where('status', 'active')
            ->update(['status' => 'unactive']);

        $batchPromotions = Promotion::where('apply_to', 'batch')
            ->where('status', 'active')
            ->get();

        foreach ($batchPromotions as $promotion) {
            $totalQuantity = Batch::whereIn('batch_id', function ($query) use ($promotion) {
                $query->select('batch_id')
                    ->from('batch_promotions')
                    ->where('promotion_id', $promotion->promotion_id);
            })
                ->sum('quantity');

            if ($totalQuantity == 0) {
                $promotion->update(['status' => 'unactive']);
            }
        }


        $this->info('Promotion statuses updated successfully.');
        return 0;
    }
}
