<?php

namespace App\Http\Resources;

use App\Models\Country;
use App\Models\Specialization;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CompanyProfileResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $country = Country::find($this->country_id);
        $specialization = Specialization::find($this->specialization_id);

        return [
            'company_name' => $this->company_name,
            'email' => $this->email,
            'specialization' => $specialization ? $specialization->name : null,
            'website' => $this->website,
            'location' => $this->location,
            'description' => $this->description,
            'followers' => $this->followers,
            'photo' => $this->photo ? $this->photo->name : null,
            'country' => $country ? $country->name : null,
            'my_jobs' => CompanyJobResource::collection($this->whenLoaded('jobs')),
            'ratings' => $this->whenLoaded('ratingsReceived', function () {
                return new RatingsCollection($this->ratingsReceived);
            })
        ];
    }
}
