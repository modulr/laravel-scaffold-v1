<?php

namespace App\Http\Controllers\Lists;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Lists\ListContact;

class ListContactController extends Controller
{
    public function all()
    {
        return ListContact::get();
    }
}
