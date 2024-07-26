<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class JobResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $payment = $this->hourly_payment ?? $this->fixed_price;
        $rating = optional($this->whenLoaded('owner'))->rating ?? "UnRated";
        // $rating = $this->whenLoaded('owner',$this->owner->rating) ?? "UnRated";
        $createdAt = $this->created_at->diffForHumans();
        return [
            'job id' => $this->id,
            'owner' => optional($this->whenLoaded('owner'))->name,
            //'owner' => $this->whenLoaded('owner',$this->owner->name),
            'owner rating' => $rating,
            'title' => $this->title,
            'description' => $this->description,
            'experience level' => $this->experience_lvl,
            'payment' => $payment,
            'duration' => $this->duration,
            'skills' => $this->whenLoaded('skills', function () {
                return $this->skills->pluck('name');
            }),
            'proposals' => $this->applicants_count,
            'posted at' => $createdAt
        ];
    }
}
