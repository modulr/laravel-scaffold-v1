<?php

namespace App\Http\Controllers\Lists;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Lists\ListPosition;

class ListPositionController extends Controller
{
    public function all()
    {
        return ListPosition::get();
    }
}
