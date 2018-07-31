@component('mail::message')

Estimado **_{{$student->name}} {{$student->last_name}}_**,


Saludos,<br>
{{ config('app.name') }}
@endcomponent
