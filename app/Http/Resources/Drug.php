<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

use App\GeneLib;

class Drug extends JsonResource
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
            'label' => $this->label,
            'curie' => $this->curie,
            'application' => ($this->has_dosage ? 'D' : '') . ($this->has_actionability ? 'A' : '') . ($this->has_validity ? 'V' : '')
                            . ($this->has_variant ? 'R' : '') . ($this->has_pharma ? 'P' : ''),
            'has_pharma' => $this->curation_activities['pharma'] ? 1 : 0
            ];

            //'http://purl.bioontology.org/ontology/RXNORM/706898'
    }

    /**
     *
     * Map the node structure to a json consumable array
     *
     */
    protected function mapCurie()
    {
		if (empty($this->curie))
			return '';

        $pos = strrpos($this->curie, '/');

        if($pos !== false)
        {
            $subject = substr_replace($this->curie, ':', $pos, 1);
            return basename($subject);
        }

        return basename($this->curie);

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
