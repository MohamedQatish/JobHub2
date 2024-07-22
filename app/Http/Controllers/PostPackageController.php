<?php

namespace App\Http\Controllers;

use App\Http\Requests\PurchasePackageRequest;
use App\Http\Resources\PostPackageResource;
use App\Models\Company;
use App\Models\CompanyPostPackage;
use App\Models\Freelancer;
use App\Models\PostPackage;
use App\Models\Wallet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PostPackageController extends Controller
{
    public function show()
    {
        $packages = PostPackage::all();
        return response()->json([
            'success' => true,
            'data' => PostPackageResource::collection($packages),
        ]); return response()->json($packages);
    }
    public function purchase(PurchasePackageRequest $request)
    {
        try {
            DB::beginTransaction();
            $company = Auth::user();
            if (!$company || !$company->isCompany()) {
                return response()->json(['message' => 'Unauthorized'], 403);
            }
            $validated = $request->validated();
            if ($request->has('package_id')) {
                $package_id = $validated['package_id'];
                $postPackage = PostPackage::findOrFail($package_id);
                $price = $postPackage->price;
                $quantity = $postPackage->quantity;
            } else {
                $quantity = $validated['quantity'];
                $price_per_post = PostPackage::findOrFail(1)->price;
                $price = $price_per_post * $quantity;
            }


            $wallet = Wallet::where('owner_id', $company->id)->where('owner_type', 'App\Models\Company')->firstOrFail();
            if ($wallet->balance < $price) {
                $needed_balance = $price - $wallet->balance;
                return response()->json(['message' => "Insufficient balance. You need an additional $needed_balance to complete this purchase."], 401);
            }

            $wallet->balance -= $price;
            $wallet->save();

            $companyPackage = CompanyPostPackage::create([
                'company_id' => $company->id,
                'post_package_id' => $request->has('package_id') ? $package_id : 1,
                'remaining_posts' => $quantity,
            ]);

            DB::commit();

            return response()->json(['message' => 'Package purchased successfully', 'companyPackage' => $companyPackage], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'An error occurred', 'error' => $e->getMessage()], 500);
        }
    }


    public function totalPosts($companyId)
    {
        try {
            $totalPosts = CompanyPostPackage::where('company_id', $companyId)->sum('remaining_posts');
            return response()->json(['total_posts' => $totalPosts], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'An error occurred', 'error' => $e->getMessage()], 500);
        }
    }
}
