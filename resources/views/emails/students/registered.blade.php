@component('mail::message')

Estimado **_{{$user->name}}_**,

Nombre del participante **_{{$student->name}} {{$student->last_name}}_**<br>
Tienda: **_{{$student->store->store_id}} {{$student->store->name}}_**<br>
Región: **_{{$student->store->region}}_**<br>
Gerencia: **_{{$student->store->management}}_**<br>

Ha realizado su preinscripción en línea en el diplomado:

@foreach($student->certificates as $certificate)
- **{{$certificate->name}}**<br>
@endforeach

Requerimos de tu apoyo para validar la pertenencia de este participante al construrama que señala.

Si hay alguna corrección de los datos proporcionados por el participante te pedimos que la realices en la plataforma.

Cualquier incidencia para darle seguimiento a esta solicitud de inscripción te pedimos nos contactes a uconstrurama@cemex.com


@component('mail::button', ['url' => url("/students/{$student->id}")])
Ver registro
@endcomponent


Saludos,<br>
{{ config('app.name') }}
@endcomponent
