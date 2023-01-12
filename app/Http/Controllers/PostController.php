<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Posts;
use App\Http\Resources\PostsResource;
use App\Http\Resources\PostsDetailResource;
use Illuminate\Auth\Events\Validated;

class PostController extends Controller
{
    public function index()
    {
        $post = Posts::with('writer:id,username')->get();
        return PostsDetailResource::collection($post);
    }

    public function show($id)
    {
        $post = Posts::with('writer:id,username')->findOrFail($id);
        return new PostsDetailResource($post);
    }

    public function store(Request $request)
    {
        $validated = $request->validate( [
            'title' => 'required|max:255',
            'news_content' => 'required',
        ]);

        $request->merge(['author' => $request->user()->id]);
        $post = Posts::create($request->all());
        return new PostsDetailResource($post->LoadMissing('writer:id,username'));
    }

    public function update(Request $request, $id){
        $validated = $request->validate( [
            'title' => 'required|max:255',
            'news_content' => 'required',
        ]);

        $post = Posts::findOrFail($id);
        $post->update($request->all());

        return new PostsDetailResource($post->LoadMissing('writer:id,username'));
    }

    public function destroy($id)
    {
        $post = Posts::findOrFail($id);
        $post->delete();
        return response()->json(['message' => 'Post deleted successfully'], 200);
    }
}
