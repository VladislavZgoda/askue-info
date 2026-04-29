<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreUspdRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'model' => [
                'required',
                Rule::in([
                    'RTR8A.LRsGE-1-1-RUFG',
                    'RTR8A.LRsGE-2-1-RUFG',
                    'RTR8A.LGE-2-2-RUF',
                    'RTR58A.LG-1-1',
                    'RTR58A.LG-2-1',
                ]),
            ],
            'serial_number' => ['required', 'integer', 'digits:7', 'unique:uspds'],
            'lan_ip' => [
                'nullable',
                'ipv4',
                Rule::unless(
                    fn () => $this->input('lan_ip') === '192.168.0.100',
                    Rule::unique('uspds', 'lan_ip'),
                ),
            ],
        ];
    }
}
