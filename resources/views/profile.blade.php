@extends('layouts.app')

@section('content')
<div class="container">
    @if (empty($user))
        <div class="row justify-content-center">
            <h2>This profile does not exist</h2>
        </div>
    @else
    <div class="row justify-content-center">
        <div class="col-md-2" id="user-details">
            @if (empty($user->profileImage))
                <img src="{{URL('/images/blank-profile-picture.png')}}" height="100" width="100">
            @else
                <img src="{{$user->profileImage}}" height="100" width="100">
            @endif
            <p>{{$user->name}}</p>
            <p id="following-count">Following: {{$followingCount}}</p>
            <p id="followers-count">Followers: {{$followerCount}}</p>
            
            @if (Auth::user()->id !== $user->id)
                <button class="follow-user">
                    {{ $following ? "Unfollow" : "Follow" }}
                </button>
            @endif
        </div>
        <div class="col-md-8">
            <div class="card">
                    Feed
            </div>
        </div>
    </div>
    @endif
</div>

@push('user-scripts')
    <script>
        jQuery(function(){
            $(".follow-user").on('click', function() {
                let idOfUserToFollow = JSON.parse("{{json_encode($user->id)}}");
                $.ajax({
                    type: "POST",
                    url: `${idOfUserToFollow}/follow`,
                    success: function (result) {
                        if (result.following == true) {
                            $(".follow-user").html('Unfollow');
                        } else {
                            $(".follow-user").html('Follow');
                        }

                        // Update followers.
                        $("#following-count").html(`Following: ${result.followingCount}`);
                        $("#followers-count").html(`Followers: ${result.followerCount}`);
                    },
                    data: {
                        "_token": "{{ csrf_token() }}",
                    },
                    dataType:"json"
                });
            });
        });
    </script>
@endpush

@endsection
