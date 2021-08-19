<?php

namespace App\Http\Controllers\API\v1\Student;

use App\Models\User;
use App\Models\Standard;
use Illuminate\Http\Request;
use App\Models\SectionStandard;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UpdateStudentController extends Controller
{
    public function update(Request $request, User $user){

        if (Auth::user()->hasRole('director') !== true) {
            return response([
                'header' => 'Forbidden',
                'message' => 'Please Logout and Login again.'
            ], 401);
        }

        $this->validate($request, [
            'name' => 'required|max:255',
            'email' => [
                'required', 'email', 'max:255',
                Rule::unique('users')->ignore($user)
            ],
            'phone' => 'required|max:255',
            'phone_alternate' => 'nullable|max:255',
            'dob' => 'nullable|date',
            'gender_id' => 'nullable|exists:genders,id',
            'blood_group_id' => 'nullable|exists:blood_groups,id',
            'address' => 'nullable|max:255',
            'pincode' => 'nullable|max:255',
            'fathers_name' => 'nullable|max:255',
            'mothers_name' => 'nullable|max:255',
            'pan_no' => 'nullable|max:255',
            'passport_no' => 'nullable|max:255',
            'voter_id' => 'nullable|max:255',
            'aadhar_no' => 'nullable|max:255',
            'dl_no' => 'nullable|max:255',
            'password' => 'nullable|min:8|max:24',

            'section_id' => 'required|min:1|exists:sections,id',
            'standard_id' => 'required|min:1|exists:standards,id',
            'roll_no' => 'required|max:255',
        ]);

        $user->update([
            'email' => $request->email,
        ]);

        if(!empty($request->password)){
            $user->update(['password' => Hash::make($request->password)]);
        }

        $user->userDetail()->update($request->only([
            'name', 'phone', 'phone_alternate', 'dob', 'gender_id', 'blood_group_id', 'address', 'pincode', 'fathers_name', 'mothers_name', 'pan_no', 'passport_no', 'voter_id', 'aadhar_no', 'dl_no'
        ]));

        $section_standard_id = SectionStandard::where('section_id', $request->section_id)->where('standard_id', $request->standard_id)->first()->id;

        $user->student->update([
            'section_standard_id' => $section_standard_id,
            'roll_no' => $request->roll_no,
        ]);

        $subjects = Standard::find($request->standard_id)->subjects()->get()->modelKeys();

        $user->student->subjects()->sync($subjects);

        return $user;
    }
}
