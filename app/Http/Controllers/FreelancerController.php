<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginFreelancerRequest;
use App\Http\Requests\RegisterFreelancerRequest;
use App\Models\Freelancer;
use App\Http\Requests\StoreFreelancerRequest;
use App\Http\Requests\UpdateFreelancerRequest;
use App\Http\Resources\FreelancerCollection;
use App\Http\Resources\FreelancerProfileResource;
use App\Http\Resources\FreelancerResource;
use App\Mail\codeMail;
use App\Mail\newApplicationMail;
use App\Mail\WelcomeMail;
use App\Models\Code;
use App\Models\FreelancerFavoriteCategories;
use App\Models\FreelancerFavoriteJobs;
use App\Models\FreelancerRating;
use App\Models\FreelancerSkill;
use App\Models\Job;
use App\Models\Company;
use App\Models\CompanyJob;
use App\Models\JobApplicants;
use App\Models\Photo;
use App\Models\Ratings;
use App\Models\Wallet;
use App\Traits\UploadCvTrait;
use App\Traits\UploadPhotoTrait;
use App\Traits\VerificationTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use PDO;

use function PHPUnit\Framework\isEmpty;
use function PHPUnit\Framework\isNull;

class FreelancerController extends Controller
{
    use UploadPhotoTrait,UploadCvTrait,VerificationTrait;
    /**
     * Display a listing of the resource.
     */
    // public function register(StoreFreelancerRequest $request){
    //     try{
    //         $validated = $request->validated();
    //         $validated['password'] = Hash::make($validated['password']);
    //         $skills = $validated['skills'];
    //         unset($validated['skills']);
    //         unset($validated['photo']);
    //         DB::beginTransaction();
    //         $freelancer = Freelancer::create($validated);
    //         //First Approach
    //         // "skills":[
    //         //     {
    //         //       "skill_id":1
    //         //     },{
    //         //       "skill_id":1
    //         //     },
    //         foreach($skills as $skill){
    //             FreelancerSkill::create([
    //                 'freelancer_id' => $freelancer->id,
    //                 'skill_id' => $skill['skill_id']
    //             ]);
    //         }
    //         //Second Approach
    //         //"skills" : [1,2,3]
    //         // foreach($skills as $skill){
    //         //     FreelancerSkill::create([
    //         //         'freelancer_id' => $freelancer->id,
    //         //         'skill_id' => $skill
    //         //     ]);
    //         // }
    //         if($request->hasFile('photo')){
    //             $photo = $this->uploadPhoto($request,'freelancers');
    //             $freelancer->photo()->create([
    //                 'name' => $photo
    //             ]);
    //         }
    //         $token = $freelancer->createToken('freelancer')->plainTextToken;
    //         //Mail::to($freelancer->email)->send(new WelcomeMail());
    //         DB::commit();
    //         return response()->json([
    //             'freelancer' => $freelancer,
    //             'token' => $token
    //         ]);
    //     }catch(\Exception $e){
    //         DB::rollBack();
    //         return response()->json([
    //             'message' => $e->getMessage()
    //         ],500);
    //     }
    // }

    public function register(RegisterFreelancerRequest $request){
        try{
            DB::beginTransaction();
            $validated = $request->validated();
            $validated['password'] = Hash::make($validated['password']);
            $freelancer = Freelancer::create($validated);
            $code = $this->sendCode($freelancer);
            $token = $freelancer->createToken('freelancer')->plainTextToken;
            Wallet::create([
                'owner_id' => $freelancer->id,
                'owner_type' => Freelancer::class,
                'balance' => 0.00,
            ]);
            DB::commit();
            return response()->json([
                'freelancer' => $freelancer,
                'code' => $code,
                'token' => $token
            ],200);
        }catch(\Exception $e){
            DB::rollBack();
            return response()->json([
                'message' => $e->getMessage()
            ],500);
        }
    }

