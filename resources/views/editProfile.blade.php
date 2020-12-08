@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center w-100">
        <form class="m-2 w-100" method="POST" action="{{route('updateProfile')}}" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <h3 for="description" class="card-header w-100 m-1">Profile Picture</h3>
                <label for="image">Choose Image</label>
                <input id="image" type="file" name="image">
            </div>
            
            <div class="form-group">
                <h3 for="description" class="card-header w-100 m-1">Description</h3>
                <textarea type="text" class="form-control" id="description" placeholder="Description" name="description">{{empty($profile) ? "" : $profile->description}}</textarea>
            </div>
            <button type="submit" class="btn btn-dark d-block w-100 mx-auto">Submit</button>
        </form>
    </div>
    
    @if (!empty($created))
    <br/>
    <br/>
    <br/>
    <br/>
    <div class="row justify-content-center w-100">
        <form class="m-2 w-100" method="POST" action="{{route('deleteUser')}}">
            @csrf
            @method('DELETE')
            <div class="form-group">
                <h3 for="name" class="card-header w-100 m-1">Delete Profile</h3>
                <p><b>WARNING</b>: This action cannot be undone. You will be asked to confirm deletion before your account is permanently deleted.</p>
                <button type="submit" class="btn btn-danger d-block">Delete profile</button>
            </div>
        </form>
    </div>
    @endif    
</div>

@endsection
