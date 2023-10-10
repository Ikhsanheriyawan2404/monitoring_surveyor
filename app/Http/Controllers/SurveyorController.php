<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use App\Models\Surveyor;
use App\Models\SurveyorPerformance;
use Illuminate\Support\Facades\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Validation\Rule;

class SurveyorController extends Controller
{
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
        Surveyor::create(
            Request::validate([
                'name' => ['required', 'max:255'],
                'branch_id' => ['nullable', Rule::exists('branches', 'id')]
            ])
        );

        return Redirect::route('surveyors')->with('success', 'Surveyor created.');
    }

    public function edit(Surveyor $surveyor)
    {
        $months = [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12];
        $performanceData = []; // Ini akan berisi data skor performa untuk setiap bulan

        foreach ($months as $month) {
            // Mengambil data performa surveyor berdasarkan bulan
            $performance = SurveyorPerformance::select()
                ->where('surveyor_id', $surveyor->id)
                ->where('month', $month)->get();

            // Menghitung total skor untuk bulan tersebut
            $totalScore = $performance->sum('score');

            // Menambahkan data skor ke dalam array performanceData
            $performanceData[] = $totalScore;
        }

        return Inertia::render('Surveyors/Edit', [
            'surveyor' => [
                'id' => $surveyor->id,
                'name' => $surveyor->name,
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
            'performanceData' => $performanceData,
        ]);
    }

    public function update(Surveyor $surveyor)
    {
        $surveyor->update(
            Request::validate([
                'name' => ['required', 'max:255'],
                'branch_id' => [
                    Rule::exists('branches', 'id'),
                ],
            ])
        );

        return Redirect::back()->with('success', 'Surveyor updated.');
    }

    public function destroy(Surveyor $surveyor)
    {
        $surveyor->delete();

        return Redirect::back()->with('success', 'surveyor deleted.');
    }

    public function restore(Surveyor $surveyor)
    {
        $surveyor->restore();

        return Redirect::back()->with('success', 'surveyor restored.');
    }
}
