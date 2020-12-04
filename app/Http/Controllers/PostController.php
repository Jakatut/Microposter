<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Post;
use Auth;
class PostController extends Controller
{
    //
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
    	
    	// dd($posts);
    	$posts = $this->getUserPosts();
    	if (count($posts) < 1)
    	{
    		session()->flash('status', 'No posts!');
            return view('posts');
    	}

    	return view('posts')->with('posts');
    }

    public function newPost()
    {
    	return view('newPost');
    }

    public function createNewPost(Request $request)
    {
    	$form_data = $request->except(['_token']);
    	dd($request->all());
    }

    public function getUserPosts()
    {
    	//get user id
    	$user = Auth::User();
    	$posts = $user->posts()->get();
    	return $posts;

    }
}
