<?php

namespace App\Http\Controllers\Lists;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Lists\ListRelationship;

class ListRelationshipController extends Controller
{
    public function all()
    {
        return ListRelationship::get();
    }
}
