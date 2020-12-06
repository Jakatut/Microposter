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
					<button class="btn btn-block btn-primary col-2"><i class="fa fa-thumbs-up">Like</i> </button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
