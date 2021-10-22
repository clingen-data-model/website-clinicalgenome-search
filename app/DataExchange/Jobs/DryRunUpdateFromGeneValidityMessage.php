<?php

namespace App\DataExchange\Jobs;

use App\Curation;
use App\Affiliation;
use App\Contracts\GeneValidityCurationUpdateJob;
use App\Gci\GciMessage;
use App\ModeOfInheritance;
use Illuminate\Bus\Queueable;
use App\DataExchange\Maps\GciStatusMap;
use App\Gci\GciClassificationMap;
use App\Exceptions\GciSyncException;
use Illuminate\Foundation\Bus\Dispatchable;

class DryRunUpdateFromGeneValidityMessage implements GeneValidityCurationUpdateJob
{
    use Dispatchable, Queueable;

    protected $curation;
    protected $gciMessage;
    protected $statusMap;
    protected $classificationMap;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(GciStatusMap $statusMap, GciClassificationMap $classificationMap, Curation $curation, GciMessage $gciMessage)
    {
        $this->curation = $curation;
        $this->gciMessage = $gciMessage;
        $this->statusMap = $statusMap;
        $this->classificationMap = $classificationMap;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $affiliation = Affiliation::findByClingenId($this->gciMessage->affiliation->id);
        $moi = ModeOfInheritance::findByHpId($this->gciMessage->moi);

        $updateSummary = [
            'gdm_uuid' => $this->gciMessage->uuid,
            'affiliation_id' => $affiliation->id,
            'moi_id' => $moi->id,
        ];

        try {
            if ($this->gciMessage->status == 'created') {
                \Log::debug($updateSummary);
                return;
            }
    
            $updateSummary['addStatus'] = [
                'status' => $this->statusMap->get($this->gciMessage->status) ? $this->statusMap->get($this->gciMessage->status)->name : 'No Status??',
                'status_date' => $this->gciMessage->messageDate->format('Y-m-d H:i:s')
            ];
    
            $updateSummary['addClassification'] = [
                'classification' => $this->classificationMap->get($this->gciMessage->classification) ? $this->classificationMap->get($this->gciMessage->classification)->name : 'No Classification??',
                'classification_date' => $this->gciMessage->messageDate->format('Y-m-d H:i:s')
            ];
        } catch (GciSyncException $gciSyncException) {
            \Log::error($gciSyncException);
        }

        \Log::debug($updateSummary);
    }
}
