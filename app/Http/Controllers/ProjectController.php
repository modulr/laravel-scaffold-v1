<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Validator;
use App\Models\Projects\Project;
use App\Models\Projects\ListPriority;
use App\Models\Projects\ListProjectStatus;
use App\Models\Customers\Customer;
use App\Models\Customers\Client;
use App\Models\Lists\ListArea;
use DB;

class ProjectController extends Controller
{
    public function __construct()
    {
        $this->relationships = ['owner', 'priority', 'client', 'client.customer', 'quote', 'quote.designer', 'quote.salesman', 'quote.status', 'quote.currency', 'area', 'leader', 'supervisor', 'project_status', 'comment', 'comment.user'];
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        return view('projects.projects', ['breadcrumb' => $request->path()]);
    }

    public function show(Request $request, $id)
    {
        $project = Project::with($this->relationships)->where('status', 2)->find($id);

        return view('projects.project', ['breadcrumb' => $request->path(), 'project' => $project]);
    }

    public function all(Request $request)
    {
        // return Project::with($this->relationships)->where('status', 2)->paginate(10);
        $query = Project::query();
        $query->where('status', 2);
        if($request->name) {
            $query->where('projects.name', 'LIKE', '%'.$request->name.'%');
        }
        if($request->client) {
          $query->whereIn('client_id', explode(",",$request->client));
        }
        if($request->customer) {
          $query->join('clients', 'projects.client_id', '=', 'clients.id');
          $query->select('projects.*');
          $query->whereIn('clients.customer_id', explode(",",$request->customer));
        }
        if($request->area) {
          $query->whereIn('area_id', explode(",",$request->area));
        }
        if($request->status) {
            $query->whereIn('project_status_id', explode(",",$request->status));
          }
        $north = clone $query;
        $south = clone $query;
        $center = clone $query;

        // Inicio de calculos para total de facturas cobradas moneda MXN
        $invoiceChargedMXN = clone $query;        
        $invoiceChargedMXN->join('quotes', function ($join) {
                    $join->on('projects.id', '=', 'quotes.project_id')
                        ->where('quotes.currency_id', '=', 1);
                })
                ->join('invoice_quote', 'quotes.id', '=', 'invoice_quote.quote_id')                
                ->join('invoices', function ($join) {
                    $join->on('invoice_quote.invoice_id', '=', 'invoices.id')
                        ->where('invoices.invoice_status_id', '=', 3);
                })
                ->select('invoice_quote.amount as InvoiceAmountPerQuote')->get();
        $amountChargedMXN = 0.00;
        foreach ($invoiceChargedMXN->get() as $invoice) {
            $amountChargedMXN+= $invoice['InvoiceAmountPerQuote'];
        }
        // Fin de calculos para total de facturas cobradas moneda MXN

        // Inicio de calculos para total de facturas pendientes moneda MXN
        $invoicePenddingMXN = clone $query;        
        $invoicePenddingMXN->join('quotes', function ($join) {
                    $join->on('projects.id', '=', 'quotes.project_id')
                        ->where('quotes.currency_id', '=', 1);
                })
                ->join('invoice_quote', 'quotes.id', '=', 'invoice_quote.quote_id')                
                ->join('invoices', function ($join) {
                    $join->on('invoice_quote.invoice_id', '=', 'invoices.id')
                        ->where('invoices.invoice_status_id', '=', 1);
                })
                ->select('invoice_quote.amount as InvoiceAmountPerQuote')->get();
        $amountPenddingMXN = 0.00;
        foreach ($invoicePenddingMXN->get() as $invoice) {
            $amountPenddingMXN+= $invoice['InvoiceAmountPerQuote'];
        }
        // Fin de calculos para total de facturas pendientes moneda MXN
        
        // Inicio de calculos para total de facturas cobradas moneda USD
        $invoiceChargedUSD = clone $query;        
        $invoiceChargedUSD->join('quotes', function ($join) {
                    $join->on('projects.id', '=', 'quotes.project_id')
                        ->where('quotes.currency_id', '=', 2);
                })
                ->join('invoice_quote', 'quotes.id', '=', 'invoice_quote.quote_id')                
                ->join('invoices', function ($join) {
                    $join->on('invoice_quote.invoice_id', '=', 'invoices.id')
                        ->where('invoices.invoice_status_id', '=', 3);
                })
                ->select('invoice_quote.amount as InvoiceAmountPerQuote')->get();
        $amountChargedUSD = 0.00;
        foreach ($invoiceChargedUSD->get() as $invoice) {
            $amountChargedUSD+= $invoice['InvoiceAmountPerQuote'];
        }
        // Fin de calculos para total de facturas cobradas moneda USD

        // Inicio de calculos para total de facturas pendientes moneda USD
        $invoicePenddingUSD = clone $query;        
        $invoicePenddingUSD->join('quotes', function ($join) {
                    $join->on('projects.id', '=', 'quotes.project_id')
                        ->where('quotes.currency_id', '=', 2);
                })
                ->join('invoice_quote', 'quotes.id', '=', 'invoice_quote.quote_id')                
                ->join('invoices', function ($join) {
                    $join->on('invoice_quote.invoice_id', '=', 'invoices.id')
                        ->where('invoices.invoice_status_id', '=', 1);
                })
                ->select('invoice_quote.amount as InvoiceAmountPerQuote')->get();
        $amountPenddingUSD = 0.00;
        foreach ($invoicePenddingUSD->get() as $invoice) {
            $amountPenddingUSD+= $invoice['InvoiceAmountPerQuote'];
        }
        // Fin de calculos para total de facturas pendientes moneda MXN

        $done = clone $query;
        $inProgress = clone $query;
        $canceled = clone $query;
        $new = clone $query;

        $projects = $query->orderBy('created_at', 'desc')->paginate(10);
        $projects->load($this->relationships);
        // return $projects;
        return response()->json([
            'projects' => $projects,
            'north' => $north->where('area_id', 1)->count(),
            'south' => $south->where('area_id', 2)->count(),
            'center' => $center->where('area_id', 3)->count(),            
            'amountChargedMXN' => $amountChargedMXN,            
            'amountChargedUSD' => $amountChargedUSD,            
            'amountPenddingMXN' => $amountPenddingMXN,    
            'amountPenddingUSD' => $amountPenddingUSD,   
            'done' => $done->where('project_status_id', 1)->count(),
            'inProgress' => $inProgress->where('project_status_id', 2)->count(),
            'canceled' => $canceled->where('project_status_id', 3)->count(),
            'new' => $new->where('project_status_id', 4)->count(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|string',
            'quotes' => 'numeric',
            'registered_date' => 'required|date',
            'priority' => 'required',
            'area' => 'required',
            'client' => 'required',
        ]);

        $project = Project::create([
            'name' => $request->name,
            'quotes' => $request->quotes,
            'registered_date' => $request->registered_date,
            'status' => 2, // 2 = Project
            'description' => $request->description,
            'owner_id' => Auth::id(),
            'priority_id' => $request->priority,
            'client_id' => $request->client,
            'area_id' => $request->area,
        ])->load($this->relationships);

        return $project;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $q = Project::find($id);

        if ($q->name != $request->name) {
            $q->name = $request->name;
        }

        if ($q->description != $request->description) {
            $q->description = $request->description;
        }

        if ($q->priority_id != $request->priority['id']) {
            $q->priority_id = $request->priority['id'];
        }

        if ($q->area_id != $request->area) {
            $q->area_id = $request->area['id'];
        }

        if ($q->start_date != $request->start_date) {
            $q->start_date = $request->start_date;
        }

        if ($q->end_date != $request->end_date) {
            $q->end_date = $request->end_date;
        }

        if ($q->leader_id != $request->leader['id']) {
            $q->leader_id = $request->leader['id'];
        }

        if ($q->supervisor_id != $request->supervisor['id']) {
            $q->supervisor_id = $request->supervisor['id'];
        }

        if ($q->completed_percentage != $request->completed_percentage) {
            $q->completed_percentage = $request->completed_percentage;
        }

        if ($q->project_status_id != $request->project_status['id']) {
            $q->project_status_id = $request->project_status['id'];
        }

        $q->save();

        return $q->load($this->relationships);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        return Project::destroy($id);
    }

    /**
     * Get a list of project priorities.
     *
     * @return \Illuminate\Http\Response
     */
    public function listPriorities()
    {
        return ListPriority::all();
    }

    /**
     * Get a list of project clients.
     *
     * @return \Illuminate\Http\Response
     */
    public function listClients()
    {
        return Client::with('customer')->get();
    }

     /**
      * Get a list of project customers.
      *
      * @return \Illuminate\Http\Response
      */
    public function listCustomers()
    {
        return Customer::with('client')->get();
    }

     /**
      * Get a list of owners.
      *
      * @return \Illuminate\Http\Response
      */
    public function listOwners()
    {
        return DB::table('projects')
                    ->join('users', 'projects.owner_id', '=', 'users.id')
                    ->select('users.name')
                    ->distinct()
                    ->get();
    }

     /**
      * Get a list of project areas.
      *
      * @return \Illuminate\Http\Response
      */
    public function listAreas()
    {
        return ListArea::all();
    }

     /**
      * Get a list of project statuses.
      *
      * @return \Illuminate\Http\Response
      */
    public function listStatuses()
    {
        return ListProjectStatus::all();
    }



}
