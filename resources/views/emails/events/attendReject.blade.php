@component('mail::message')
# Reservación

Lamentamos informarte que el cocinero **_{{$event->owner->name}}_** ha rechazado tu reservacion para el platillo **_{{$event->name}}_**.

@component('mail::button', ['url' => url("/platillos/{$event->id}")])
Hacer una reservacion nueva
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
