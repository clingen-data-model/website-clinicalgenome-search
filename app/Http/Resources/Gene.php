<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class Gene extends JsonResource
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
            'curation' => ($this->has_dosage ? 'D' : '') . ($this->has_actionability ? 'A' : '') . ($this->has_validity ? 'V' : '')
                            . ($this->has_variant ? 'R' : '') . ($this->has_pharma ? 'P' : ''),
            'has_actionability' => $this->has_actionability,
            'has_validity' => $this->has_validity,
            'has_dosage' => $this->has_dosage,
            'has_pharma' => $this->has_pharma ?? false,
            'has_variant' => $this->has_variant ?? false,
            'locus_type' => $this->locus_type,
            'date' => $this->displayDate($this->last_curated_date),
            'rawdate' => $this->last_curated_date
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
