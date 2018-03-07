@extends('layouts.app')

@section('content')

    <span class="status"></span><br><br>
<div class="row">
  @if(!empty($images))
        <table class="table">
            <thead class="thead-dark">
            <tr>
                <th scope="col">#</th>
                <th scope="col">Image</th>
                <th scope="col">Cropped Image</th>
                <th scope="col">RGB</th>
                <th scope="col">Labels</th>
                <th scope="col">Detect Image</th>
            </tr>
            </thead>
            <tbody>
            @foreach($images as $image)
                <tr class="big">
                    <th scope="row">{{$loop->iteration}}</th>
                    <td>
                        <img src="/images/{{$image["imageId"]}}.{{$image["type"]}}" class="instagramImage rounded float-left" style="width: 170px;height: 170px;padding: 10px; border: 1px solid black">
                    </td>
                    <td>
                        <img id="image{{$loop->iteration}}" src="question_mark.jpg" class="instagramImage rounded float-left" style="width: 170px;height: 170px;padding: 10px; border: 1px solid black">
                    </td>
                    <td id="colors{{$loop->iteration}}" class="big">&nbsp;</td>
                    <td id="label{{$loop->iteration}}" class="big">&nbsp;</td>
                    <td>
                        <button id="button{{$loop->iteration}}" onclick="detect('{{$image["imageId"]}}','{{$image["type"]}}','{{$loop->iteration}}')">Detect</button>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
  @endif
</div>
@endsection
