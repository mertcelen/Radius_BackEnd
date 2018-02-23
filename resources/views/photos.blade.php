@extends('layouts.app')

@section('content')

<h1>Upload new Photos</h1>
<form action="{{ URL::to('photos/upload') }}" method="post" enctype="multipart/form-data">
    <label>Select image to upload:</label><br>
    <input type="file" name="photo"/><br>
    <input type="submit" value="Upload" name="submit"><br>
    <input type="hidden" value="{{ csrf_token() }}" name="_token">
</form>
@endsection