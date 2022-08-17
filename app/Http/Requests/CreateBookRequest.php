<?php

namespace App\Http\Requests;

use App\Facades\General;
use App\Rules\MonthRule;
use App\Rules\WorkdayRule;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class CreateBookRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'date'        => [
                'required',
                'date_format:' . General::DATE_FORMAT,
                'after:today',
                new WorkdayRule(),
                new MonthRule()
            ],
            'numOfGuests' => 'required|integer|min:1|max:' . General::GUEST_LIMIT
        ];
    }

    /**
     * Handle a failed validation attempt.
     *
     * @param Validator $validator
     */
    public function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'success' => false,
            'message' => __('messages.validation_errors'),
            'data'    => $validator->errors()
        ]));
    }

    /**
     * @return array
     */
    public function messages()
    {
        return [
            'date.required'        => __('messages.date_required'),
            'date.date_format'     => __('messages.date_format'),
            'date.after'           => __('messages.date_after'),
            'numOfGuests.required' => __('messages.guest_required'),
        ];
    }
}
