@component('mail::message')
# Nuevo Registro

El estudiante **_{{$student->name}} {{$student->last_name}}_** se ha registrado al sistema e indico que su sucursal es **_{{$student->store->name}}_**, por favor revisa el registro del estudiante y da tu aprovacion.

@component('mail::button', ['url' => url("/student/{$student->id}")])
Ver registro
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
