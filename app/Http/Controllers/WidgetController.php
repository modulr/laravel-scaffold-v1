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
        $activeArray = array();

        for ($i=1; $i <= 12; $i++) {
            $activeData = Project::where('status', 1)->whereRaw('MONTH(start_date) = ?' , $i)->get();
            $activeArray[$i] = $activeData->count();
        }

        $rejectedArray = array();

        for ($i=1; $i <= 12; $i++) {
            $rejectedData = Project::where('status', 0)->whereRaw('MONTH(start_date) = ?' , $i)->get();
            $rejectedArray[$i] = $rejectedData->count();
        }

        return array(
            'active' => $activeArray,
            'rejected' => $rejectedArray,
        );
    }



    public function getMonthQuotes()
    {
        $currentMonth = date('m');
        return Quotes::where('status', 1)->whereRaw('MONTH(start_date) = ?', [$currentMonth])->count();
    }

    public function getQuoteInsights()
    {
        $activeQuotes = Quote::whereIn('status_id', [1, 2, 3])->get();
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

    public function quoteCharts()
    {
        $activeArray = array();

        for ($i=1; $i <= 12; $i++) {
            $activeData = Quote::whereIn('status_id', [1, 2, 3])->whereRaw('MONTH(request_date) = ?' , $i)->get();
            if ($activeData->count() != 0) {
                $activeArray[$i] = $activeData->count();
            } else {
                $activeArray[$i] = 0;
            }
        }

        $rejectedArray = array();

        for ($i=1; $i <= 12; $i++) {
            $rejectedData = Quote::where('status_id', 4)->whereRaw('MONTH(request_date) = ?' , $i)->get();
            if ($rejectedData->count() != 0) {
                $rejectedArray[$i] = $rejectedData->count();
            } else {
                $rejectedArray[$i] = 0;
            }
        }

        return array(
            'active' => $activeArray,
            'rejected' => $rejectedArray,
        );
    }
}
