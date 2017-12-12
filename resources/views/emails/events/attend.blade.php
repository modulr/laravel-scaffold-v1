@component('mail::message')
# Reservación

El usuario **_{{$user->name}}_** ha hecho una reservacion para el platillo **_{{$event->name}}_**

@component('mail::button', ['url' => url("/platillos/{$event->id}")])
Ver mas
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
