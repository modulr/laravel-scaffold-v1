<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Validator;
use App\Quote;
class QuoteController extends Controller
{

  public function index(Request $request)
  {
      return view('quotes.quotes', ['breadcrumb' => $request->path()]);
  }

  public function all(Request $request)
  {
      return Quote::with('designer', 'seller', 'customer', 'project', 'status', 'service')->get();
  }

  public function show($id)
  {
      $q = Quote::with('designer', 'seller')->find($id);
      return $q;
  }

  public function store(Request $request)
  {
      $this->validate($request, [
          'name' => 'required|string',
          'project' => 'required',
          'designer' => 'required',
          'salesman' => 'required',
          'customer' => 'required',
          'request_date' => 'required|date',
      ]);
      $quote = Quote::create([
        'name' => $request->name,
        'description' => $request->description,
        'owner_id' => Auth::id(),
        'designer_id' => $request->designer_id,
        'seller_id' => $request->seller_id,
        'project_id' => $request->project_id,
        'customer_id' => $request->customer_id,
        'request_date' => $request->request_date,
        'delivery_date' => $request->delivery_date,
        'close_date' => $request->close_date,
        'amount' => $request->amount,
        'status_id' => 1,
        'service_id'=> $request->service_id])->load('designer', 'seller', 'customer', 'owner', 'project', 'status', 'service');
      return $quote;
  }

  public function update(Request $request, $id)
  {

      $q = Quote::find($id);
      $q->name = $request->name;
      $q->description = $request->description;
      $q->designer_id = $request->designer_id;
      $q->seller_id = $request->seller_id;
      $q->project_id = $request->project_id;
      $q->customer_id = $request->customer_id;
      $q->delivery_date = $request->delivery_date;
      $q->amount = $request->amount;
      $q->status_id = $request->status_id;
      $q->save();
      // $data = $request->all();

      // return response()->json($data);
      return $q->load('designer', 'seller', 'customer', 'owner', 'project', 'status');
  }

  public function destroy($id)
  {
      return Quote::destroy($id);
  }

}
