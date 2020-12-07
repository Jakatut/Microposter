<?php

namespace App\Http\Controllers;

use App\Models\Follower;
use App\Models\Profile;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;
use Makeable\CloudImages\Nova\Fields\CloudImage;

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
        // User profile requests. (No id).
        if ($id === null) {
            $user = Auth::user();
            $id = $user->id;
        } else {
            $user = User::find($id);
        }
        $following = Follower::isFollowing($id);
        $followCounts = self::getFollowCounts($id);
        $posts = $user->posts()->get();
        $profileImage = $this->getProfileImageURL($user);
        
        return view('profile', array_merge(['user' => $user, 'posts' => $posts, 'profileImageURL' => $profileImage], $following, $followCounts));
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

    /**following, $followCounts));
    }
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
     * Displays the new profile view.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function editProfile(Request $request)
    {
        return view('editProfile');
    }


    /**
     * Store a newly created profile.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function updateProfile(Request $request) {
        $profile = new Profile; 
        $profile->description = $request->description;
        $image = $request->image;
        $user = User::find(auth()->id());
        $profile = $user->profile();
        $profile->image = $image->hashName();
        // $profile->save(); This method does not exist? Collection is empty. Don't think I set model/db up properly.

        $location = $this->getProfileImageName($user);
        $disk = Storage::disk('gcs');
        $ret = $disk->put($location, $image);
        $url = $disk->url($location);

        return route('profile', ['profileImage' => $url]);
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

    private function getProfileImageURL($user) {
        if ($user === null) {
            return "";
        }
        $location = $this->getProfileImageName($user);
        $disk = Storage::disk('gcs');
        $url = $disk->url($location . '/' . $user->profile()->image);

        return $url;
    }

    private function getProfileImageName($user) {
        return $user->id . '-' . $user->name;
    }
}
