<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Lists\ListTypeService;

class ListTypeServiceController extends Controller
{
  public function all(Request $request)
  {
      return ListTypeService::all();
  }
}
