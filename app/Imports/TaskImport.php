<?php

namespace App\Imports;

use App\Models\Surveyor;
use App\Models\Task;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class TaskImport implements ToCollection, WithHeadingRow, WithValidation
{
    public function collection(Collection $rows)
    {
        foreach ($rows as $row) {
            if ($row['surveyor_name']) {
                $surveyor = Surveyor::where('name', $row['surveyor_name'])->first();
                if (! $surveyor) {
                    $surveyor = Surveyor::create([
                        'name' => $row['surveyor_name'],
                    ]);
                }
            }

            $data = [
                'name' => $row['name'],
                'surveyor_id' => $surveyor->id,
            ];

            Task::create($data);
        }
    }

    public function rules(): array
    {
        return [
            'name' => 'required|max:255',
            'surveyor_name' => 'required|max:255',
        ];
    }
}
