<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Posts;
use App\Http\Resources\PostsDetailResource;
use Illuminate\Support\Facades\Storage;

class PostController extends Controller
{
    public function index()
    {
       $psots = Posts::all();
       return PostsDetailResource::collection($psots->LoadMissing(['writer:id,username', 'comments']));
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

        $image = 'default.png';
        if ($request->file){
            $fileName = $this->generateRandomString();
            $extension = $request->file->extension();
            $image = $fileName . '.' . $extension; 

            Storage::putFileAs(
                'public/images',
                $request->file,
                $image
            );
        }

        $request['image'] = $image;
        $request['author'] = $request->user()->id;
        $post = Posts::create($request->all());
        return new PostsDetailResource($post->LoadMissing('writer:id,username'));
    }

    function generateRandomString($length = 50) {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
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
