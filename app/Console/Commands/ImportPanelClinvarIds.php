<?php

// app/Console/Commands/ImportPanelClinvarIds.php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class ImportPanelClinvarIds extends Command
{
    protected $signature = 'panels:import-clinvar {path?}';
    protected $description = 'Update panels.clinvar_org_id from CSV';

    public function handle()
    {
        $path = public_path() . '/clinvar.csv';

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

        DB::beginTransaction();

        try {
            while (($row = fgetcsv($handle)) !== false) {
                [$affiliateId, $clinvarOrgId] = $row;

                DB::table('panels')
                    ->where('affiliate_id', $affiliateId)
                    ->update(['clinvar_org_id' => $clinvarOrgId]);
            }

            DB::commit();
        } catch (\Throwable $e) {
            DB::rollBack();
            $this->error($e->getMessage());
            return 1;
        } finally {
            fclose($handle);
        }

        $this->info('Panels updated successfully.');
        return 0;
    }
}
