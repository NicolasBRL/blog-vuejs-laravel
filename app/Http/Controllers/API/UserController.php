<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\EditProfileRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        //
    }

    /**
     * Modify user profile
     */
    public function editProfile(EditProfileRequest $request)
    {
        $user = User::where('id', $request->id)->first();

        // Upload image here
        if($request->has('image')){
            $filenameWithExt = $request->image->getClientOriginalName();
            $filenameWithoutExt = pathinfo($filenameWithExt, PATHINFO_FILENAME);

                $extension = $request->image->getClientOriginalExtension();

                $fileName = Str::slug($filenameWithoutExt) . '_' . time() . '.' . $extension;

                $request->image->storeAs('public/uploads/users', $fileName);
            
            // Delete old image
            if(Storage::exists('public/' . $user->image)){
                Storage::delete('public/' . $user->image);
            }
        }

        $user->update(array_merge(
            $request->all(),
            ['image' => $request->has('image') ? "uploads/users/".$fileName : $user->image]
        ));

        return response()->json([
            'status' => true,
            'message' => 'Profil modifié avec succès',
            'user' => $user,
            'debug' => $request->all(),
            'debug4' => $request->has('image') ? "uploads/users/".$fileName : $user->image,
        ]);
    }
}
