<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Validator;
use Illuminate\Support\Facades\DB;
use App\Models\Invoices\Invoice;
use Storage;

class InvoiceController extends Controller
{
    public function __construct()
    {
        $this->relationships = ['quotes', 'owner'];
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        return view('invoices.invoices', ['breadcrumb' => $request->path()]);
    }

    public function all(Request $request)
    {
        // return Project::with($this->relationships)->where('status', 2)->paginate(10);
        $query = Invoice::query();
        // $query->where('status', 2);
        if($request->owner) {
            $query->whereIn('owner_id', explode(",",$request->owner));
        }

        $invoices = $query->orderBy('created_at', 'desc')->paginate(10);
        $invoices->load($this->relationships);

        return response()->json([
            'invoices' => $invoices,
        ]);
    }

    public function show(Request $request, $id)
    {
        $invoice = Invoice::with($this->relationships)->find($id);

        return view('invoices.invoice', ['breadcrumb' => $request->path(), 'invoice' => $invoice]);
    }

    public function store(Request $request)
    {        

        $invoice = Invoice::create([
        'owner_id' => Auth::id(),
        'amount' => $request->amount,
        'description' => $request->description,
        'invoice_status_id' => $request->invoice_status_id,        
        'name' => "dummy",
        'basename' => "dummy"]);        
                
        foreach ($request->quotes as $quote) {                           
            DB::table('invoice_quote')->insert([
                ['invoice_id' => $invoice->id, 'quote_id' => $quote['id'], 'amount' => $quote['invoice_amount']]
            ]);
        }      

        return $invoice->load($this->relationships);
        // $data = $request->all();

        // return response()->json($data);
    }

    public function all(Request $request)
    {
        return Invoice::with('invocie_status')->get();
    }

    public function updateFile(Request $request)
    {
        $upload = $this->upload($request->file);
        $request->name = $request->file->getClientOriginalName();
        $request->basename = $upload['basename'];
        $request->extension = $upload['extension'];
        $q = Invoice::find($request->invoice_id);
        $q->name = $request->name;
        $q->basename = $request->basename;
        $q->save();
        $data = $request->all();

        return response()->json($data);
    }

    private function upload($file)
    {
        $path = $file->store('files/'.Auth::id().'/invoices');
  
        $infoFile = pathinfo($path);
        Storage::put('files/'.Auth::id().'/'.$infoFile['basename'], $file);
  
        return $infoFile;
    }

    /**
      * Get a list of owners.
      *
      * @return \Illuminate\Http\Response
      */
    public function listOwners()
    {
        return DB::table('invoices')
                    ->join('users', 'invoices.owner_id', '=', 'users.id')
                    ->select('users.name', 'users.id')
                    ->distinct()
                    ->get();
    }
}
