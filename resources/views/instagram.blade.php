@extends('layouts.app')

@section('content')

    <script>
        function detect(){
            $.each($('.instagramImage'),function () {
                var current = $(this);
                var url = encodeURIComponent(current.attr('src'));
                $.get( "face/?url=" + url + "&id=" + current.attr('id'), function( response ) {
                    if(response == 0){
                        current.attr('src','');
                    }
                });
            });
        }
    </script>
    <button class="btn btn-primary" onclick="detect();">Detect Faces</button><br><br>
    <span class="status"></span><br><br>
<div class="row">
    @foreach($images as $image)
        <div id="wrapper{{$indexKey}}">
            <img id="{{$image["id"]}}" src="{{$image["image"]}}" class="instagramImage rounded float-left" style="width: 170px;height: 170px;padding: 10px; border: 1px solid black">
        </div>
    @endforeach
</div>
@endsection