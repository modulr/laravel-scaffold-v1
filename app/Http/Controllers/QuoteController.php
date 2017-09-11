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
      return Quote::with('designer', 'seller', 'customer', 'project')->get();
  }

  public function show($id)
  {
      $q = Quote::with('designer', 'seller')->find($id);
      return $q;
  }

  public function store(Request $request)
  {
      // $this->validate($request, [
      //     'name' => 'required|string',
      // ]);
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
        'ammount' => $request->ammount,
        'status' => 1])->load('designer', 'seller', 'customer', 'owner', 'project');
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
      $q->ammount = $request->ammount;
      $q->save();
      // $data = $request->all();

      // return response()->json($data);
      return $q->load('designer', 'seller', 'customer', 'owner', 'project');
  }

  public function destroy($id)
  {
      return Quote::destroy($id);
  }

}
