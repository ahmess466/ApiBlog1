<?php

namespace App\Http\Resources;

use App\Models\Comment;
use App\Models\Like;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SinglePostResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id ,
        'Post_title' => $this->title ,
        'Post_content' => $this->content ,
        'Author_id' => $this->user_id ,
        'Author_name' => User::find($this->user_id),
        'Published_at' => $this->created_at ,
        'Last_udpdate' => $this->updated_at ,
'Likes Count' => Like::where('post_id', $this->id)
                        ->where('user_id', $this->user_id)
                        ->count(),

        'Comments Count' => Comment::where('post_id',$this->id)->count() ,

        'Comments' => Comment::where('post_id',$this->id)->get() , //Commenttt

        ] ;



    }
}
