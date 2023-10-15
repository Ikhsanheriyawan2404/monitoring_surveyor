<?php

namespace App\Imports;

use App\Models\Surveyor;
use App\Models\Task;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;


class TaskImport implements ToCollection, WithHeadingRow, WithValidation
{
    public function collection(Collection $rows)
    {
        foreach ($rows as $row) {
            if ($row['surveyor_name']) {
                $slug = Str::slug($row['surveyor_name']);
                $surveyor = Surveyor::where('slug_name', $slug)->first();
                // if (! $surveyor) {
                //     $surveyor = Surveyor::create([
                //         'name' => $row['surveyor_name'],
                //         'slug_name' => $slug,
                //     ]);
                // }
            }

            $data = [
                // 'name' => $row['name'],
                'surveyor_id' => $surveyor->id,
                'debitur_name' => $row['debitur_name'],
                'survey_date' => Carbon::instance(
                    \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject(
                        $row['survey_date']
                    )
                ),
                'plat_number' => $row['plat_number'],
                'information' => $row['keterangan'],
                'take_over' => $row['take_over'] == 'Y' ? true : false,
                'bpkb_onhand' => $row['bpkb_onhand'] == 'Y' ? true : false,
                'leasing_name' => $row['leasing_name'],
                'slik_grouping' => $row['slik_grouping'],
                'status' => $row['status'],
            ];

            Task::create($data);
        }
    }

    public function rules(): array
    {
        return [
            'surveyor_name' => 'required|max:255',
        ];
    }
}
