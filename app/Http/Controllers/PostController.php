<?php

namespace App\Http\Controllers;

use App\Http\Resources\SinglePostResource;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class PostController extends Controller
{
    public function addNewPost(Request $request){
        $validated = Validator::make($request->all(),[
            'title' => 'required|string',
            'content' => 'string|required',

        ]);
        if ($validated->fails()) {
            return response()->json(['error' => $validated->messages()], 422);
        }
        try {
            $post = new Post();
            $post->title = $request->title;
            $post->content = $request->content;
            $post->user_id = auth()->user()->id;


            $post->save();
            return response()->json([
                'message' => 'Post Created Successfully',




            ],status:200);

        } catch (\Exception $th) {
            return response()->json(['error' => $th->getMessage()], 500); // تم تصحيح "status"

        }



    }
    public function getAllPosts(){
        try {
            $posts = Post::all();
            return response()->json([
                'posts' => $posts,
            ]);
        } catch (\Throwable $th) {
            return response()->json(['error' => $th->getMessage()], 500); // تم تصحيح "status"
        }

    }
    public function getPostById($id)
{
    try {
        $post = Post::with('user', 'comments', 'likes')->find($id);

        if (!$post) {
            return response()->json(['error' => 'Post not found'], 404); // Return 404 if post doesn't exist
        }

        // Wrap the post in a resource
        $post_data = new SinglePostResource($post);

        return response()->json([
            'post' => $post_data,
        ], 200);

    } catch (\Throwable $th) {
        return response()->json(['error' => $th->getMessage()], 500);
    }
}


    public function updatePost( Request $request ,$id){
        $validated = Validator::make($request->all(),[
            'title' => 'required|string',
            'content' => 'string|required',

        ]);
        if ($validated->fails()) {
            return response()->json(['error' => $validated->messages()], 422);
        }
        try {
            $post = Post::find($id);
           $updated_post = $post->update([
                'title' => $request->title ,
                'content' => $request->content


            ]);


            return response()->json([
                'message' => 'Post Updated Successfully',
                'post' => $post,
                'updated_post' =>  $updated_post
            ],status:200);

        } catch (\Exception $th) {
            return response()->json(['error' => 'The Post Is Not Found'], 404); // تم تصحيح "status"

        }
    }
    public function deletePost($id){
        try {
            $post = Post::findOrFail($id);
            $post->delete();
            return response()->json([
                'message' => 'Post Deleted Successfully',
            ],status:200);
    }
    catch (\Exception $th){
        return response()->json(['error' => 'The Post Is Deleted Or Not Found'], 404);

    };
}
}
