@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-6">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        News
                    </div>
                    <div class="panel-body">
                        <news :user="user"></news>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        Tasks
                    </div>
                    <div class="panel-body">
                        <tasks :user="user"></tasks>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
