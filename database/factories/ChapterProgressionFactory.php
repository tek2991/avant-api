<?php

namespace Database\Factories;

use App\Models\Chapter;
use App\Models\Section;
use App\Models\Session;
use App\Models\Teacher;
use App\Models\ChapterProgression;
use Illuminate\Database\Eloquent\Factories\Factory;

class ChapterProgressionFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = ChapterProgression::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $sessions = collect(Session::all()->modelKeys());
        $chapters = collect(Chapter::all()->modelKeys());
        $sections = collect(Section::all()->modelKeys());
        $teachers = collect(Teacher::all()->modelKeys());
        
        return [
            
        ];
    }
}
