<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;

use App\Rules\CheckImageBase64;
use App\Rules\CheckFileName;

class EmailRequest extends FormRequest
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
            'emails' => 'required|array',
            'emails.*.email' => 'required|email|max:50',
            'emails.*.subject' => 'required|max:180',
            'emails.*.body' => 'required',
            'emails.*.attachment' => [
                new CheckImageBase64(),
                new CheckFileName()
            ]
        ];
    }

    /**
     * Get the validation response.
     *
     * @return json
     */

    public function failedValidation(Validator $validator)
    {
       throw new HttpResponseException(response()->json([
         'success'   => false,
         'message'   => 'Validation errors',
         'data'      => $validator->errors()
       ]));
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'emails.*.email.required'  => 'The email field is required.',
            'emails.*.subject.required'  => 'The subject field is required.',
            'emails.*.body.required'  => 'The body field is required.',
            
        ];
    }
}
