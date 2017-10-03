<?php

namespace App\Http\Controllers;

use App\Project;
use Illuminate\Http\Request;

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
        return Project::with('owner', 'priority', 'contact', 'contact.customer')->where('status', 2)->get();
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
     * Get a list of oportunity priorities.
     *
     * @return \Illuminate\Http\Response
     */
     public function listPriorities()
     {
        return Priority::all();
     }

    /**
     * Get a list of oportunity contacts.
     *
     * @return \Illuminate\Http\Response
     */
     public function listContacts()
     {
        return Contact::with('customer')->get();
     }

     public function makeProject($id)
    {
        $q = Project::find($id);
        $q->status = 2;

        $q->save();

        return $q;
    }
}
