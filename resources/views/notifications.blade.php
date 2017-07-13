@extends('layouts.app')

@section('content')
    <h2>{{$breadcrumb}}</h2>
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="panel panel-default">
                <div class="panel-body">
                    <notifications></notifications>
                </div>
            </div>
        </div>
    </div>
@endsection
