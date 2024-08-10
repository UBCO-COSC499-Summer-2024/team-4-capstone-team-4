<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckReportAccess
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();
        $instructorId = $request->route('instructor_id');
        if ($user->hasRoles(['admin', 'dept_head', 'dept_staff'])) {
            return $next($request);
        }else{
            //dd($instructorId, $user->roles->where('role', 'instructor')->first()->id );
            if($instructorId != $user->roles->where('role', 'instructor')->first()->id){
                abort(403, 'Unauthorized access.');
            }else{
                return $next($request);
            }
        }
       
    }
}
