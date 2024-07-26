<?php

namespace App\Http\Resources;

use App\Models\Company;
use App\Models\Freelancer;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class RatingsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        if($this->rater_type == 'App\Models\Freelancer'){
            $rater = Freelancer::find($this->rater_id)->first();
        }else{
            $rater = Company::find($this->rater_id)->first();
        }
        return [
            'rater' => $rater->name,
            'rating' => $this->rating,
            'comment' => $this->comment
        ];
    }
}
