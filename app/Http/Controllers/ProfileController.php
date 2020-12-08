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

    const DEFAULT_IMAGE_NAME="profiles/blank-profile-picture.png";

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
    public function profile(Request $request, $id = null) {
        // User profile requests. (No id).
        if ($id === null) {
            $user = Auth::user();
            $id = $user->id;
        } else {
            $user = User::find($id);
        }
        
        return view('profile', $this->getProfileData($user));
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
        $user = User::find(auth()->id());
        $profile = $user->profile()->get()->first();
        
        return view('editProfile', ['profile' => $profile, 'created' => 'true']);
    }


    /**
     * Store a newly created profile.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function updateProfile(Request $request) {
        $user = User::find(auth()->id());
        $profile = $user->profile()->get();
        if (count($profile) == 0) {
            $profile = new Profile;
        } else {
            $profile = $profile->first();
        }

        $disk = Storage::disk('gcs');

        $image = $request->image;
        if (!empty($profile->image) && $image != null) {
            $location = $this->getProfileImageName($user);
            $disk->delete($location);
            $profile->image = $image->hashName();
        }
        $profile->description = $request->description ?? $profile->description;
        $profile->save();

        $location = $this->getProfileImageUploadName($user);
        $disk->put($location, $image);
        $location = $this->getProfileImageName($user);
        $disk->setVisibility($location, 'public');

        return redirect()->route('profile');
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
        if ($disk->exists($location)) {
            $url = $disk->url($location);
        } else {
            $url = $disk->url(self::DEFAULT_IMAGE_NAME);
        }

        return $url;
    }

    private function getProfileImageName($user) {
        $location = "";
        if ($user !== null) {
            $profile = $user->profile()->get()->first();
            $location = 'profiles/' . $user->id . '-' . $user->name . '/' . $profile->image;
            $location = $profile->image ? $location : self::DEFAULT_IMAGE_NAME;
        }
        return $location;
    }

    private function getProfileImageUploadName($user) {
        $location = "";
        if ($user !== null) {
            $profile = $user->profile()->get()->first();
            $location = 'profiles/' . $user->id . '-' . $user->name . '/';
        }
        return $location;
    }

    private function getProfileData($user) {
        $following = Follower::isFollowing($user->id);
        $followCounts = $this->getFollowCounts($user->id);
        $posts = $user->posts()->get();
        $profileImage = $this->getProfileImageURL($user);
        $profile = $user->profile()->get()->first();
        return array_merge(['user' => $user, 'posts' => $posts, 'profileImageURL' => $profileImage, 'profile' => $profile], $following, $followCounts);
    }
}
