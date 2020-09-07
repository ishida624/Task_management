<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
// use Illuminate\Support\Facades\Validator;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class TodosRequest extends FormRequest
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
        // $this->redirect=url('todolist');
        return [
            'card_name' => 'required|max:255'
        ];
    }
    public function messages()
    {
        return [
            'card_name.required' => 'card_name can not null.',
            'card_name.max' => 'card_name can not over 255 characters'
        ];
    }
    protected function failedValidation(Validator $validator)
    {
        $message = $validator->errors()->getMessages();
        throw new HttpResponseException(response()->json(['status' => false, 'error' => $message['card_name']['0']], 400));
    }
    // public function getValidatorInstance()
    // {
    //     return parent::getValidatorInstance();
    // }
}
