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
            'item' => 'required|max:255'
        ];
    }
    public function messages()
    {
        return [
            'item.required' => 'item can not null.' ,
            'item.max' => 'item can not over 255 characters'
    ];
    }
    protected function failedValidation(Validator $validator)
    {
        $message = $validator->errors()->getMessages();
        throw new HttpResponseException(response()->json(['message'=>'bad request','reason'=>$message['item']['0']], 400));
    }
    // public function getValidatorInstance()
    // {
    //     return parent::getValidatorInstance();
    // }
}
