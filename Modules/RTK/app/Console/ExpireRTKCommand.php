<?php

namespace Modules\RTK\Console;

use Illuminate\Console\Command;
use Modules\RTK\Enums\RTKStatus;
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
    public function handle() {
        $currentYear = now()->year;
        $expired = RencanaTenagaKerja::where('status', RTKStatus::APPROVED->value)
            ->where('end_date', '<', $currentYear)
            ->update([
                'status' => RTKStatus::EXPIRED->value,
                'is_active' => false
            ]);

        $this->info("RTK expired updated: {$expired}");
    }
}
