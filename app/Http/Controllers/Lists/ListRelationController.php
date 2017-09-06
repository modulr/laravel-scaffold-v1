<?php

namespace App\Http\Controllers\Lists;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Lists\ListRelation;

class ListRelationController extends Controller
{
    public function all()
    {
        return ListRelation::get();
    }
}
