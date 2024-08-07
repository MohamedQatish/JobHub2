<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class JobCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray(Request $request): array
    {
        return parent::toArray($request);
    }
    

    // return [
    //     'data' => JobResource::collection($this->collection),
    //     'pagination' => [
    //         'total' => $this->total(),
    //         'count' => $this->count(),
    //         'per_page' => $this->perPage(),
    //         'current_page' => $this->currentPage(),
    //         'total_pages' => $this->lastPage(),
    //         'next_page_url' => $this->nextPageUrl(),
    //         'prev_page_url' => $this->previousPageUrl(),
    //     ],
    // ];
}
