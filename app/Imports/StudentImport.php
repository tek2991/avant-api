<?php

namespace App\Imports;

use App\Models\User;
use App\Models\Teacher;
use App\Models\Standard;
use App\Models\SectionStandard;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
// use Maatwebsite\Excel\Concerns\WithChunkReading;

class StudentImport implements ToCollection, WithHeadingRow, WithValidation
{
    use Importable;

    public function rules(): array
    {
        return [
            '*.username' => 'required|alpha_num|unique:users|max:255',
            '*.email' => 'required|email|unique:users|max:255',
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

    // public function chunkSize(): int
    // {
    //     return 20;
    // }

    /**
     * @param Collection $collection
     */
    public function collection(Collection $collection)
    {
        foreach ($collection as $row) {
            $user = User::create([
                'username' => $row['username'],
                'email' => $row['email'],
                'password' => Hash::make($row['password']),
            ]);

            $user->userDetail()->create([
                'name' => $row['name'],
                'phone' => $row['phone'],
                'phone_alternate' => $row['phone_alternate'],
                'dob' => $row['dob'],
                'gender_id' => $row['gender_id'],
                'blood_group_id' => $row['blood_group_id'],
                'address' => $row['address'],
                'pincode' => $row['pincode'],
                'fathers_name' => $row['fathers_name'],
                'mothers_name' => $row['mothers_name'],
                'pan_no' => $row['pan_no'],
                'passport_no' => $row['passport_no'],
                'voter_id' => $row['voter_id'],
                'aadhar_no' => $row['aadhar_no'],
                'dl_no' => $row['dl_no']
            ]);

            $user->assignRole('student');

            $section_standard = SectionStandard::where('section_id', $row['section_id'])->where('standard_id', $row['standard_id'])->get();

            $section_standard_id = '';

            if($section_standard->count() > 0 ){
                $section_standard_id = $section_standard->first()->id;
            }else{
                SectionStandard::create([
                    'section_id' => $row['section_id'],
                    'standard_id' => $row['standard_id'],
                    'teacher_id' => Teacher::first()->id,
                ]);
                $section_standard_id = SectionStandard::where('section_id', $row['section_id'])->where('standard_id', $row['standard_id'])->first()->id;
            }

            // dd($section_standard_id);

            $user->student()->create([
                'section_standard_id' => $section_standard_id,
                'roll_no' => $row['roll_no'],
            ]);

            $subjects = Standard::find($row['standard_id'])->subjects()->get()->modelKeys();

            $user->student->subjects()->sync($subjects);
        }
    }
}
