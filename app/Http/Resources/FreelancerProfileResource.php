<?php

namespace App\Http\Resources;

use App\Models\Country;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class FreelancerProfileResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $country = Country::find($this->country_id)->first();
        return [
            'name' => $this->name,
            'email' => $this->email,
            'rating' => $this->rating,
            'photo' => $this->photo ? $this->photo->name : null,
            'cv' => $this->cv ? $this->cv->name : null,
            'county' => $country->name,
            'skills' => $this->whenLoaded('skills', function () {
                return $this->skills->pluck('name');
            }),
            'my jobs' => $this->whenLoaded('jobs',function (){
                return new JobCollection($this->jobs);
            }),
            'ratings' => $this->whenLoaded('ratingsReceived',function (){
                return new RatingsCollection($this->ratingsReceived);
            })
        ];
    }
}
