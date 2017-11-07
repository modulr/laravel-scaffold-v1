@extends('layouts.app')

@section('content')
    <div class="container-fluid profile-edit">
        <div class="row">
            <div class="col-md-12">
                <profile-work-edit :user="{{$user}}"></profile-work-edit>
            </div>
        </div>
    </div>
@endsection
