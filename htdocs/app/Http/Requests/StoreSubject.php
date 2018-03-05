<?php

namespace App\Http\Requests;

use App\Subject;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class StoreSubject extends FormRequest
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
     * @return array
     */
    public function rules()
    {
        return [
            'sex' => [
                'required',
                Rule::in( array_keys( Subject::getSexes() ) )
            ],
            'given_name'         => 'required|string|max:191',
            'family_name'        => 'required|string|max:191',
            'works'              => 'bool',
            'studies'            => 'bool',
            'studies_at'         => 'nullable|string|max:191',
            'personal_id'        => 'nullable|string|max:32',
            'consent_at'         => 'date_format:Y-m-d H:i:s',
            'last_connection_at' => 'date_format:Y-m-d H:i:s',
        ];
    }
}
