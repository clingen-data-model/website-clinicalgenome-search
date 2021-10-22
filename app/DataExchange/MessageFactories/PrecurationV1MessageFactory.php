<?php

namespace App\DataExchange\MessageFactories;

use App\Curation;
use Ramsey\Uuid\Uuid;

class PrecurationV1MessageFactory implements MessageFactoryInterface
{
    public function make(Curation $curation, $eventType): array
    {
        $message = [
            'event_type' => $eventType,
            'schema_version' => $this->getSchemaVersion(),
        ];

        if ($eventType == 'deleted') {
            return array_merge($message, [
                'data' => [
                    'id' => $curation->id,
                    'uuid' => $curation->uuid,
                    'gene' => $this->getGene($curation),
                    'group' => $this->getGroup($curation)
                ]
            ]);
        }

        return array_merge($message, [
            'data' => $this->assembleData($curation),
        ]);
    }

    private function makeMessageKey()
    {
        return Uuid::uuid4()->toString();
    }

    private function getSchemaVersion()
    {
        return 1;
    }

    private function assembleData($curation)
    {
        $curation = $curation->fresh();
        return array_filter([
            'id' => $curation->id,
            'uuid' => $curation->uuid,
            'gene' => $this->getGene($curation),
            'disease_entity' => $this->getDiseaseEntity($curation),
            'mode_of_inheritance' => $this->getMoi($curation),
            'gdm_uuid' => $curation->gdm_uuid,
            'group' => $this->getGroup($curation),
            'curation' => $this->getCurator($curation),
            'status' => $this->getStatus($curation),
            'rationales' => $this->getRationales($curation),
            'curation_type' => $curation->curationType
                                ? $curation->curationType->toArray()
                                : null,
            'omim_phenotypes' => $this->getOmimPhenotypes($curation),
            // Keep using notes instead of renamed 'curation_notes' to prevent bumping schema version
            'notes' => $curation->curation_notes,
            'date_created' => $curation->created_at->toIsoString(),
            'date_updated' => $curation->updated_at->toIsoString(),
        ]);
    }

    private function getGene($curation)
    {
        return [
            'hgnc_id' => $curation->hgnc_id,
            'symbol' => $curation->gene_symbol
        ];
    }

    private function getDiseaseEntity($curation)
    {
        if (empty($curation->mondo_id) && empty($curation->disease_entity_notes)) {
            return null;
        }
        return [
            'mondo_id' => $curation->mondo_id,
            'notes' => $curation->disease_entity_notes
        ];
    }
    
    

    private function getMoi($curation)
    {
        if ($curation->moi) {
            return [
                'name' => $curation->moi->name,
                'hp_id' => $curation->moi->hp_id,
            ];
        }

        return null;
    }

    private function getGroup($curation)
    {
        if ($curation->expertPanel) {
            return [
                'id' => $curation->expertPanel->uuid,
                'name' => $curation->expertPanel->name,
                'affiliation_id' => $curation->expertPanel->affiliation
                                        ? $curation->expertPanel->affiliation->clingen_id
                                        : null,
            ];
        }

        return null;
    }

    private function getCurator($curation)
    {
        return null;
    }

    private function getStatus($curation)
    {
        return [
            "name" => $curation->currentStatus->name,
            "effective_date" => $curation->currentStatusDate ? $curation->currentStatusDate->toIsoString() : null
        ];
    }

    private function getRationales($curation)
    {
        if ($curation->rationales->count() === 0 && empty($curation->pmids)) {
            return null;
        }
        return [
            'pmids' => !empty($curation->pmids)
                            ? $curation->pmids
                            : null,
            'rationales' => $curation->rationales->count() > 0
                            ? $curation->rationales->pluck('name')->toArray()
                            : null,
            'notes' => $curation->rationale_notes
        ];
    }

    private function getOmimPhenotypes($curation)
    {
        if ($curation->phenotypes->count() > 0) {
            return [
                "included" => $curation->phenotypes->pluck('mim_number')->toArray(),
                "excluded" => $curation->excludedPhenotypes->pluck('mim_number')->toArray()
            ];
        }
        return null;
    }
}
