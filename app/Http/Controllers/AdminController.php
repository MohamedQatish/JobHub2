<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginFreelancerRequest;
use App\Models\Admin;
use App\Http\Requests\StoreAdminRequest;
use App\Http\Requests\UpdateAdminRequest;
use App\Http\Resources\SkillCollection;
use App\Mail\bannedMail;
use App\Models\Category;
use App\Models\Company;
use App\Models\Country;
use App\Models\Freelancer;
use App\Models\Job;
use App\Models\JobSkill;
use App\Models\Skill;
use App\Models\Specialization;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

use function PHPUnit\Framework\isNull;

class AdminController extends Controller
{
    
    public function login(LoginFreelancerRequest $request){
        try{
            $validated = $request->validated();
            $admin = Admin::where('email',$validated['email'])->first();
            if(!Hash::check($validated['password'],$admin->password)){
                return response()->json([
                    'message' => 'wrong password!'
                ]);
            }
            $token = $admin->createToken('admin')->plainTextToken;
            return response()->json([
                // 'admin' => $admin,
                'token' => $token
            ]);
        }catch(\Exception $e){
            return response()->json([
                'message' => $e->getMessage()
            ],500);
        }
    }
    public function logout(Request $request){
        try{
            $request->user()->tokens()->delete();
            return response()->json(['message' => 'Successfully logged out']);
        }catch(\Exception $e){
            return response()->json([
                'message' => $e->getMessage()
            ],500);
        }
    }

    public function ban(Request $request,$id){
        try{
            $request->validate([
                'duration' => ['required','numeric']
            ]);
            $admin = Auth::user();
            if ($request->routeIs('banFreelancer')){
                $user = Freelancer::findorfail($id);
            }else{
                $user = Company::findorfail($id);
            }
            DB::beginTransaction();
            $user->bans()->create([
                'banned_by' => $admin->id,
                'ban_reason' => $request->ban_reason,
                'banned_at' => now(),
                'ban_until' => now()->addDays($request->duration)
            ]);
            Mail::to($user)->send(new bannedMail($user,$admin,$request->duration,$request->ban_reason));
            DB::commit();
            return response()->json([
                'message' => 'User banned successfully'
            ],200);
        }catch(\Exception $e){
            DB::rollBack();
            return response()->json([
                'message' => $e->getMessage()
            ],500);
        }
    }

    public function report(Request $request){
        try {
            $request->validate([
                'from' => ['nullable', 'date'],
                'till' => ['nullable', 'date', 'after_or_equal:from']
            ]);

            $from = $request->from ?? '2024-01-01';
            $till = $request->till ?? now()->toDateString();    
            
            $freelancersCount = Freelancer::whereBetween('created_at', [$from, $till])->count();
            
            $companiesCount = Company::whereBetween('created_at', [$from, $till])->count();
            
            $usersCount = $freelancersCount + $companiesCount;
    
            $jobsCount = Job::whereBetween('created_at', [$from, $till])->count();
    
            $doneJobsCount = Job::where('status', 'done')->whereBetween('created_at', [$from, $till])->count();
    
            $mostWantedSkill = Skill::select('skills.name', DB::raw('COUNT(job_skills.job_id) as job_count'))
                ->join('job_skills', 'skills.id', '=', 'job_skills.skill_id')
                ->join('jobs', 'jobs.id', '=', 'job_skills.job_id')
                ->whereBetween('jobs.created_at', [$from, $till])
                ->groupBy('skills.id', 'skills.name')
                ->orderBy('job_count', 'desc')
                ->first();
    
            $mostWantedCategory = Category::select('categories.name', DB::raw('COUNT(jobs.id) as job_count'))
                ->join('jobs', 'categories.id', '=', 'jobs.category_id')
                ->whereBetween('jobs.created_at', [$from, $till])
                ->groupBy('categories.id', 'categories.name')
                ->orderBy('job_count', 'desc')
                ->first();
    
            $mostActiveFreelancer = Freelancer::select('freelancers.name', DB::raw('COUNT(jobs.id) as job_count'))
                ->join('jobs', 'jobs.owner_id', '=', 'freelancers.id')
                ->whereBetween('jobs.created_at', [$from, $till])
                ->groupBy('freelancers.name')
                ->orderBy('job_count', 'desc')
                ->first();
    
            $bestRatedFreelancer = Freelancer::whereBetween('created_at', [$from, $till])
                ->orderBy('rating', 'desc')
                ->first(['name', 'rating']);
    
            $mostCountry = Country::select('countries.name', DB::raw('COUNT(freelancers.id) as freelancers_count'))
                ->join('freelancers', 'freelancers.country_id', '=', 'countries.id')
                ->whereBetween('freelancers.created_at', [$from, $till])
                ->groupBy('countries.name')
                ->orderBy('freelancers_count', 'desc')
                ->first();
    
            return response()->json([
                'usersCount' => $usersCount,
                'freelancersCount' => $freelancersCount,
                'companiesCount' => $companiesCount,
                'jobsCount' => $jobsCount,
                'doneJobsCount' => $doneJobsCount,
                'mostWantedSkill' => $mostWantedSkill,
                'mostWantedCategory' => $mostWantedCategory,
                'mostActiveFreelancer' => $mostActiveFreelancer,
                'bestRatedFreelancer' => $bestRatedFreelancer,
                'mostCountry' => $mostCountry,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function specializations(){
        try{
            $specializations = Specialization::all();
            return response()->json([
                'specializations' => new SkillCollection($specializations)
            ]);
        }catch(\Exception $e){
            return response()->json([
                'message' => $e->getMessage()
            ],500);
        }
    }

    public function Categories(){
        try{
            $categories = Category::all();
            return response()->json([
                'categories' => new SkillCollection($categories)
            ]);
        }catch(\Exception $e){
            return response()->json([
                'message' => $e->getMessage()
            ],500);
        }
    }
    
    public function addCategory(Request $request){
        try{
            $validated = $request->validate([
                'specialization_id' => ['required','exists:specializations,id'],
                'name' => ['required','string','unique:categories,name'],
            ]);
            DB::beginTransaction();
            Category::create($validated);
            DB::commit();
            return response()->json([
                'message' => 'you\'ve added a new category successfully'
            ],200);
        }catch(\Exception $e){
            DB::rollBack();
            return response()->json([
                'message' => $e->getMessage()
            ],500);
        }
    }

    public function skills(){
        try{
            $skills = Skill::all();
            return response()->json([
                'skills' => new SkillCollection($skills)
            ]);
        }catch(\Exception $e){
            return response()->json([
                'message' => $e->getMessage()
            ],500);
        }
    }

    public function addSkill(Request $request){
        try{
            $validated = $request->validate([
                'name' => ['required','string','unique:skills,name']
            ]);
            DB::beginTransaction();
            Skill::create($validated);
            DB::commit();
            return response()->json([
                'message' => 'you\'ve added a new skill successfully'
            ],200);
        }catch(\Exception $e){
            DB::rollBack();
            return response()->json([
                'message' => $e->getMessage()
            ]);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreAdminRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Admin $admin)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Admin $admin)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateAdminRequest $request, Admin $admin)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Admin $admin)
    {
        //
    }
}
