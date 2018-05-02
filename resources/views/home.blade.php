@extends('layouts.app')

@section('content')
    <script>
        var type = '{{Auth::user()->type}}';
        var link = "/";
        function preview(imageId, brand, purchaseLink, source) {
            var message = "";
            if (type == 1) {
                if(source == 1){
                    message = "your uploaded photos.";
                }else if( source === 2){
                    message = "your style";
                }
            } else {
                if (source == 1){
                    message = "your posts from Instagram.";
                }else if(source == 2){
                    message = "your likes from Instagram";
                }else{
                    message = "posts of people you follow";
                }
            }
            this.link = purchaseLink;
            let imageUrl = "/products/" + imageId + ".jpg";
            $("#bigImage").attr('src', imageUrl);
            $("#removeButton").attr('onclick', "remove('" + imageId + "')");
            $("#brand").html("Buy from " + brand);
            $("#source").html("We recommended this from " + message);
            $("#previewImage").modal();
        }

        function purchase(){
            var win = window.open(link, '_blank');
            win.focus();
        }
    </script>
    <div class="container">
        <div style="padding:10px;font-weight: bolder;color: white">
            <h2>Your recommendations are listed below</h2>
            <h6>Click images to see details</h6>
        </div>

        @isset($recommendations)
            <div class="photos">
                @foreach($recommendations as $recommendation)
                    <div class='photoWrapper'>
                        <img src="/products/{{$recommendation["image"]}}.jpg"
                             class='photo float-left'
                             onclick="preview('{{$recommendation["image"]}}'
                                     ,'{{$recommendation["brand"]}}'
                                     ,'{{$recommendation["link"]}}',
                             {{$recommendation["source"]}})"/>
                    </div>
                @endforeach
            </div>
        @endisset
    </div>
    <div id="popup">
        <!-- Modal -->
        <div class="modal fade" id="previewImage" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
             aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Product Preview</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <h4 id="source"></h4>
                        <center>
                                <img style="cursor: pointer" id="bigImage" alt="" onclick="purchase()">
                        </center>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger"><i class="fas fa-heart"></i> Add to Favorites</button>
                        <button type="button" class="btn btn-custom" id="brand" onclick="purchase()"></button>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
