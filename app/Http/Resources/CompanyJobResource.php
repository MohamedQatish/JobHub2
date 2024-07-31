<?php

namespace App\Http\Resources;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CompanyJobResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $category =Category::findOrFail($this->category_id);
        return [
            'id' => $this->id,
            'owner' => $this->owner_id,
            'category' => $this->category->name ?? null,
            'title' => $this->title,
            'description' => $this->description,
            'vacancies' => $this->vacancies,
            'scope' => $this->scope,
            'work_schedule' => $this->work_schedule,
            'price_type' => $this->price_type,
            'hourly_rate_min' => $this->price_type === 'hourly' ? $this->hourly_rate_min : null,
            'hourly_rate_max' => $this->price_type === 'hourly' ? $this->hourly_rate_max : null,
            'fixed_rate' => $this->price_type === 'fixed' ? $this->fixed_rate : null,
            'duration' => $this->duration,
            'applicants_count' => $this->applicants_count,
            'skills' => SkillResource::collection($this->whenLoaded('skills')),
        ];
    }
}
