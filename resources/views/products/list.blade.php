@extends('layouts.app')

@section('content')
    <div class="ml-auto mr-auto">
        {{$products->links()}}
    </div>
    <table class="table">
        <thead class="thead">
        <tr>
            <th scope="col">#</th>
            <th scope="col">Type</th>
            <th scope="col">Brand</th>
            <th scope="col">Color</th>
            <th scope="col">Image</th>
            <th scope="col">Link</th>
        </tr>
        </thead>
        <tbody>
        @foreach ($products as $product)
            <tr>
                <th scope="row">{{$loop->iteration + (20 * $products->currentPage() - 20)}}</th>
                <td>{{$product->type}}</td>
                <td>{{$product->brand}}</td>
                <td>{{$product->color}}</td>
                <td>{{$product->image}}</td>
                <td><a href="{{$product->link}}" target="_blank" style="text-decoration: underline">{{$product->link}}</a></td>
            </tr>
        @endforeach
        </tbody>
    </table>

@endsection