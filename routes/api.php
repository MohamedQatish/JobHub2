<?php

use App\Http\Controllers\ChatController;
use App\Http\Controllers\ChatMessageController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\CompanyJobController;
use App\Http\Controllers\CompanyRatingController;
use App\Http\Controllers\FreelancerController;
use App\Http\Controllers\JobController;
use App\Http\Controllers\VerificationController;
use App\Http\Middleware\isFreelancer;
use App\Http\Middleware\isCompany;
use App\Models\CompanyRating;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


Route::prefix('freelancers')->group(function () {
    Route::controller(FreelancerController::class)->group(function () {
        // Routes that do not require authentication
        Route::post('/register', 'register');
        Route::post('/login', 'login');

        // Routes that require authentication
        Route::middleware(['auth:sanctum', 'isFreelancer'])->group(function () {
            Route::post('/checkCode', 'checkCode');
            Route::post('/createProfile', 'createProfile');
            Route::get('/myJobs', 'myJobs');
            Route::get('/myApplications', 'myApplications');
            Route::post('/applyToJob/{job}', 'applyToJob');
            Route::post('/rate/{freelancer}', 'rateFreelancer');
        });
    });

    Route::prefix('jobs')->middleware(['auth:sanctum', 'isFreelancer'])->group(function () {
        Route::controller(JobController::class)->group(function () {
            Route::get('', 'index');
            Route::post('/store', 'store');
            Route::get('/{job}/applications', 'jobApplications')->middleware('isOwner');
        });
        Route::controller(FreelancerController::class)->group(function () {
            Route::post('/addToFavorites/{job}', 'addToFavorites');
        });
    });
});

// Route::controller(VerificationController::class)->middleware(['auth:sanctum'])->group(function(){
//     Route::post('/sendCode','sendCode');
//     Route::post('/checkCode','checkCode');
// });


//--------------------------------------------------------------------------------------------------



Route::prefix('companies')->group(function () {
    Route::post('/register', [CompanyController::class, 'register']);
    Route::post('/login', [CompanyController::class, 'login']);

    Route::middleware(['auth:sanctum', 'isCompany'])->group(function () {
        Route::post('/logout', [CompanyController::class, 'logout']);
        Route::post('/checkCode', [CompanyController::class, 'checkCode']);
        Route::post('/createProfile', [CompanyController::class, 'createProfile']);
        Route::post('/createProfile', [CompanyController::class, 'createProfile']);
        Route::post('rating/{id}',[CompanyRatingController::class,'store']);


        Route::prefix('chats')->group(function () {
            Route::get('chat', [ChatController::class, 'index']);
            Route::post('store', [ChatController::class, 'store']);
            Route::get('show/{chat}', [ChatController::class, 'show']);


            // ChatMessage routes
            Route::get('chat_message', [ChatMessageController::class, 'index']);
            Route::post('chat_message', [ChatMessageController::class, 'store']);
        });

        route::prefix('jobs')->group(function () {
            Route::post('/', [CompanyJobController::class, 'create']);
            Route::put('/{id}', [CompanyJobController::class, 'update']);
            Route::delete('/{id}', [CompanyJobController::class, 'delete']);
        });
    });
});
