<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\Branch;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Redirect;

class BranchController extends Controller
{
    public function index()
    {
        return Inertia::render('Branches/Index', [
            'filters' => Request::all('search', 'trashed'),
            'branches' => Branch::select('id', 'name', 'created_at', 'updated_at', 'deleted_at')
                ->orderBy('name')
                ->filter(Request::only('search', 'trashed'))
                ->paginate(10)
                ->withQueryString()
                ->through(fn ($branch) => [
                    'id' => $branch->id,
                    'name' => $branch->name,
                    'created_at' => $branch->created_at,
                ]),
        ]);
    }

    public function create()
    {
        return Inertia::render('Branches/Create');
    }

    public function store()
    {
        $request = Request::validate([
            'name' => ['required' ,'unique:branches,slug', 'max:255'],
        ]);
        $request['slug'] =  Str::slug($request['name']);

        Branch::create($request);

        return Redirect::route('branches')->with('success', 'Branches created.');
    }

    public function edit(Branch $branch)
    {
        return Inertia::render('Branches/Edit', [
            'branch' => [
                'id' => $branch->id,
                'name' => $branch->name,
                'deleted_at' => $branch->deleted_at,
                'surveyors' => $branch->surveyors()->orderByName()->get()->map->only('id', 'name'),
            ],
        ]);
    }

    public function update(Branch $branch)
    {
        $request = Request::validate([
            'name' => ['required', 'unique:branches,slug,' . $branch->id, 'max:255'],
        ]);
        $request['slug'] =  Str::slug($request['name']);
        $branch->update($request);

        return Redirect::back()->with('success', 'Branch updated.');
    }

    public function destroy(Branch $branch)
    {
        $branch->delete();

        return Redirect::back()->with('success', 'Branch deleted.');
    }

    public function restore(Branch $branch)
    {
        $branch->restore();

        return Redirect::back()->with('success', 'Branch restored.');
    }
}
