<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class checkOwner
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $job = $request->route('job');
        $jobId = $job->id;
        $freelancer = $request->user();
        if($freelancer->jobs->where('id',$jobId)->isEmpty()){
            return response()->json([
                'message' => 'unAuthorized'
            ],403);
        }
        return $next($request);
    }
}
