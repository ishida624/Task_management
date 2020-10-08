<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class UserRequest extends FormRequest
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
            'username' => 'required|max:16|alpha_dash',
            'password' => 'required|regex:/[0-9a-zA-Z]{8}/',
            'email' => 'required|email',
        ];
    }
    public function messages()
    {
        return [
            'username.required' => 'username can not null. ',
            'username.max' => 'username can not over 16 characters. ',
            'username.alpha_dash' => 'username only have alpha-numeric characters, as well as dashes and underscores . ',
            'password.regex' => 'password should over 8 characters and only 0-9,a-z,A-Z. ',
            'password.required' => 'password should over 8 characters and only 0-9,a-z,A-Z. ',
            'email.email' => 'The email must be a valid email address. ',
        ];
    }
    protected function failedValidation(Validator $validator)
    {
        $message = $validator->errors();
        throw new HttpResponseException(response()->json(['status' => false, 'error' => $message->first()], 400));
    }
}
