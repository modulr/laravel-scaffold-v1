<?php

namespace App\Http\Controllers\Lists;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Lists\ListGender;

class ListGenderController extends Controller
{
    public function all()
    {
        return ListGender::get();
    }
}
