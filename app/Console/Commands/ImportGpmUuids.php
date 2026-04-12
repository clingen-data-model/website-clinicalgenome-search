<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Panel;

class ImportGpmUuids extends Command
{
    protected $signature = 'panels:import-gpm-uuids {path? : Path to the CSV file} {--dry-run : Preview changes without saving}';
    protected $description = 'Update gpm_id on panels from GPM checkpoint CSV by matching affiliation_id or name';

    public function handle()
    {
        $path = $this->argument('path') ?? public_path('data.csv');

        if (! file_exists($path)) {
            $this->error("File not found: {$path}");
            return 1;
        }

        if (($handle = fopen($path, 'r')) === false) {
            $this->error("Cannot open file: {$path}");
            return 1;
        }

        // Skip header
        fgetcsv($handle);

        $updated = 0;
        $skipped = 0;
        $notFound = 0;

        while (($row = fgetcsv($handle)) !== false) {
            $value = trim($row[5] ?? '', '"');

            $data = json_decode($value, true);
            if (! $data) {
                $skipped++;
                continue;
            }

            $type = data_get($data, 'data.type');
            $uuid = data_get($data, 'data.uuid');
            $name = data_get($data, 'data.name');

            if (! $uuid) {
                $skipped++;
                continue;
            }

            if (in_array($type, ['gcep', 'vcep'])) {
                $affiliationId = data_get($data, 'data.expert_panel.affiliation_id');

                if (! $affiliationId) {
                    $skipped++;
                    continue;
                }

                $panel = Panel::where('affiliate_id', $affiliationId)->first();

                if (! $panel) {
                    $this->warn("[{$type}] No panel found for affiliation_id: {$affiliationId} ({$name})");
                    $notFound++;
                    continue;
                }
            } elseif ($type === 'cdwg') {
                $panel = Panel::where('name', $name)
                    ->orWhere('title', $name)
                    ->first();

                if (! $panel) {
                    $this->warn("[{$type}] No panel found matching name: {$name}");
                    $notFound++;
                    continue;
                }
            } else {
                $skipped++;
                continue;
            }

            if ($this->option('dry-run')) {
                $this->line("[DRY RUN] [{$type}] Would update {$panel->name} -> {$uuid}");
            } else {
                $panel->gpm_id = $uuid;
                $panel->save();
                $this->line("[{$type}] Updated {$panel->name} -> {$uuid}");
            }
            $updated++;
        }

        fclose($handle);

        $this->info("Done. Updated: {$updated}, Not found: {$notFound}, Skipped: {$skipped}");
        return 0;
    }
}
