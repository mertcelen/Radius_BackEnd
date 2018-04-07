@extends('layouts.app')

@section('content')

    <div class="container">
      @empty($images)
        <h2>No photo found, go ahead and <a href="/photos">upload</a> photos right now.</h2>
      @else
        <h2>Your recommendations will show up in here.</h2>
      @endempty
    </div>

@endsection
