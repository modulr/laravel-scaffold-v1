@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-10 col-md-offset-1">
                <div class="row">
                    <div class="col-sm-3 profile-sidebar">
                        <profile-sidebar :user="{{$user}}"></profile-sidebar>
                    </div>
                    <div class="col-sm-9 profile-content">
                        <profile :user="{{$user}}"></profile>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
