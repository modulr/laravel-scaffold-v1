@component('mail::message')
# Reservación

El cocinero **_{{$saucer->owner->name}}_** ha aprovado tu reservacion para el platillo **_{{$saucer->name}}_**, ahora completa tu reservacion pagando con Paypal.

@component('mail::button', ['url' => url("/platillos/{$saucer->id}")])
Pagar
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
