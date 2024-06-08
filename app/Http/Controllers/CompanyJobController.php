<?php

namespace App\Http\Controllers;

use App\Http\Requests\CompanyJobRequest;
use App\Models\CompanyJob;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CompanyJobController extends Controller
{
    public function create(CompanyJobRequest $request)
    {
        try {
            DB::beginTransaction();
            $company = Auth::User();
            $validated = $request->validated();
            $validated['owner_id'] = $company->id;
            $skills = $validated['skills'];
            unset($validated['skills']);
            $job = CompanyJob::create($validated);
            $job->skills()->attach($skills);
            DB::commit();
            return response()->json(['message' => 'Job created successfully', 'job' => $job], 201);
        } catch (\Exception $e) {
            DB::rollback();
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
