<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class StudentImport implements ToCollection, WithHeadingRow, WithValidation
{
    /**
    * @param Collection $collection
    */
    public function collection(Collection $collection)
    {
        //
    }

    public function rules(): array
    {
        return [
            '*.username' => 'required|alpha_num|max:255',
            '*.email' => 'required|email|max:255',
            '*.password' => 'nullable|min:8|max:24',

            '*.name' => 'required|max:255',
            '*.phone' => 'required|max:255',
            '*.phone_alternate' => 'nullable|max:255',
            '*.dob' => 'nullable|date',
            '*.gender_id' => 'nullable|exists:genders,id',
            '*.blood_group_id' => 'nullable|exists:blood_groups,id',
            '*.address' => 'nullable|max:255',
            '*.pincode' => 'nullable|max:255',
            '*.fathers_name' => 'nullable|max:255',
            '*.mothers_name' => 'nullable|max:255',
            '*.pan_no' => 'nullable|max:255',
            '*.passport_no' => 'nullable|max:255',
            '*.voter_id' => 'nullable|max:255',
            '*.aadhar_no' => 'nullable|max:255',
            '*.dl_no' => 'nullable|max:255',

            '*.section_id' => 'required|min:1|exists:sections,id',
            '*.standard_id' => 'required|min:1|exists:standards,id',
            '*.roll_no' => 'required|max:255',
        ];
    }
}
