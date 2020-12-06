@extends('layouts.app')

@section('content')
<div class="container">
    @if (is_null($user))
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
            <a href="{{route('followers', ['id' => $user->id])}}" id="followers-count">Followers: {{$followerCount}}</a>
            <br/>
            <a href="{{route('following', ['id' => $user->id])}}" id="following-count">Following: {{$followingCount}}</a>
            <br/>
            @if (Auth::user()->id !== $user->id)
                <button class="follow-user">
                    {{ $following ? "Unfollow" : "Follow" }}
                </button>
            @endif
        </div>
        <div class="col-md-8">
            @include('postCard')
        </div>
    </div>
</div>

@push('user-scripts')
    <script>
        jQuery(function(){
            $(".follow-user").on('click', function() {
                let idOfUserToFollow = JSON.parse("{{json_encode($user->id)}}");
                let baseUrl = "{{url('/')}}".replace("&quot;", "");
                $.ajax({
                    type: "POST",
                    url: `${baseUrl}/${idOfUserToFollow}/toggleFollow`,
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
@endif

@endsection
