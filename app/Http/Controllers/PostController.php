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
    	//get all users posts
    	$posts = $this->getUserPosts();

    	//make sure there are posts
    	if (count($posts) < 1)
    	{
    		//return status message if no posts
    		session()->flash('status', 'No posts!');
            return view('posts');
    	}

    	//return posts to view
    	return view('posts')->with('posts', $posts);
    }

    public function newPost()
    {
    	return view('newPost');
    }

    //passes in post ID from route and returns post view with ID
    public function viewPost($id)
    {
    	//get users posts
    	$post = $this->getSinglePost($id);

    	//return single post view with sing post
    	return view('viewPost')->with('post', $post);
    }

    public function viewUserPost($id)
    {
    	//get single user post
    	$post = Post::find($id);


    	return view('viewUserPost')->with('post', $post);
    }


    public function createNewPost(Request $request)
    {
    	//validates behind the scences automatically returns error message on false
    	// dd($request);
    	$validFormData = $request->validate([
    		'content' => 'required',
    		'title' => 'required'
    	]);

    	//form is valid add to DB
    	//get user
    	$user = auth()->user();

    	//make new post
    	$post = new Post;

    	//fill post content and id
    	$post->content = $request->content;
    	$post->user_id = $user->id;
    	$post->title = $request->title;

    	//save to DB
    	$post->save();

    	return redirect()->back()->with('success', 'Message posted!');
    }

    public function editPost(Request $request, $postId)
    {
    	//get content from request, make sure its valid
    	$validFormData = $request->validate([
    		'content' => 'required',
    		'title' => 'required'
    	]);

    	//get user id
    	$user = auth()->user();

    	//find post based on postId
    	$post = $user->posts()->find($postId);

    	//edit post content and title
    	$post->content = $request->content;
    	$post->title = $request->title;

    	//save changes to DB
    	$post->save();

    	//redirect back with success message
    	return redirect()->back()->with('success', 'Post updated!');
    }

    public function deletePost($postId)
    {
    	//get user id
    	$user = auth()->user();

    	//find post based on postId
    	$post = $user->posts()->find($postId);

    	//delete post
    	$post->delete();

    	return redirect()->to('/posts')->with('success', 'Post deleted!');
    }

    public function getSinglePost($postId)
    {
    	//get user from auth
    	$user = Auth::User();

    	//get posts from user based on ID
    	$post = $user->posts()->find($postId);

    	return $post;
    }

    public function getUserPosts()
    {
    	//get user id
    	$user = Auth::User();

    	//get all posts for that user
    	$posts = $user->posts()->get();

    	return $posts;
    }

    
}
