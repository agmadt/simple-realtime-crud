<?php

namespace App\Api\V1\Requests;

use App\Http\Requests\JsonRequest;

class StoreTeamRequest extends JsonRequest
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
            'name' => 'required|max:255',
            'email' => 'required|max:255|email',
            'photo' => 'sometimes|mimes:jpg|file|max:102400'
        ];
    }
}
