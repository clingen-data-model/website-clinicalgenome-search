<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class Filter extends JsonResource
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
            'ident' => $this->ident,
            'name' => $this->name,
            'display_name' => $this->display_name,
            'screen' => $this->screen,
            'settings' => $this->settings,
            'default' => (boolean) $this->default
        ];
    }
}
