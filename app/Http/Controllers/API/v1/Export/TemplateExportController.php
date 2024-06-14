<?php

namespace App\Http\Controllers\API\v1\Export;

use App\Exports\AttributeExport;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\StudentImportTemplate;
use App\Exports\TeacherImportTemplate;

class TemplateExportController extends Controller
{
    public function attributeExport(){
        $name = 'attributes.xlsx';
        return Excel::download(new AttributeExport, $name);
    }

    public function studentTemplate()
    {
        $name = 'student_upload_template.xlsx';
        return Excel::download(new StudentImportTemplate, $name);
    }

    public function teacherTemplate()
    {
        $name = 'teacher_upload_template.xlsx';
        return Excel::download(new TeacherImportTemplate, $name);
    }
}
