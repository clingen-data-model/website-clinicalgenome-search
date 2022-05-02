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

        // foreach ($this->contributions as $contribution) {
        //     //dd($contribution);
        //     // Check if the current agent is this one.
        //         if ($this->attributed_to->curie == $contribution->agent->curie) {
        //             if ($contribution->realizes->curie == "SEPIO:0000155") {
        //                 $contributor_type = "Primary";
        //             }
        //             if ($contribution->realizes->curie == "SEPIO:0004099") {
        //                 $contributor_type = "Secondary";
        //             }
        //         }
        // }

        $temp = Genelib::ValidityClassificationString($this->classification->label);

        if ($this->animal_model_only)
            $temp .= '*';

        return [
            'symbol' => $this->gene->label,
            'hgnc_id' => $this->gene->hgnc_id,
            'href' => $this->href,
            'disease' => displayMondoLabel($this->disease->label) . '  ' . displayMondoObsolete($this->disease->label),
            'mondo' => $this->disease->curie,
            'moi' => $this->displayMoi($this->mode_of_inheritance->curie ?? ''),
            'sop' => Genelib::ValidityCriteriaString($this->specified_by->label ?? ''),
            'classification' => $temp,
            'order' => Genelib::ValiditySortOrder($temp),
            'perm_id' => $this->curie,
            'contributor_type' => $this->contributor_type,
            'animal_model_only' => $this->animal_model_only,
            'report_id' => $this->report_id ?? null,
            'released' => $this->displayDate($this->report_date),
            'date' => $this->report_date
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
