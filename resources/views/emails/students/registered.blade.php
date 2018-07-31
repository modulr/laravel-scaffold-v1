@component('mail::message')

Estimado **_{{$user->name}}_**,

Se ha registrado el participante con el nombre **_{{$student->name}} {{$student->last_name}}_** en el diplomado **_{{$student->certificate->name}}_** de la Universidad Construrama,

Tienda: **_{{$student->store->name}}_**<br>
Región: **_{{$student->store->region}}_**<br>
Gerencia: **_{{$student->store->management}}_**<br>

Requerimos de tu apoyo para validar la información por lo cual te solicitamos ingreses con tu usuario y contraseña a la liga para liberar la inscripción de dichos participantes.

Si hay alguna corrección de los datos proporcionados por el participante te pedimos que la realices en la plataforma.

Cualquier incidencia para darle seguimiento a esta solicitud de inscripción te pedimos nos contactes a uconstrurama@cemex.com


@component('mail::button', ['url' => url("/students/{$student->id}")])
Ver registro
@endcomponent


Saludos,<br>
{{ config('app.name') }}
@endcomponent
