<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Project;
use App\Quote;

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

    public function getOpportunityInsights()
    {
        $approvedOpportunities = Project::where('status', 2)->get();
        $activeOpportunities = Project::where('status', 1)->get();
        $rejectedOpportunities = Project::where('status', 0)->get();
        $allOpportunities = Project::all();

        return response()->json([
            'approved' => $approvedOpportunities,
            'active' => $activeOpportunities,
            'rejected' => $rejectedOpportunities,
            'all' => $allOpportunities,
        ]);
    }

    public function getMonthQuotes()
    {
        $currentMonth = date('m');
        return Quotes::where('status', 1)->whereRaw('MONTH(start_date) = ?', [$currentMonth])->count();
    }

    public function getQuoteInsights()
    {
        $activeQuotes = Quote::where('status_id', 1)->get();
        $rejectedQuotes = Quote::where('status_id', 0)->get();
        $getTotalAmountQuoted = Quote::sum('amount');
        $allQuotes = Quote::all();

        return response()->json([
            'totalAmount' => $getTotalAmountQuoted,
            'active' => $activeQuotes,
            'rejected' => $rejectedQuotes,
            'all' => $allQuotes,
        ]);
    }
}
