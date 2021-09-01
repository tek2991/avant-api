<?php

namespace Database\Factories;

use App\Models\Gender;
use App\Models\Language;
use App\Models\Religion;
use App\Models\BloodGroup;
use App\Models\UserDetail;
use Illuminate\Database\Eloquent\Factories\Factory;

class UserDetailFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = UserDetail::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $genders = collect(Gender::all()->modelKeys());
        $languages = collect(Language::all()->modelKeys());
        $religions = collect(Religion::all()->modelKeys());
        $bloodGroups = collect(BloodGroup::all()->modelKeys());
        
        return [
            'name' => $this->faker->name,
            'phone' => $this->faker->phoneNumber,
            'phone_alternate' => $this->faker->phoneNumber,
            'dob' => $this->faker->date,
            'gender_id' => $genders->random(),
            'blood_group_id' => $bloodGroups->random(),
            'language_id' => $languages->random(),
            'religion_id' => $religions->random(),
            'fathers_name' => $this->faker->name('male'),
            'mothers_name' => $this->faker->name('female'),
            'address' => $this->faker->address,
            'pincode' => mt_rand(110001, 880001),
            'pan_no' => $this->faker->bothify('?????####?'),
            'aadhar_no' => $this->faker->bothify('####-####-####-####'),
            'dl_no'=> $this->faker->bothify('??#########'),
            'voter_id'=> $this->faker->bothify('??##### ?'),
            'passport_no'=> $this->faker->bothify('???####???'),
        ];
    }
}
