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
            <p>Following: 0</p>
            <p>Followers: 0</p>
            
            @if (Auth::user()->id !== $user->id)
                <button class="follow-user">Follow</button>
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
                let idOfUserToFollower = JSON.parse("{{json_encode($user->id)}}");
                $.ajax({
                    type: "POST",
                    url: `${idOfUserToFollower}/follow`,
                    success: function (result) {
                        if (result.following == true) {
                            
                            $(".follow-user").html('Unfollow');
                        } else {
                            $(".follow-user").html('Follow');
                        }
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
