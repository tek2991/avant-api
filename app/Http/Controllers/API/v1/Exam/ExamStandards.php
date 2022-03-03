<?php

namespace App\Http\Controllers\API\v1\Exam;

use App\Models\Exam;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\SectionStandard;
use App\Models\Standard;
use Illuminate\Support\Facades\Auth;

class ExamStandards extends Controller
{
    public function index(Exam $exam)
    {
        $user = Auth::user();
        if ($user->hasRole('director') !== true || $user->hasRole('teacher') !== true) {
            return response([
                'header' => 'Forbidden',
                'message' => 'Please Logout and Login again.'
            ], 401);
        }

        $section_standard_ids = $exam->sectionStandards()->pluck('section_standard_id');
        $standard_ids = SectionStandard::whereIn('id', $section_standard_ids)->pluck('standard_id');

        $standards = Standard::whereIn('id', $standard_ids)->paginate();

        return $standards;
    }
}
