<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreJobRequest extends FormRequest
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
            //'owner_id' => ['required','exists:freelancers,id'],
            'category_id' => ['required','exists:categories,id'],
            'title' => ['required','string'],
            'description' => ['string'],
            'qualifications' => ['string'],
            'experience_lvl' => [Rule::in(['entry level','intermediate level','senior level'])],
            'scope' => [Rule::in(['small','medium','large'])],
            'duration' => [Rule::in(['less than 1 month','1 to 3 months','3 to 6 months','more than 6 months'])],
            'fixed_price' => ['numeric'],
            'hourly_payment' => ['numeric'],
            'skills' => ['array'],
            'skills.*.skill_id' => ['exists:skills,id']
        ];
    }
}
