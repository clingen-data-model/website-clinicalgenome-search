<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

use Auth;

use App\User;

class Genomeconnect extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {

        $notification = Auth::guard('api')->user()->notification;

        return [
                'symbol' => $this->gene->name,
                'variant_count' => $this->variant_count,
                'remove' => '<span class="action-remove-gc"><i class="fas fa-trash" style="color:red"></i></span>',
                'hgnc' => $this->gene->hgnc_id,
                'ident' => $this->ident,
                'display_last' => $this->displayDate($this->updated_at)
                    ];
    }
}
