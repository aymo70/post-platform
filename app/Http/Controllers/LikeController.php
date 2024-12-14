<?php

namespace App\Http\Controllers;

use App\Models\Like;
use App\Models\Post;
use Illuminate\Http\Request;

class LikeController extends Controller
{
    // إضافة أو إلغاء الإعجاب
    public function toggleLike($postId)
    {
        $post = Post::findOrFail($postId);

        $like = Like::where('user_id', auth()->id())->where('post_id', $postId)->first();

        if ($like) {
            $like->delete();
            return response()->json(['message' => 'Unliked'], 200);
        }

        Like::create([
            'user_id' => auth()->id(),
            'post_id' => $postId,
        ]);

        return response()->json(['message' => 'Liked'], 201);
    }
}
