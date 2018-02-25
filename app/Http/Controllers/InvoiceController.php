<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Validator;
use Illuminate\Support\Facades\DB;
use App\Models\Invoices\Invoice;
use Storage;
use App\Http\Helpers\Upload;

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
        'invoice_status_id' => 1,        
        'name' => $request->name,
        'basename' => $request->basename]);        
                
        foreach ($request->quotes as $quote) {                           
            DB::table('invoice_quote')->insert([
                ['invoice_id' => $invoice->id, 'quote_id' => $quote['id'], 'amount' => $quote['invoice_amount']]
            ]);
        }

        if ($request->pathFile) {
            $upload = new Upload();
            $upload->move($request->pathFile, 'files/'.Auth::id().'/invoices/'.$invoice->id)
                    ->getData();
        }

        return $invoice->load($this->relationships);
    }

    // Images
    public function uploadImageTemp(Request $request)
    {
        $this->validate($request, [
            'file' => 'required|max:10000',
        ]);
        $upload = new Upload();
        $uploadData = $upload->uploadTemp($request->file)->getData();
        return $uploadData;
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
