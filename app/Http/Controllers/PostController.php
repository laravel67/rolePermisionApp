<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Routing\Controllers\HasMiddleware;

class PostController extends Controller
{
    public static function middleware(){
        return [
            new Middleware('permission:posts-access', only: ['index']),
            new Middleware('permission:posts-create', only: ['store']),
            new Middleware('permission:posts-update', only: ['update']),
            new Middleware('permission:posts-delete', only: ['destroy']),
        ];    
    }

    public function index(Request $request){
        $posts = Post::with('user')
        ->whereUserId($request->user()->id)
        ->when($request->search, fn($query) => $query->where('title', 'like', '%'.$request->search.'%'))
        ->latest()
        ->paginate(6)->withQueryString();
        return inertia('Posts/Index', ['posts' => $posts]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|min:3|max:255',
            'content' => 'required|min:3|max:255',
        ]);
        Post::create([
            'user_id' => $request->user()->id,
            'title' => $request->title,
            'content' => $request->content,
        ]);
        return back();
    }

    public function update(Request $request, Post $post)
    {
        $request->validate([
            'title' => 'required|min:3|max:255',
            'content' => 'required|min:3|max:255',
        ]);
    
        // update post
        $post->update([
            'title' => $request->title,
            'content' => $request->content,
        ]);
    
        // render view
        return back();
    }

    public function destroy(Post $post)
    {
        $post->delete();
        return back();
    }
}
