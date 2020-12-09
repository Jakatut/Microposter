<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Follower;
use App\Traits\UserImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class UserController extends Controller
{
    use UserImage;

    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Displays a list of profiles that the user with the provided id (or logged in user if not provided)
     * is following.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * 
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, $id = null) {

        $query = $request->get('query');
        if ($id === null && !empty($query)) {
            return $this->searchUser($request, $query);
        }

        // User profile requests (no id)
        $users = User::where('id', '!=', auth()->id())->get();
        $foundUsers = [];
        foreach($users as $user) {
            $following = Follower::isFollowing($user->id)['following'];
            $profileImageURL = $this->getProfileImageURL($user);
            array_push($foundUsers, ['details' => $user, 'following' => $following, 'profileImageURL' => $profileImageURL]);
        }
        
        return view('users', ['users' => $foundUsers]);
    }

    public function deleteUser(Request $request) {
        $user = User::find(auth()->id());
        Auth::logout();
        if ($user->delete()) {
            return redirect()->route('login')->with('global', 'Your account has been deleted!');
        }
    }

    public function searchUser(Request $request, $query) {
        $where = $this->getSQLSearchFilter($query);
        $users = User::where($where['field'], $where['op'], $where['value'])->get();
        $foundUsers = [];
        foreach($users as $user) {
            $following = Follower::isFollowing($user->id)['following'];
            $profileImageURL = $this->getProfileImageURL($user);
            array_push($foundUsers, ['details' => $user, 'following' => $following, 'profileImageURL' => $profileImageURL]);
        }
        
        return view('users', ['users' => $foundUsers]);
    }

    public function getSQLSearchFilter($query) {
        $filter = ['field' => 'id', 'op' => '=', 'value' => $query];
        if (!is_numeric($query)) { 
            $filter['field'] = 'name';
            $filter['op'] = 'like';
            $filter['value'] = "%${query}%";
        }

        return $filter;
    }
}
