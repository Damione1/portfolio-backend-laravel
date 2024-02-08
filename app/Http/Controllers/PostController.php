<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePostRequest;
use App\Http\Requests\UpdatePostRequest;
use App\Http\Resources\PostCollection;
use App\Http\Resources\PostResource;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Spatie\QueryBuilder\QueryBuilder;

class PostController extends Controller
{
    public function index(Request $request)
    {
        $posts = QueryBuilder::for(Post::class)
            ->allowedFilters(['status', 'user_id'])
            ->allowedSorts(['title', 'status', 'created_at', 'updated_at'])
            ->with('coverImage')
            ->paginate();
        return new PostCollection($posts);
    }


    public function show(Request $request, Post $post)
    {
        $post->load('coverImage');
        return new PostResource($post);
    }


    public function store(StorePostRequest $request)
    {
        $validated = $request->validated();

        $post = Auth::user()->Posts()->create($validated);

        return new PostResource($post);
    }


    public function update(UpdatePostRequest $request, Post $post)
    {
        $validated = $request->validated();

        $post->update($validated);

        return new PostResource($post);
    }

    public function destroy(Request $request, Post $post)
    {
        $post->delete();
        return response()->noContent();
    }
}
