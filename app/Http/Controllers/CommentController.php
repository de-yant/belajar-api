<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Comments;

class CommentController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'post_id' => 'required|exists:posts,id',
            'comments_content' => 'required|string'
        ]);

        $request['user_id'] = auth()->user()->id;

        $comment = Comments::create($request->all());

        return response()->json([
            'message' => 'Comment created successfully',
            'comment' => $comment
        ], 201);
    }
}
