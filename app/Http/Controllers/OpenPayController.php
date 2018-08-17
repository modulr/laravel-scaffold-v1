<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Openpay;

use App\Models\Stb\Reservation;


class OpenPayController extends Controller
{
    public function pay(Request $request)
    {
        Openpay::setId(config('openpay.id'));
        Openpay::setApiKey(config('openpay.secret_key'));
        Openpay::setProductionMode(config('openpay.prod_mode'));

        $openpay = Openpay::getInstance();

        $customerData = array(
            'name' => Auth::user()->name,
            'email' => Auth::user()->email,
        );

        $chargeData = array(
            'method' => 'card',
            'source_id' => $request->tokenId,
            'amount' => $request->reservation['saucer']['price'] * $request->reservation['quantity'],
            'description' => 'Pago de platillo ID: ' . $request->reservation['saucer']['id'] .', Nombre: '. $request->reservation['saucer']['name'].', Cantidad: '.$request->reservation['quantity'],
            'device_session_id' => $request->deviceDataId,
            'customer' => $customerData
        );

        $charge = $openpay->charges->create($chargeData);

        $reservation = Reservation::find($request->reservation['id']);
        $reservation->paid = 1;
        $reservation->paypal_id = $charge->id;
        $reservation->save();

        return response()->json($charge);
    }


}
