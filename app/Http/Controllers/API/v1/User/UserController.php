<?php

namespace App\Http\Controllers\API\v1\User;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        return $user->select(['id', 'username', 'email'])->with('userDetail');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {
        if(Auth::user()->id !== $user->id && Auth::user()->hasRole('director') !== true){
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
        ]);

        $user->update([
            'email' => $request->email,
        ]);

        $user->userDetail()->update($request->only([
            'name', 'phone', 'phone_alternate', 'dob', 'gender_id', 'blood_group_id', 'address', 'pincode', 'fathers_name', 'mothers_name', 'pan_no', 'passport_no', 'voter_id', 'aadhar_no', 'dl_no'
        ]));

        if(!empty($request->password)){
            $user->update(['password' => Hash::make($request->password)]);
        }

        $profile = $user->where('id', $user->id)->with('userDetail')->first();

        $response = [
            'user' => $profile,
        ];

        return response($response, 201);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        //
    }
}
