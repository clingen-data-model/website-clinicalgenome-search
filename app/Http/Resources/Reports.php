<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

use App\Title;

class Reports extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $edit = ($this->type == Title::TYPE_USER ?
                '<span class="action-edit-report mr-2" data-uuid="' . $this->ident . '" title="Edit Report"><i class="fas fa-edit" style="color:black"></i></span>' : '');

        $lock = ($this->status == Title::STATUS_ACTIVE ?
                '<span class="action-lock-report mr-2" data-uuid="' . $this->ident . '" title="Lock Report"><i class="fas fa-unlock" style="color:lightgray"></i></span>' 
                : '<span class="action-unlock-report mr-2" data-uuid="' . $this->ident . '" title="Unlock Report"><i class="fas fa-lock" style="color:red"></i></span>');
        
        return [
            'title' => '<a href="' . route('dashboard-show-report', ['id' => $this->ident]) . '" target="_report" >' . $this->title . '</a>',
            'type' => $this->display_type,
            'display_created' => $this->display_created_date,
            'display_last' => $this->display_last_date,
            'remove' => $edit
                        . $lock
                        . '<span class="action-share-report mr-2" data-uuid="' . $this->ident . '" title="Share Report"><i class="fas fa-share" style="color:lightgray"></i></span>'
                        . '<span class="action-remove-report" data-uuid="' . $this->ident . '" title="Delete Report"><i class="fas fa-trash" style="color:red"></i></span>',
            'ident' => $this->ident
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
