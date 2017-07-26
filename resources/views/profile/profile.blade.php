@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <h2>{{$breadcrumb}}</h2>
        <div class="row">
            <div class="col-md-10 col-md-offset-1">
                <profile :user="{{$user}}"></profile>
            </div>
        </div>
    </div>
@endsection
