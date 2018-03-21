<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Validator;
use Illuminate\Support\Facades\DB;
use App\Models\Invoices\Invoice;
use App\Models\Invoices\ListInvoiceStatus;
use Storage;
use App\Http\Helpers\Upload;

class InvoiceController extends Controller
{
    public function __construct()
    {
        $this->relationships = ['quotes', 'owner', 'invoice_status'];
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
        $query = Invoice::query();
        // $query->where('status', 2);
        if($request->owner) {
            $query->whereIn('owner_id', explode(",",$request->owner));
        }
        
        if($request->status) {
            $query->whereIn('invoice_status_id', explode(",",$request->status));
        }

        if($request->status) {
            $query->whereIn('invoice_status_id', explode(",",$request->status));
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
        'name' => "pendding",
        'basename' => "pendding"]);        
                
        foreach ($request->quotes as $quote) {                           
            DB::table('invoice_quote')->insert([
                ['invoice_id' => $invoice->id, 'quote_id' => $quote['id'], 'amount' => $quote['invoice_amount']]
            ]);
        }

        // if ($request->pathFile) {
        //     $upload = new Upload();
        //     $upload->move($request->pathFile, 'files/'.Auth::id().'/invoices/'.$invoice->id)
        //             ->getData();
        // }

        return $invoice->load($this->relationships);
    }

    public function update(Request $request, $id)
    {
        $invoice = Invoice::find($id);
        $invoice->invoice_status_id = $request->invoice_status_id;
        $invoice->save();
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

    public function uploadFile(Request $request)
    {
        $this->validate($request, [
            'name' => 'string|max:255',
            'basename' => 'string'
        ]);
  
        $upload = $this->upload($request->file,$request->invoice_id);
        $request->name = $request->file->getClientOriginalName();
        $request->basename = $upload['basename'];
        $request->extension = $upload['extension'];

        $invoice = Invoice::find($request->invoice_id);
        $invoice->name = $request->name;
        $invoice->basename = $request->basename;
        $invoice->save();
  
        return $invoice;
    }

    private function upload($file, $invoice_id)
    {
        $path = $file->store('files/'.Auth::id().'/invoices/'.$invoice_id);

        $infoFile = pathinfo($path);
        Storage::put('files/'.Auth::id().'/invoices/'.$invoice_id.'/'.$infoFile['basename'], $file);

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

    public function listStatuses()
    {
        return ListInvoiceStatus::all();
    }

    public function invoicesPerMonth(Request $request)
    {
        $invoiceQuery = Invoice::query();
    }
}
