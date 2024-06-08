<?php

namespace App\Http\Controllers;

use App\Http\Requests\RatingRequest;
use App\Models\CompanyRating;
use Illuminate\Http\Request;

class CompanyRatingController extends Controller
{
    public function store(RatingRequest $request, $id)
    {
        $validated=$request->validated();

        $rating = CompanyRating::updateOrCreate(
            [
                'company_id' => $id,
                'freelancer_id' => auth()->id(),
            ],
            [
                'rating' => $validated['rating']
            ]);

        return response()->json([
            'message' => 'Company rated successfully',
            'rating' => $rating
        ], 201);
    }
}
