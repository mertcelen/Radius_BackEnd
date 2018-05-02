@extends('layouts.app')

@section('content')
    <script>
        var secret = "{{Auth::user()->secret}}";
        function gender(type){
            var loading = $(".loading").html();
            $(".modal-body").html(loading);
            $("#modalDialog").modal();
            $.post({
                url: "/api/user/gender",
                data: {
                    "gender": type,
                    "secret" : secret
                },
                success: function (data) {
                    if (data.success) {
                        location.reload();
                    } else {
                        $(".modal-body").html(data.error.message);
                    }
                }
            });
        }
    </script>
    <table style="margin-left: auto;margin-right: auto;margin-top:200px">
        <th>
            <td rowspan="2" style="color:white;font-weight: bolder;padding-left: 20px;padding-right: 20px"><h1>Please select your gender.</h1></td>
        </th>
        <tr>
            <td style="color:#2196F3;font-size: 160px" onclick="gender(1)"><i class="fas fa-male"></i></td>
            <td style="color:pink;font-size: 160px" onclick="gender(2)"><i class="fas fa-female"></i></td>
        </tr>
    </table>
    @include('layouts.modal')
    @include('layouts.loading')
@endsection