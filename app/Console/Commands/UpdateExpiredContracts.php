<?php

namespace App\Console\Commands;

use App\Models\EstablishmentContracts;
use App\Models\StudentContracts;
use Illuminate\Console\Command;
use Carbon\Carbon;

class UpdateExpiredContracts extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'contracts:update-expired';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Atualiza o status de contratos que já expiraram (end_date passou) para "vencido"';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $today = Carbon::now()->toDateString();

        // Atualizar contratos de estabelecimentos
        $establishmentContractsUpdated = EstablishmentContracts::where('status', 'pago')
            ->where('active', true)
            ->whereDate('end_date', '<', $today)
            ->update([
                'status' => 'vencido'
            ]);

        // Atualizar contratos de alunos
        $studentContractsUpdated = StudentContracts::where('status', 'pago')
            ->where('active', true)
            ->whereDate('end_date', '<', $today)
            ->update([
                'status' => 'vencido'
            ]);

        $this->info("Contratos atualizados para 'vencido':");
        $this->info("- Estabelecimentos: {$establishmentContractsUpdated}");
        $this->info("- Alunos: {$studentContractsUpdated}");

        return Command::SUCCESS;
    }
}
