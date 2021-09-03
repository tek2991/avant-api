<?php

namespace App\Exports;

use App\Models\Language;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;

class LanguageExport implements
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
        $languages = Language::get();
        return $languages;
    }

    public function map($language): array
    {
        return [
            $language->id,
            $language->name,
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
        return 'Languages';
    }
}
