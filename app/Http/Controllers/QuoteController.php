<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Validator;
use Illuminate\Support\Facades\DB;
use App\Models\Quotes\Quote;
use App\Models\Lists\ListStatusQuote;
use App\Models\Commissions\RangeOfCommission;
class QuoteController extends Controller
{
    public function __construct()
    {
        $this->relationships = ['designer', 'salesman', 'customer', 'project', 'status', 'service', 'attachment', 'currency', 'invoices', 'invoices.invocie_status'];
    }

  public function index(Request $request)
  {
      return view('quotes.quotes', ['breadcrumb' => $request->path()]);
  }

  public function all(Request $request)
  {
    $query = Quote::query();

    if($request->name) {
        $query->where('name', 'LIKE', '%'.$request->name.'%');
    }
    if($request->customer) {
        $query->where('customer_id', $request->customer);
    }
    if($request->project) {
        $query->where('project_id', $request->project);
    }
    if($request->status) {
        $query->where('status_id', $request->status);
    }
    if($request->service) {
        $query->where('service_id', $request->service);
    }
    $quotesMXN = clone $query;
    $quotesUSD = clone $query;
    $quotesPending = clone $query;
    $quotesApprove = clone $query;
    $quotesSent = clone $query;
    $quotesRefuse = clone $query;
    $quotes = $query->orderBy('created_at', 'desc')->paginate(10);
    $quotes->load($this->relationships);
    return response()->json([
        'quotes' => $quotes,
        'amountMXN' => $quotesMXN->where('currency_id', 1)->sum('amount'),
        'amountUSD' => $quotesUSD->where('currency_id', 2)->sum('amount'),
        'pending' => $quotesPending->where('status_id', 1)->count(),
        'sent' => $quotesSent->where('status_id', 2)->count(),
        'approve' => $quotesApprove->where('status_id', 3)->count(),
        'refuse' => $quotesRefuse->where('status_id', 4)->count(),
    ]);
  }

  public function show($id)
  {
      $q = Quote::with($this->relationships)->find($id);

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
        'currency_id' => $request->currency])->load($this->relationships);

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
      return $q->load($this->relationships);
  }

  public function destroy($id)
  {
      return Quote::destroy($id);
  }

  public function getStatus()
  {
      return ListStatusQuote::all();
  }

  public function quotesPerMonth(Request $request)
  {
    $query = Quote::query();
    $commissionQuery = RangeOfCommission::query();    
    // $query->where('status', '!=', 4); todas las cotizaciones que no esten rechazadas
    $query->whereRaw('MONTH(request_date) = ?', [$request->month]);
    if($request->designer) {
        $query->where('designer_id', $request->designer);
        $commissionQuery->where('type', 1);
    }
    if($request->salesman) {
        $query->where('salesman_id', $request->salesman);
        $commissionQuery->where('type', 2);
    }
    $total = 0.00;
    $quotes = $query->get()->load($this->relationships);
    foreach ($quotes as $quote) {               
        $total += $quote['currency']['exchange_rate'] * $quote['amount'];
    }

    $commissionQuery->where('lower_limit', '<=', $total)
        ->where('upper_limit', '>=', $total);
    $range_commission = $commissionQuery->get();
    // $range_commission = RageOfCommission::where('lower_limit', '<=', $total)
    //                                     ->where('upper_limit', '>=', $total)->get();
    
    // return DB::table('rage_of_commissions')->where([
    //     ['lower_limit', '>=', 12999],
    //     ['upper_limit', '<=', 12999],
    // ])->get();
                                    

    return response()->json([
        'quotes' => $quotes,
        'total' => $total,
        'commission_title' => $range_commission[0]->title,
        'commission_percentage' => $range_commission[0]->percentage
    ]);
  } 

}
