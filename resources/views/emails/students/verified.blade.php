@component('mail::message')

Estimado **_{{$student->name}} {{$student->last_name}}_**,
Tienda: **_{{$student->store->store_id}} {{$student->store->name}}_**<br>
Región: **_{{$student->store->region}}_**<br>
Gerencia: **_{{$student->store->management}}_**<br>

Validamos tu inscripción. En un lapso de 15 días hábiles recibirás un correo con tu usuario, contraseña y fecha de inicio del diplomado.

Te hacemos llegar la información bancaria para el pago de los diplomados en

@foreach($student->certificates as $certificate)
- **_{{$certificate->name}}_**<br>
@endforeach

Cada diplomado tiene un costo de $1,570.00 + IVA

Siguientes pasos:

1. Realiza el depósito bancario o transferencia electrónica utilizando la siguiente información:

@component('mail::table')
| EMPRESA             | BANCO                | MONEDA   | CUENTA   | CLABE              |
| ------------------- | -------------------- | -------- | -------- | ------------------ |
| Cemex SAB SA de CV  | Banamex S.A. de C.V. | MXN      | 13551006 | 002580000135510064 |
|                     | Sucursal 0001        |          |          |                    |
| REFERENCIA BANCARIA | ########             |          |          |                    |
@endcomponent

2. Envía el comprobante de pago a uconstrurama@cemex.com, y confirma que los datos de facturación son correctos **Denominación RSO y RFC RSO **

3. Recibida la información anterior validaremos el depósito y enviaremos tu factura.

Para cualquier duda contáctanos en uconstrurama@cemex.com

Saludos,<br>
{{ config('app.name') }}
@endcomponent
