<?php

namespace App\Http\Controllers;

use App\Imports\SurveyorsImport;
use App\Models\Branch;
use App\Models\Surveyor;
use Carbon\Carbon;
use Inertia\Inertia;
use App\Models\SurveyorPerformance;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Str;

class SurveyorController extends Controller
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
        return Inertia::render('Surveyors/Index', [
            'filters' => Request::all('search', 'trashed'),
            'surveyors' => Surveyor::select()
                ->with('branch')
                ->orderByName()
                ->filter(Request::only('search', 'trashed'))
                ->paginate(100)
                ->withQueryString()
                ->through(fn ($surveyor) => [
                    'id' => $surveyor->id,
                    'name' => $surveyor->name,
                    'join_date' => $surveyor->join_date,
                    'status' => $surveyor->status,
                    'created_at' => $surveyor->created_at,
                    'branch' => $surveyor->branch->only('name'),
                ]),
        ]);
    }

    public function create()
    {
        return Inertia::render('Surveyors/Create', [
            'branches' => Branch::select()
                ->orderBy('name')
                ->get(),
        ]);
    }

    public function store()
    {
        $request = Request::validate([
            'name' => ['required' ,'unique:surveyors,slug_name', 'max:255'],
            'branch_id' => ['required', Rule::exists('branches', 'id')],
            'join_date' => ['required', 'date'],
        ]);
        $joinDate = Carbon::parse($request['join_date']);
        $status = $joinDate->diffInDays(Carbon::now()) > 90 ? 'permanent' : 'probation';
        $request['status'] = $status;
        $request['slug_name'] = Str::slug($request['name']);
        Surveyor::create($request);

        return Redirect::route('surveyors')->with('success', 'Surveyor created.');
    }

    public function edit(Surveyor $surveyor)
    {
        $performanceFinal = [];
        $performanceComponent = [];

        foreach ($this->months as $key => $month) {
            $performance = SurveyorPerformance::select()
                ->where('surveyor_id', $surveyor->id)
                ->where('month', $key)->get();

            $totalScore = $performance->sum('score');

            $performanceComponent['efficiency'][] = $performance->sum('efficiency');
            $performanceComponent['productivity'][] = $performance->sum('productivity');
            $performanceComponent['quality'][] = $performance->sum('quality');
            $performanceFinal[] = $totalScore;
        }

        return Inertia::render('Surveyors/Edit', [
            'surveyor' => [
                'id' => $surveyor->id,
                'name' => $surveyor->name,
                'join_date' => $surveyor->join_date,
                'branch_id' => $surveyor->branch_id,
                'deleted_at' => $surveyor->deleted_at,
                'tasks' => $surveyor->tasks()->get()->map->only('id', 'name'),
                'performances' => $surveyor->performances()->get()->map->only(
                    'id',
                    'efficiency',
                    'productivity',
                    'quality',
                    'month',
                    'year',
                    'score',
                ),
            ],
            'branches' => Branch::select()
                ->orderBy('name')
                ->get(),
            'performanceFinal' => $performanceFinal,
            'performanceComponent' => $performanceComponent,
            'months' => $this->months,
        ]);
    }

    public function update(Surveyor $surveyor)
    {
        $request = Request::validate([
            'name' => ['required' ,'unique:surveyors,slug_name,' . $surveyor->id, 'max:255'],
            'branch_id' => [
                'required',
                Rule::exists('branches', 'id'),
            ],
            'join_date' => ['required', 'date'],
        ]);
        $joinDate = Carbon::parse($request['join_date']);
        $status = $joinDate->diffInDays(Carbon::now()) > 90 ? 'permanent' : 'probation';
        $request['slug_name'] = Str::slug($request['name']);
        $request['status'] = $status;

        $surveyor->update($request);

        return Redirect::back()->with('success', 'Surveyor updated.');
    }

    public function destroy(Surveyor $surveyor)
    {
        $surveyor->delete();

        return Redirect::back()->with('success', 'Surveyor deleted.');
    }

    public function restore(Surveyor $surveyor)
    {
        $surveyor->restore();

        return Redirect::back()->with('success', 'Surveyor restored.');
    }

    public function viewImport()
    {
        return Inertia::render('Surveyors/ImportExcel');
    }

    public function import()
    {
        Request::validate([
            'file' => ['required', 'mimes:xlsx'],
        ]);

        Excel::import(new SurveyorsImport, Request::file('file'));

        return Redirect::route('surveyors')->with('success', 'Surveyors imported.');
    }
}
