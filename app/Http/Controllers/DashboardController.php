<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use App\Models\Surveyor;
use Inertia\Inertia;

class DashboardController extends Controller
{
    public function index()
    {
        $totalBranch = Branch::count();
        $totalSurveyor = Surveyor::count();

        return Inertia::render('Dashboard/Index', [
            'totalBranch' => $totalBranch,
            'totalSurveyor' => $totalSurveyor,
        ]);
    }
}
