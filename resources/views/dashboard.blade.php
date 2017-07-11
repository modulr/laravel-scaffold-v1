@extends('layouts.app')

@section('content')
    <h2>{{$breadcrumb}}</h2>
    <div class="row">
        <div class="col-md-6">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <i class="fa fa-fw fa-bullhorn"></i> News
                    <a href="news" class="pull-right"><i class="fa fa-chevron-right" aria-hidden="true"></i></a>
                </div>
                <div class="panel-body" style="height:400px; overflow-y:scroll;">
                    <news :user="user"></news>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <i class="fa fa-fw fa-tasks"></i> Tasks
                    <a href="tasks" class="pull-right"><i class="fa fa-chevron-right" aria-hidden="true"></i></a>
                </div>
                <div class="panel-body" style="height:400px; overflow-y:scroll;">
                    <tasks :user="user"></tasks>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <i class="fa fa-fw fa-address-book-o"></i> Contacts
                    <a href="contacts" class="pull-right"><i class="fa fa-chevron-right" aria-hidden="true"></i></a>
                </div>
                <div class="panel-body" style="height:500px; overflow-y:scroll;">
                    <contacts></contacts>
                </div>
            </div>
        </div>
    </div>
@endsection
