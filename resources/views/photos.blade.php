@extends('layouts.app')
@section('content')
    <script>
        var secret = "{{Auth::user()->secret}}";
    </script>
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
    <div class="photos">
        <script>
            update();
        </script>
    </div>
    <div id="popup">
        <!-- Modal -->
        <div class="modal fade" id="previewImage" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
             aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Image Preview</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <center>
                            <img id="bigImage" alt="">
                        </center>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-custom2" data-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-custom" id="removeButton">Remove Image</button>
                        {{--<button type="button" class="btn btn-success">Promote Image</button>--}}
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
