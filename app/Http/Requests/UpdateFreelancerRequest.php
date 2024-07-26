<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateFreelancerRequest extends FormRequest
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
            'name' => ['sometimes','required','string','min:6','max:40','unique:freelancers,name'],
            // 'email' => ['required','email','unique:freelancers,email'],
            'password' => ['sometimes','required','confirmed','min:6'],
            'title' => ['sometimes','required','string','max:30'],
            'description' => ['sometimes','required','string','min:15'],
            'hourly_wage' => ['sometimes','required','numeric','min:3'],
            'skills' => ['sometimes','required','array','distinct'],
            'skill.*.skill_id' => ['sometimes','required','exists:skills,id','distinct'],
            'favorite_categories' => ['sometimes','required','array','distinct'],
            'favorite_categories.*.category_id' => ['sometimes','required','exists:categories,id'],
            'photo' => ['sometimes','required','file'],
            'country_id' => 'sometimes,required|exists:countries,id',
            'cv' => ['sometimes','required','file'],
        ];
    }
}
