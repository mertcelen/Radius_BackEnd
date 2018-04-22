@extends('layouts.app')

@section('content')
    <script src="{{asset('js/admin.js')}}"></script>
    <div class="container" style="background: white;padding:30px;border-radius: 30px;">
        <h2>Add Product to Database</h2>
        <div class="alert alert-danger productError" role="alert" style="background-color:#bc5100;color:white" hidden>
        </div>
        <div class="btn-group" style="margin-bottom:10px">
            <select id="typeSelect" class="mdb-select">
                <option value="" disabled selected>Choose type</option>
                @foreach($types as $type)
                    <option value="{{$type->name}}">{{$type->name}}</option>
                @endforeach
            </select>
        </div>
        <div class="btn-group">
            <select id="colorSelect" class="mdb-select" style="margin-bottom:10px">
                <option value="" disabled selected>Choose color</option>
                @foreach($colors as $color)
                    <option value="{{$color->name}}" style="background-color: blue">{{$color->name}}</option>
                @endforeach
            </select>
        </div>
        <div class="btn-group" style="margin-bottom:10px">
            <select id="brandSelect" class="mdb-select">
                <option value="" disabled selected>Choose brand</option>
                @foreach($brands as $brand)
                    <option value="{{$brand->name}}">{{$brand->name}}</option>
                @endforeach
            </select>
        </div>
        <div class="md-form">
            <input id="productLink" type="text" class="form-control" required style="color: #bc5100;">
            <label for="productLink" style="color: #bc5100;">Product Link</label>
        </div>
        <div class="md-form">
            <input id="imageLink" type="text" class="form-control" required style="color: #bc5100;">
            <label for="imageLink" style="color: #bc5100;">Image Link</label>
        </div>
        <button class="btn btn-custom btn-block" onclick="addProduct()">Add Product</button>
    </div>

@endsection
