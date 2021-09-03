<?php

namespace App\Exports;

use App\Models\Religion;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;

class ReligionExport implements
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
        $religions = Religion::get();
        return $religions;
    }

    public function map($religion): array
    {
        return [
            $religion->id,
            $religion->name,
        ];
    }

    public function headings(): array
    {
        return [
            'id',
            'name'
        ];
    }

    public function title(): string
    {
        return 'Religions';
    }
}
