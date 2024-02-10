<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePostRequest;
use App\Http\Requests\UpdatePostRequest;
use App\Http\Resources\PostCollection;
use App\Http\Resources\PostResource;
use App\Models\Post;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
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

    public function publicIndex(Request $request, string $user_id)
    {
        $posts = QueryBuilder::for(Post::class)
            ->allowedFilters(['status'])
            ->allowedSorts(['title', 'status', 'created_at', 'updated_at'])
            ->with('coverImage')
            ->paginate();
        return new PostCollection($posts);
    }

    public function show(Request $request, Post $post)
    {
        try {
            $post->load('coverImage');
            return new PostResource($post);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Post not found'], Response::HTTP_NOT_FOUND);
        }
    }

    public function publicShow(string $user_id, string $id)
    {
        try {
            $post = Post::with('coverImage')
                ->where('id', $id)
                ->firstOrFail();
            return new PostResource($post);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Post not found'], Response::HTTP_NOT_FOUND);
        }
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
