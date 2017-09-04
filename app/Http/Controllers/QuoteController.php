<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Quote;
class QuoteController extends Controller
{

  public function index(Request $request)
  {
      return view('news', ['breadcrumb' => $request->path()]);
  }

  public function all(Request $request)
  {
      return Quote::all();
  }

  public function show($id)
  {
      $q = Quote::with('designer', 'seller')->find($id);
      return $q;
  }

  public function store(Request $request)
  {
      $quote = Quote::create([
        'name' => $request->name,
        'desginer_id' => $request->designer,
        'seller_id' => $request->seller,
        'project_id' => $request->project,
        'customer_id' => $request->customer,
        'request_date' => $request->request_date,
        'delivery_date' => $request->delivery_date,
        'close_date' => $request->close_date,
        'ammount' => $request->ammount,
        'status' => 1,
        'timestamps' => false]);
      return $quote;
  }

  public function update(Request $request, $id)
  {

      $q = Quote::find($id);
      $q->name = $request->name;
      $q->desginer_id = $request->desginer_id;
      $q->seller_id = $request->seller_id;
      $q->project_id = $request->project_id;
      $q->customer_id = $request->customer_id;
      $q->request_date = $request->request_date;
      $q->delivery_date = $request->delivery_date;
      $q->close_date = $request->close_date;
      $q->ammount = $request->ammount;
      $q->status = $request->status;
      $q->save();
      // $data = $request->all();

      // return response()->json($data);
      return $q;
  }

  public function destroy($id)
  {
      return Quote::destroy($id);
  }
}