    public function checkCode(Request $request){
        try{
            DB::beginTransaction();
            $user = Auth::user();
            if(isset($user->verified_at)){
                return response()->json([
                    'messsage' => 'you are already verified'
                ]);
            }
            $validated = $request->validate([
                'code' => ['required','numeric','digits:6','exists:codes,code']
            ]);
            $code = Code::where('code',$validated['code'])->where('email',$user->email)->first();
            if(!isset($code)){
                return response()->json([
                    'message' => 'invalid code'
                ],500);
            }
            if($code->created_at > now()->addHour()){
                $code->delete();
                return response()->json([
                    'message' => 'the code is outdated'
                ]);
            }
            $user->verified_at = now();
            $user->save();
            $code->delete();
            DB::commit();
            return response()->json([
                'message' => 'your email is verified'
            ],200);
        }catch(\Exception $e){
            return response()->json([
                'message' => $e->getMessage()
            ],500);
        }
    }

    public function createProfile(StoreFreelancerRequest $request){
        try{
            $freelancer = Auth::user();
            if($freelancer->hourly_wage > 0.1){
                return response()->json([
                    'message' => 'youve already created your profile'
                ]);
            }
            $validated = $request->validated();
            DB::beginTransaction();
            if($request->hasFile('photo')){
                $photo = $this->uploadPhoto($request,'photos','freelancers');
                $freelancer->photo()->create([
                    'name' => $photo
                ]);
            }
            unset($validated['photo']);
            if($request->hasFile('cv')){
                $cv = $this->uploadCv($request,'cvs');
                $freelancer->cv()->create([
                    //'freelancer_id' => $freelancer->id,
                    'name' => $cv
                ]);
            }
            unset($validated['cv']);
            $skills = $validated['skills'];
            unset($validated['skills']);
            foreach($skills as $skill){
                FreelancerSkill::create([
                    'freelancer_id' => $freelancer->id,
                    'skill_id' => $skill['skill_id']
                ]);
            }
            $favoriteCategories = $validated['favorite_categories'];
            unset($validated['favorite_categories']);
            foreach($favoriteCategories as $category){
                FreelancerFavoriteCategories::create([
                    'freelancer_id' => $freelancer->id,
                    'category_id' => $category['category_id']
                ]);
            }
            $freelancer->update($validated);
            DB::commit();
            return response()->json([
                'message' => 'your profile has been created successfully'
            ],200);
        }catch(\Exception $e){
            DB::rollBack();
            return response()->json([
                'message' => $e->getMessage()
            ],500);
        }
    }

