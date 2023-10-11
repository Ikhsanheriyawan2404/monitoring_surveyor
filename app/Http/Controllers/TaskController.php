<?php

namespace App\Http\Controllers;

use App\Imports\TaskImport;
use App\Models\Task;
use App\Models\Surveyor;
use Illuminate\Support\Facades\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Facades\Excel;

class TaskController extends Controller
{
    public function index()
    {
        return Inertia::render('Tasks/Index', [
            'filters' => Request::all('search', 'trashed'),
            'tasks' => Task::select()
                ->with('surveyor')
                ->orderByName()
                ->filter(Request::only('search', 'trashed'))
                ->paginate(10)
                ->withQueryString()
                ->through(fn ($task) => [
                    'id' => $task->id,
                    'name' => $task->name,
                    'created_at' => $task->created_at,
                    'surveyor' => $task->surveyor->only('name'),
                ]),
        ]);
    }

    public function create()
    {
        return Inertia::render('Tasks/Create', [
            'surveyors' => Surveyor::select('id', 'name')
                ->orderBy('name')
                ->get(),
        ]);
    }

    public function store()
    {
        Task::create(
            Request::validate([
                'name' => ['required', 'max:255'],
                'surveyor_id' => ['nullable', Rule::exists('surveyors', 'id')]
            ])
        );

        return Redirect::route('tasks')->with('success', 'Task created.');
    }

    public function edit(Task $task)
    {
        return Inertia::render('Tasks/Edit', [
            'task' => [
                'id' => $task->id,
                'name' => $task->name,
                'surveyor_id' => $task->surveyor_id,
                'deleted_at' => $task->deleted_at,
            ],
            'surveyors' => Surveyor::select()
                ->orderBy('name')
                ->get(),
        ]);
    }

    public function update(Task $task)
    {
        $task->update(
            Request::validate([
                'name' => ['required', 'max:255'],
                'surveyor_id' => [
                    Rule::exists('surveyors', 'id'),
                ],
            ])
        );

        return Redirect::back()->with('success', 'Task updated.');
    }

    public function destroy(Task $task)
    {
        $task->delete();

        return Redirect::back()->with('success', 'Task deleted.');
    }

    public function restore(Task $task)
    {
        $task->restore();

        return Redirect::back()->with('success', 'Task restored.');
    }

    public function viewImport()
    {
        return Inertia::render('Tasks/ImportExcel');
    }

    public function import()
    {
        Request::validate([
            'file' => ['required', 'mimes:xlsx'],
        ]);

        Excel::import(new TaskImport, Request::file('file'));

        return Redirect::route('tasks')->with('success', 'Tasks imported.');
    }
}
