<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Post;
use Illuminate\Http\Request;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {  
        $posts = Post::all();

        return response()->json([
            'data' => $posts
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Upload image here
        if($request->has('image')){
            $filenameWithExt = $request->image->getClientOriginalName();
            $filenameWithoutExt = pathinfo($filenameWithExt, PATHINFO_FILENAME);

                $extension = $request->image->getClientOriginalExtension();

                $fileName = $filenameWithoutExt . '_' . time() . '.' . $extension;

                $request->image->storeAs('public/uploads', $fileName);
        }

        $post = Post::create(array_merge(
            $request->all(),
            ['image' => $request->has('image') ? "uploads/$fileName" : null]
        )
        );

        return response()->json([
            'data' => $post
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Post $post)
    {
        return response()->json([
            'data' => $post
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Post $post)
    {
        $post->update($request->all());

        return response()->json([
            'data' => $post
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Post $post)
    {
        $post->delete();
        return response()->json([
            'data' => 'Post deleted'
        ]);
    }

    /**
     * Récupère les articles par pages
     */
    public function getArticles(Request $request)
    {
        $posts = Post::paginate($request->get('per_page', 10));

        return response()->json([
            'data' => $posts,
        ]);
    }
}
