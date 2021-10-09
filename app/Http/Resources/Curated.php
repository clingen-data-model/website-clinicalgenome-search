<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

use App\GeneLib;

class Curated extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'symbol' => $this->symbol,
            'hgnc_id' => $this->hgnc_id,
            'name' => $this->name,
            'followed' => $this->followed,
            'has_actionability' => $this->has_actionability ? 'Curated' : null,
            'has_validity' => $this->has_validity ? 'Curated' : null,
            'has_dosage' => $this->has_dosage ? 'Curated' : null,
            'has_pharma' => $this->has_pharma ? 'Curated' : null,
            'has_variant' => $this->has_variant ? 'Approved VCEP' : null,
            'acmg59' => $this->acmg59,
            'vcep' => $this->vcep
        ];
    }


    /**
     *
     * Map the node structure to a json consumable array
     *
     */
    protected function mapCurations()
    {
		if (empty($this->curations))
			return [];

		foreach($this->curations as $node)
		{
			$map = $node->values();
			$map['labels'] = $node->labels();
			$curations[] = $map;
		}

		return $curations;
	}
}
