<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorePostRequest;
use App\Http\Requests\UpdatePostRequest;
use App\Models\Post;
use Illuminate\Contracts\Cache\Store;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class PostController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum')->only(['store', 'update', 'destroy']);
    }

    public function index(Request $request)
    {
        $posts = Post::orderBy('id', 'DESC')->paginate($request->get('per_page', 10));

        return response()->json([
            'success' => true,
            'posts' => $posts,
        ]);
    }

    public function store(StorePostRequest $request)
    {
        // Upload image here
        if($request->has('image')){
            $filenameWithExt = $request->image->getClientOriginalName();
            $filenameWithoutExt = pathinfo($filenameWithExt, PATHINFO_FILENAME);

                $extension = $request->image->getClientOriginalExtension();

                $fileName = Str::slug($filenameWithoutExt) . '_' . time() . '.' . $extension;

                $request->image->storeAs('public/uploads', $fileName);
        }

        $post = Post::create(array_merge(
            $request->all(),
            ['image' => $request->has('image') ? "uploads/$fileName" : null]
        )
        );

        return response()->json([
            'success' => true,
            'message' => 'Article créer avec succès',
            'post' => $post
        ]);
    }

    public function show(Post $post)
    {
        return response()->json([
            'success' => true,
            'post' => $post
        ]);
    }

    public function update(UpdatePostRequest $request, Post $post)
    {
        // Upload image here
        if($request->has('image')){
            $filenameWithExt = $request->image->getClientOriginalName();
            $filenameWithoutExt = pathinfo($filenameWithExt, PATHINFO_FILENAME);

                $extension = $request->image->getClientOriginalExtension();

                $fileName = Str::slug($filenameWithoutExt) . '_' . time() . '.' . $extension;

                $request->image->storeAs('public/uploads', $fileName);
            
            // Delete old image
            if(Storage::exists('public/' . $post->image)){
                Storage::delete('public/' . $post->image);
            }
        }

        $post->update(array_merge(
            $request->all(),
            ['image' => $request->has('image') ? "uploads/$fileName" : $post->image]
        ));

        return response()->json([
            'success' => true,
            'message' => 'Article modifié avec succès',
            'post' => $post,
        ]);
    }

    public function destroy(Post $post)
    {
        // Delete post image
        if(Storage::exists('public/' . $post->image)){
            Storage::delete('public/' . $post->image);
        }
        
        $post->delete();
        return response()->json([
            'success' => true,
            'message' => 'Article supprimer avec succès'
        ]);
    }
}
