<?php

namespace App\Exports;

use App\Models\BloodGroup;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithTitle;

class BloodGroupExport implements
    FromCollection,
    WithMapping,
    WithHeadings,
    WithTitle
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        $bloodgroups = BloodGroup::get();
        return $bloodgroups;
    }

    public function map($bloodgroup):array{
        return [
            $bloodgroup->id,
            $bloodgroup->name,
        ];
    }

    public function headings(): array {
        return [
            'id',
            'name'
        ];
    }

    public function title():string {
        return 'BloodGroups';
    }
}
