<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use App\Models\Surveyor;
use App\Models\SurveyorPerformance;
use App\Models\Task;
use Inertia\Inertia;

class DashboardController extends Controller
{
    public function index()
    {
        $totalBranch = Branch::count();
        $totalSurveyor = Surveyor::count();
        $totalTask = Task::count();
        $surveyorScore = SurveyorPerformance::select('score')->get();

        $excelent = 0;
        $good = 0;
        $bad = 0;
        foreach($surveyorScore as $performance) {
            switch ($performance->score) {
                case $performance->score >= 100:
                    $excelent += 1;
                    break;
                case $performance->score > 70 && $performance->score <= 99:
                    $good += 1;
                    break;
                case $performance->score <= 70:
                    $bad += 1;
                    break;
                default:
                    break;
            }
        }

        $totalCountAllCategoryRating = [$excelent, $good, $bad];

        return Inertia::render('Dashboard/Index', [
            'totalBranch' => $totalBranch,
            'totalSurveyor' => $totalSurveyor,
            'totalTask' => $totalTask,
            'totalCountAllCategoryRating' => $totalCountAllCategoryRating,
        ]);
    }
}
