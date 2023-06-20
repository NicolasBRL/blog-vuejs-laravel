<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\EditProfileRequest;
use App\Http\Requests\StoreUserRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class UserController extends Controller
{

    /**
     * Register a new user
     */
    public function store(StoreUserRequest $request)
    {
        $user = User::create(array_merge(
            $request->all(), 
            ['password' => Hash::make($request->password)]
        ));

        return response()->json([
            'success' => true,
            'message' => 'Votre compte à été créer avec succès',
            'user' => $user,
        ]);
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
            [
                'image' => $request->has('image') ? "uploads/users/".$fileName : $user->image, 
                'password' => $request->has('password') ? Hash::make($request->password) : $user->password
            ]
        ));

        return response()->json([
            'success' => true,
            'message' => 'Profil modifié avec succès',
            'user' => $user,
        ]);
    }
}
