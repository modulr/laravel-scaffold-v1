@component('mail::message')

Estimado **_{{$student->name}}_**,


Saludos,<br>
{{ config('app.name') }}
@endcomponent
