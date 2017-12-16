@extends('layouts.app')

@section('content')
    <form action="/user/setup" method="post">
        {{ csrf_field() }}
        <table class="table">
            <thead>
            <tr>
                <th colspan="2" scope="col">
                    Initial Setup for User
                </th>
            </tr>
            </thead>
            <tbody>
            <tr>
                <td>Body Type</td>
                <td>
                    <select class="custom-select" name="body_type">
                        <option selected>Choose your body type</option>
                        <option value="Curved">Curved</option>
                        <option value="Petite">Petite</option>
                        <option value="Tall">Tall</option>
                    </select>
                </td>
            </tr>
            <tr>
                <td>Style</td>
                <td>
                    <select class="custom-select" type="style">
                        <option selected>Choose Style</option>
                        <option value="Athleisure">Athleisure</option>
                        <option value="Avant-garde">Avant-garde</option>
                        <option value="Bohemian">Bohemian</option>
                        <option value="Casual">Casual</option>
                        <option value="Casual Chic">Casual Chic</option>
                        <option value="Chic Elegant">Chic Elegant</option>
                        <option value="Preppy">Preppy</option>
                        <option value="Street Style">Street Style</option>
                        <option value="Swag">Swag</option>
                        <option value="Vibrant">Vibrant</option>
                        <option value="Vintage">Vintage</option>
                        <option value="Whimsical">Whimsical</option>

                    </select>
                </td>
            </tr>
            <tr>
                <td colspan="2">
                    <button type="submit" class="btn btn-primary">Save preferences</button>
                </td>
            </tr>
            </tbody>
        </table>

    </form>
@endsection