    public function login(LoginFreelancerRequest $request){
        try{
            $validated = $request->validated();
            $freelancer = Freelancer::where('email',$validated['email'])->first();
            if(!Hash::check($validated['password'],$freelancer->password)){
                return response()->json([
                    'message' => 'wrong password!'
                ]);
            }
            $token = $freelancer->createToken('freelancer')->plainTextToken;
            return response()->json([
                'freelancer' => $freelancer,
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

    public function myJobs(){
        try{
            // $freelancer = Freelancer::where('id',1)->first();
            $freelancer = Auth::user();
            $jobs = $freelancer->jobs;
            return response()->json([
                'jobs' => $jobs
            ]);
        }catch(\Exception $e){
            return response()->json([
                'message' => $e->getMessage()
            ],500);
        }
    }

    public function applyToJob(Job $job){
        try{
            $freelancer = Auth::user();
            if(!$freelancer->jobs->where('id',$job->id)->isEmpty()){
                return response()->json([
                    'message' => 'you can\'t apply to your own jobs'
                ],400);
            }
            if($freelancer->applications->where('job_id',$job->id)->isNotEmpty()){
                return response()->json([
                    'message' => "you have already applied to this job!"
                ],400);
            }
            DB::beginTransaction();
            // $owner = Freelancer::where('id',$job->owner_id);
            // $job->applicants_count = $job->applicants_count + 1;
            // $job->save();
            $job->increment('applicants_count');
            JobApplicants::create([
                'job_id' => $job->id,
                'freelancer_id' => $freelancer->id
            ]);
            DB::commit();
            Mail::to($job->owner)->send(new newApplicationMail($freelancer,$job));
            return response()->json([
                'message' => "you've applied to this job"
            ],200);
        }catch(\Exception $e){
            DB::rollBack();
            return response()->json([
                'message' => $e->getMessage()
            ],500);
        }
    }

    public function myApplications(){
        try{
            $freelancer = Auth::user();
            $applications = $freelancer->applications;
            $jobs = [];
            foreach($applications as $application){
                $jobs[]=[$application->job];
            }
            return response()->json([
                'applications' => $jobs
            ],200);
        }catch(\Exception $e){
            return response()->json([
                'message' => $e->getMessage()
            ],500);
        }
    }

    public function addToFavorites(Job $job){
        try{
            $freelancer = Auth::user();
            DB::beginTransaction();
            $j = $freelancer->favoriteJobs()->where('job_id',$job->id)->first();
            if(isset($j)){
                $j->delete();
                DB::commit();
                return response()->json([
                    'message' => 'you\'ve removed this job from your favorites list'
                ],200);
            }
            // FreelancerFavoriteJobs::create([
            //     'freelancer_id' => $freelancer->id,
            //     'job_id' => $job->id
            // ]);
            //deatach
            $freelancer->favoriteJobs()->attach($job->id);
            DB::commit();
            return response()->json([
                'message' => 'you\'ve added this job to your favorite jobs'
            ],200);
        }catch(\Exception $e){
            DB::rollBack();
            return response()->json([
                'message' => $e->getMessage()
            ],500);
        }
    }

    public function rateFreelancer(Request $request,Freelancer $freelancer){
        try{
            $request->validate([
                'rating'=>['required','digits_between:0,5']
            ]);
            //DB::beginTransaction();
            $user = Auth::user();
            if($user->id == $freelancer->id){
                return response()->json([
                    'message' => 'you cant rate yourself'
                ]);
            }
            $oldRate = $freelancer->ratings()->where('user_id',$user->id);
            if(isset($oldRate)){
                $oldRate->delete();
            }
            FreelancerRating::create([
                'freelancer_id' => $freelancer->id,
                'user_id' => $user->id,
                'rating' => $request->rating
            ]);
            $ratings = $freelancer->ratings;
            $rat = 0;
            foreach($ratings as $rating){
                $rat += $rating['rating'];
            }
            $freelancer->update([
                'rating' => $rat/count($ratings)
            ]);
            return response()->json([
                'message' => 'you rated him successfully'
            ],200);
            //DB::commit();
        }catch(\Exception $e){
            //DB::rollBack();
            return response()->json([
                'message' => $e->getMessage()
            ],500);
        }
    }

    public function report(Request $request,$id){
        try{
            $request->validate([
                'reason' => 'required'
            ]);
            if($request->routeIs('report.freelancer')){
                $reported = Freelancer::findOrFail($id);
            }elseif($request->routeIs('report.company')){
                $reported = Company::findOrFail($id);
            }elseif($request->routeIs('report.job')){
                $reported = Job::findOrFail($id);
            }
            elseif($request->routeIs('report.companyJob')){
                $reported = CompanyJob::findOrFail($id);
            }
            $freelancer = Auth::user();
            DB::beginTransaction();
            $reported->reported()->create([
                'reporter' => $freelancer->id,
                'report_reason' => $request->reason
            ]);
            DB::commit();
            return response()->json([
                'message' => 'Report submitted successfully'
            ],200);
        }catch(\Exception $e){
            DB::rollBack();
            return response()->json([
                'message' => $e->getMessage()
            ]);
        }
    }

    public function rate(Request $request,$id){
        try{
            $request->validate([
                'rating'=>['required','between:0,5'],
                'comment' => ['string']
            ]);

            $user = Auth::user();

            if ($request->routeIs('rate.freelancer')) {
                $rated = Freelancer::findOrFail($id);
                if ($user instanceof Freelancer && $user->id == $rated->id) {
                    return response()->json([
                        'message' => 'You cannot rate yourself'
                    ], 400);
                }
            } elseif ($request->routeIs('rate.company')) {
                $rated = Company::findOrFail($id);
                if ($user instanceof Company && $user->id == $rated->id) {
                    return response()->json([
                        'message' => 'You cannot rate yourself'
                    ], 400);
                }
            } else {
                return response()->json(['message' => 'Invalid route'], 400);
            }

            DB::beginTransaction();

            $oldRate = $rated->ratingsReceived()->where('rater_id',$user->id);
            if(isset($oldRate)){
                $oldRate->delete();
            }
            $rating = new Ratings([
                'rating' => $request->rating,
                'comment' => $request->comment
            ]);
            
            $rating->rater()->associate($user);
            $rating->rateable()->associate($rated);
            $rating->save();

            $ratings = $rated->ratingsReceived;
            $totalRating = $ratings->sum('rating');
            $averageRating = $ratings->count() > 0 ? $totalRating / $ratings->count() : 0;

            $rated->update(['rating' => $averageRating]);
            DB::commit();
            return response()->json([
                'message' => 'you rated him successfully'
            ],200);
        }catch(\Exception $e){
            DB::rollBack();
            return response()->json([
                'message' => $e->getMessage()
            ],500);
        }
    }

    // public function sendCode(){
    //     $freelancer = Freelancer::
    //     $code = mt_rand(100000,999999);
    //     Code::create([
    //         'code' => $code,
    //     ]);
    // }

    public function index()
    {
        try{
            $freelancers = Freelancer::whereNotNull('verified_at')->get();
            return response()->json([
                'freelancers'=> new FreelancerCollection($freelancers)
            ]);
        }catch(\Exception $e){
            return response()->json([
                'message'=>$e->getMessage()
            ]);
        }
    }


    /**
     * Display the specified resource.
     */
    public function show(Freelancer $freelancer)
    {
        try{
            $freelancer->load('photo', 'cv', 'skills', 'jobs', 'ratingsReceived');
            return response()->json([
                'freelancer' => new FreelancerProfileResource($freelancer)
            ]);
        }catch(\Exception $e){
            return response()->json([
                'message'=>$e->getMessage()
            ]);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Freelancer $freelancer)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateFreelancerRequest $request)
    {
        try{
            $freelancer = Auth::user();
            $validated = $request->validated();
            DB::beginTransaction();
            if($request->hasFile('photo')){
                $photo = $this->uploadPhoto($request,'photos','freelancers');
                $freelancer->photo()->create([
                    'name' => $photo
                ]);
                unset($validated['photo']);
            }
            if($request->hasFile('cv')){
                $cv = $this->uploadCv($request,'cvs');
                $freelancer->cv()->create([
                    //'freelancer_id' => $freelancer->id,
                    'name' => $cv
                ]);
                unset($validated['cv']);
            }
            if($request['skills']){
                $skills = $validated['skills'];
                unset($validated['skills']);
                foreach($skills as $skill){
                    FreelancerSkill::create([
                        'freelancer_id' => $freelancer->id,
                        'skill_id' => $skill['skill_id']
                    ]);
                }
            }
            if($request['favorite_categories']){
                $favoriteCategories = $validated['favorite_categories'];
                unset($validated['favorite_categories']);
                foreach($favoriteCategories as $category){
                    FreelancerFavoriteCategories::create([
                        'freelancer_id' => $freelancer->id,
                        'category_id' => $category['category_id']
                    ]);
                }
            }
            $freelancer->update($validated);
            DB::commit();
            return response()->json([
                'message' => 'Your information has been successfully updated.'
            ]);
        }catch(\Exception $e){
            DB::rollBack();
            return response()->json([
                'message'=>$e->getMessage()
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Freelancer $freelancer)
    {
        try{
            $freelancer->delete();
            return response()->json([
                'message' => 'the freelancer has been deleted successfully'
            ],200);
        }catch(\Exception $e){
            return response()->json([
                'message' => $e->getMessage()
            ]);
        }
    }
}
