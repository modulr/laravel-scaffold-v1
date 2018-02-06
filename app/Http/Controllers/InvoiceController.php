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
    public function store(Request $request)
    {
        // $this->validate($request, [
        //     'amount' => 'required',
        //     'description' => 'required'
        // ]);          

        // $upload = $this->upload($request->file);
        // $request->name = $request->file->getClientOriginalName();
        // $request->basename = $upload['basename'];
        // $request->extension = $upload['extension'];

        $invoice = Invoice::create([
        'owner_id' => Auth::id(),
        'amount' => $request->amount,
        'description' => $request->description,          
        'name' => "dummy",
        'basename' => "dummy"]);
        // 'name' => $request->name,
        // 'basename' => $request->basename]);
                
        foreach ($request->quotes as $quote) {                           
            DB::table('invoice_quote')->insert([
                ['invoice_id' => $invoice->id, 'quote_id' => $quote['id'], 'amount' => $quote['invoice_amount']]
            ]);
        }      

        return $invoice->load("quotes");
        // $data = $request->all();

        // return response()->json($data);
    }

    public function updateFile(Request $request)
    {
        // $upload = $this->upload($request->file);
        // $request->name = $request->file->getClientOriginalName();
        // $request->basename = $upload['basename'];
        // $request->extension = $upload['extension'];
        $q = Invoice::find($request->invoice_id);
        // $q->name = $request->name;
        // $q->basename = $request->basename;
        // $q->save();
        // return $request->invoice_id;
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
}
