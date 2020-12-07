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
                            <a  class="btn btn-primary" data-toggle="modal"  data-backdrop="static" data-keyboard="false" data-target="#myModal">
                                <span style="color:white">
                                    <i class="fa fa-share"></i>
                                </span>
                                Share
                            </a>
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
                            <a class="btn btn-primary" data-toggle="modal"  data-backdrop="static" data-keyboard="false" data-target="#myModal">
                                <span style="color:white">
                                    <i class="fa fa-share"></i>
                                </span>
                                Share
                            </a>
                        </form>
                        
                    @endif
                    
                </div>
            </div>
        </div>
    </div>
    <!-- The Modal -->
<div class="modal" id="myModal">
  <div class="modal-dialog">
    <div class="modal-content">

      <!-- Modal Header -->
      <div class="modal-header">
        <h4 class="modal-title">Share Link</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>

      <!-- Modal body -->
      <div class="modal-body" >
        <mark><input id="post-url" value="{{Request::url()}}" readonly></mark>
      </div>

      <!-- Modal footer -->
      <div class="modal-footer">
        <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-danger" id="copy-button" onclick="copyToClipboard('post-url')"> Copy </button>
      </div>

    </div>
  </div>
</div>
</div>
<script>
     function copyToClipboard(id) {
        document.getElementById(id).select();
        document.execCommand('copy');
        document.getElementById('copy-button').innerText = 'Copied!';
    }
</script>

@endsection
