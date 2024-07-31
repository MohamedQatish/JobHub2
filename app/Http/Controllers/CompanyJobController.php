<?php

namespace App\Http\Controllers;

use App\Http\Requests\CompanyJobRequest;
use App\Http\Resources\CompanyJobResource;
use App\Models\Company;
use App\Models\CompanyJob;
use App\Models\CompanyPostPackage;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CompanyJobController extends Controller
{
    public function myJobs($id)
    {
        try {
            $company = Company::findOrFail($id);
            $jobs = $company->jobs()->with('category', 'skills')->get();
            return response()->json(['jobs' => CompanyJobResource::collection($jobs)], 200);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json(['message' => 'Company not found', 'error' => $e->getMessage()], 404);
        } catch (\Exception $e) {
            return response()->json(['message' => 'An error occurred while fetching the jobs', 'error' => $e->getMessage()], 500);
        }
    }
    public function create(CompanyJobRequest $request)
    {
        try {
            DB::beginTransaction();
            $company = Auth::user();


            $companyPackage = CompanyPostPackage::where('company_id', $company->id)
                ->where('remaining_posts', '>', 0)
                ->first();

            if (!$companyPackage) {
                return response()->json(['message' => 'No available post package. Please purchase a package.'], 403);
            }

            $validated = $request->validated();
            $validated['owner_id'] = $company->id;
            $skills = $validated['skills'];
            unset($validated['skills']);
            $job = CompanyJob::create($validated);
            $job->skills()->attach($skills);

            $companyPackage->decrement('remaining_posts');

            DB::commit();
            return response()->json(['message' => 'Job created successfully', 'job' => $job], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }
    public function update(CompanyJobRequest $request, $id)
    {
        try {
            DB::beginTransaction();
            $job = CompanyJob::findOrFail($id);
            $company = Auth::user();
            if ($job->owner_id !== $company->id) {
                return response()->json(['message' => 'Unauthorized'], 403);
            }
            $validated = $request->validated();
            $skills = $validated['skills'];
            unset($validated['skills']);
            $job->update($validated);
            $job->skills()->sync($skills);
            DB::commit();

            return response()->json(['message' => 'Job updated successfully', 'job' => $job], 200);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['message' => 'Failed to update job. Please try again.', 'error' => $e->getMessage()], 500);
        }
    }
    public function delete($id)
    {
        try {
            DB::beginTransaction();
            $job = CompanyJob::findOrFail($id);
            $company = Auth::user();
            if ($job->owner_id !== $company->id) {
                return response()->json(['message' => 'Unauthorized'], 403);
            }
            $job->skills()->detach();
            $job->delete();
            DB::commit();
            return response()->json(['message' => 'Job deleted successfully'], 200);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['message' => 'Failed to delete job. Please try again.', 'error' => $e->getMessage()], 500);
        }
    }
}
