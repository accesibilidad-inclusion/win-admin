<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreQuestion extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     * @todo
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'formulation'          => 'required|max:255',
			'dimension'            => 'required|integer|exists:dimensions,id',
			'needs_especification' => 'nullable|boolean',
			'specification'        => 'nullable|string',
			'category'             => 'required|integer|exists:categories,id',
			'assistances'          => 'required|array|integer|exists:assistances,id',
			'options_yes.*'        => 'string',
			'options_no.*'         => 'string'
        ];
    }
}
