<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class TasksRequest extends FormRequest
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
            'title' => 'required|max:40',
            'description' => 'max:255',
            'status' => 'max:5',
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        // $message = $validator->errors()->getMessages();
        $message = $validator->errors();
        // dd($message);
        throw new HttpResponseException(response()->json(['status' => false, 'error' => $message->first()], 400));
    }
}
