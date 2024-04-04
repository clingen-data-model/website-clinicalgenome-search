<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

use Auth;

use App\User;

class Followdisease extends JsonResource
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

        $actions = '<img src="/images/clinicalValidity-' . ($this->hasActivity('validity') ? 'on' : 'off') . '.png" width="22" height="22">' .
                    '<img src="/images/dosageSensitivity-' . ($this->hasActivity('dosage') ? 'on' : 'off') . '.png" width="22" height="22">' .
                    '<img src="/images/clinicalActionability-' . ($this->hasActivity('actionability') ? 'on' : 'off') . '.png" width="22" height="22">' .
                    '<img src="/images/variantPathogenicity-' . ($this->hasActivity('varpath') ? 'on' : 'off') . '.png" width="22" height="22">' .
                    '<img src="/images/Pharmacogenomics-' . ($this->hasActivity('pharma') ? 'on' : 'off') . '.png" width="22" height="22">';

        return [
                'symbol' => $this->label,
                '_symbol_data' => ['value' => $this->label],
                'curations' => $actions,
                'hgnc' => $this->curie,
                'curie' => $this->curie,
                'ident' => $this->ident,
                'display_last' => $this->last_curated_date,
                'notify' => '<div class="btn-group">' .
                                ' <button type="button" class="text-left btn btn-sm btn-block dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">' .
                                '    <span class="selection">' . ($this->curie == '*' || $this->curie[0] == '@' || $this->curie[0] == '%'  || $this->curie[0] == '!' ? $notification->setting($this->curie) : $notification->setting($this->label)) . '</span><span class="caret"></span>' .
                                '</button>' .
                                '<ul class="dropdown-menu action-disease-frequency">' .
                                    '<li><a data-value="Daily">Daily</a></li>' .
                                    '<li><a data-value="Weekly">Weekly</a></li>' .
                                    '<li><a data-value="Monthly">Monthly</a></li>' .
                                    '<li role="separator" class="divider"></li>' .
                                    '<li><a data-value="Default">Default</a></li>' .
                                    '<li role="separator" class="divider"></li>' .
                                    '<li><a data-value="Pause">Pause</a></li>' .
                                '</ul>' .
                            '</div>',
                'unfollow' => '<span class="action-follow-disease"><i class="fas fa-star" style="color:green"></i></span>'
                    ];
    }
}
