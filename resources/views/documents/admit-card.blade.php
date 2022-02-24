<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Exam Name - Ac Year</title>
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

        .school-heading{
            color:#384353;
        }

    </style>
</head>

<body>
    <div class="a4-container">
        <table style="border: none">
            <tr>
                <th style="text-align: left">
                    {{-- <img src="{{ url('/img/board_logo.png') }}" alt="seba_logo" class="logo"> --}}
                    <img src="{{ public_path('img/board_logo.png') }}" alt="board_logo" class="logo">
                </th>
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
                    @if ($exam_user->examUserState->name == 'Provisional')
                        <span style="font-size: 1.3rem; color:Tomato;">Provisional Admit Card</span>
                    @else
                        <span style="font-size: 1.3rem;">Admit Card</span><br>
                    @endif
                </th>
            </tr>
        </table>
        <table style="border: none; margin-top:2rem;">
            <tr>
                <td style="width: 75%; padding: 0;">
                    <table style="border-collapse: collapse;">
                        <tr>
                            <td style="width: 60%; border: 1px solid black">
                                <span style="font-weight: bold">Exam Name:</span> {{ $exam->name }}
                            </td>
                            <td style="width: 40%; border: 1px solid black; text-align: start;">
                                <span style="font-weight: bold">Session:</span> {{ $exam->session->name }}
                            </td>
                        </tr>
                        <tr>
                            <td style="width: 60%; border: 1px solid black">
                                <span style="font-weight: bold">Name:</span> {{ $exam_user->user->userDetail->name }}
                            </td>
                            <td style="width: 40%; border: 1px solid black; text-align: start;">
                                <span style="font-weight: bold">Class:</span>
                                {{ $exam_user->user->student->sectionStandard->standard->name }} -
                                {{ $exam_user->user->student->sectionStandard->section->name }}
                            </td>
                        </tr>
                        <tr>
                            <td style="width: 60%; border: 1px solid black">
                                <span style="font-weight: bold">User ID:</span> {{ $exam_user->user->username }}
                            </td>
                            <td style="width: 40%; border: 1px solid black; text-align: start;">
                                <span style="font-weight: bold">Roll No:</span>
                                {{ $exam_user->user->student->roll_no }}
                            </td>
                        </tr>
                        <tr>
                            <td style="width: 60%; border: 1px solid black">
                                <span style="font-weight: bold">Admission No:</span>
                                {{ $exam_user->user->student->admission_no }}
                            </td>
                            <td style="width: 40%; border: 1px solid black; text-align: start;">
                                <span style="font-weight: bold">D.O.B</span> {{ $exam_user->user->userDetail->dob }}
                            </td>
                        </tr>
                    </table>
                </td>
                <td style="width: 25% padding: 0;  text-align: right; border: 1px solid black">
                    {{-- <img src="{{ $exam_user->user->profilePicture->url ? url('storage/' . $exam_user->user->profilePicture->url) : url('/img/profile.png') }}"
                        alt="seba_logo" style="max-width: 150px"> --}}
                    <img src="{{ $exam_user->user->profilePicture->url ? public_path('storage/' . $exam_user->user->profilePicture->url) : public_path('img/logo.png') }}"
                        style="max-width: 150px; max-height: 200px" />
                </td>
            </tr>
        </table>

        <table style="margin-top: 2rem" class="routine-table">
            <tr>
                <th colspan="5">Exam Routine</th>
            </tr>
            <tr class="heading">
                <th>SL</th>
                <th>Date</th>
                <th>Time (HH:MM)</th>
                <th>Subject</th>
                <th>Marks (P) F </th>
            </tr>
            @forelse ($exam_subjects as $item)
                <tr class="data">
                    <td>{{ $loop->index + 1 }}</td>
                    <td>{{ $item->examSchedule->start->toFormattedDateString() }}</td>
                    <td>{{ $item->examSchedule->start->format('H:m') }} to
                        {{ $item->examSchedule->end->format('H:m') }}</td>
                    <td>{{ $item->subject->name }}</td>
                    <td>({{ $item->pass_mark }}) {{ $item->full_mark }}</td>
                </tr>
            @empty
                <tr class="data">
                    <td colspan="5">No Data</td>
                </tr>
            @endforelse
        </table>
        <h5 style="text-align: center;">** END **</h5>
    </div>
</body>

</html>
