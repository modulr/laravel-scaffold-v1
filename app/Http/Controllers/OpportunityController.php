<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Validator;
use App\Models\Projects\Project;
use App\Models\Projects\ListPriority;
use App\Models\Projects\ListOpportunityStatus;
use App\Models\Customers\Customer;
use App\Models\Customers\Client;
use App\Models\Lists\ListArea;
use DB;

class OpportunityController extends Controller
{
    public function __construct()
    {
        $this->relationships = ['owner', 'priority', 'client', 'client.customer', 'quote', 'quote.designer', 'quote.salesman', 'quote.status', 'quote.currency', 'area', 'comment', 'comment.user', 'opportunity_status'];
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        return view('opportunities.opportunities', ['breadcrumb' => $request->path()]);
    }

    public function show(Request $request, $id)
    {
        $opportunity = Project::with($this->relationships)->where('status', 1)->find($id);

        return view('opportunities.opportunity', ['breadcrumb' => $request->path(), 'opportunity' => $opportunity]);
    }

    public function all(Request $request)
    {
        $query = Project::query();
        $query->where('status', 1);
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

        $north = clone $query;
        $south = clone $query;
        $center = clone $query;

        $high = clone $query;
        $medium = clone $query;
        $low = clone $query;        

        $opportunities = $query->paginate(10);
        $opportunities->load($this->relationships);
        // return $opportunities;
        return response()->json([
            'opportunities' => $opportunities,
            'north' => $north->where('area_id', 1)->count(),
            'south' => $south->where('area_id', 2)->count(),
            'center' => $center->where('area_id', 3)->count(),
            'high' => $high->where('priority_id', 1)->count(),
            'medium' => $medium->where('priority_id', 2)->count(),
            'low' => $low->where('priority_id', 3)->count()            
        ]);
    }

    public function listByCustomer(Request $request, $id)
    {
        return DB::table('projects')
                    ->join('clients', 'projects.client_id', '=', 'clients.id')
                    ->join('customers', 'clients.customer_id', '=', 'customers.id')
                    ->select('projects.*')
                    ->where('customers.id', $id)
                    ->get();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
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

        $opportunity = Project::create([
            'name' => $request->name,
            'quotes' => $request->quotes,
            'registered_date' => $request->registered_date,
            'status' => 1, // 1 = Opportunity
            'opportunity_status_id' => 4,
            'description' => $request->description,
            'owner_id' => Auth::id(),
            'priority_id' => $request->priority,
            'client_id' => $request->client['id'],
            'area_id' => $request->area,
        ])->load($this->relationships);

        return $opportunity;
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

        if ($q->priority_id != $request->priority) {
            $q->priority_id = $request->priority['id'];
        }

        if ($q->area_id != $request->area) {
            $q->area_id = $request->area['id'];
        }

        if ($q->opportunity_status_id != $request->opportunity_status['id']) {
            $q->opportunity_status_id = $request->opportunity_status['id'];
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
     * Get a list of opportunity priorities.
     *
     * @return \Illuminate\Http\Response
     */
    public function listPriorities()
    {
        return ListPriority::all();
    }

    /**
     * Get a list of opportunity clients.
     *
     * @return \Illuminate\Http\Response
     */
    public function listClients()
    {
        return Client::with('customer')->get();
    }

     /**
      * Get a list of opportunity customers.
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
      * Get a list of opportunity areas.
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
        return ListOpportunityStatus::all();
    }

    public function makeProject($id)
    {
        $q = Project::find($id);
        $q->status = 2;
        $q->project_status_id = 4;
        $q->opportunity_status_id = 4;
        $q->acceptance_date = date('Y-m-d h:i:s');

        $q->save();

        return $q;
    }
}
