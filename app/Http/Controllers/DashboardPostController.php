<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\User;
use Illuminate\Http\Request;
use App\Models\Add;
use App\Models\Salary;

class DashboardPostController extends Controller
{

    public function index()
    {
        return view('dashboard.posts.index', [
            'posts' => Post::all()
        ]);
    }

    public function create()
    {
        $tags = Add::all();
        $gaji = Salary::all();
        return view('dashboard.posts.create', compact('tags', 'gaji'));
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'tag' => 'required|max:255',
            'name' => 'required',
            'address' => 'required',
            'telp' => 'required',
            'start' => 'required',
            'salary' => 'required',
            'holiday_salary' => 'required',
            'username' => 'required|string|max:255|unique:users',
            'role' => 'required',
            'password' => 'required|string|min:8',
            'image' => 'image|file|max:1024'

        ]);

        if ($request->file('image')) {
            $validatedData['image'] = $request->file('image')->store('post-images');
        }

        Post::create($validatedData);
        User::create([
            'tag' => $validatedData['tag'],
            'name' => $validatedData['name'],
            'username' => $validatedData['username'],
            'role' => $validatedData['role'],
            'password' => $validatedData['password'],

        ]);

        Add::where('tag', $validatedData['tag'])->delete();
        return redirect('/dashboard/posts')->with('success', 'New employee has been added!');
    }


    public function show(Post $post)
    {
        return view('dashboard.posts.show', [
            'post' => $post,
        ]);
    }


    public function edit(Post $post)
    {
        $gaji = Salary::all();
        return view('dashboard.posts.edit', compact('gaji'), [
            'post' => $post,
        ]);
    }


    public function update(Request $request, Post $post)
    {
        $rules = [
            'start' => 'required',
            'salary' => 'required',
            'holiday_salary' => 'required',
        ];

        $validatedData = $request->validate($rules);

        Post::where('id', $post->id)
            ->update($validatedData);

        return redirect('/dashboard/posts')->with('success', 'Data has been updated!');
    }


    public function destroy(Post $post)
    {
        // dd($post->id);
        Post::destroy($post->id);
        User::destroy($post->id);
        return redirect('/dashboard/posts')->with('success', 'Data has been deleted!');
    }
}
