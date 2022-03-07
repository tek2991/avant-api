<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title> {{ $exam->name }} </title>
    <style>
        body {
            font-family: Arial, Helvetica, sans-serif;
            margin: auto;
            /* width: 29.7cm; */
            /* padding: 1cm; */
        }

        .a4-container {
            /* border: 2px solid black; */
            /* padding: 15px; */
            margin: auto;
        }

        table {
            width: 100%;
            border: 1px solid black;
        }

        table tr td {
            padding: 1rem;
        }

        table tr td:nth-child(2) {
            text-align: center;
        }

        table tr td:nth-child(3) {
            text-align: right;
        }

        table.routine-table {
            border-collapse: collapse;
        }

        table.routine-table .heading th,
        table.routine-table .data td {
            text-align: left;
            border: 1px solid black;
            padding: 5px;
        }

        .logo {
            width: 120px;
        }

        .school-heading {
            color: #384353;
            text-align: left;
        }

        .under-evaluation {
            background-color: bisque;
            color: darkred;
        }

    </style>
</head>

<body>
    <div class="a4-container">
        <table style="border: none">
            <tr>
                <th class="school-heading">
                    <span style="font-size: 1.5rem;">{{ $variables['ADDRESS_LINE_1'] }}</span> <br>
                    {{ $variables['ADDRESS_LINE_2'] }} <br>
                    {{ $variables['ADDRESS_LINE_3'] }}<br>
                    School Reg. ID: {{ $variables['SCHOOL_REG_ID'] }}
                </th>
                <th style="text-align: right">
                    {{-- <img src="{{ url('/img/school_logo.png') }}" alt="school_logo" class="logo"> --}}
                    <img src="{{ public_path('img/school_logo.png') }}" alt="school_logo" class="logo">
                </th>
            </tr>
        </table>
        <table style="border: none; border-collapse: collapse;">
            <tr>
                <th colspan="2" style="padding: 20px;">

                    <span style="font-size: 1.3rem;">Exam Report Card</span>

                </th>
            </tr>
        </table>
        <table style="border: none; margin-top:2rem; border-collapse: collapse;">
            <tr>
                <td style="width: 50%; border: 1px solid black">
                    <span style="font-weight: bold">Exam Name:</span> {{ $exam->name }}
                </td>
                <td style="width: 50%; border: 1px solid black; text-align: start;">
                    <span style="font-weight: bold">Session:</span> {{ $exam->session->name }}
                </td>
            </tr>
            <tr>
                <td style="width: 50%; border: 1px solid black">
                    <span style="font-weight: bold">Name:</span> {{ $exam_user->user->userDetail->name }}
                </td>
                <td style="width: 50%; border: 1px solid black; text-align: start;">
                    <span style="font-weight: bold">Class:</span>
                    {{ $exam_user->user->student->sectionStandard->standard->name }} -
                    {{ $exam_user->user->student->sectionStandard->section->name }}
                </td>
            </tr>
            <tr>
                <td style="width: 50%; border: 1px solid black">
                    <span style="font-weight: bold">User ID:</span> {{ $exam_user->user->username }}
                </td>
                <td style="width: 50%; border: 1px solid black; text-align: start;">
                    <span style="font-weight: bold">Roll No:</span>
                    {{ $exam_user->user->student->roll_no }}
                </td>
            </tr>
        </table>

        <table style="margin-top: 2rem" class="routine-table">
            <tr>
                <th colspan="7">Exam Result</th>
            </tr>
            <tr class="heading">
                <th>SL</th>
                <th>Date</th>
                <th>Subject</th>
                <th>Marks (P) F</th>
                <th>Marks</th>
                <th>Percent</th>
                <th>Result</th>
            </tr>
            @php
                $total_marks = 0;
                $total_pass_marks = 0;
                $total_obtained_marks = 0;
            @endphp
            @forelse ($exam_subjects as $item)
                @php
                    $is_under_evaluation = $item->examSubjectState->name != 'Locked';
                @endphp
                <tr class="data {{ $is_under_evaluation ? 'under-evaluation' : '' }}">
                    <td>{{ $loop->index + 1 }}</td>
                    <td>{{ $item->examSchedule->start->toFormattedDateString() }}</td>
                    <td>{{ $item->subject->name }} {{ $is_under_evaluation ? '(Under Evaluation)' : '' }}
                    </td>
                    <td>({{ $item->pass_mark }}) {{ $item->full_mark }}</td>
                    <td>{{ $exam_subject_scores[$item->id]->marks_secured }} </td>
                    <td>{{ round(($exam_subject_scores[$item->id]->marks_secured / $item->full_mark) * 100, 2) }}%
                    </td>
                    <td> {{ $exam_subject_scores[$item->id]->marks_secured >= $item->pass_mark ? 'Pass' : 'Fail' }}
                    </td>
                </tr>
                @php
                    $total_marks += $item->full_mark;
                    $total_pass_marks += $item->pass_mark;
                    $total_obtained_marks += $exam_subject_scores[$item->id]->marks_secured;
                @endphp
            @empty
                <tr class="data">
                    <td colspan="7">No Data</td>
                </tr>
            @endforelse
            <tr class="data" style="font-weight: bold">
                <td colspan="3">Summary</td>
                <td> ({{ $total_pass_marks }}) {{ $total_marks }} </td>
                <td> {{ $total_obtained_marks }} </td>
                <td> {{ round(($total_obtained_marks / $total_marks) * 100, 2) }}% </td>
                <td> {{ $total_obtained_marks >= $total_pass_marks ? 'Pass' : 'Fail' }} </td>
            </tr>
        </table>
        <h5 style="text-align: center;">** END **</h5>
    </div>
</body>

</html>
