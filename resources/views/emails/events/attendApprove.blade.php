@component('mail::message')
# Reservación

El cocinero **_{{$event->owner->name}}_** ha aprovado tu reservacion para el platillo **_{{$event->name}}_**, esta es la ubicación donde será servido tu platillo.

**_{{$event->place}}_**

@component('mail::button', ['url' => url("/platillos/{$event->id}")])
Ver mas
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
