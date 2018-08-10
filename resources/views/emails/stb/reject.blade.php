@component('mail::message')
# Reservación

Lamentamos informarte que el cocinero **_{{$saucer->owner->name}}_** ha rechazado tu reservacion para el platillo **_{{$saucer->name}}_**.

@component('mail::button', ['url' => url("/platillos/{$saucer->id}")])
Hacer una reservacion nueva
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
