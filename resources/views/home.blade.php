@extends('layouts.app')

@section('content')

    <div class="row">
        <div class="panel panel-default">
            <table class="table">
                <thead class="thead-dark">
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Image</th>
                    <th scope="col">Cropped Upper</th>
                    <th scope="col">Cropped Lower</th>
                    <th scope="col">Cropped Body</th>
                    <th scope="col">RGB</th>
                    <th scope="col">Labels</th>
                    <th scope="col">Timestamps</th>
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
                            <img id="image{{$loop->iteration}}_1" src="question_mark.jpg"
                                 class="instagramImage rounded float-left" onclick="detect('{{$image->imageId}}','{{$image->type}}','{{$loop->iteration}}',1)">
                        </td>
                        <td>
                            <img id="image{{$loop->iteration}}_2" src="question_mark.jpg"
                                 class="instagramImage rounded float-left" onclick="detect('{{$image->imageId}}','{{$image->type}}','{{$loop->iteration}}',2)">
                        </td>
                        <td>
                            <img id="image{{$loop->iteration}}_3" src="question_mark.jpg"
                                 class="instagramImage rounded float-left" onclick="detect('{{$image->imageId}}','{{$image->type}}','{{$loop->iteration}}',3)">
                        </td>
                        <td class="big instagramImage">
                            <table style="padding: 0px;">
                                <tr id="color_{{$loop->iteration}}_1" style="padding: 0px;"><td style="padding: 0px;height: 50px;width: 50px">&nbsp;</td></tr>
                                <tr id="color_{{$loop->iteration}}_2" style="padding: 0px;"><td style="padding: 0px;height: 50px;width: 50px">&nbsp;</td></tr>
                                <tr id="color_{{$loop->iteration}}_3" style="padding: 0px;"><td style="padding: 0px;height: 50px;width: 50px">&nbsp;</td></tr>
                            </table>
                        </td>
                        <td class="big instagramImage">
                            <ul class="liste">
                                <li id="label_{{$loop->iteration}}_1">&nbsp;</li>
                                <li id="label_{{$loop->iteration}}_2">&nbsp;</li>
                                <li id="label_{{$loop->iteration}}_3">&nbsp;</li>
                            </ul>
                        </td>
                        <td class="instagramImage">
                            <ul class="liste">
                                <li id="time_{{$loop->iteration}}_1">&nbsp;</li>
                                <li id="time_{{$loop->iteration}}_2">&nbsp;</li>
                                <li id="time_{{$loop->iteration}}_3">&nbsp;</li>
                            </ul>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
            <div class="panel-body">
                @if (session('status'))
                    <div class="alert alert-success">
                        {{ session('status') }}
                    </div>
                @endif
            </div>
        </div>
    </div>

@endsection