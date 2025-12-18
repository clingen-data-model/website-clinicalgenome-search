<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Carbon\Carbon;

class AppendCurrentTime extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'app:append-time'

    /**
     * The console command description.
     */
    protected $description = 'Append the current time to a file (creates it if missing)';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {

        $path = public_path() . '/time.txt';

        // Ensure directory exists
        File::ensureDirectoryExists(dirname($path));

        $timestamp = Carbon::now()->toDateTimeString();


        // Append with newline
        File::append($path, $timestamp . PHP_EOL);

        $this->info("Time appended to {$path}: {$timestamp}");

        return self::SUCCESS;
    }
}
