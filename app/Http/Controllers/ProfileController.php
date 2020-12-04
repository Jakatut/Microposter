<?php

namespace App\Http\Controllers;

use App\Models\Follower;
use App\Models\Profile;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

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
     * Display the logged in user's profile.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = Auth::user();
        $followCounts = self::getFollowCounts($user->id);
        return view('profile', array_merge(['user' => $user], $followCounts));
    }

    /**
     * Display a profile with the given id.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * 
     * @return \Illuminate\Http\Response
     */
    public function profileById(Request $request, $id) {
        $user = User::find($id);
        $following = Profile::isFollowing($id);
        $followCounts = self::getFollowCounts($id);
        return view('profile', array_merge(['user' => $user], $following, $followCounts));
    }

    public function follow(Request $request, $id) {
        $result = Profile::follow($id);
        return response()->json($result);
    }

    public function isFollowing(Request $request, $id) {
        $result = Profile::isFollowing($id);
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
