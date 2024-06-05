<?php

namespace App\Http\Controllers;

use App\Filters\JobFilter;
use App\Models\Job;
use App\Http\Requests\StoreJobRequest;
use App\Http\Requests\UpdateJobRequest;
use App\Http\Resources\FreelancerCollection;
use App\Http\Resources\JobCollection;
use App\Models\JobSkill;
use App\Models\Skill;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

use function PHPUnit\Framework\isNull;

class JobController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
{
    $freelancer = Auth::user();
    $filter = new JobFilter();
    $latest = $request->query('latest');
    $byRating = $request->query('byRating');
    $filteredItems = $filter->transform($request);
    $categories = $freelancer->favoriteCategories->pluck('category_id');

    $jobsQuery = Job::whereIn('category_id', $categories)->with('owner')->with('skills');
    
    foreach ($filteredItems as $condition) {
        $jobsQuery->where(...$condition);
    }
    
    if ($byRating) {
        $jobsQuery->join('freelancers', 'jobs.owner_id', '=', 'freelancers.id')
                  ->orderBy('freelancers.rating', 'desc');
    }

    if ($latest) {
        $jobsQuery->orderBy('created_at', 'desc');
    }

    $jobs = $jobsQuery->paginate(20)->appends($request->query());
    return response()->json([
        'jobs' => new JobCollection($jobs)
    ]);
}

        // public function index(Request $request)
    // {
    //     $filter= new customerFilter();
    //     $filterItems= $filter->transform($request);
    //     $includeInvoices= $request->query('includeInvoices');
    //     $customers=Customer::where($filterItems);
    //     if($includeInvoices){
    //         $customers=$customers->with('invoices');
    //     }
    //     return new CustomerCollection($customers->paginate()->appends($request->query()));
    // }

    /**$jobs = $jobsQuery->paginate()->appends($request->query());

     * Store a newly created resource in storage.
     */
    public function store(StoreJobRequest $request)
    {
        try{
            $freelancer = Auth::user();
            // $freelancer = $request->user();
            // if(isNull($freelancer)){
            //     return response()->json([
            //         'message' => 'null'
            //     ]);
            // }
            $validated = $request->validated();
            $skills = $validated['skills'];
            unset($validated['skills']);
            DB::beginTransaction();
            $job = Job::create(array_merge($validated,['owner_id' => $freelancer->id]));
            // $job = Job::create($validated);
            //$job->save();
            foreach($skills as $skill){
                JobSkill::create([
                    'job_id' => $job->id,
                    'skill_id' => $skill['skill_id']
                ]);
            }
            DB::commit();
            return response()->json([
                'job' => $job,
                'message' => 'Your job has been successfully published.'
            ]);            
        }catch(\Exception $e){
            DB::rollBack();
            return response()->json([
                'message' => $e->getMessage()
            ],500);
        }
    }

    // public function jobApplications(Job $job){
    //     try{
    //         // $max = 0;
    //         // for($i =1 ; $i<30; $i+=1){
    //         //     $temp = count(JobSkill::where('skill_id',$i));
    //         //     if($max<$temp)
    //         //     {
    //         //         $j = $i;
    //         //         $max=$temp;
    //         //     }
    //         // }
    //         // $skill = Skill::where('id',$j);
    //         $applications = $job->applicants;
    //         $freelancers = [];
    //         foreach($applications as $application){
    //             $freelancers[] = [$application->freelancer];
    //         }
    //         return response()->json([
    //             'freelancers' => $freelancers
    //         ],200);
    //     }catch(\Exception $e){
    //         return response()->json([
    //             'message' => $e->getMessage()
    //         ],500);
    //     }
    // }

    public function jobApplications(Job $job){
        try{
            $applications = $job->applicants()->with('freelancer.photo')->get();
            $freelancers = $applications->map(function($application){
                return $application->freelancer;
            });
            return response()->json([
                new FreelancerCollection($freelancers)
            ],200);
        }catch(\Exception $e){
            return response()->json([
                'message' => $e->getMessage()
            ],500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Job $job)
    {
        
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Job $job)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateJobRequest $request, Job $job)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Job $job)
    {
        //
    }
}
