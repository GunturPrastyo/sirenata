<?php

namespace Modules\RTK\Console;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Modules\RTK\Enums\RTKStatus;
use Modules\RTK\Enums\RTKStatusVerification;
use Modules\RTK\Enums\StatusDocument;
use Modules\RTK\Models\RencanaTenagaKerja;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class ExpireRTKCommand extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'rtk:expired';

    /**
     * The console command description.
     */
    protected $description = 'Update RTK menjadi expired jika sudah melewati periode.';

    /**
     * Create a new command instance.
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $currentYear = now()->year;

        // RTK yang expired adalah:
        // status_verification = APPROVED + status_document = VALID + is_active = true
        // dan end_date sudah lewat
        $expired = RencanaTenagaKerja::where('status_verification', RTKStatusVerification::APPROVED->value)
            ->where('status_document', StatusDocument::VALID->value)
            ->where('is_active', true)
            ->where('end_date', '<', $currentYear)
            ->update([
                'status_document' => StatusDocument::EXPIRED->value,
                'is_active'       => false,
                // status_verification tetap APPROVED — hanya dokumen yang expired
            ]);

        $this->info("RTK expired diupdate: {$expired} data");
    }
}
