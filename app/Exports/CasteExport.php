<?php

namespace App\Exports;

use App\Models\Caste;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;

class CasteExport implements
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
        $castes = Caste::get();
        return $castes;
    }

    public function map($caste): array
    {
        return [
            $caste->id,
            $caste->name,
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
        return 'Castes';
    }
}
