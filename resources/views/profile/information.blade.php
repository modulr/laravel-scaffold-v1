@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-10 col-md-offset-1">
                <div class="row">
                    <div class="col-sm-3 profile-sidebar">
                        @include('profile.sidebar')
                    </div>
                    <div class="col-sm-9 profile-content">
                        <profile-information :user="{{$user}}"></profile-information>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
