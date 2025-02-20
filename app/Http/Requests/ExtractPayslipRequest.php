<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ExtractPayslipRequest extends FormRequest
{
    public function authorize(): bool
    {
        /**
         * Given this is an API usually this would token based authentication etc.
         */
        return true;
    }

    public function rules(): array
    {
        return [
            'file' => ['required', 'file', 'mimes:pdf', 'max:5120'],
        ];
    }

    public function messages(): array
    {
        return [
            'file.required' => 'File is required',
            'file.mimes' => 'File must be pdf',
            'file.max' => 'File size must be less than 5MB',
        ];
    }
}
