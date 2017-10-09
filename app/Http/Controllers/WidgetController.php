<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Project;
use App\Quotes;

class WidgetController extends Controller
{
    public function opportunities()
    {
        return $projects = Project::all();
    }

    public function getMonthOpportunities()
    {
        $currentMonth = date('m');
        return Project::where('status', 1)->whereRaw('MONTH(start_date) = ?', [$currentMonth])->count();
    }
}
