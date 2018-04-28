@extends('layouts.app')

@section('content')
    <script src="/js/list.js"></script>
    <div class="ml-auto mr-auto">
        {{$products->links()}}
    </div>
    <div class="searchWrapper">
        <div class="btn-group" style="margin-bottom:10px">
            <select id="typeSelect" class="mdb-select">
                <option value="" selected>Choose type</option>
                @foreach($types as $type)
                    <option value="{{$type->name}}">{{$type->name}}</option>
                @endforeach
            </select>
        </div>
        <div class="btn-group">
            <select id="colorSelect" class="mdb-select" style="margin-bottom:10px">
                <option value="" selected>Choose color</option>
                @foreach($colors as $color)
                    <option value="{{$color->name}}"
                            style="background-color: {{$color->name}}">{{$color->name}}</option>
                @endforeach
            </select>
        </div>
        <div class="btn-group" style="margin-bottom:10px">
            <select id="brandSelect" class="mdb-select">
                <option value="" selected>Choose brand</option>
                @foreach($brands as $brand)
                    <option value="{{$brand->name}}">{{$brand->name}}</option>
                @endforeach
            </select>
        </div>
        <div class="btn-group" style="margin-bottom:10px">
            <select id="genderSelect" class="mdb-select">
                <option value="" selected>Choose gender</option>
                    <option value="male">male</option>
                    <option value="female">female</option>
            </select>
        </div>
        <button class="btn btn-custom" onclick="search()">Filter</button>
    </div>
    <table class="table">
        <thead class="thead">
        <tr>
            <th scope="col">#</th>
            <th scope="col">Type</th>
            <th scope="col">Brand</th>
            <th scope="col">Color</th>
            <th scope="col">Gender</th>
            <th scope="col">Image</th>
            <th scope="col">Purchase Link</th>
        </tr>
        </thead>
        <tbody>
        @foreach ($products as $product)
            <tr>
                <th scope="row">{{$loop->iteration + (20 * $products->currentPage() - 20)}}</th>
                <td>{{$product->type}}</td>
                <td>{{$product->brand}}</td>
                <td>{{$product->color}}</td>
                <td>{{$product->gender}}</td>
                <td><a href="/products/{{$product->image}}.jpg" target="_blank" style="text-decoration: underline">{{$product->image}}</a></td>
                <td><a href="{{$product->link}}" target="_blank" style="text-decoration: underline">{{$product->link}}</a></td>
            </tr>
        @endforeach
        </tbody>
    </table>

@endsection