<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreFormRequests extends FormRequest
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
            'users_id' => 'required',
            'title' => 'required|max:50',
            'description' => 'required|max:300',
        ];
    }

    public function messages()
    {
        return [
            'users_id.required' => 'Kindly choose the users for this posts.',
            'title.required' => 'A nice title is required for the post.',
            'description.required' => 'Please add content for the post.',
            'title.max' => 'A title is the post is maximum 50 leters only.',
            'description.max' => 'Please maximum 300 leters only in content for the post.',
        ];
    }
}
