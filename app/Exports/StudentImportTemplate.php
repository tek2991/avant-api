<?php

namespace App\Exports;

use App\Models\User;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;

class StudentImportTemplate implements
    FromCollection,
    WithMapping,
    WithHeadings,
    WithColumnFormatting
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        $users = User::where('id', '<', '200')->get();
        return $users;
    }

    public function map($user): array
    {
        $userDetail = $user->userDetail;
        $student = $user->student()->exists() ? $user->student : null;
        $standard_id = $student ? $student->sectionStandard->standard_id : 'id';
        $section_id = $student ? $student->sectionStandard->section_id : 'id';
        $roll_no = $student ? $student->roll_no : 'no';

        return [
            $user->username,
            $user->email,
            'password',
            $userDetail->name,
            $userDetail->phone,
            $userDetail->phone_alternate,
            $userDetail->dob,
            $userDetail->gender_id,
            $userDetail->language_id,
            $userDetail->religion_id,
            $userDetail->caste_id,
            $userDetail->blood_group_id,
            $userDetail->address,
            $userDetail->pincode,
            $userDetail->fathers_name,
            $userDetail->mothers_name,
            $userDetail->pan_no,
            $userDetail->passport_no,
            $userDetail->voter_id,
            $userDetail->aadhar_no,
            $userDetail->dl_no,
            $standard_id,
            $section_id,
            $roll_no,
        ];
    }

    public function headings(): array
    {
        return [
            'username',
            'email',
            'password',
            'name',
            'phone',
            'phone_alternate',
            'dob',
            'gender_id',
            'language_id',
            'religion_id',
            'caste_id',
            'blood_group_id',
            'address',
            'pincode',
            'fathers_name',
            'mothers_name',
            'pan_no',
            'passport_no',
            'voter_id',
            'aadhar_no',
            'dl_no',
            'standard_id',
            'section_id',
            'roll_no'
        ];
    }

    public function columnFormats(): array
    {
        return [
            'G' => NumberFormat::FORMAT_DATE_DDMMYYYY,
        ];
    }
}
