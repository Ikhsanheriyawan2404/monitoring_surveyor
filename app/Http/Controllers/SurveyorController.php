<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use App\Models\Surveyor;
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
                ->paginate(10)
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
        // $branches = Branch::select()
        //         ->orderBy('name')
        //         ->get();
        // dd($branches);
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
        return Inertia::render('Surveyor/Edit', [
            'surveyor' => [
                'id' => $surveyor->id,
                'name' => $surveyor->name,
                'branch_id' => $surveyor->branch_id,
                'deleted_at' => $surveyor->deleted_at,
            ],
            'branches' => Branch::select()
                ->orderBy('name')
                ->get()
                ->map
                ->only('id', 'name'),
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
