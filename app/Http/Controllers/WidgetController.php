<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Projects\Project;
use App\Models\Quotes\Quote;
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
        $currentYear = date('Y');

        return Project::where('status', 1)
        ->whereYear('registered_date', $currentYear)
        ->whereRaw('MONTH(registered_date) = ?', [$currentMonth])
        ->count();
    }

    public function getOpportunityInsights()
    {
        $currentYear = date('Y');
        $approvedOpportunities = Project::where('status', 2)->whereYear('registered_date', $currentYear)->get();
        $activeOpportunities = Project::where('status', 1)->whereYear('registered_date', $currentYear)->get();
        $rejectedOpportunities = Project::where('status', 0)->whereYear('registered_date', $currentYear)->get();
        $allOpportunities = Project::whereYear('registered_date', $currentYear)->get();

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
        $currentYear = date('Y');

        for ($i=1; $i <= 12; $i++) {
            $activeData = Project::where('status', 1)
                ->whereYear('registered_date', $currentYear)
                ->whereRaw('MONTH(registered_date) = ?' , $i)
                ->get();
            $activeArray[$i] = $activeData->count();
        }

        $rejectedArray = array();

        for ($i=1; $i <= 12; $i++) {
            $rejectedData = Project::where('status', 0)
                ->whereYear('registered_date', $currentYear)
                ->whereRaw('MONTH(registered_date) = ?' , $i)
                ->get();
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
        $currentYear = date('Y');

        return Quotes::where('status', 1)
        ->whereYear('request_date', $currentYear)
        ->whereRaw('MONTH(request_date) = ?', [$currentMonth])
        ->count();
    }

    public function getQuoteInsights()
    {
        $currentYear = date('Y');
        
        $activeQuotes = Quote::whereIn('status_id', [1, 2, 3])->whereYear('request_date', $currentYear)->get();
        $rejectedQuotes = Quote::where('status_id', 4)->whereYear('request_date', $currentYear)->get();
        $getTotalMXN = Quote::where('currency_id', 1)->whereYear('request_date', $currentYear)->sum('amount');
        $getTotalUSD = Quote::where('currency_id', 2)->whereYear('request_date', $currentYear)->sum('amount');
        $allQuotes = Quote::whereYear('request_date', $currentYear)->get();

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
        $currentYear = date('Y');
        for ($i=1; $i <= 12; $i++) {
            $activeData = Quote::whereIn('status_id', [1, 2, 3])
                ->whereYear('request_date', $currentYear)
                ->whereRaw('MONTH(request_date) = ?' , $i)
                ->get();
            if ($activeData->count() != 0) {
                $activeArray[$i] = $activeData->count();
            } else {
                $activeArray[$i] = 0;
            }
        }

        $rejectedArray = array();

        for ($i=1; $i <= 12; $i++) {
            $rejectedData = Quote::where('status_id', 4)
                ->whereYear('request_date', $currentYear)
                ->whereRaw('MONTH(request_date) = ?' , $i)
                ->get();
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
