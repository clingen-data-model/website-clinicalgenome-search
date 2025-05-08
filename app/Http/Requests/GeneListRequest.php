<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

class GeneListRequest extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * NOTE:  Don't forget update models with any changes
     *
     * @return array
     */
    public function rules()
    {

        return [
			'page' => 'integer|between:1,9999|nullable',
			'size' => 'integer|between:1,9999|nullable',
			'order' => 'string|max:20|nullable',
			'sort' => 'string|max:80|nullable',
			'search' => 'string|max:80|nullable',
            'byName' => 'integer|nullable'
        ];
    }
}
