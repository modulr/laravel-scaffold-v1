@extends('layouts.app')

@section('content')
    <div class="container-fluid dashboard">
        <div class="row">
            @permission('read-events')
            <div class="col-md-6">
                <div class="panel">
                    <div class="panel-body">
                        <a href="/platillos">
                            <div class="jumbotron text-center">
                              <h1>Quiero Comerr</h1>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="panel">
                    <div class="panel-body">
                        <a href="/mis_platillos">
                            <div class="jumbotron text-center">
                              <h1>Quiero Cocinar</h1>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
            @endpermission
          </div>
        </div>
    </div>
@endsection
