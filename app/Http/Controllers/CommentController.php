<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CommentController extends Controller
{

    public function postComment(Request $request)
{
    // Validate the request data
    $validated = Validator::make($request->all(), [
        'comment' => 'required|string',
        'post_id' => 'required|integer|exists:posts,id',
    ]);

    if ($validated->fails()) {
        return response()->json(['error' => $validated->errors()], 422); // Use 422 for validation errors
    }

    try {
        // Create a new Comment instance
        $comment = new Comment();
        $comment->post_id = $request->post_id;
        $comment->comment = $request->comment;
        $comment->user_id = auth()->user()->id; // Simplified for user ID
        $comment->save();

        return response()->json([
            'message' => 'Comment added successfully',
            'comment' => $comment,
        ], 201); // Use 201 for successful creation

    } catch (\Exception $th) {
        // Log the error for debugging

        return response()->json(['error' => 'Comment not added'], 500); // Use 500 for server errors
    }
}

}
