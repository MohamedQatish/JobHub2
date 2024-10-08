<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginCompanyRequest;
use App\Http\Requests\RegisterCompanyRequest;
use App\Http\Requests\StoreCompanyRequest;
use App\Http\Resources\CompanyResource;
use App\Http\Resources\CompanyProfileResource;
use App\Mail\SendCodeResetPassword;
use App\Models\Code;
use App\Models\Company;
use App\Models\Freelancer;
use App\Models\freelancerCompaneFollower;
use App\Models\Ratings;
use App\Models\ResetCode;
use App\Models\Wallet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Traits\VerificationTrait;
use Illuminate\Support\Facades\Log;
use App\Traits\UploadPhotoTrait;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class CompanyController extends Controller
{
    use VerificationTrait, UploadPhotoTrait;
    public function register(RegisterCompanyRequest $request)
    {
        try {
            DB::beginTransaction();
            $validated = $request->validated();
            $validated['password'] = Hash::make($validated['password']);
            $company = Company::create($validated);
            $code = $this->sendCode($company);
            $token = $company->createToken('company')->plainTextToken;
            Wallet::create([
                'owner_id' => $company->id,
                'owner_type' => Company::class,
                'balance' => 500.00,
            ]);
            DB::commit();
            return response()->json([
                'company' => $company,
                'code' => $code,
                'token' => $token
            ], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function checkCode(Request $request)
    {
        try {
            DB::beginTransaction();
            $user = Auth::user();
            if (isset($user->verified_at)) {
                return response()->json([
                    'messsage' => 'you are already verified'
                ]);
            }
            $validated = $request->validate([
                'code' => ['required', 'numeric', 'exists:codes,code']
            ]);
            $code = Code::where('code', $validated['code'])->where('email', $user->email)->first();
            if (!isset($code)) {
                return response()->json([
                    'message' => 'invalid code'
                ], 500);
            }
            if ($code->created_at > now()->addHour()) {
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
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function createProfile(StoreCompanyRequest $request)
    {
        try {
            $company = Auth::user();
            $validated = $request->validated();
            DB::beginTransaction();

            if ($request->hasFile('photo')) {
                $photo = $this->uploadPhoto($request, 'photos', "companies");
                if ($company->photo) {
                    $company->photo()->update([
                        'name' => $photo
                    ]);
                } else {
                    $company->photo()->create([
                        'name' => $photo
                    ]);
                }
            }
            unset($validated['photo']);

            $company->update($validated);
            DB::commit();

            return response()->json(['message' => 'Profile updated successfully', 'company' => $company]);
        } catch (\Throwable $th) {
            DB::rollBack();
            Log::error($th);

            return response()->json(['message' => 'An error occurred', 'error' => $th->getMessage()], 500);
        }
    }

    // public function updateProfile(StoreCompanyRequest $request)
    // {
    //     try {
    //         $company = Auth::user();
    //         $validated = $request->validated();
    //         DB::beginTransaction();

    //         if ($request->hasFile('photo')) {
    //             // تحقق مما إذا كان هناك صورة قديمة واحذفها
    //             if ($company->photo) {
    //                 Storage::delete('companies/'.$company->photo->name);
    //                 $company->photo()->delete();
    //             }

    //             $photo = $this->uploadPhoto($request, 'photos', "companies");
    //             $company->photo()->create([
    //                 'name' => $photo
    //             ]);
    //         }

    //         unset($validated['photo']);

    //         $company->update($validated);
    //         DB::commit();

    //         return response()->json(['message' => 'Profile updated successfully', 'company' => $company]);
    //     } catch (\Throwable $th) {
    //         DB::rollBack();
    //         Log::error($th);

    //         return response()->json(['message' => 'An error occurred', 'error' => $th->getMessage()], 500);
    //     }
    // }


    public function login(LoginCompanyRequest $request)
    {
        try {
            $validated = $request->validated();
            $company = Company::where('email', $validated['email'])->first();
            if (!Hash::check($validated['password'], $company->password)) {
                return response()->json([
                    'message' => 'wrong password!'
                ]);
            }
            $token = $company->createToken('company')->plainTextToken;
            return response()->json([
                'company' => new CompanyResource($company),
                'token' => $token
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function logout(Request $request)
    {
        try {
            $request->user()->tokens()->delete();
            return response()->json(['message' => 'Successfully logged out']);
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ], 500);
        }
    }



    public function follow($companyId)
    {
        $freelancer = Auth::user();
        if (!$freelancer) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $company = Company::find($companyId);
        if (!$company) {
            return response()->json(['message' => 'Company not found'], 404);
        }

        $freelancer->followedCompanies()->syncWithoutDetaching([$company->id]);
        $company->increment('followers');
        return response()->json(['message' => 'Company followed successfully.']);
    }

    public function unfollow($companyId)
    {
        $freelancer = Auth::user();
        if (!$freelancer) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $company = Company::find($companyId);
        if (!$company) {
            return response()->json(['message' => 'Company not found'], 404);
        }

        $freelancer->followedCompanies()->detach($company->id);
        $company->decrement('followers');
        return response()->json(['message' => 'Company unfollowed successfully.']);
    }


    public function rate(Request $request, $id)
    {
        try {
            $request->validate([
                'rating' => ['required', 'digits_between:0,5'],
                'comment' => ['string']
            ]);

            $user = Auth::user();

            if ($request->routeIs('rate.freelancer2')) {
                $rated = Freelancer::findOrFail($id);
                if ($user instanceof Freelancer && $user->id == $rated->id) {
                    return response()->json([
                        'message' => 'You cannot rate yourself'
                    ], 400);
                }
            } elseif ($request->routeIs('rate.company2')) {
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

            $oldRate = $rated->ratingsReceived()->where('rater_id', $user->id);
            if (isset($oldRate)) {
                $oldRate->delete();
            }
            $rating = Ratings::create([
                'rating' => $request->rating,
                'comment' => $request->comment
            ]);
            $user->ratingsGiven()->save($rating);
            $rated->ratingsReceived()->save($rating);

            $ratings = $rated->ratingsReceived;
            $totalRating = $ratings->sum('rating');
            $averageRating = $ratings->count() > 0 ? $totalRating / $ratings->count() : 0;

            $rated->update(['rating' => $averageRating]);
            return response()->json([
                'message' => 'you rated him successfully'
            ], 200);
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => $e->getMessage()
            ], 500);
        }
    }
    // public function show(Company $company)
    // {
    //     try {
    //         $company->load('photo', 'jobs.skills', 'ratingsReceived');
    //         return response()->json([
    //             'company' => new CompanyProfileResource($company)
    //         ]);
    //     } catch (\Exception $e) {
    //         return response()->json([
    //             'message' => $e->getMessage()
    //         ]);
    //     }
    // }
    public function show($id)
    {
        $company = Company::with(['jobs', 'ratingsReceived'])->findOrFail($id);
        return new CompanyProfileResource($company);
    }

    //----------- Rest password -------------

    public function forgotpassword(Request $request)
    {
        $data = $request->validate([
            'email' => 'required|email|exists:companies',
        ]);

        ResetCode::where('email', $request->email)->delete();

        $data['code'] = mt_rand(100000, 999999);

        $codeData = ResetCode::create($data);

        Mail::to($request->email)->send(new SendCodeResetPassword($codeData->code));

        return response(['message' => trans('code.sent')], 200);
    }

    public function Check(Request $request)
    {
        $request->validate([
            'code' => 'required|string|exists:reset_codes',
        ]);

        $passwordReset = ResetCode::firstWhere('code', $request->code);

        if ($passwordReset->created_at > now()->addHour()) {
            $passwordReset->delete();
            return response(['message' => trans('passwords.code_is_expire')], 422);
        }

        return response([
            'code' => $passwordReset->code,
            'message' => trans('passwords.code_is_valid')
        ], 200);
    }




    public function ResetCodePassword(Request $request)
    {
        $request->validate([
            'code' => 'required|string|exists:reset_codes',
            'password' => 'required|string|min:6|confirmed',
        ]);

        $passwordReset = ResetCode::firstWhere('code', $request->code);

        if ($passwordReset->created_at > now()->addHour()) {
            $passwordReset->delete();
            return response(['message' => trans('passwords.code_is_expire')], 422);
        }

        $user = Company::firstWhere('email', $passwordReset->email);

        $user->update(['password' => Hash::make($request->input('password'))]);

        $passwordReset->delete();

        return response(['message' => 'password has been successfully reset'], 200);
    }
}
