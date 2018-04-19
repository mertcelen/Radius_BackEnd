@extends('layouts.app')
@section('content')
    <!-- View Specific Imports -->
    <script type="text/javascript" src="/js/libraries/dropzone.js"></script>
    <script type="text/javascript" src="/js/photos.js"></script>
    <link rel="stylesheet" href="/css/photo.css">
    <!-- End of imports -->

    <form id="uploadPhoto" action="/photos/upload" class="dropzone">
        <div class="fallback">
            <input name="photo" type="file"/>
        </div>
    </form>
    <button class="btn btn-custom my-1 mx-1" onclick="toggle()">Remove</button>
    <div class="photos">
        @foreach($images as $image)
            <div class="photoWrapper">
                <img id="{{$image->imageId}}" src="/thumb/{{$image->imageId}}.jpg" class="photo float-left"
                     onclick="remove('{{$image->imageId}}')"/>
            </div>
        @endforeach
    </div>
    @include('layouts.modal')
@endsection
