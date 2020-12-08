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
            @if (empty($profileImageURL))
                <img src="{{URL('/images/blank-profile-picture.png')}}" height="100" width="100">
            @else
                <img src="{{$profileImageURL}}" height="100" width="100">
            @endif
            <br/>
            <h3>{{$user->name}}</h3>
            <span>{{$profile->description}}</span>
            <br/>
            <a href="{{route('followers', ['id' => $user->id])}}" id="followers-count">Followers: {{$followerCount}}</a>
            <br/>
            <a href="{{route('following', ['id' => $user->id])}}" id="following-count">Following: {{$followingCount}}</a>
            <br/>
            <a href="{{route('editProfile')}}" id="edit-profile"><i class="fa fa-edit"></i>Edit</a>
            @if (Auth::user()->id !== $user->id)
                <button class="follow-user">
                    {{ $following ? "Unfollow" : "Follow" }}
                </button>
            @endif
        </div>
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Posts Dashboard') }}
                    
                </div>
            
                <div class="card-body">
                    
                    <div class="form-group row">
                        
                        @csrf
                        
                        @if (Auth::user()->id === $user->id)
                        <div class="col-4 offset-8">
                            <a type="submit" class="btn btn-primary" href="{{ route('newPost') }}">
                                {{ __('Create New') }}
                            </a>
                        </div>
                        @endif
                    </div>
            
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @else
                        @foreach($posts as $post)
                        <div>
                            @if (Auth::user()->id === $user->id)
                            <a href="{{ route('posts.viewPost', $post->id)}}">{{$post->title}}</a>
                            @else
                            <a href="{{ route('posts.viewUserPost', $post->id)}}">{{$post->title}}</a>
                            @endif
                            <!-- {{$post->content}} -->
                        </div>
                        @endforeach
                    @endif
            
                    
                </div>
            </div>
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
