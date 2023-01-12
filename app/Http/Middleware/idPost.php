<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Posts;

class idPost
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $currentPost = Auth::user();
        $post = Posts::findOrFail($request->id);

        if($post->author != $currentPost->id)
            return response()->json(['message' => 'You are not the author of this post'], 403);
        //return response()->json($currentPost->id);
        return $next($request);
    }
}
