<?php

namespace App\Http\Controllers;

use App\Models\Follower;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a profile with the given id.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * 
     * @return \Illuminate\Http\Response
     */
    public function profileById(Request $request, $id = null) {
        if ($id === null) {
            $user = Auth::user();
            $id = $user->id;
        } else {
            $user = User::find($id);
        }
        $following = Follower::isFollowing($id);
        $followCounts = self::getFollowCounts($id);
        return view('profile', array_merge(['user' => $user], $following, $followCounts));
    }

    /**
     * Follows or unfollows a user depending on the current follow state (following => not following).
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * 
     * @return \Illuminate\Http\Response
     */
    public function toggleFollow(Request $request, $id) {
        $result = Follower::toggleFollow($id);
        return response()->json($result);
    }

    /**
     * Check if the current logged in user is following the user with the provided id.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * 
     * @return \Illuminate\Http\Response
     */
    public function isFollowing(Request $request, $id) {
        $result = Follower::isFollowing($id);
        return response()->json($result);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }


    /**
     * Agregates the follwer count and following count to ana array.
     *
     * @param  int  $userId
     * @return \Illuminate\Http\Response
     */
    private static function getFollowCounts($userId) {
        $user = User::find($userId);
        $followerCount = 0;
        $followingCount = 0;
        if ($user) {
            $followerCount = $user->followers()->count();
            $followingCount = Follower::where('follower_id', $userId)->count();
        }
        return ['followerCount' => $followerCount, 'followingCount' => $followingCount];
    }

}
