<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Events\Event;

use PayPal\Rest\ApiContext;
use PayPal\Auth\OAuthTokenCredential;
use PayPal\Api\Amount;
use PayPal\Api\Details;
use PayPal\Api\Item;
use PayPal\Api\ItemList;
use PayPal\Api\Payer;
use PayPal\Api\Payment;
use PayPal\Api\RedirectUrls;
use PayPal\Api\Transaction;
use PayPal\Api\PaymentExecution;

class PaypalController extends Controller
{
    private $apiContext;
    private $clientId;
    private $secret;

    // Create a new instance with our paypal credentials
    public function __construct()
    {
        // Detect if we are running in live mode or sandbox
        if(config('paypal.settings.mode') == 'live'){
            $this->clientId = config('paypal.live_client_id');
            $this->secret = config('paypal.live_secret');
        } else {
            $this->clientId = config('paypal.sandbox_client_id');
            $this->secret = config('paypal.sandbox_secret');
        }

        // Set the Paypal API Context/Credentials
        $this->apiContext = new ApiContext(new OAuthTokenCredential($this->clientId, $this->secret));
        $this->apiContext->setConfig(config('paypal.settings'));
    }

    public function getCheckout(Request $request)
    {
        $payer = new Payer();
        $payer->setPaymentMethod('paypal');

        $item1 = new Item();
        $item1->setName($request->name)
            ->setCurrency('MXN')
            ->setQuantity(1)
            ->setSku($request->id)
            ->seturl(url('/platillos/'.$request->id))
            ->setPrice($request->price);

        $itemList = new ItemList();
        $itemList->setItems(array($item1));

        $amount = new Amount();
        $amount->setCurrency('MXN')
            ->setTotal($request->price);

        $transaction = new Transaction();
        $transaction->setAmount($amount)
            ->setItemList($itemList)
            ->setDescription('Your transaction description');

        $redirectUrls = new RedirectUrls();
        $redirectUrls->setReturnUrl(action('PaypalController@getStatus'))
            ->setCancelUrl(action('PaypalController@getStatus'));

        $payment = new Payment();
        $payment->setIntent('sale')
            ->setPayer($payer)
            ->setRedirectUrls($redirectUrls)
            ->setTransactions(array($transaction));

        try {
            $payment->create($this->apiContext);
        } catch (Exception $ex) {
            info($ex);
            return response()->json(["error" => $ex], 400);
        }

        return response()->json(["success" => $payment, 'approvalUrl' => $payment->getApprovalLink()], 200);
    }

    public function getStatus(Request $request)
    {
        if ($request->get('paymentId')) {
            $payment = Payment::get($request->get('paymentId'), $this->apiContext);

            $execution = new PaymentExecution();
            $execution->setPayerId($request->get('PayerID'));

            try {
                $result = $payment->execute($execution, $this->apiContext);
            } catch (Exception $ex) {
                return response()->json(["error" => $ex], 400);
            }

            if ($result->getState() == 'approved') {
                $event = Event::find($result->transactions[0]->item_list->items[0]->sku);
                $event->attendings()->updateExistingPivot(Auth::id(), ['paid' => true, 'paypal_id' => $payment->getId()]);
            }

            return redirect($result->transactions[0]->item_list->items[0]->url);
        } else {
            return back()->withInput();
        }

    }
}
