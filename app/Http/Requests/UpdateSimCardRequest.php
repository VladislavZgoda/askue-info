<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateSimCardRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'number' => [
                'required',
                'regex:/^(\+7|8)\d{10}$/',
                'max:12',
                Rule::unique('sim_cards')->ignore($this->route('sim_card')),
            ],
            'operator' => [
                'required',
                Rule::in(['МТС', 'Билайн', 'МегаФон']),
            ],
            'ip' => [
                'nullable',
                'ipv4',
                Rule::unique('sim_cards')->ignore($this->route('sim_card')),
            ],
        ];
    }
}
