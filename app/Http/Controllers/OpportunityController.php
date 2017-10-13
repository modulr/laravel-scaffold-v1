<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Validator;
use App\Project;
use App\Priority;
use App\Client;
use App\Models\Lists\ListArea;

class OpportunityController extends Controller
{
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
        $opportunity = Project::with('owner', 'priority', 'client', 'client.customer', 'quote', 'quote.designer', 'quote.seller', 'quote.status', 'area')->where('status', 1)->find($id);

        return view('opportunities.opportunity', ['breadcrumb' => $request->path(), 'opportunity' => $opportunity]);
    }

    public function all()
    {
        return Project::with('owner', 'priority', 'client', 'client.customer', 'quote', 'area')->where('status', 1)->get();
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
            'start_date' => 'required|date',
            'priority' => 'required',
            'area' => 'required',
            'client' => 'required',
        ]);

        $opportunity = Project::create([
            'name' => $request->name,
            'quotes' => $request->quotes,
            'start_date' => $request->start_date,
            'status' => 1, // 1 = Opportunity
            'description' => $request->description,
            'owner_id' => Auth::id(),
            'priority_id' => $request->priority,
            'client_id' => $request->client,
            'area_id' => $request->area,
        ])->load('owner', 'priority', 'client', 'client.customer', 'area');

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

        $q->save();

        return $q->load('owner', 'priority', 'client', 'client.customer', 'area');
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
     * Get a list of oportunity priorities.
     *
     * @return \Illuminate\Http\Response
     */
     public function listPriorities()
     {
        return Priority::all();
     }

    /**
     * Get a list of oportunity clients.
     *
     * @return \Illuminate\Http\Response
     */
     public function listClients()
     {
        return Client::with('customer')->get();
     }

     public function listAreas()
     {
         return ListArea::all();
     }
}
