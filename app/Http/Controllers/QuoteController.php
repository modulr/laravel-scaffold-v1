<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Validator;
use Illuminate\Support\Facades\DB;
use App\Quote;
use App\Models\Lists\ListStatusQuote;
class QuoteController extends Controller
{

  public function index(Request $request)
  {
      return view('quotes.quotes', ['breadcrumb' => $request->path()]);
  }

  public function all(Request $request)
  {
      return Quote::with('designer', 'salesman', 'customer', 'status', 'service', 'attachment', 'currency')->paginate(10);
  }

  public function show($id)
  {
      $q = Quote::with('designer', 'salesman')->find($id);
      return $q;
  }

  public function store(Request $request)
  {
      $this->validate($request, [
          'name' => 'required',
          'designer' => 'required',
          'salesman' => 'required',
          'project' => 'required',
          'customer' => 'required',
          'service' => 'required',
          'request_date' => 'required|date'
      ]);
      $quote = Quote::create([
        'name' => $request->name,
        'description' => $request->description,
        'owner_id' => Auth::id(),
        'designer_id' => $request->designer,
        'salesman_id' => $request->salesman,
        'project_id' => $request->project,
        'customer_id' => $request->customer,
        'request_date' => $request->request_date,
        'delivery_date' => $request->delivery_date,
        'close_date' => $request->close_date,
        'amount' => $request->amount,
        'status_id' => 1,
        'service_id'=> $request->service,
        'currency_id' => $request->currency])->load('designer', 'salesman', 'customer', 'owner', 'status', 'service', 'currency');
      return $quote;
  }

  public function update(Request $request, $id)
  {

      $q = Quote::find($id);
      $q->name = $request->name;
      $q->description = $request->description;
      $q->designer_id = $request->designer_id;
      $q->salesman_id = $request->salesman_id;
      $q->project_id = $request->project_id;
      $q->customer_id = $request->customer_id;
      $q->delivery_date = $request->delivery_date;
      $q->amount = $request->amount;
      $q->status_id = $request->status_id;
      $q->service_id = $request->service_id;
      $q->currency_id = $request->currency_id;
      $q->save();
      // $data = $request->all();

      // return response()->json($data);
      return $q->load('designer', 'salesman', 'customer', 'owner', 'status', 'currency', 'attachment');
  }

  public function destroy($id)
  {
      return Quote::destroy($id);
  }

  public function getStatus()
  {
      return ListStatusQuote::all();
  }

}
