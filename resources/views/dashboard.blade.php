@extends('layouts.app')

@section('content')
    <div class="container dashboard">
        <div class="row">
            <div class="col-md-12 text-center">
                <img class="logo" src="/img/logos/stb.png" alt="">
            </div>
            @permission('read-events')
            <div class="col-md-4 col-md-offset-2">
                <div class="panel">
                    <div class="panel-body text-center">
                        <a href="/platillos">
                            <img src="/img/events/comer.svg" alt="">
                            <h3>Quiero Comer</h3>
                        </a>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="panel">
                    <div class="panel-body text-center">
                        <a href="/mis_platillos">
                            <img src="/img/events/cocinar.svg" alt="">
                            <h3>Quiero Cocinar</h3>
                        </a>
                    </div>
                </div>
            </div>
            @endpermission
          </div>
        </div>
    </div>
@endsection
