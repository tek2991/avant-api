<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class AttributeExport implements WithMultipleSheets
{
    public function sheets(): array{
        $sheets = [
            new GenderExport,
            new LanguageExport,
            new ReligionExport,
            new CasteExport,
            new BloodGroupExport,
            new StandardExport,
            new SectionExport
        ];

        return $sheets;
    }
}
