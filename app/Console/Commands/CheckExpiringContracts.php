<?php

namespace App\Console\Commands;

use App\Services\NotificationService;
use Illuminate\Console\Command;

class CheckExpiringContracts extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'notifications:check-expiring-contracts {--days=7 : Number of days before expiration to notify}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check for contracts expiring soon and create notifications';

    protected $notificationService;

    public function __construct(NotificationService $notificationService)
    {
        parent::__construct();
        $this->notificationService = $notificationService;
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $days = $this->option('days');
        
        $this->info("Checking for contracts expiring in {$days} days...");
        
        $result = $this->notificationService->checkExpiringContracts($days);
        
        $this->info("Found {$result['student_contracts']} student contracts expiring soon.");
        $this->info("Found {$result['establishment_contracts']} establishment contracts expiring soon.");
        
        $total = $result['student_contracts'] + $result['establishment_contracts'];
        $this->info("Total notifications created: {$total}");
        
        return Command::SUCCESS;
    }
}
