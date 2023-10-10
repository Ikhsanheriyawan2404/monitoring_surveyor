<?php

namespace App\Http\Controllers;

use App\Models\Surveyor;
use Inertia\Inertia;
use App\Models\SurveyorPerformance;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Validation\Rule;

class SurveyorPerformanceController extends Controller
{
    public $months = [
        1 => 'January',
        2 => 'February',
        3 => 'March',
        4 => 'April',
        5 => 'May',
        6 => 'June',
        7 => 'July',
        8 => 'August',
        9 => 'September',
        10 => 'October',
        11 => 'November',
        12 => 'December',
    ];

    public function index()
    {
        return Inertia::render('SurveyorPerformances/Index', [
            'filters' => Request::all('search', 'trashed'),
            'performances' => SurveyorPerformance::select()
                ->with('surveyor')
                ->orderBy('created_at')
                ->filter(Request::only('search', 'trashed'))
                ->paginate(10)
                ->withQueryString()
                ->through(fn ($performance) => [
                    'id' => $performance->id,
                    'month' => $performance->month,
                    'year' => $performance->year,
                    'productivity' => $performance->productivity,
                    'quality' => $performance->quality,
                    'efficiency' => $performance->efficiency,
                    'score' => $performance->score,
                    'created_at' => $performance->created_at,
                    'surveyor' => $performance->surveyor->only('name'),
                ]),
        ]);
    }

    public function create()
    {
        return Inertia::render('SurveyorPerformances/Create', [
            'surveyors' => Surveyor::select()
                ->orderBy('name')
                ->get(),
            'months' => $this->months,
        ]);
    }

    public function store()
    {
        $request = Request::validate([
            'month' => ['required', 'numeric'],
            'year' => ['required', 'numeric'],
            'efficiency' => ['required', 'numeric'],
            'productivity' => ['required', 'numeric'],
            'quality' => ['required', 'numeric'],
            'surveyor_id' => ['required', Rule::exists('surveyors', 'id')]
        ]);

        $score = $this->calculateScore($request['efficiency'], $request['productivity'], $request['quality']);
        $request['score'] = $score;

        SurveyorPerformance::create($request);

        return Redirect::route('performances')->with('success', 'Performance created.');
    }

    public function storePerformance()
    {
        $request = Request::validate([
            'month' => ['required', 'numeric'],
            'year' => ['required', 'numeric'],
            'efficiency' => ['required', 'numeric'],
            'productivity' => ['required', 'numeric'],
            'quality' => ['required', 'numeric'],
            'surveyor_id' => [Rule::exists('surveyors', 'id')]
        ]);

        $score = $this->calculateScore($request['efficiency'], $request['productivity'], $request['quality']);
        $request['score'] = $score;

        SurveyorPerformance::create($request);

        return Redirect::back()->with('success', 'Performance created.');
    }

    public function edit(SurveyorPerformance $performance)
    {
        return Inertia::render('SurveyorPerformances/Edit', [
            'performance' => [
                'id' => $performance->id,
                'surveyor_id' => $performance->surveyor_id,
                'month' => $performance->month,
                'year' => $performance->year,
                'productivity' => $performance->productivity,
                'quality' => $performance->quality,
                'efficiency' => $performance->efficiency,
                'score' => $performance->score,
                'created_at' => $performance->created_at,
                'deleted_at' => $performance->deleted_at,
                'surveyor' => $performance->surveyor->only('name'),
            ],
            'surveyors' => Surveyor::select()
                ->orderBy('name')
                ->get(),
            'months' => $this->months,
        ]);
    }

    public function update(Surveyor $surveyor)
    {
        $surveyor->update(
            Request::validate([
                Request::validate([
                    'month' => ['required', 'numeric'],
                    'year' => ['required', 'numeric'],
                    'efficiency' => ['required', 'numeric'],
                    'productivity' => ['required', 'numeric'],
                    'quality' => ['required', 'numeric'],
                    'surveyor_id' => ['required', Rule::exists('surveyors', 'id')]
                ])
            ])
        );

        return Redirect::back()->with('success', 'Surveyor updated.');
    }

    public function destroy(SurveyorPerformance $performance)
    {
        $performance->delete();

        return Redirect::back()->with('success', 'Performance deleted.');
    }

    public function restore(SurveyorPerformance $performance)
    {
        $performance->restore();

        return Redirect::back()->with('success', 'Performance restored.');
    }

    private function calculateScore($efficiency, $productivity, $quality)
    {
        $score = ($efficiency * 1.2) + ($productivity * 1.2) + ($quality * 1.2);
        $dibagi = (1.2 + 1.2 + 1.2) * 1.2;
        return $score / $dibagi;
    }
}
