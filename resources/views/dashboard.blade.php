@extends('layouts.app')

@section('content')
    <div class="container-fluid dashboard">
        <div class="row">
            @permission('read-news')
            <div class="col-md-4">
                <div class="panel panel-default panel-dashboard">
                    <div class="panel-heading">
                        <h3 class="panel-title">
                            News
                            <a href="/news" class="pull-right">View all</a>
                        </h3>
                    </div>
                    <div style="height:400px; overflow-y:scroll;">
                        <news-list></news-list>
                    </div>
                </div>
            </div>
            @endpermission
            @permission('read-tasks')
            <div class="col-md-4">
                <div class="panel panel-default panel-dashboard">
                    <div class="panel-heading">
                        <h3 class="panel-title">
                            Tasks
                            <a href="/tasks" class="pull-right">View all</a>
                        </h3>
                    </div>
                    <div class="panel-body" style="height:400px; overflow-y:scroll;">
                        <tasks></tasks>
                    </div>
                </div>
            </div>
            @endpermission
            @permission('read-events')
            <div class="col-md-4">
                <div class="panel panel-default panel-dashboard">
                    <div class="panel-heading">
                        <h3 class="panel-title">
                            Events
                            <a href="/events" class="pull-right">View all</a>
                        </h3>
                    </div>
                    <div class="panel-body" style="height:400px; overflow-y:scroll;">
                        <events-widget></events-widget>
                    </div>
                </div>
            </div>
            @endpermission
            @permission('read-contacts')
            <div class="col-md-5">
                <div class="panel panel-default panel-dashboard">
                    <div class="panel-heading">
                        <h3 class="panel-title">
                            Contacts
                            <a href="/contacts" class="pull-right">View all</a>
                        </h3>
                    </div>
                    <div class="panel-body" style="height:400px; overflow-y:scroll;">
                        <contacts-widget></contacts-widget>
                    </div>
                </div>
            </div>
            @endpermission
            @permission('read-files')
            <div class="col-md-7">
                <div class="panel panel-default panel-dashboard">
                    <div class="panel-heading">
                        <h3 class="panel-title">
                            Files
                            <a href="/files" class="pull-right">View all</a>
                        </h3>
                    </div>
                    <div class="panel-body" style="height:400px; overflow-y:scroll;">
                        <files-widget></files-widget>
                    </div>
                </div>
            </div>
            @endpermission
          </div>
        </div>
    </div>
@endsection
