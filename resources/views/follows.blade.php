@extends('layouts.app')

@section('content')
<div class="container">
    @if (is_null($user))
        <div class="row justify-content-center">
            <h2>This profile does not exist</h2>
        </div>
    @else
    <div class="row justify-content-center" id="tabbed-header">
        <div class="col-md-2" id="user-details">
            <a href="{{route('followers')}}">Followers</a>
        </div>
        <div class="col-md-2" id="user-details">
            <a href="{{route('following')}}">Following</a>
        </div>
    </div>
    @if ($followContext == "following")
        @include('followingCard')
    @elseif ($followContext == "followers")
        @include('followersCard')
    @endif

</div>

@push('user-scripts')
    <script>
        jQuery(function(){
            $(".follow-user").on('click', function() {
                let idOfUserToFollow = JSON.parse("{{json_encode($user->id)}}");
                $.ajax({
                    type: "POST",
                    url: `${idOfUserToFollow}/toggleFollow`,
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
