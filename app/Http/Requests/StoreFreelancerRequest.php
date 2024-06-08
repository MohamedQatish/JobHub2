<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreFreelancerRequest extends FormRequest
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
            // 'name' => ['required','string','min:6','max:40','unique:freelancers,name'],
            // 'email' => ['required','email','unique:freelancers,email'],
            // 'password' => ['required','confirmed','min:6'],
            'title' => ['required','string','max:30'],
            'description' => ['required','string','min:15'],
            'hourly_wage' => ['required','numeric','min:3'],
            'skills' => ['required','array','distinct'],
            'skill.*.skill_id' => ['required','exists:skills,id','distinct'],
            'favorite_categories' => ['required','array','distinct'],
            'favorite_categories.*.category_id' => ['required','exists:categories,id'],
            'photo' => ['required','file'],
            'country_id' => 'required|exists:countries,id',
            'cv' => ['required','file'],
        ];
    }
}
