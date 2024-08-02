<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

use App\GeneLib;

class AffiliateDetail extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $temp = Genelib::ValidityClassificationString($this->classification);

        if ($this->animal_model_only)
            $temp .= '*';

        return [
            'symbol' => $this->label,
            'hgnc_id' => $this->hgnc_id,
            'symbol_id' => $this->hgnc_id,
            'ep' => $this->ep ?? '',
            'affiliate_id' => $this->affiliate_id,
            'disease_name' => displayMondoLabel($this->disease) . '  ' . displayMondoObsolete($this->disease),
            'mondo' => $this->mondo,
            'moi' => $this->displayMoi($this->moi),
            'sop' => Genelib::ValidityCriteriaString($this->sop),
            'classification' => $temp,
            'contributor_type' => $this->contributor_type,
            'order' => Genelib::ValiditySortOrder($temp),
            'perm_id' => $this->perm_id,
            'animal_model_only' => $this->animal_model_only,
            'report_id' => $this->report_id ?? null,
            'released' => $this->displayDate($this->date),
            'date' => $this->date
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
