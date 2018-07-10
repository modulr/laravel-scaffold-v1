@component('mail::message')
# Pago Realizado

El pago ha sido realizado exitosamente para el platillo **_{{$event->name}}_**.

Thanks,<br>
{{ config('app.name') }}
@endcomponent
