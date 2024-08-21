<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
   
    public function __invoke(Request $request)
    {
        $posts = Post::with('user')
            ->when($request->search,function($query) use($request){
                $query->where('title', 'like', '%'.$request->search.'%')
                    ->orWhereHas('user', fn($query) => $query->where('name', 'like', '%'.$request->search.'%'));
            })
            ->latest()->paginate(12);
        return inertia('Dashboard', ['posts' => $posts]);
    }
}
