<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class ClearLog extends Command
{
    protected $signature = 'log:clear';

    protected $description = 'Clear log files in storage/logs';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        // Path ke folder logs
        $logPath = storage_path('logs');

        // Hapus semua file log
        File::cleanDirectory($logPath);

        // Tampilkan pesan konfirmasi
        $this->info('Log files have been cleared!');
    }
}
