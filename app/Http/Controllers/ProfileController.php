<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{

    public function index()
    {
        //
    }


    public function create()
    {
        //
    }


    public function store(Request $request)
    {
        //
    }

    public function show(Post $post)
    {
        $user = Auth::user();
        $post = Post::where('tag', $user->tag)->first();
        return view('employee.profile.show', compact('post'));
    }


    public function edit(Post $post)
    {
        $user = Auth::user();
        $post = Post::where('tag', $user->tag)->first();
        return view('employee.profile.edit', compact('post', 'user'));
    }


    public function update(Request $request, Post $post)
    {
        $user = Auth::user();
        $post = Post::where('tag', $user->tag)->first();

        $rules = [
            'name' => 'required',
            'address' => 'required',
            'telp' => 'required',
            'image' => 'image|file|max:1024'
        ];

        $validate = [
            'username' => 'required',
            'password' => 'required'
        ];

        $validatedData = $request->validate($rules);
        $validated = $request->validate($validate);

        if ($request->file('image')) {
            $validatedData['image'] = $request->file('image')->store('post-images');
        }

        Post::where('tag', $post->tag)->update($validatedData);
        User::where('tag', $user->tag)->update([
            'username' => $validated['username'],
            'password' => Hash::make($validated['password']),
        ]);


        return redirect('/employee/profile/show')->with('success', 'Data has been updated!');
        return redirect('/employee/profile/show')->with('error', 'Failed to update data!');
    }


    public function destroy(Post $post)
    {
        //
    }
}
