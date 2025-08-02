<?php

namespace App\Console\Commands;

use App\Services\PointService;
use Illuminate\Console\Command;

class ProcessExpiredPoints extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'points:process-expired';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Process expired points and update user point balances';

    protected $pointService;

    /**
     * Create a new command instance.
     */
    public function __construct(PointService $pointService)
    {
        parent::__construct();
        $this->pointService = $pointService;
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting to process expired points...');

        try {
            $this->pointService->processExpiredPoints();
            $this->info('Successfully processed expired points!');
        } catch (\Exception $e) {
            $this->error('Error processing expired points: ' . $e->getMessage());
            return 1;
        }

        return 0;
    }
}
