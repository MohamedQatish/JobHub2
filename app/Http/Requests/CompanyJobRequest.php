<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CompanyJobRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'vacancies' => 'required|integer|min:1',
            'scope' => 'required|in:small,medium,large',
            'work_schedule' => 'required|in:Full-time,Part-time',
            'price_type' => 'required|in:hourly,fixed',
            'hourly_rate_min' => 'required_if:price_type,hourly|numeric|min:0',
            'hourly_rate_max' => 'required_if:price_type,hourly|numeric|min:0|gte:hourly_rate_min',
            'fixed_rate' => 'required_if:price_type,fixed|numeric|min:0',
            'category_id'=>'required|exists:categories,id',
            'skills' => 'required|array',
            'skills.*' => 'exists:skills,id',
        ];
    }
    public function messages(): array
    {
        return [
            'title.required' => 'The job title is required.',
            'description.required' => 'The job description is required.',
            'vacancies.required' => 'The number of vacancies is required.',
            'scope.required' => 'The job scope is required.',
            'work_schedule.required' => 'The work schedule is required.',
            'price_type.required' => 'The price type is required.',
            'hourly_rate_min.required_if' => 'The minimum hourly rate is required when price type is hourly.',
            'hourly_rate_max.required_if' => 'The maximum hourly rate is required when price type is hourly.',
            'fixed_rate.required_if' => 'The fixed rate is required when price type is fixed.',
            'skills.required' => 'Skills are required.',
            'skills.*.exists' => 'The selected skill is invalid.',
        ];
    }
}
