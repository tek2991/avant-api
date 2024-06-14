<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>{{ $exam->name }} {{ $exam->session->name }} </title>
    <style>
        @page {
            footer: page-footer;
        }

        body {
            margin: auto;
            font-size: 1.5rem
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
        <table style="border: none; padding: 0 0 0 20px">
            <tr>
                <th class="school-heading">
                    <span style="font-size: 1.5rem;">{{ $variables['ADDRESS_LINE_1'] }}</span>
                    <br>
                    <span> {{ $exam->name }} {{ $exam->session->name }} </span> <br>
                    <span> {{ $examSubject->subject->name }} </span>
                    <br>
                    <span> Class: {{ $examSubject->subject->standard->name }} </span>
                </th>
                <th style="text-align: right">
                    {{-- Check if $view exists --}}
                    @if ($view)
                        <img src="{{ url('/img/school_logo.png') }}" alt="school_logo" class="logo">
                    @else
                        <img src="{{ public_path('img/school_logo.png') }}" alt="school_logo" class="logo">
                    @endif
                </th>
            </tr>
        </table>
        <table style="border: none; border-collapse: collapse;">
            <tr>
                <th colspan="2" style="padding: 0 20px 0 20px; text-align: left">
                    {{ $examSubject->full_mark }} marks
                </th>
                <th colspan="2" style="padding: 0 20px 0 20px; text-align: right">
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
        <hr>
        @php
            $isAssamese = $examSubject->subject->isAssamese();
        @endphp
        <table style="border: none; border-collapse: collapse; font-family: {{ $isAssamese ? 'nikosh' : '' }}">
            @foreach ($exam_questions as $question)
                <tr style="margin-bottom: 15px">
                    @php
                        $question_type = $question->examQuestionType->name;
                        $description = $question->description;
                    @endphp
                    <td style="padding: .25rem .5rem 0 1rem;">
                        <table style="border: none; border-collapse: collapse; margin:.75rem 0 0 0;">
                            <tr>
                                <th style="text-align: left"> Q.{{ $loop->index + 1 }} </th>
                                <th style="text-align: right"> Marks: {{ $question->marks }} </th>
                            </tr>
                        </table>
                        <div>
                            @php
                                $question_description = $question->description;
                            @endphp
                            {!! $question->description !!}
                            @if ($question_type == 'Objective' && $question->examQuestionOptions->count() > 0)
                                @php
                                    $options = $question->examQuestionOptions;
                                @endphp
                                <br>

                                @foreach ($options as $option)
                                    ({{ $loop->index + 1 }})
                                    {{ $option->description }} <br>
                                @endforeach
                            @endif
                        </div>
                    </td>
                </tr>
            @endforeach
        </table>
        <h5 style="text-align: center;">** END **</h5>
    </div>

    <htmlpagefooter name="page-footer">
        <p style="text-align: center; font-size: .75rem; padding:.25rem">Page: {PAGENO} of {nb}</p>
    </htmlpagefooter>
</body>

</html>
