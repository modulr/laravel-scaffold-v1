@component('mail::message')
# Reservación

El cocinero **_{{$event->owner->name}}_** ha aprovado tu reservacion para el platillo **_{{$event->name}}_**, ahora completa tu reservacion pagando con Paypal.

@component('mail::button', ['url' => url("/platillos/{$event->id}")])
Pagar
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
