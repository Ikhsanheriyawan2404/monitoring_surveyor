<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use App\Models\Branch;
use App\Models\Surveyor;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class SurveyorsImport implements ToCollection, WithHeadingRow, WithValidation
{
    public function collection(Collection $rows)
    {
        foreach ($rows as $row) {
            if ($row['branch']) {
                $branchSlugName = Str::slug($row['branch']);
                $branch = Branch::where('slug', $branchSlugName)->first();
                if (! $branch) {
                    $branch = Branch::create([
                        'name' => $row['branch'],
                        'slug' => $branchSlugName,
                    ]);
                }
            }

            $joinDate = \Carbon\Carbon::instance(
                \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject(
                    $row['join_date'
                ])
            );

            $status = $joinDate->diffInDays(Carbon::now()) > 90 ? 'permanent' : 'probation';

            $data = [
                'name' => $row['name'],
                'join_date' => $joinDate,
                'branch_id' => $branch->id,
                'status' => $status,
            ];

            Surveyor::create($data);
        }
    }

    public function rules(): array
    {
        return [
            'name' => 'required|max:255',
            'branch' => 'required|max:255',
            'join_date' => 'required',
            'status' => [Rule::in(['permanent', 'probation'])],
        ];
    }
}
