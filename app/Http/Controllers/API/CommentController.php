<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCommentRequest;
use App\Models\Comment;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum')->only(['store', 'update', 'destroy']);
    }

    public function store(StoreCommentRequest $request)
    {
        $comment = Comment::create(
            $request->all(),
        );

        return response()->json([
            'status' => true,
            'message' => 'Commentaire publié !',
            'comment' => $comment
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Comment $comment)
    {
        $comment->delete();
        return response()->json([
            'success' => true,
            'message' => 'Commentaire supprimer avec succès'
        ]);
    }
}
