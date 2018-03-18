@extends('layouts.app')

@section('content')

    <div class="row">
      @empty($images)
        <h2>No photo found, go ahead and <a href="/photos">upload</a> photos right now.</h2>
      @else
            <table class="table">
                <thead class="thead-dark">
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Image</th>
                    <th scope="col">Cropped Upper</th>
                    <th scope="col">Cropped Lower</th>
                    <th scope="col">Cropped Body</th>
                </tr>
                </thead>
                <tbody>
                @foreach($images as $image)
                    <tr class="big">
                        <th scope="row">{{$loop->iteration}}</th>
                        <td>
                            <img src="/images/{{$image->imageId}}.{{$image->type}}"
                                 class="instagramImage rounded float-left">
                        </td>
                        <td>
                          @empty($image->red1)
                            <img id="image{{$loop->iteration}}_1" src="question_mark.jpg"
                                 class="instagramImage rounded float-left" onclick="detect('{{$image->imageId}}','{{$image->type}}','{{$loop->iteration}}',1)">
                          @else
                            <img class="instagramImage rounded float-left" src="/cropped/{{$image->imageId}}_1.jpg" alt="">
                          @endif
                        </td>
                        <td>
                          @empty($image->red2)
                            <img id="image{{$loop->iteration}}_2" src="question_mark.jpg"
                                 class="instagramImage rounded float-left" onclick="detect('{{$image->imageId}}','{{$image->type}}','{{$loop->iteration}}',2)">
                          @else
                            <img class="instagramImage rounded float-left" src="/cropped/{{$image->imageId}}_2.jpg" alt="">
                          @endif
                        </td>
                        <td>
                          @empty($image->red3)
                            <img id="image{{$loop->iteration}}_3" src="question_mark.jpg"
                                 class="instagramImage rounded float-left" onclick="detect('{{$image->imageId}}','{{$image->type}}','{{$loop->iteration}}',3)">
                          @else
                            <img class="instagramImage rounded float-left" src="/cropped/{{$image->imageId}}_3.jpg" alt="">
                          @endif
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
            @endempty
            <div class="panel-body">
                @if (session('status'))
                    <div class="alert alert-success">
                        {{ session('status') }}
                    </div>
                @endif
            </div>
    </div>

@endsection
