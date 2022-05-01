<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{ $exam->name }} {{ $exam->session->name }} </title>
    <style>
        body {
            font-family: Arial, Helvetica, sans-serif;
            margin: auto;
            /* width: 29.7cm; */
            /* padding: 1cm; */
        }

        .a4-container {
            /* border: 2px solid black; */
            /* padding: 1.5rem; */
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

        .logo {
            width: 120px;
        }

        .school-heading {
            color: #384353;
            text-align: left;
        }

    </style>
</head>

<body>
    <div class="a4-container">
        <table style="border: none; padding: 20px">
            <tr>
                <th class="school-heading">
                    <span style="font-size: 1.5rem;">{{ $variables['ADDRESS_LINE_1'] }}</span> <br>
                    <span> {{ $exam->name }} {{ $exam->session->name }} </span> <br>
                    <span> {{ $examSubject->subject->name }} </span>
                    <br>
                    <span> {{ $examSubject->subject->standard->name }} </span>
                </th>
                <th style="text-align: right">
                    {{-- <img src="{{ url('/img/school_logo.png') }}" alt="school_logo" class="logo"> --}}
                    <img src="{{ public_path('img/school_logo.png') }}" alt="school_logo" class="logo">
                </th>
            </tr>
        </table>
        <table style="border: none; border-collapse: collapse;">
            <tr>
                <th colspan="2" style="padding: 20px; text-align: left">
                    {{ $examSubject->full_mark }} marks
                </th>
                <th colspan="2" style="padding: 20px; text-align: right">
                    @php
                        $examSchedule = $examSubject->examSchedule()->exists() ? $examSubject->examSchedule : null;
                        $duration = null;
                        if ($examSchedule) {
                            $start = $examSchedule->start;
                            $end = $examSchedule->end;
                            $duration = $end->diff($start)->format('%h:%i');
                        }
                    @endphp
                    {{ $examSchedule ? $duration . '(hh:mm)' : 'N/A' }}
                </th>
            </tr>
        </table>
        <table style="border: none; margin-top:1rem;">
            @foreach ($exam_questions as $question)
                <tr>
                    @php
                        $question_type = $question->examQuestionType->name;
                        $description = $question->description;
                    @endphp
                    <td style="padding: .25rem 1rem 0 1rem ">
                        <table style="border: none; margin:.5rem 0;">
                            <tr>
                                <th style="text-align: left"> Q.{{ $loop->index + 1 }} </th>
                                <th style="text-align: right"> Marks: {{ $question->marks }} </th>
                            </tr>
                        </table>
                        <span style="padding-left: 2rem">
                            @php
                                $question_description = $question->description;
                                
                            @endphp
                            {!! $question->description !!}
                            {{-- {{ $question->description }} --}}
                            @if ($question_type == 'Objective' && $question->examQuestionOptions->count() > 0)
                            @php
                                $options = $question->examQuestionOptions;
                                @endphp
                            <br>
                            <ul>
                                @foreach ($options as $option)
                                <li>
                                    {{ $option->description }}
                                </li>
                                @endforeach
                            </ul>
                            @endif
                        </span>
                    </td>
                </tr>
            @endforeach
        </table>
        <h5 style="text-align: center;">** END **</h5>
    </div>
</body>

</html>
