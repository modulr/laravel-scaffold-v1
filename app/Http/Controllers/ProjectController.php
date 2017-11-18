<?php

namespace App\Http\Controllers;

use App\Models\Projects\Project;
use Illuminate\Http\Request;
use App\Models\Lists\ListArea;
use App\Models\Projects\ListPriority;

class ProjectController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        return view('projects.projects', ['breadcrumb' => $request->path()]);
    }

    public function all()
    {
        return Project::with('owner', 'priority', 'client', 'client.customer', 'quote', 'area', 'quote.currency', 'project_status')->where('status', 2)->get();
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
        ]);

        $opportunity = Project::create([
            'name' => $request->name,
            'quotes' => $request->quotes,
            'start_date' => $request->start_date,
            'status' => 1, // 1 = Opportunity
            'description' => $request->description,
            'owner_id' => Auth::id(),
            'priority_id' => $request->priority,
            'contact_id' => $request->contact,
        ])->load('owner', 'priority', 'contact', 'contact.customer');

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

        $q->save();

        return $q->load('owner', 'priority', 'contact', 'contact.customer');
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
}
