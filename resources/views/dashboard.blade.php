@extends('layouts.app')

@section('content')
    <div class="container-fluid dashboard">
        <div class="row">
            @permission('read-events')
            <div class="col-md-6">
                <div class="panel">
                    <div class="panel-body">
                        <div class="jumbotron text-center">
                          <h1>¿Quieres comer?</h1>
                          <p>...</p>
                          <p><a class="btn btn-primary btn-lg" href="/events" role="button">Learn more</a></p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="panel">
                    <div class="panel-body">
                        <div class="jumbotron text-center">
                          <h1>¿Quieres cocinar?</h1>
                          <p>...</p>
                          <p><a class="btn btn-primary btn-lg" href="/events" role="button">Learn more</a></p>
                        </div>
                    </div>
                </div>
            </div>
            @endpermission
          </div>
        </div>
    </div>
@endsection
