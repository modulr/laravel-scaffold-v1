<?php

namespace App\Http\Controllers\Lists;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Lists\ListProfession;

class ListProfessionController extends Controller
{
    public function all()
    {
        return ListProfession::get();
    }
}
