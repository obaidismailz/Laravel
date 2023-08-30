<?php

namespace App\Http\Controllers;

use App\Http\Requests\PostRequest;
use App\Http\Resources\PostResource;
use App\Models\Post;

class PostController extends Controller
{
    //All Listings
    public function index()
    {
        $posts = Post::orderBy('created_at', 'asc')->paginate(10);
        return PostResource::collection($posts);
    }

    // Show Particular Post
    public function show($id)
    {
        $post = Post::find($id);

        if (!$post) {
            return response()->json([
                'response' => [
                    'message' => 'Post not found',
                    'status' => 404,
                ],
            ]);
        }
        return new PostResource($post);
    }

    //Create new Post
    public function store(PostRequest $request)
    {
        $post = new Post;
        if ($request->hasFile('imagePath')) {
            $file = $request->file('imagePath');
            if ($file->isValid()) {
                $imageName = time() . '.' . $file->getClientOriginalExtension();
                $imagePath = public_path('files');
                $file->move($imagePath, $imageName);
                $post->imagePath = $imageName;
            } else {
                $post->imagePath = '';
            }
        }
        $post->name = $request->name;
        $post->description = $request->description;
        $post->save();

        return new PostResource($post);
    }

    //Update a post
    public function update(PostRequest $request, $id)
    {
        $post = Post::find($id);

        if (!$post) {
            return response()->json([
                'response' => [
                    'message' => 'Post not found',
                    'status' => 404,
                ],
            ]);
        }

        if ($request->hasFile('imagePath')) {
            $file = $request->file('imagePath');
            if ($file->isValid()) {
                $imageName = time() . '.' . $file->getClientOriginalExtension();
                $imagePath = public_path('files');
                $file->move($imagePath, $imageName);
                $post->imagePath = $imageName;
            } else {
                $post->imagePath = '';
            }
        }

        $post->update([
            'name' => $request->name,
            'description' => $request->description
        ]);

        $post->save();
        return new PostResource($post);
    }

    //Delete a Post
    public function destroy($id)
    {
        $post = Post::find($id);
        if (!$post) {
            return response()->json([
                'response' => [
                    'message' => 'Post not found',
                    'status' => 404,
                ],
            ]);
        }
        $post->delete();
        return response()->json([
            'response' => [
                'message' => 'Post deleted',
                'status' => 200,
            ],
        ]);
    }
}
