@extends('layouts.app')
@section('content')
<script type="text/javascript" src="/js/dropzone.js"></script>
<script type="text/javascript">
Dropzone.options.uploadPhoto = {
    paramName: "photo",
    thumbnail : null,
    processing : function(){
      $(".message").html("Uploading");
    },
    success : function(){
      $(".message").html("Photo uploaded.");
    }
};
</script>
<style media="screen">
  form{
    width: 300px;
    height: 300px;
    background-color: darkgrey;
    line-height: 300px;
    text-align: center;
    font-size: 20px;
  }
</style>
<div>
  <h2 class="message">Ready to upload</h2>
</div>
<form id="uploadPhoto" action="/photos/upload" class="dropzone">
  <div class="fallback">
    <input name="photo" type="file"/>
  </div>
</form>
@foreach($images as $image)
<tbody>
    <tr class="big">
        <td>
            <img src="/images/{{$image->imageId}}.{{$image->type}}"
                 class="instagramImage rounded float-left">
        </td>
    </tr>
</tbody>
@endforeach
@endsection
