<?php

namespace App\Exports;

use App\Models\Section;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;

class SectionExport implements
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
        $sections = Section::get();
        return $sections;
    }

    public function map($section): array
    {
        return [
            $section->id,
            $section->name,
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
        return 'Sections';
    }
}
