<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use App\Models\Branch;
use App\Models\Surveyor;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class SurveyorsImport implements ToCollection, WithHeadingRow, WithValidation
{
    public function collection(Collection $rows)
    {
        foreach ($rows as $row) {
            if ($row['branch']) {
                $branch = Branch::where('name', $row['branch'])->first();
                if (! $branch) {
                    $branch = Branch::create([
                        'name' => $row['branch'],
                    ]);
                }
            }

            $data = [
                'name' => $row['name'],
                'join_date' => $row['join_date'],
                'branch_id' => $branch->id,
                'status' => $row['status'],
            ];

            Surveyor::create($data);
        }
    }

    public function rules(): array
    {
        return [
            'name' => 'required|max:255',
            'branch' => 'required|max:255',
            'join_date' => 'required|date',
            'permanent' => 'required',
        ];
    }
}
