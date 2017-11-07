@extends('layouts.app')

@section('content')
    <div class="container-fluid profile">
        <div class="row">
            <div class="col-md-12">
                <div class="row">
                    <div class="col-sm-3 profile-sidebar">
                        <profile-sidebar :user="{{$user}}"></profile-sidebar>
                    </div>
                    <div class="col-sm-9 profile-content">
                        <profile-work :user="{{$user}}"></profile-work>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
