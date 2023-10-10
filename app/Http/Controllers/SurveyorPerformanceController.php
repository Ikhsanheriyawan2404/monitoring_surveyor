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
        ]);
    }

    public function store()
    {
        SurveyorPerformance::create(
            Request::validate([
                'name' => ['required', 'max:255'],
                'branch_id' => ['required', Rule::exists('branches', 'id')]
            ])
        );

        return Redirect::route('performances')->with('success', 'Performance created.');
    }

    public function edit(SurveyorPerformance $performance)
    {
        return Inertia::render('SurveyorPerformances/Edit', [
            'performance' => [
                'id' => $performance->id,
                'month' => $performance->month,
                'year' => $performance->year,
                'productivity' => $performance->productivity,
                'quality' => $performance->quality,
                'effisiency' => $performance->effisiency,
                'created_at' => $performance->created_at,
                'surveyor' => $performance->surveyor->only('name'),
            ],
            'surveyors' => Surveyor::select()
                ->orderBy('name')
                ->get(),
        ]);
    }

    public function update(Surveyor $surveyor)
    {
        $surveyor->update(
            Request::validate([
                'name' => ['required', 'max:255'],
                'branch_id' => [
                    'required',
                    Rule::exists('branches', 'id'),
                ],
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
}
