<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class UserAccess
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $postId = $request->route()->parameters('postId');
        $userId = $request->user()->id;
        $userPosts = $request->user()->posts()->where([
            ['id', '=', $postId],
            ['user_id', '=', $userId]
        ])->get();
        if ($userPosts->count() < 1) 
        {
            if ($request->wantsJson()) 
            {
                return response()->json(['Message', 'You are not authorized to view this page.'], 403);
            }
            
            abort(403, 'You are not authorized to view this page.');
        }
        return $next($request);
    }
}
