@component('mail::message')
# Reservación

El comensal **_{{$user->name}}_** ha hecho una reservacion para el platillo **_{{$event->name}}_**, por favor revisa el perfil del comensal y danos tu aprovacion.

@component('mail::button', ['url' => url("/platillos/{$event->id}")])
Revisar reservacion
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
