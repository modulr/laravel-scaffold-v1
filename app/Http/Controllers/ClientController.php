<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Customers\Client;
use App\Models\Customers\Customer;

class ClientController extends Controller
{
    public function index(Request $request)
    {
        return view('customers.clients', ['breadcrumb' => $request->path()]);
    }

    public function all()
    {
        return Client::with('customer')->get();
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'client.name' => 'required|string',
        ]);


        if ($request->customer) {
            $this->validate($request, [
                'customer.name' => 'unique:customers,name',
            ]);
            
            $customer = Customer::create([
                'name' => $request->customer['name']
            ])->load('client');

            $client = Client::create([
                'name' => $request->client['name'],
                'email' => $request->client['email'],
                'phone_number_1' => $request->client['phone_number_1'],
                'phone_number_2' => $request->client['phone_number_2'],
                'phone_number_3' => $request->client['phone_number_3'],
                'customer_id' => $customer->id,
                ])->load('customer');

            return $client;
        }

        $client = Client::create([
            'name' => $request->client['name'],
            'email' => $request->client['email'],
            'phone_number_1' => $request->client['phone_number_1'],
            'phone_number_2' => $request->client['phone_number_2'],
            'phone_number_3' => $request->client['phone_number_3'],
            'customer_id' => $request->client['customer'],
            ])->load('customer');

        return $client;
    }

    public function update(Request $request, $id)
    {
        $q = Client::find($id);

        if ($q->name != $request->name) {
            $q->name = $request->name;
        }

        if ($q->email != $request->email) {
            $q->email = $request->email;
        }

        if ($q->phone_number_1 != $request->phone_number_1) {
            $q->phone_number_1 = $request->phone_number_1;
        }

        if ($q->phone_number_2 != $request->phone_number_2) {
            $q->phone_number_2 = $request->phone_number_2;
        }

        if ($q->phone_number_3 != $request->phone_number_3) {
            $q->phone_number_3 = $request->phone_number_3;
        }

        if ($q->customer_id != $request->customer) {
            $q->customer_id = $request->customer;
        }

        $q->save();

        return $q->load('customer');
    }

    public function destroy($id)
    {
        return Client::destroy($id);
    }
}
