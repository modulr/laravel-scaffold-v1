<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Project;
use App\Quote;
use DB;
use Carbon\Carbon;

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

    public function opportunityCharts()
    {
        $activeCharts = DB::table('projects')
                    ->where('status', 1)
                    ->select(DB::raw('MONTHNAME(start_date) as date'), DB::raw('count(*) as projects'))
                    ->groupBy('date')
                    ->orderBy('date', 'DESC')
                    ->get();

        $rejectedCharts = DB::table('projects')
                    ->where('status', 0)
                    ->select(DB::raw('MONTHNAME(start_date) as date'), DB::raw('count(*) as projects'))
                    ->groupBy('date')
                    ->orderBy('date', 'DESC')
                    ->get();

        return array(
            'activeCharts' => $activeCharts,
            'rejectedCharts' => $rejectedCharts,
            'title' => 'Active Opportunities',
        );
    }

    public function getMonthQuotes()
    {
        $currentMonth = date('m');
        return Quotes::where('status', 1)->whereRaw('MONTH(start_date) = ?', [$currentMonth])->count();
    }

    public function getQuoteInsights()
    {
        $activeQuotes = Quote::where('status_id', 1)->get();
        $rejectedQuotes = Quote::where('status_id', 4)->get();
        $getTotalMXN = Quote::where('currency_id', 1)->sum('amount');
        $getTotalUSD = Quote::where('currency_id', 2)->sum('amount');
        $allQuotes = Quote::all();

        return response()->json([
            'totalMXN' => $getTotalMXN,
            'totalUSD' => $getTotalUSD,
            'active' => $activeQuotes,
            'rejected' => $rejectedQuotes,
            'all' => $allQuotes,
        ]);
    }
}
