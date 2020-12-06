@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                	<h2 class="media-heading">
                	{{$post->title}}
                	</h2>
                </div>

                <div class="card-body">
					<h4>{{$post->content}}</h4>
					@if(!auth()->user()->hasLiked($post))
                        <form action="/like" method="post">
                            @csrf
                            <input type="hidden" name="likeable" value="{{ get_class($post) }}">
                            <input type="hidden" name="id" value="{{ $post->id }}">
                            <button type="submit" class="btn btn-primary">
                                <span style="color:white">
                                <i class="fa fa-thumbs-up"></i> Like
                                    

                                </span>
                            </button>
                        </form>
                    @else
                        <form action="{{ route('dislike')}}" method="post">
                            @method('DELETE')
                            @csrf
                            <input type="hidden" name="likeable" value="{{ get_class($post) }}">
                            <input type="hidden" name="id" value="{{ $post->id }}">
                            <button type="submit" class="btn btn-primary">
                                <span style="color:white">
                                    <i class="fa fa-thumbs-down"></i> 
                                </span>
                                {{ $post->likes()->count() }} likes
                            </button>
                        </form>
                        <!-- <button class="btn btn-secondary" disabled>
                            {{ $post->likes()->count() }} likes
                        </button> -->
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
