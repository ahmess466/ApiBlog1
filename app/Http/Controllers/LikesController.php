<?php

namespace App\Http\Controllers;

use App\Models\Like;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class LikesController extends Controller
{

    public function likePost(Request $request)
{
    // Validate the request data
    $validated = Validator::make($request->all(), [
        'post_id' => 'required|integer|exists:posts,id',
    ]);

    if ($validated->fails()) {
        return response()->json(['error' => $validated->errors()], 422); // Use 422 for validation errors
    }

    try {
        $userLikedPostBefore = Like::where('user_id',auth()->user()->id)->where('post_id',$request->post_id)->exists();
        if($userLikedPostBefore){
            return response()->json(['message' => 'You Canot Like The Post Twice'], 422); // Use 422 for validation errors
        }
        else{
            $comment = new Like();
            $comment->post_id = $request->post_id;
            $comment->user_id = auth()->user()->id; // Simplified for user ID
            $comment->save();

            return response()->json([
                'message' => 'Post Liked  successfully',
            ], 201); // Use 201 for successful creation

        }


    } catch (\Exception $th) {
        // Log the error for debugging

        return response()->json(['error' => 'ike not added'], 500); // Use 500 for server errors
    }
}
}
