<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Validator;
use App\Models\Projects\Project;
use App\Models\Projects\ListPriority;
use App\Models\Customers\Customer;
use App\Models\Customers\Client;
use App\Models\Lists\ListArea;
use DB;

class ProjectController extends Controller
{
    public function __construct()
    {
        $this->relationships = ['owner', 'priority', 'client', 'client.customer', 'quote', 'quote.designer', 'quote.salesman', 'quote.status', 'quote.currency', 'area', 'leader', 'supervisor'];
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
        $projects = $query->paginate(10);
        $projects->load($this->relationships);
        return $projects;
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

}
